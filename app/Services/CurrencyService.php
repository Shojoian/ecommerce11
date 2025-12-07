<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected string $baseCurrency;

    public function __construct()
    {
        $this->baseCurrency = config('app.currency', 'PHP');
    }

    public function getSupportedCurrencies(): array
    {
        return ['PHP', 'USD', 'EUR', 'JPY']; // extend as needed
    }

    public function convert(float $amount, string $toCurrency): float
    {
        $toCurrency = strtoupper($toCurrency);
        if ($toCurrency === $this->baseCurrency) {
            return $amount;
        }

        $response = Http::get(config('services.currency.url'), [
            'base' => $this->baseCurrency,
            'symbols' => $toCurrency,
        ]);

        if (!$response->ok()) {
            return $amount;
        }

        $rate = $response->json('rates.' . $toCurrency, 1);

        return round($amount * $rate, 2);
    }
}
