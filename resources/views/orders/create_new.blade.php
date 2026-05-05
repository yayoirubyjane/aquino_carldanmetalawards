@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Create New Order</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="max-w-4xl">
        @csrf

        @if ($errors->any())
            <div class="rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Order Header Section -->
        <div class="bg-gray-50 p-4 rounded mb-6 border border-gray-300">
            <h3 class="text-lg font-bold mb-4">Order Details</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1">Client:</label>
                    <select name="ClientID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->ClientID }}" {{ old('ClientID') == $client->ClientID ? 'selected' : '' }}>
                                {{ $client->ClientFN }} {{ $client->ClientLN }}
                            </option>
                        @endforeach
                    </select>
                    @error('ClientID')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold mb-1">Assigned Employee:</label>
                    <select name="EmployeeID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->EmployeeID }}" {{ old('EmployeeID') == $employee->EmployeeID ? 'selected' : '' }}>
                                {{ $employee->EmployeeFN }} {{ $employee->EmployeeLN }}
                            </option>
                        @endforeach
                    </select>
                    @error('EmployeeID')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold mb-1">Order Date:</label>
                    <input type="date" name="OrderDate" value="{{ old('OrderDate') }}" required class="w-full border border-gray-300 p-2 rounded">
                    @error('OrderDate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold mb-1">Delivery Date:</label>
                    <input type="date" name="DeliveryDate" value="{{ old('DeliveryDate') }}" required class="w-full border border-gray-300 p-2 rounded">
                    @error('DeliveryDate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <h3 class="text-lg font-bold mb-4">Order Items</h3>
        
        <div id="products-container" class="mb-6">
            <!-- Product rows will be added here by JavaScript -->
        </div>

        <button type="button" onclick="addProductRow()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-6">+ Add Product</button>

        <!-- Total Section -->
        <div class="bg-gray-50 p-4 rounded mb-6 border border-gray-300">
            <div class="text-right">
                <h3 class="text-lg font-bold">Total Amount: <span id="total-amount">₱0.00</span></h3>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Order</button>
            <a href="{{ route('orders.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>

    <script>
        let productRowCount = 0;

        function addProductRow(productId = '', quantity = '', price = '') {
            const container = document.getElementById('products-container');
            const row = document.createElement('div');
            row.className = 'product-row bg-white p-4 rounded border border-gray-300 mb-4 grid grid-cols-5 gap-4';
            row.innerHTML = `
                <div>
                    <label class="block font-bold mb-1">Product:</label>
                    <select name="products[${productRowCount}][ProductID]" required class="w-full border border-gray-300 p-2 rounded product-select" onchange="updateProductPrice(this)">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->ProductID }}" data-price="{{ $product->Price }}" {{ $productId == $product->ProductID ? 'selected' : '' }}>
                                {{ $product->ProductName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-1">Quantity:</label>
                    <input type="number" name="products[${productRowCount}][Quantity]" value="${quantity}" min="1" required class="w-full border border-gray-300 p-2 rounded quantity-input" onchange="calculateTotal()">
                </div>

                <div>
                    <label class="block font-bold mb-1">Price (₱):</label>
                    <input type="number" name="products[${productRowCount}][Price]" value="${price}" step="0.01" min="0" required class="w-full border border-gray-300 p-2 rounded price-input" onchange="calculateTotal()">
                </div>

                <div>
                    <label class="block font-bold mb-1">Total:</label>
                    <div class="w-full border border-gray-300 p-2 rounded bg-gray-50 line-item-total">₱0.00</div>
                </div>

                <div>
                    <label class="block font-bold mb-1">Action:</label>
                    <button type="button" onclick="removeProductRow(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Remove</button>
                </div>
            `;
            container.appendChild(row);
            productRowCount++;
            calculateTotal();
        }

        function removeProductRow(button) {
            button.closest('.product-row').remove();
            calculateTotal();
        }

        function updateProductPrice(select) {
            const price = select.options[select.selectedIndex].dataset.price || 0;
            const priceInput = select.closest('.product-row').querySelector('.price-input');
            priceInput.value = price;
            calculateTotal();
        }

        function calculateTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const lineTotal = quantity * price;
                row.querySelector('.line-item-total').textContent = '₱' + lineTotal.toFixed(2);
                grandTotal += lineTotal;
            });
            document.getElementById('total-amount').textContent = '₱' + grandTotal.toFixed(2);
        }

        // Initialize with one empty product row
        addProductRow();
    </script>
@endsection
