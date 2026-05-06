@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Products</h2>
                <p class="text-sm text-slate-500">Read-only catalog of product details, pricing, and required materials.</p>
            </div>
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
                            <td>{{ $product->available_stock }}</td>
                            <td>PHP {{ number_format($product->Price, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <a href="{{ route('products.show', $product->ProductID) }}" class="page-button-secondary">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500">No products found.</td>
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
