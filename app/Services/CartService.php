<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'cart';

    public function all(): array
    {
        return session($this->sessionKey, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->all();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
            ];
        }

        session([$this->sessionKey => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->all();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, $quantity);
        }
        session([$this->sessionKey => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        session([$this->sessionKey => $cart]);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function subtotal(): float
    {
        return collect($this->all())->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
}
