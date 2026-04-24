@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Orders Management</h2>
        <a href="{{ route('orders.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Create New Order</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 border">Order ID</th>
                <th class="p-3 border">Client Name</th>
                <th class="p-3 border">Product</th>
                <th class="p-3 border">Quantity</th>
                <th class="p-3 border">Buildable Stock</th>
                <th class="p-3 border">Handled By</th>
                <th class="p-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border font-semibold">PO-{{ str_pad($order->OrderID, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="p-3 border">{{ $order->client ? $order->client->ClientFN . ' ' . $order->client->ClientLN : 'N/A' }}</td>
                <td class="p-3 border">{{ $order->product ? $order->product->ProductName : 'N/A' }}</td>
                <td class="p-3 border">{{ $order->Quantity }}</td>
                <td class="p-3 border">{{ $order->product ? $order->product->available_stock : 'N/A' }}</td>
                <td class="p-3 border">{{ $order->employee ? $order->employee->EmployeeFN : 'N/A' }}</td>
                <td class="p-3 border flex gap-3">
                    <a href="{{ route('orders.edit', $order->OrderID) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('orders.destroy', $order->OrderID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
