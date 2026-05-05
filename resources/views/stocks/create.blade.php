@extends('layouts.app')

@section('content')
    @php
        $defaultMode = old('material_mode', request('material') ? 'existing' : 'new');
        $defaultSupplierMode = old('supplier_mode', 'existing');
    @endphp

    <div class="page-card max-w-5xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Add Inventory Item</h2>
        <p class="mb-6 text-sm text-slate-500">Stock out is deducted automatically once production starts, so you only need to encode incoming stock here.</p>

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

                <div class="mb-5 flex flex-wrap gap-4">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="radio" name="supplier_mode" value="existing" class="h-4 w-4" {{ $defaultSupplierMode === 'existing' ? 'checked' : '' }}>
                        Use Existing Supplier
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="radio" name="supplier_mode" value="new" class="h-4 w-4" {{ $defaultSupplierMode === 'new' ? 'checked' : '' }}>
                        Add Supplier Here
                    </label>
                </div>

                <div id="existing-supplier-fields">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Supplier</label>
                            <select name="SupplierID" class="page-select">
                                <option value="">Select supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->SupplierID }}" @selected(old('SupplierID') == $supplier->SupplierID)>{{ $supplier->SupplierName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="new-supplier-fields" class="space-y-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Supplier Name</label>
                            <input type="text" name="SupplierName" value="{{ old('SupplierName') }}" class="page-input">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Contact</label>
                            <input type="text" name="SupplierContact" value="{{ old('SupplierContact') }}" class="page-input">
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Street</label>
                            <input type="text" name="SupplierStreet" value="{{ old('SupplierStreet') }}" class="page-input">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Barangay</label>
                            <input type="text" name="SupplierBarangay" value="{{ old('SupplierBarangay') }}" class="page-input">
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                            <input type="text" name="SupplierCity" value="{{ old('SupplierCity') }}" class="page-input">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Province</label>
                            <input type="text" name="SupplierProvince" value="{{ old('SupplierProvince') }}" class="page-input">
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Stock In</label>
                        <input type="number" min="0" name="StockIN" value="{{ old('StockIN', 0) }}" class="page-input" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Available Quantity</label>
                        <input type="text" id="quantity-preview" value="{{ old('StockIN', 0) }}" class="page-input bg-slate-100" readonly>
                        <p class="mt-1 text-xs text-slate-500">This starts equal to stock in. Stock out will be updated by the system.</p>
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
        const quantityPreview = document.getElementById('quantity-preview');
        const materialModeInputs = document.querySelectorAll('input[name="material_mode"]');
        const supplierModeInputs = document.querySelectorAll('input[name="supplier_mode"]');
        const newMaterialFields = document.getElementById('new-material-fields');
        const existingMaterialFields = document.getElementById('existing-material-fields');
        const newSupplierFields = document.getElementById('new-supplier-fields');
        const existingSupplierFields = document.getElementById('existing-supplier-fields');

        function updateQuantityPreview() {
            const stockIn = Number(stockInInput.value || 0);
            quantityPreview.value = stockIn;
        }

        function updateMaterialMode() {
            const selectedMode = document.querySelector('input[name="material_mode"]:checked')?.value;
            const isNew = selectedMode === 'new';

            newMaterialFields.style.display = isNew ? 'grid' : 'none';
            existingMaterialFields.style.display = isNew ? 'none' : 'block';
        }

        function updateSupplierMode() {
            const selectedMode = document.querySelector('input[name="supplier_mode"]:checked')?.value;
            const isNew = selectedMode === 'new';

            newSupplierFields.style.display = isNew ? 'block' : 'none';
            existingSupplierFields.style.display = isNew ? 'none' : 'block';
        }

        stockInInput.addEventListener('input', updateQuantityPreview);
        materialModeInputs.forEach((input) => input.addEventListener('change', updateMaterialMode));
        supplierModeInputs.forEach((input) => input.addEventListener('change', updateSupplierMode));

        updateQuantityPreview();
        updateMaterialMode();
        updateSupplierMode();
    </script>
@endsection
