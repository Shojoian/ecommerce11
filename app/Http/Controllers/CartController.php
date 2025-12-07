<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart, CurrencyService $currency, Request $request)
    {
        $items = $cart->all();
        $subtotal = $cart->subtotal();

        $selectedCurrency = $request->get('currency', config('app.currency'));
        $displaySubtotal = $currency->convert($subtotal, $selectedCurrency);

        return view('storefront.cart.index', compact(
            'items', 'subtotal', 'selectedCurrency', 'displaySubtotal'
        ));
    }

    public function add(Request $request, CartService $cart)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart->add($product, $request->quantity);

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartService $cart)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $cart->update($request->product_id, $request->quantity);
        return redirect()->route('cart.index');
    }

    public function remove(int $productId, CartService $cart)
    {
        $cart->remove($productId);
        return redirect()->route('cart.index');
    }
}
