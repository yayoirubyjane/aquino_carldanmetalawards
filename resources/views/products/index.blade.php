@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Products</h2>
                <p class="text-sm text-slate-500">Each product can use multiple materials through the product material list.</p>
            </div>
            <a href="{{ route('products.create') }}" class="page-button-primary">Add Product</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Required Materials</th>
                        <th>Available to Build</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->ProductID }}</td>
                            <td class="font-semibold">{{ $product->ProductName }}</td>
                            <td>{{ $product->ProductType }}</td>
                            <td>
                                @if ($product->materials->isNotEmpty())
                                    {{ $product->materials->map(fn ($material) => $material->MaterialName . ' x' . $material->pivot->RequiredQuantity)->implode(', ') }}
                                @else
                                    <span class="text-slate-500">No materials assigned</span>
                                @endif
                            </td>
                            <td>{{ $product->available_stock }}</td>
                            <td>PHP {{ number_format($product->Price, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product->ProductID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('products.destroy', $product->ProductID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
