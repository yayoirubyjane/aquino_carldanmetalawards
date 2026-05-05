@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Orders</h2>
                <p class="text-sm text-slate-500">Each order can contain multiple products with individual quantities and prices.</p>
            </div>
            <a href="{{ route('orders.create') }}" class="page-button-primary">Create Order</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-table">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Client</th>
                        <th>Employee</th>
                        <th>Dates</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="font-semibold">PO-{{ str_pad($order->OrderID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->client?->full_name ?? 'N/A' }}</td>
                            <td>{{ $order->employee?->full_name ?? 'N/A' }}</td>
                            <td>
                                <div>Order: {{ optional($order->OrderDate)->format('M d, Y') ?? '-' }}</div>
                                <div>Delivery: {{ optional($order->DeliveryDate)->format('M d, Y') ?? '-' }}</div>
                            </td>
                            <td>
                                @foreach ($order->productOrders as $item)
                                    <div>{{ $item->product?->ProductName }} x{{ $item->Quantity }}</div>
                                @endforeach
                            </td>
                            <td>PHP {{ number_format($order->total_amount, 2) }}</td>
                            <td>PHP {{ number_format($order->amount_paid, 2) }}</td>
                            <td>
                                <span class="status-pill {{ $order->OrderStatus === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($order->OrderStatus === 'In Production' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $order->OrderStatus }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('orders.edit', $order->OrderID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('orders.destroy', $order->OrderID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this order?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-slate-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
