@extends('layouts.app')

@section('content')
    <div class="page-card max-w-4xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Edit Stock Entry</h2>
        <p class="mb-6 text-sm text-slate-500">Stock out is system-generated from production usage. You can adjust stock in, supplier, or material here.</p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('stocks.update', $stock->StockID) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Supplier</label>
                    <select name="SupplierID" class="page-select" required>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->SupplierID }}" @selected(old('SupplierID', $stock->SupplierID) == $supplier->SupplierID)>{{ $supplier->SupplierName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Material</label>
                    <select name="Material_ID" class="page-select" required>
                        @foreach ($materials as $material)
                            <option value="{{ $material->Material_ID }}" @selected(old('Material_ID', $stock->Material_ID) == $material->Material_ID)>{{ $material->MaterialName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Stock In</label>
                    <input type="number" min="0" name="StockIN" value="{{ old('StockIN', $stock->StockIN) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Stock Out</label>
                    <input type="text" value="{{ $stock->StockOUT }}" class="page-input bg-slate-100" readonly>
                    <p class="mt-1 text-xs text-slate-500">Automatically deducted when production moves to in progress.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Computed Quantity</label>
                    <input type="text" id="quantity-preview" value="{{ $stock->quantity }}" class="page-input bg-slate-100" readonly>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Update Entry</button>
                <a href="{{ route('stocks.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const stockInInput = document.querySelector('input[name="StockIN"]');
        const quantityPreview = document.getElementById('quantity-preview');
        const stockOutValue = {{ (int) $stock->StockOUT }};

        function updateQuantityPreview() {
            const stockIn = Number(stockInInput.value || 0);
            quantityPreview.value = stockIn - stockOutValue;
        }

        stockInInput.addEventListener('input', updateQuantityPreview);
        updateQuantityPreview();
    </script>
@endsection
