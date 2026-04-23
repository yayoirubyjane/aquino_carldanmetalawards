@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Products Inventory</h2>
        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Add New Product</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 border">ID</th>
                <th class="p-3 border">Product Name</th>
                <th class="p-3 border">Type</th>
                <th class="p-3 border">Material Used</th>
                <th class="p-3 border">Price</th>
                <th class="p-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border">{{ $product->ProductID }}</td>
                <td class="p-3 border font-semibold">{{ $product->ProductName }}</td>
                <td class="p-3 border">{{ $product->ProductType }}</td>
                <td class="p-3 border">{{ $product->material ? $product->material->MaterialName : 'None' }}</td>
                <td class="p-3 border">₱{{ number_format($product->Price, 2) }}</td>
                <td class="p-3 border flex gap-3">
                    <a href="{{ route('products.edit', $product->ProductID) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('products.destroy', $product->ProductID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection