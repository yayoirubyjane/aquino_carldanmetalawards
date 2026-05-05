@extends('layouts.app')

@section('content')
    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <div class="page-card border border-sky-100 bg-gradient-to-br from-sky-500 to-sky-700 text-white">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-100">Total Revenue</p>
                <p class="mt-3 text-3xl font-bold">PHP {{ number_format($totalRevenue, 2) }}</p>
                <p class="mt-2 text-sm text-sky-100">Based on all order items in the system.</p>
            </div>
        </div>

        <div class="page-card border border-sky-100 bg-white">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">Amount Collected</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">PHP {{ number_format($amountCollected, 2) }}</p>
                <p class="mt-2 text-sm text-slate-500">Downpayments and final payments already received.</p>
            </div>
        </div>

        <div class="page-card border border-amber-100 bg-amber-50">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Pending Balance</p>
                <p class="mt-3 text-3xl font-bold text-amber-900">PHP {{ number_format($pendingBalance, 2) }}</p>
                <p class="mt-2 text-sm text-amber-700">Remaining amount to collect from active orders.</p>
            </div>
        </div>
    </div>

    <div class="page-card mb-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Sales & Payments</h2>
                <p class="text-sm text-slate-500">Each order supports two payments: a 50% downpayment and a 50% final payment.</p>
            </div>
            <a href="{{ route('payments.create') }}" class="page-button-primary">Record Payment</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Client</th>
                        <th>Employee</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->PaymentID }}</td>
                            <td>PO-{{ str_pad($payment->OrderID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $payment->order?->client?->full_name ?? 'N/A' }}</td>
                            <td>{{ $payment->employee?->full_name ?? 'N/A' }}</td>
                            <td>{{ $payment->PaymentMethod }}</td>
                            <td>{{ optional($payment->PaymentDate)->format('M d, Y') ?? '-' }}</td>
                            <td>PHP {{ number_format((float) $payment->Amount, 2) }}</td>
                            <td>{{ $payment->ReferenceNumber ?: '-' }}</td>
                            <td>
                                <span class="status-pill {{ $payment->order?->OrderStatus === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($payment->order?->OrderStatus === 'In Production' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $payment->order?->OrderStatus ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('payments.edit', $payment->PaymentID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('payments.destroy', $payment->PaymentID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-slate-500">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <div class="page-card">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-slate-900">Order Settlement Summary</h3>
                <p class="text-sm text-slate-500">Progress of each order based on item totals and payments received.</p>
            </div>

            <div class="page-table">
                <table>
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Collected</th>
                            <th>Pending</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesOrders as $order)
                            <tr>
                                <td>PO-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->client?->full_name ?? 'N/A' }}</td>
                                <td>PHP {{ number_format($order->total_amount, 2) }}</td>
                                <td>PHP {{ number_format($order->amount_paid, 2) }}</td>
                                <td>PHP {{ number_format($order->balance_due, 2) }}</td>
                                <td>
                                    <span class="status-pill {{ $order->OrderStatus === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($order->OrderStatus === 'In Production' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $order->OrderStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">No sales records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="page-card">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Payment Details View</h3>
                    <p class="text-sm text-slate-500">Latest rows from <code>vw_PaymentDetails</code>.</p>
                </div>

                <div class="space-y-3">
                    @forelse ($paymentDetails as $detail)
                        <div class="rounded-xl border border-sky-100 bg-sky-50/40 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">PO-{{ str_pad($detail->OrderID, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-sm text-slate-500">{{ $detail->ClientFullName }}</p>
                                </div>
                                <span class="status-pill {{ $detail->OrderStatus === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($detail->OrderStatus === 'In Production' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $detail->OrderStatus }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ $detail->PaymentMethod }} | PHP {{ number_format((float) $detail->Amount, 2) }}</p>
                            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($detail->PaymentDate)->format('M d, Y') }} | {{ $detail->EmployeeFullName }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No payment view rows found.</p>
                    @endforelse
                </div>
            </div>

            <div class="page-card">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Order Items View</h3>
                    <p class="text-sm text-slate-500">Latest rows from <code>vw_OrderItems</code>.</p>
                </div>

                <div class="space-y-3">
                    @forelse ($orderItems as $item)
                        <div class="rounded-xl border border-sky-100 bg-white p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">PO-{{ str_pad($item->OrderID, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->ProductName }} | {{ $item->ProductType }}</p>
                                </div>
                                <p class="font-semibold text-sky-700">PHP {{ number_format((float) $item->TotalItemCost, 2) }}</p>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">Qty: {{ $item->Quantity }} at PHP {{ number_format((float) $item->Price, 2) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No order item view rows found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
