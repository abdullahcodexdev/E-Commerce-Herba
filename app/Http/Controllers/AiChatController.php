<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AiService;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function __construct(protected AiService $ai) {}

    public function chat(Request $request)
    {
        if (! $this->ai->enabled()) {
            return response()->json([
                'reply' => 'Our assistant is currently offline. Please contact us at care@herbalroots.pk and we\'ll be happy to help!',
            ]);
        }

        $data = $request->validate([
            'message'           => 'required|string|max:1000',
            'history'           => 'nullable|array|max:10',
            'history.*.role'    => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:1000',
        ]);

        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt()],
        ];

        foreach ($data['history'] ?? [] as $turn) {
            $messages[] = ['role' => $turn['role'], 'content' => $turn['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $data['message']];

        $reply = $this->ai->chat($messages)
            ?? 'Sorry, I had trouble answering just now. Please try again, or email care@herbalroots.pk.';

        return response()->json(['reply' => $reply]);
    }

    /**
     * Build the assistant's instructions, including a compact product catalog
     * so it can make grounded recommendations instead of inventing products.
     */
    protected function systemPrompt(): string
    {
        $catalog = Product::with('category')
            ->where('stock', '>', 0)
            ->get()
            ->map(fn ($p) => sprintf(
                '- %s (%s) — Rs.%s%s. %s',
                $p->name,
                $p->category->name ?? 'General',
                number_format($p->current_price),
                $p->on_sale ? ' [on sale]' : '',
                $p->short_description ?: 'Herbal product.'
            ))
            ->implode("\n");

        return <<<PROMPT
            You are "Herbal Roots Assistant", a friendly customer-support helper for an
            online herbal-products store based in Pakistan (prices in PKR / Rs.).

            Your job:
            - Greet warmly and answer questions about products, herbal benefits, orders,
              shipping (Rs.250 under Rs.5000, free above), payment (Cash on Delivery or
              Card via Stripe), and returns.
            - When a customer describes a health goal or problem (e.g. "can't sleep",
              "low energy", "weak immunity"), recommend the most relevant products from
              the catalog below by name, and briefly say why.
            - ONLY recommend products that appear in the catalog. Never invent products,
              prices, or medical claims. Keep wellness advice general and add a gentle
              note to consult a doctor for medical conditions.
            - Be concise (2-4 short sentences). Reply in the customer's language
              (English, Urdu, or Roman Urdu) to match how they wrote.

            CURRENT PRODUCT CATALOG:
            {$catalog}
            PROMPT;
    }
}
