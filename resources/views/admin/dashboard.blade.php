@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-sm text-slate-500 mb-2">Total Products</h3>
        <p class="text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-sm text-slate-500 mb-2">Total Orders</h3>
        <p class="text-3xl font-bold">{{ \App\Models\Order::count() }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-sm text-slate-500 mb-2">Pending Orders</h3>
        <p class="text-3xl font-bold">{{ \App\Models\Order::where('status','pending')->count() }}</p>
    </div>

</div>

<div class="mt-10">
    <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>

    <div class="bg-white rounded-xl border shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Order ID</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Order::latest()->take(5)->get() as $order)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="px-4 py-3">#{{ $order->id }}</td>
                        <td class="px-4 py-3">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3">{{ $order->currency }} {{ number_format($order->total_amount,2) }}</td>
                        <td class="px-4 py-3 capitalize">{{ $order->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
