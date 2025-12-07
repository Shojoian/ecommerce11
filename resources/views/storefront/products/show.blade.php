@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="aspect-[4/3] bg-slate-100">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @endif
        </div>
    </div>
    <div class="flex flex-col gap-4">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-500">
                {{ $product->category?->name }}
            </p>
            <h1 class="text-2xl font-semibold mt-1">{{ $product->name }}</h1>
        </div>
        <p class="text-lg font-semibold">
            {{ $product->display_currency }} {{ number_format($product->display_price, 2) }}
        </p>
        <p class="text-sm text-slate-600 leading-relaxed">
            {{ $product->description }}
        </p>
        <p class="text-xs text-slate-500">
            Stock: {{ $product->stock }}
        </p>

        <form action="{{ route('cart.add') }}" method="post" class="flex items-center gap-3 mt-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="number" name="quantity" min="1" value="1"
                   class="w-20 border rounded-lg px-2 py-2 text-sm">
            <button class="px-6 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium">
                Add to cart
            </button>
        </form>
    </div>
</div>
@endsection
