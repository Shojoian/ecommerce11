@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">
        Order #{{ $order->payment_reference }}
    </h1>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
        <p><strong>Status:</strong> <span class="capitalize">{{ $order->status }}</span></p>
        <p><strong>Total:</strong> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
    </div>

    <h2 class="text-xl font-semibold mb-4">Items</h2>

    @foreach ($order->items as $item)
        <div class="bg-white p-4 rounded-lg shadow mb-3 flex justify-between">
            <div>
                <p class="font-semibold">{{ $item->product->name }}</p>
                <p class="text-sm text-slate-600">Qty: {{ $item->quantity }}</p>
            </div>

            <div class="text-right">
                <p class="font-semibold">
                    {{ $order->currency }} {{ number_format($item->price * $item->quantity, 2) }}
                </p>
            </div>
        </div>
    @endforeach

</div>
@endsection
