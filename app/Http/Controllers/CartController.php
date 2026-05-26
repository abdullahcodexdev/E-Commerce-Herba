<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return view('cart.index', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $this->cart->add($product->id, $qty);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $product->name.' added to cart',
                'count' => $this->cart->count(),
            ]);
        }

        return back()->with('success', $product->name.' added to your cart.');
    }

    public function update(Request $request, Product $product)
    {
        $this->cart->update($product->id, (int) $request->input('quantity', 1));

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $this->cart->count(),
                'subtotal' => number_format($this->cart->subtotal(), 0),
                'shipping' => number_format($this->cart->shipping(), 0),
                'total' => number_format($this->cart->total(), 0),
            ]);
        }

        return back();
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);
        return back()->with('success', 'Item removed from cart.');
    }

    public function count(CartService $cart)
    {
        return response()->json(['count' => $cart->count()]);
    }
}
