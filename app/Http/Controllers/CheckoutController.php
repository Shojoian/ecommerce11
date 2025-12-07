<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\CurrencyService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(CartService $cart, CurrencyService $currency, Request $request)
    {
        $items = $cart->all();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->subtotal();
        $selectedCurrency = $request->get('currency', config('app.currency'));
        $displayTotal = $currency->convert($subtotal, $selectedCurrency);

        return view('storefront.checkout.index', compact(
            'items', 'subtotal', 'selectedCurrency', 'displayTotal'
        ));
    }

    public function process(
        Request $request,
        CartService $cart,
        CurrencyService $currency,
        PayPalService $paypal
    ) {
        $request->validate([
            'customer_name'  => ['required', 'string'],
            'customer_email' => ['required', 'email'],
            'address'        => ['required', 'string'],
            'city'           => ['required', 'string'],
            'postal_code'    => ['required', 'string'],
            'currency'       => ['required', 'string'],
        ]);

        $items = $cart->all();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->subtotal();
        $selectedCurrency = $request->currency;
        $paymentAmount = $currency->convert($subtotal, $selectedCurrency);

        // Create PayPal order
        $orderResponse = $paypal->createOrder($paymentAmount, $selectedCurrency);

        if (!isset($orderResponse['id'])) {
            return back()->with('error', 'Unable to create PayPal order.');
        }

        // Temporarily store checkout info in session (or DB)
        session([
            'checkout_data' => [
                'customer' => $request->only([
                    'customer_name','customer_email','address','city','postal_code'
                ]),
                'subtotal' => $subtotal,
                'currency' => $selectedCurrency,
                'paypal_order_id' => $orderResponse['id'],
            ],
        ]);

        // Redirect to PayPal approval link
        foreach ($orderResponse['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }

        return back()->with('error', 'PayPal approval link not found.');
    }

    public function paypalSuccess(Request $request, PayPalService $paypal, CartService $cart)
    {
        $token = $request->get('token');
        $checkoutData = session('checkout_data');

        if (!$token || !$checkoutData) {
            return redirect()->route('cart.index')->with('error', 'Invalid PayPal session.');
        }

        $capture = $paypal->capturePayment($token);

        if (!isset($capture['status']) || $capture['status'] !== 'COMPLETED') {
            return redirect()->route('cart.index')->with('error', 'Payment not completed.');
        }

        DB::transaction(function () use ($checkoutData, $cart, $capture) {
            $order = Order::create([
                'user_id'         => auth()->id(),
                'customer_name'   => $checkoutData['customer']['customer_name'],
                'customer_email'  => $checkoutData['customer']['customer_email'],
                'customer_phone'  => null,
                'address'         => $checkoutData['customer']['address'],
                'city'            => $checkoutData['customer']['city'],
                'postal_code'     => $checkoutData['customer']['postal_code'],
                'status'          => 'processing',
                'total_amount'    => $checkoutData['subtotal'],
                'currency'        => $checkoutData['currency'],
                'payment_method'  => 'paypal',
                'payment_status'  => 'paid',
                'payment_reference'=> $capture['id'] ?? null,
            ]);

            $items = $cart->all();
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            $cart->clear();
        });

        session()->forget('checkout_data');

        return redirect()->route('orders.show', ['order' => $capture['id'] ?? null])
            ->with('success', 'Order placed successfully!');
    }

    public function paypalCancel()
    {
        return redirect()->route('cart.index')->with('error', 'Payment cancelled.');
    }
}
