@extends('layouts.app')

@section('content')
    <div class="page-card max-w-5xl">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-500">Product Details</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ $product->ProductName }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $product->ProductType }} | Available to build: {{ $product->available_stock }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="page-button-secondary">Back to Products</a>
        </div>

        <div class="mb-6 grid gap-5 md:grid-cols-3">
            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-5">
                <p class="text-sm font-semibold text-slate-500">Product ID</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $product->ProductID }}</p>
            </div>

            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-5">
                <p class="text-sm font-semibold text-slate-500">Selling Price</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">PHP {{ number_format($product->Price, 2) }}</p>
            </div>

            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-5">
                <p class="text-sm font-semibold text-slate-500">Material Count</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $product->materials->count() }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-sky-100 bg-white p-5">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-slate-900">Required Materials</h3>
                <p class="text-sm text-slate-500">Materials and quantities needed to produce one unit of this product.</p>
            </div>

            <div class="page-table">
                <table>
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Type</th>
                            <th>Required Qty</th>
                            <th>Unit Cost</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->materials as $material)
                            <tr>
                                <td class="font-semibold">{{ $material->MaterialName }}</td>
                                <td>{{ $material->MaterialType }}</td>
                                <td>{{ $material->pivot->RequiredQuantity }}</td>
                                <td>PHP {{ number_format($material->UnitCost, 2) }}</td>
                                <td>{{ $material->current_quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">No materials assigned to this product.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
