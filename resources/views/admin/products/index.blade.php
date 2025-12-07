@extends('admin.layout')

@section('title', 'Products')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Products</h1>
    <a href="{{ route('admin.products.create') }}"
       class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm">
        + Add Product
    </a>
</div>

<div class="bg-white border rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left">Image</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left">Price</th>
                <th class="px-4 py-3 text-left">Stock</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $product)
                <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="w-12 h-12 rounded object-cover">
                        @else
                            <div class="w-12 h-12 bg-slate-200 rounded"></div>
                        @endif
                    </td>

                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>

                    <td class="px-4 py-3">{{ $product->category->name }}</td>

                    <td class="px-4 py-3">PHP {{ number_format($product->price,2) }}</td>

                    <td class="px-4 py-3">{{ $product->stock }}</td>

                    <td class="px-4 py-3">
                        @if($product->is_active)
                            <span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-slate-200 text-slate-600">Inactive</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-blue-600 hover:underline text-sm">Edit</a>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              class="inline-block ml-3">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm"
                                    onclick="return confirm('Delete this product?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection
