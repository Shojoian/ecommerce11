@extends('admin.layout')

@section('title', 'Add Product')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Add Product</h1>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
      class="bg-white border rounded-xl shadow p-6 space-y-6">
    @csrf

    <div>
        <label class="text-sm font-medium">Name</label>
        <input type="text" name="name"
               class="w-full border rounded-lg px-3 py-2"
               required>
    </div>

    <div>
        <label class="text-sm font-medium">Category</label>
        <select name="category_id" class="w-full border rounded-lg px-3 py-2">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium">Price (PHP)</label>
            <input type="number" step="0.01" name="price"
                   class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm font-medium">Stock</label>
            <input type="number" name="stock"
                   class="w-full border rounded-lg px-3 py-2" required>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium">Description</label>
        <textarea name="description" rows="4"
                  class="w-full border rounded-lg px-3 py-2"></textarea>
    </div>

    <div>
        <label class="text-sm font-medium">Product Image</label>
        <input type="file" name="image" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div class="flex items-center space-x-2">
        <input type="checkbox" name="is_active" checked>
        <label class="text-sm">Active</label>
    </div>

    <button class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
        Create Product
    </button>
</form>

@endsection
