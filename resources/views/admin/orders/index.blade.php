@extends('admin.layout')

@section('title', 'Orders')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Orders</h1>

<div class="bg-white border rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left">Order ID</th>
                <th class="px-4 py-3 text-left">Customer</th>
                <th class="px-4 py-3 text-left">Amount</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $order)
                <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3">#{{ $order->id }}</td>
                    <td class="px-4 py-3">{{ $order->customer_name }}</td>
                    <td class="px-4 py-3">{{ $order->currency }} {{ number_format($order->total_amount,2) }}</td>
                    <td class="px-4 py-3 capitalize">{{ $order->status }}</td>
                    <td class="px-4 py-3">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-600 hover:underline text-sm">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection
