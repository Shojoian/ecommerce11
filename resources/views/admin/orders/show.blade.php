@extends('admin.layout')

@section('title', 'Order Details')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Order #{{ $order->id }}</h1>

<div class="bg-white border rounded-xl shadow p-6 mb-10">
    <h2 class="text-lg font-semibold mb-4">Customer Information</h2>

    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
    <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }} {{ $order->postal_code }}</p>

    <p class="mt-4"><strong>Status:</strong>
        <span class="px-2 py-1 bg-slate-200 rounded text-xs capitalize">
            {{ $order->status }}
        </span>
    </p>

    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
        @csrf
        @method('PATCH')

        <select name="status" class="border px-3 py-2 rounded">
            @foreach(\App\Models\Order::STATUSES as $status)
                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <button class="px-3 py-2 bg-emerald-600 text-white rounded">
            Update
        </button>
    </form>

</div>

<div class="bg-white border rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Order Items</h2>

    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left">Product</th>
                <th class="px-4 py-3 text-left">Quantity</th>
                <th class="px-4 py-3 text-left">Price (PHP)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $item->product->name }}</td>
                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                    <td class="px-4 py-3">PHP {{ number_format($item->price,2) }}</td>
                </tr>
            @endforeach

            <tr class="bg-slate-100 font-semibold">
                <td class="px-4 py-3">Total Amount</td>
                <td></td>
                <td class="px-4 py-3">{{ $order->currency }} {{ number_format($order->total_amount,2) }}</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
