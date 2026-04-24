@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Add New Product</h2>

    <form action="{{ route('products.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf

        @if ($errors->any())
            <div class="rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label class="block font-bold mb-1">Product Name:</label>
            <input type="text" name="ProductName" value="{{ old('ProductName') }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Product Type:</label>
            <input type="text" name="ProductType" value="{{ old('ProductType') }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Materials Required Per Product Unit:</label>
            <div class="space-y-3 rounded border border-gray-300 p-4">
                @foreach($materials as $material)
                    <div class="grid grid-cols-[1fr_140px] items-center gap-3">
                        <div>
                            <p class="font-semibold">{{ $material->MaterialName }}</p>
                            <p class="text-sm text-gray-500">{{ $material->MaterialType }} | Stock: {{ $material->Stocks }}</p>
                        </div>
                        <input
                            type="number"
                            name="materials[{{ $material->Material_ID }}]"
                            min="0"
                            value="{{ old('materials.' . $material->Material_ID, 0) }}"
                            class="w-full border border-gray-300 p-2 rounded"
                            placeholder="Qty needed"
                        >
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block font-bold mb-1">Selling Price (PHP):</label>
            <input type="number" step="0.01" name="Price" value="{{ old('Price') }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Product</button>
            <a href="{{ route('products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection
