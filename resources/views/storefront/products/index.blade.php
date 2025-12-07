@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">All products</h1>
            <p class="text-sm text-slate-500 mt-1">
                Browse our collection of hand-picked items.
            </p>
        </div>

        {{-- Currency Switcher --}}
        <form method="get" action="{{ route('products.index') }}" class="flex items-center gap-2">
            @foreach(['q','category','min_price','max_price','sort'] as $param)
                @if(request($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                @endif
            @endforeach

            <label class="text-xs text-slate-500">Currency</label>
            <select name="currency"
                onchange="this.form.submit()"
                class="border rounded-lg px-2 py-1 text-xs">
                @foreach($supportedCurrencies as $currency)
                    <option value="{{ $currency }}" @selected($currency === $selectedCurrency)>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="flex gap-6">

        {{-- Sidebar Filters --}}
        <aside class="w-56 hidden md:block">
            {{-- Categories --}}
            <h2 class="text-xs font-semibold text-slate-500 uppercase mb-3">Categories</h2>

            <ul class="space-y-1 text-sm mb-6">
                <li>
                    <a href="{{ route('products.index', request()->except('category')) }}"
                       class="block px-2 py-1 rounded-lg {{ !request('category') ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">
                        All
                    </a>
                </li>
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $category->slug])) }}"
                           class="block px-2 py-1 rounded-lg {{ request('category') === $category->slug ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Price Filter --}}
            <h2 class="text-xs font-semibold text-slate-500 uppercase mb-3">Price Range</h2>
            <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                @foreach(['q', 'category', 'currency', 'sort'] as $param)
                    @if(request($param))
                        <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                    @endif
                @endforeach

                <input type="number" name="min_price" placeholder="Min Price"
                       class="border rounded px-3 py-2 w-full text-sm"
                       value="{{ request('min_price') }}">

                <input type="number" name="max_price" placeholder="Max Price"
                       class="border rounded px-3 py-2 w-full text-sm"
                       value="{{ request('max_price') }}">

                <button class="w-full px-3 py-2 bg-emerald-600 text-white rounded text-sm">
                    Apply
                </button>
            </form>

            {{-- Sort By --}}
            <h2 class="text-xs font-semibold text-slate-500 uppercase mt-6 mb-3">Sort By</h2>
            <form method="GET" action="{{ route('products.index') }}">
                @foreach(['q','category','currency','min_price','max_price'] as $param)
                    @if(request($param))
                        <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                    @endif
                @endforeach

                <select name="sort" class="border rounded px-3 py-2 w-full text-sm" onchange="this.form.submit()">
                    <option value="">Default</option>
                    <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Newest</option>
                    <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="price_low_high" {{ request('sort')=='price_low_high' ? 'selected' : '' }}>
                        Price: Low → High
                    </option>
                    <option value="price_high_low" {{ request('sort')=='price_high_low' ? 'selected' : '' }}>
                        Price: High → Low
                    </option>
                </select>
            </form>
        </aside>

        {{-- Product List --}}
        <section class="flex-1">
            @if($products->count())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="group bg-white rounded-2xl border shadow-sm overflow-hidden flex flex-col">

                            {{-- Image --}}
                            <div class="aspect-[4/3] bg-slate-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition">
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-3 flex flex-col gap-1">
                                <h3 class="text-sm font-medium line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <p class="text-xs text-slate-500">
                                    {{ $product->category?->name }}
                                </p>

                                <p class="mt-1 text-sm font-semibold">
                                    {{ $product->display_currency }}
                                    {{ number_format($product->display_price, 2) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <p class="text-sm text-slate-500">No products found.</p>
            @endif
        </section>
    </div>
@endsection
