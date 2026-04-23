@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Product: {{ $product->ProductName }}</h2>

    <form action="{{ route('products.update', $product->ProductID) }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block font-bold mb-1">Product Name:</label>
            <input type="text" name="ProductName" value="{{ $product->ProductName }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Product Type:</label>
            <input type="text" name="ProductType" value="{{ $product->ProductType }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Raw Material:</label>
            <select name="Material_ID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled>Select a material...</option>
                @foreach($materials as $material)
                    <option value="{{ $material->Material_ID }}" {{ $product->Material_ID == $material->Material_ID ? 'selected' : '' }}>
                        {{ $material->MaterialName }} ({{ $material->MaterialType }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Selling Price (₱):</label>
            <input type="number" step="0.01" name="Price" value="{{ $product->Price }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Update Product</button>
            <a href="{{ route('products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection