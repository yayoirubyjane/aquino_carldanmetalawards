@extends('layouts.app')

@section('content')
    @php
        $defaultMode = old('material_mode', request('material') ? 'existing' : 'new');
    @endphp

    <div class="page-card max-w-5xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Add Inventory Item</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('stocks.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Material Setup</h3>

                <div class="mb-5 flex flex-wrap gap-4">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="radio" name="material_mode" value="new" class="h-4 w-4" {{ $defaultMode === 'new' ? 'checked' : '' }}>
                        Create New Material
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="radio" name="material_mode" value="existing" class="h-4 w-4" {{ $defaultMode === 'existing' ? 'checked' : '' }}>
                        Use Existing Material
                    </label>
                </div>

                <div id="new-material-fields" class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Material Name</label>
                        <input type="text" name="MaterialName" value="{{ old('MaterialName') }}" class="page-input">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Material Type</label>
                        <input type="text" name="MaterialType" value="{{ old('MaterialType') }}" class="page-input">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Unit Cost</label>
                        <input type="number" step="0.01" min="0" name="UnitCost" value="{{ old('UnitCost') }}" class="page-input">
                    </div>
                </div>

                <div id="existing-material-fields">
                    <div class="max-w-xl">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Existing Material</label>
                        <select name="Material_ID" class="page-select">
                            <option value="">Select material</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->Material_ID }}" @selected(old('Material_ID', request('material')) == $material->Material_ID)>
                                    {{ $material->MaterialName }} | {{ $material->MaterialType }} | PHP {{ number_format($material->UnitCost, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sky-100 bg-white p-4">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Stock Details</h3>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Supplier</label>
                        <select name="SupplierID" class="page-select" required>
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->SupplierID }}" @selected(old('SupplierID') == $supplier->SupplierID)>{{ $supplier->SupplierName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Stock In</label>
                        <input type="number" min="0" name="StockIN" value="{{ old('StockIN', 0) }}" class="page-input" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Stock Out</label>
                        <input type="number" min="0" name="StockOUT" value="{{ old('StockOUT', 0) }}" class="page-input" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Computed Quantity</label>
                        <input type="text" id="quantity-preview" value="0" class="page-input bg-slate-100" readonly>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Inventory Item</button>
                <a href="{{ route('stocks.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const stockInInput = document.querySelector('input[name="StockIN"]');
        const stockOutInput = document.querySelector('input[name="StockOUT"]');
        const quantityPreview = document.getElementById('quantity-preview');
        const materialModeInputs = document.querySelectorAll('input[name="material_mode"]');
        const newMaterialFields = document.getElementById('new-material-fields');
        const existingMaterialFields = document.getElementById('existing-material-fields');

        function updateQuantityPreview() {
            const stockIn = Number(stockInInput.value || 0);
            const stockOut = Number(stockOutInput.value || 0);
            quantityPreview.value = stockIn - stockOut;
        }

        function updateMaterialMode() {
            const selectedMode = document.querySelector('input[name="material_mode"]:checked')?.value;
            const isNew = selectedMode === 'new';

            newMaterialFields.style.display = isNew ? 'grid' : 'none';
            existingMaterialFields.style.display = isNew ? 'none' : 'block';
        }

        stockInInput.addEventListener('input', updateQuantityPreview);
        stockOutInput.addEventListener('input', updateQuantityPreview);
        materialModeInputs.forEach((input) => input.addEventListener('change', updateMaterialMode));

        updateQuantityPreview();
        updateMaterialMode();
    </script>
@endsection
