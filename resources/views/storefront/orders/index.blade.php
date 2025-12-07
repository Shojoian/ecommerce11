@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-semibold mb-6">My Orders</h1>

    @if($orders->isEmpty())
        <div class="p-6 bg-white rounded-lg shadow text-center">
            <p class="text-slate-600">You have no orders yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order->payment_reference) }}"
                   class="block bg-white p-5 rounded-lg shadow hover:shadow-md transition">

                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold text-lg">
                                Order #{{ $order->payment_reference }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ $order->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-emerald-600 font-bold text-lg">
                                {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                            </p>
                            <p class="text-sm text-slate-500 capitalize">
                                Status: {{ $order->status }}
                            </p>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
