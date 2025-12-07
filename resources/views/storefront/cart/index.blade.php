@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Shopping cart</h1>

@if(empty($items))
    <p class="text-sm text-slate-500">Your cart is empty.</p>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @foreach($items as $item)
                <div class="bg-white rounded-2xl border shadow-sm p-4 flex gap-4">
                    <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden">
                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            PHP {{ number_format($item['price'], 2) }}
                        </p>
                        <form action="{{ route('cart.update') }}" method="post" class="mt-2 flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}"
                                   class="w-20 border rounded-lg px-2 py-1 text-xs">
                            <button class="text-xs px-3 py-1 rounded-lg border">
                                Update
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('cart.remove', $item['product_id']) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-500">
                            Remove
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <aside class="bg-white rounded-2xl border shadow-sm p-4 space-y-4">
            <h2 class="text-sm font-semibold">Order summary</h2>
            <div class="flex justify-between text-sm">
                <span>Subtotal (PHP)</span>
                <span>PHP {{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Converted total</span>
                <span>{{ $selectedCurrency }} {{ number_format($displaySubtotal, 2) }}</span>
            </div>

            <a href="{{ route('checkout.show', ['currency' => $selectedCurrency]) }}"
               class="block text-center mt-4 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium">
                Checkout
            </a>
        </aside>
    </div>
@endif
@endsection
