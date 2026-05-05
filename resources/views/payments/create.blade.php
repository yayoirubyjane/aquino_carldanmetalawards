@extends('layouts.app')

@section('content')
    <div class="page-card max-w-4xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Record Payment</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('payments.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Order</label>
                    <select name="OrderID" id="order-select" class="page-select" required>
                        <option value="">Select order</option>
                        @foreach ($orders as $order)
                            <option
                                value="{{ $order->OrderID }}"
                                data-total="{{ $order->total_amount }}"
                                data-paid="{{ $order->amount_paid }}"
                                data-first="{{ $order->expected_payments[0] }}"
                                data-second="{{ $order->expected_payments[1] }}"
                                data-count="{{ $order->payments->count() }}"
                                @selected(old('OrderID') == $order->OrderID)
                            >
                                PO-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }} - {{ $order->client?->full_name }}
                            </option>
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
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Payment Method</label>
                    <select name="PaymentMethod" class="page-select" required>
                        @foreach (['Cash', 'Check', 'Bank Transfer', 'Credit Card', 'Other'] as $method)
                            <option value="{{ $method }}" @selected(old('PaymentMethod') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Payment Date</label>
                    <input type="date" name="PaymentDate" value="{{ old('PaymentDate') }}" class="page-input" required>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Amount</label>
                    <input type="number" step="0.01" min="0.01" name="Amount" id="amount-input" value="{{ old('Amount') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Reference Number</label>
                    <input type="text" name="ReferenceNumber" value="{{ old('ReferenceNumber') }}" class="page-input">
                </div>
            </div>

            <div id="payment-summary" class="rounded-xl border border-sky-100 bg-sky-50/50 p-4 text-sm text-slate-600">
                Select an order to view the required installment amount.
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Payment</button>
                <a href="{{ route('payments.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const orderSelect = document.getElementById('order-select');
        const amountInput = document.getElementById('amount-input');
        const paymentSummary = document.getElementById('payment-summary');

        function updatePaymentSummary() {
            const selected = orderSelect.options[orderSelect.selectedIndex];

            if (!selected || !selected.value) {
                paymentSummary.textContent = 'Select an order to view the required installment amount.';
                return;
            }

            const total = Number(selected.dataset.total || 0);
            const paid = Number(selected.dataset.paid || 0);
            const first = Number(selected.dataset.first || 0);
            const second = Number(selected.dataset.second || 0);
            const count = Number(selected.dataset.count || 0);
            const expected = count === 0 ? first : second;

            if (!amountInput.value) {
                amountInput.value = expected.toFixed(2);
            }

            paymentSummary.innerHTML = `
                <div>Total order amount: PHP ${total.toFixed(2)}</div>
                <div>Amount already paid: PHP ${paid.toFixed(2)}</div>
                <div>Required installment amount: PHP ${expected.toFixed(2)}</div>
                <div>Payments already recorded: ${count} of 2</div>
            `;
        }

        orderSelect.addEventListener('change', updatePaymentSummary);
        updatePaymentSummary();
    </script>
@endsection
