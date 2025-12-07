<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected PayPalClient $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function createOrder(float $amount, string $currency = 'PHP'): array
    {
        $this->provider->getAccessToken();

        return $this->provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => route('checkout.paypal.success'),
                'cancel_url' => route('checkout.paypal.cancel'),
            ],
        ]);
    }

    public function capturePayment(string $token): array
    {
        $this->provider->getAccessToken();
        return $this->provider->capturePaymentOrder($token);
    }
}
