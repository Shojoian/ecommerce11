@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <form class="bg-white rounded-2xl border shadow-sm p-4 space-y-4 lg:col-span-2"
          action="{{ route('checkout.process') }}" method="post">
        @csrf

        <input type="hidden" name="currency" value="{{ $selectedCurrency }}">

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Full name</label>
            <input type="text" name="customer_name"
                   value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm @error('customer_name') border-red-500 @enderror">
            @error('customer_name')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
            <input type="email" name="customer_email"
                   value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm @error('customer_email') border-red-500 @enderror">
            @error('customer_email')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Address</label>
            <input type="text" name="address"
                   value="{{ old('address', auth()->user()->address ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm @error('address') border-red-500 @enderror">
            @error('address')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">City</label>
                <input type="text" name="city"
                       value="{{ old('city', auth()->user()->city ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm @error('city') border-red-500 @enderror">
                @error('city')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Postal code</label>
                <input type="text" name="postal_code"
                       value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm @error('postal_code') border-red-500 @enderror">
                @error('postal_code')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button class="mt-4 px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium">
            Pay with PayPal
        </button>
    </form>

    <aside class="bg-white rounded-2xl border shadow-sm p-4 space-y-3">
        <h2 class="text-sm font-semibold">Order summary</h2>
        <div class="flex justify-between text-sm">
            <span>Subtotal (PHP)</span>
            <span>PHP {{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span>Total in {{ $selectedCurrency }}</span>
            <span>{{ $selectedCurrency }} {{ number_format($displayTotal, 2) }}</span>
        </div>
    </aside>
</div>
@endsection
