@extends('layouts.app')

@section('content')
    @php
        $initialProducts = old('products', [
            ['ProductID' => '', 'Quantity' => 1, 'Price' => ''],
        ]);
    @endphp

    <div class="page-card">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Create Order</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Client</label>
                    <select name="ClientID" class="page-select" required>
                        <option value="">Select client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->ClientID }}" @selected(old('ClientID') == $client->ClientID)>{{ $client->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Employee</label>
                    <select name="EmployeeID" class="page-select" required>
                        <option value="">Select employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->EmployeeID }}" @selected(old('EmployeeID') == $employee->EmployeeID)>{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Order Date</label>
                    <input type="date" name="OrderDate" value="{{ old('OrderDate') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Delivery Date</label>
                    <input type="date" name="DeliveryDate" value="{{ old('DeliveryDate') }}" class="page-input" required>
                </div>
            </div>

            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Order Items</h3>
                        <p class="text-sm text-slate-500">Add one or more products to this order.</p>
                    </div>
                    <button type="button" id="add-item-button" class="page-button-primary">Add Item</button>
                </div>

                <div id="products-container" class="space-y-4"></div>
            </div>

            <div class="page-card border border-sky-100 bg-sky-50/40">
                <div class="flex flex-col gap-2 text-right">
                    <p class="text-sm text-slate-500">Total amount</p>
                    <p id="total-amount" class="text-3xl font-bold text-sky-700">PHP 0.00</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Order</button>
                <a href="{{ route('orders.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const products = @json($productOptions);

        const oldProducts = @json($initialProducts);
        const container = document.getElementById('products-container');
        const addItemButton = document.getElementById('add-item-button');
        const totalAmount = document.getElementById('total-amount');
        let rowIndex = 0;

        function productOptions(selectedId = '') {
            const defaultOption = '<option value="">Select product</option>';

            return defaultOption + products.map((product) => {
                const selected = Number(selectedId) === Number(product.ProductID) ? 'selected' : '';
                return `<option value="${product.ProductID}" data-price="${product.Price}" ${selected}>${product.ProductName}</option>`;
            }).join('');
        }

        function addProductRow(item = { ProductID: '', Quantity: 1, Price: '' }) {
            const row = document.createElement('div');
            row.className = 'grid gap-4 rounded-xl bg-white p-4 md:grid-cols-[2fr_1fr_1fr_1fr_auto]';
            row.innerHTML = `
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Product</label>
                    <select name="products[${rowIndex}][ProductID]" class="page-select product-select" required>
                        ${productOptions(item.ProductID)}
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Quantity</label>
                    <input type="number" min="1" name="products[${rowIndex}][Quantity]" value="${item.Quantity ?? 1}" class="page-input quantity-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Price</label>
                    <input type="number" step="0.01" min="0" name="products[${rowIndex}][Price]" value="${item.Price ?? ''}" class="page-input price-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Line Total</label>
                    <input type="text" class="page-input line-total bg-slate-100" value="PHP 0.00" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" class="page-button-danger remove-item-button">Remove</button>
                </div>
            `;

            container.appendChild(row);
            rowIndex++;
            attachEvents(row);
            syncRowPrice(row, false);
            calculateTotals();
        }

        function attachEvents(row) {
            row.querySelector('.product-select').addEventListener('change', () => {
                syncRowPrice(row, true);
                calculateTotals();
            });

            row.querySelector('.quantity-input').addEventListener('input', calculateTotals);
            row.querySelector('.price-input').addEventListener('input', calculateTotals);
            row.querySelector('.remove-item-button').addEventListener('click', () => {
                row.remove();
                calculateTotals();
            });
        }

        function syncRowPrice(row, overwrite) {
            const select = row.querySelector('.product-select');
            const selectedOption = select.options[select.selectedIndex];
            const priceInput = row.querySelector('.price-input');

            if (overwrite || !priceInput.value) {
                priceInput.value = selectedOption?.dataset?.price ?? '';
            }
        }

        function calculateTotals() {
            let total = 0;

            document.querySelectorAll('#products-container > div').forEach((row) => {
                const quantity = Number(row.querySelector('.quantity-input').value || 0);
                const price = Number(row.querySelector('.price-input').value || 0);
                const lineTotal = quantity * price;

                row.querySelector('.line-total').value = `PHP ${lineTotal.toFixed(2)}`;
                total += lineTotal;
            });

            totalAmount.textContent = `PHP ${total.toFixed(2)}`;
        }

        addItemButton.addEventListener('click', () => addProductRow());
        oldProducts.forEach((item) => addProductRow(item));
    </script>
@endsection
