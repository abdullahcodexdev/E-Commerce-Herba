<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    /* ------------------------------------------------------------------ */
    /*  Checkout page                                                      */
    /* ------------------------------------------------------------------ */
    public function index()
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
            'stripeKey' => config('services.stripe.key'),   // publishable key for Stripe.js
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Cash on Delivery — create the order immediately (no gateway)       */
    /* ------------------------------------------------------------------ */
    public function store(Request $request)
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $data = $this->validateShipping($request);

        if ($data['payment_method'] !== 'cod') {
            // Card orders must go through the Stripe flow (createIntent → confirm).
            return back()->with('error', 'Please complete the card payment to place this order.');
        }

        $order = $this->createOrder($data, paid: false, status: 'pending');
        $this->cart->clear();

        return redirect()->route('checkout.success', $order->order_number);
    }

    /* ------------------------------------------------------------------ */
    /*  Card — Step 1: create a Stripe PaymentIntent, return clientSecret  */
    /* ------------------------------------------------------------------ */
    public function createIntent(Request $request)
    {
        if ($this->cart->count() === 0) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        if (! config('services.stripe.secret')) {
            return response()->json(['error' => 'Payment gateway is not configured. Add your Stripe keys to .env.'], 500);
        }

        // Validate shipping details before charging.
        $data = $this->validateShipping($request);

        $amount   = (int) round($this->cart->total() * 100);   // Stripe expects the smallest currency unit
        $currency = config('services.stripe.currency', 'usd');

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::create([
                'amount'               => $amount,
                'currency'             => $currency,
                'payment_method_types' => ['card'],          // card only (sandbox)
                'description'          => 'Herbal Roots order',
                'receipt_email'        => $data['email'],
                'metadata'             => [
                    'customer_name' => $data['name'],
                    'phone'         => $data['phone'],
                    'cart_count'    => (string) $this->cart->count(),
                ],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe createIntent failed: '.$e->getMessage());
            return response()->json(['error' => 'Could not start payment: '.$e->getMessage()], 502);
        }

        // Stash the validated order context so we can finalize after confirmation.
        session()->put('checkout', [
            'shipping'  => $data,
            'intent_id' => $intent->id,
            'amount'    => $amount,
            'currency'  => $currency,
        ]);

        return response()->json([
            'clientSecret'   => $intent->client_secret,
            'publishableKey' => config('services.stripe.key'),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Card — Step 2: confirm the payment server-side, then create order  */
    /* ------------------------------------------------------------------ */
    public function confirm(Request $request)
    {
        $request->validate(['payment_intent_id' => 'required|string']);

        $ctx = session('checkout');
        if (! $ctx || $ctx['intent_id'] !== $request->payment_intent_id) {
            return response()->json(['error' => 'Payment session expired. Please try again.'], 422);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Re-fetch from Stripe — never trust the client about payment status.
            $intent = PaymentIntent::retrieve([
                'id'     => $request->payment_intent_id,
                'expand' => ['latest_charge.payment_method_details'],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe retrieve failed: '.$e->getMessage());
            return response()->json(['error' => 'Could not verify payment. Please try again.'], 502);
        }

        // Only proceed when the gateway actually authorized the charge.
        if ($intent->status !== 'succeeded') {
            return response()->json([
                'error' => 'Payment was not completed (status: '.$intent->status.').',
            ], 402);
        }

        // Guard: amount actually captured must match what we asked for.
        if ((int) $intent->amount_received !== (int) $ctx['amount']) {
            Log::warning('Stripe amount mismatch', ['got' => $intent->amount_received, 'want' => $ctx['amount']]);
            return response()->json(['error' => 'Payment amount mismatch. Please contact support.'], 422);
        }

        // Pull non-sensitive card metadata (brand + last4 only — PCI safe).
        $card  = optional(optional($intent->latest_charge)->payment_method_details)->card;
        $brand = $card->brand ?? null;
        $last4 = $card->last4 ?? null;

        $order = $this->createOrder(
            $ctx['shipping'],
            paid: true,
            status: 'processing',
            extra: [
                'payment_intent_id' => $intent->id,
                'card_brand'        => $brand,
                'card_last4'        => $last4,
            ]
        );

        $this->cart->clear();
        session()->forget('checkout');

        return response()->json([
            'redirect' => route('checkout.success', $order->order_number),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Success page                                                       */
    /* ------------------------------------------------------------------ */
    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */
    protected function validateShipping(Request $request): array
    {
        return $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:120',
            'notes'          => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,card',
        ]);
    }

    /** Persist an order + its items inside a transaction, then email the customer. */
    protected function createOrder(array $data, bool $paid, string $status, array $extra = []): Order
    {
        $order = DB::transaction(function () use ($data, $paid, $status, $extra) {
            $order = Order::create(array_merge([
                'user_id'        => auth()->id(),
                'order_number'   => 'HR-'.strtoupper(Str::random(8)),
                'name'           => $data['name'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'address'        => $data['address'],
                'city'           => $data['city'],
                'subtotal'       => $this->cart->subtotal(),
                'shipping'       => $this->cart->shipping(),
                'total'          => $this->cart->total(),
                'payment_method' => $data['payment_method'],
                'notes'          => $data['notes'] ?? null,
                'status'         => $status,
                'is_paid'        => $paid,
            ], $extra));

            foreach ($this->cart->lines() as $line) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $line->product->id,
                    'product_name' => $line->product->name,
                    'price'        => $line->product->current_price,
                    'quantity'     => $line->quantity,
                ]);
            }

            return $order;
        });

        // Send the confirmation email — never let a mail failure break checkout.
        try {
            Mail::to($order->email)->send(new OrderConfirmation($order->load('items')));
        } catch (\Throwable $e) {
            Log::warning('Order confirmation email failed: '.$e->getMessage());
        }

        return $order;
    }
}
