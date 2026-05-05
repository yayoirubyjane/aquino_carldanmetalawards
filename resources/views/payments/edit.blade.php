@extends('layouts.app')

@section('content')
    <div class="page-card max-w-4xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Edit Payment</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('payments.update', $payment->PaymentID) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Order</label>
                    <select name="OrderID" class="page-select" required>
                        @foreach ($orders as $order)
                            <option value="{{ $order->OrderID }}" @selected(old('OrderID', $payment->OrderID) == $order->OrderID)>
                                PO-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }} - {{ $order->client?->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Employee</label>
                    <select name="EmployeeID" class="page-select" required>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->EmployeeID }}" @selected(old('EmployeeID', $payment->EmployeeID) == $employee->EmployeeID)>{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Payment Method</label>
                    <select name="PaymentMethod" class="page-select" required>
                        @foreach (['Cash', 'Check', 'Bank Transfer', 'Credit Card', 'Other'] as $method)
                            <option value="{{ $method }}" @selected(old('PaymentMethod', $payment->PaymentMethod) === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Payment Date</label>
                    <input type="date" name="PaymentDate" value="{{ old('PaymentDate', optional($payment->PaymentDate)->format('Y-m-d')) }}" class="page-input" required>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Amount</label>
                    <input type="number" step="0.01" min="0.01" name="Amount" value="{{ old('Amount', $payment->Amount) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Reference Number</label>
                    <input type="text" name="ReferenceNumber" value="{{ old('ReferenceNumber', $payment->ReferenceNumber) }}" class="page-input">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Update Payment</button>
                <a href="{{ route('payments.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
