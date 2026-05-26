<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected string $key = 'cart';

    /** @return array<int,int> product_id => quantity */
    protected function items(): array
    {
        return session()->get($this->key, []);
    }

    protected function save(array $items): void
    {
        session()->put($this->key, $items);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $items = $this->items();
        $items[$productId] = ($items[$productId] ?? 0) + $qty;
        $this->save($items);
    }

    public function update(int $productId, int $qty): void
    {
        $items = $this->items();
        if ($qty <= 0) {
            unset($items[$productId]);
        } else {
            $items[$productId] = $qty;
        }
        $this->save($items);
    }

    public function remove(int $productId): void
    {
        $items = $this->items();
        unset($items[$productId]);
        $this->save($items);
    }

    public function clear(): void
    {
        session()->forget($this->key);
    }

    /** @return Collection<int,object> lines with product + quantity + line_total */
    public function lines(): Collection
    {
        $items = $this->items();
        if (empty($items)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        return collect($items)->map(function ($qty, $id) use ($products) {
            $product = $products->get($id);
            if (! $product) {
                return null;
            }
            return (object) [
                'product' => $product,
                'quantity' => $qty,
                'line_total' => $product->current_price * $qty,
            ];
        })->filter()->values();
    }

    public function count(): int
    {
        return array_sum($this->items());
    }

    public function subtotal(): float
    {
        return (float) $this->lines()->sum('line_total');
    }

    public function shipping(): float
    {
        $sub = $this->subtotal();
        if ($sub <= 0 || $sub >= 5000) {
            return 0; // free shipping over Rs.5000
        }
        return 250;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shipping();
    }
}
