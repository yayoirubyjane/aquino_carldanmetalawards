@extends('layouts.app')

@section('content')
    <div class="page-card max-w-4xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Add Product</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Product Name</label>
                    <input type="text" name="ProductName" value="{{ old('ProductName') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Product Type</label>
                    <input type="text" name="ProductType" value="{{ old('ProductType') }}" class="page-input" required>
                </div>
            </div>

            <div class="max-w-sm">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Selling Price</label>
                <input type="number" step="0.01" min="0" name="Price" value="{{ old('Price') }}" class="page-input" required>
            </div>

            <div>
                <label class="mb-3 block text-sm font-semibold text-slate-700">Materials Required Per Unit</label>
                <div class="space-y-3 rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                    @foreach ($materials as $material)
                        <div class="grid gap-3 rounded-lg bg-white p-4 md:grid-cols-[1fr_180px]">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $material->MaterialName }}</p>
                                <p class="text-sm text-slate-500">{{ $material->MaterialType }} | Stock: {{ $material->current_quantity }} | Cost: PHP {{ number_format($material->UnitCost, 2) }}</p>
                            </div>
                            <input
                                type="number"
                                name="materials[{{ $material->Material_ID }}]"
                                min="0"
                                value="{{ old('materials.' . $material->Material_ID, 0) }}"
                                class="page-input"
                                placeholder="Qty needed"
                            >
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
