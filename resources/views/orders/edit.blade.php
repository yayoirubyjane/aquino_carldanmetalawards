@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Order: PO-{{ str_pad($order->OrderID, 3, '0', STR_PAD_LEFT) }}</h2>

    <form action="{{ route('orders.update', $order->OrderID) }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block font-bold mb-1">Client:</label>
            <select name="ClientID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                @foreach($clients as $client)
                    <option value="{{ $client->ClientID }}" {{ $order->ClientID == $client->ClientID ? 'selected' : '' }}>
                        {{ $client->ClientFN }} {{ $client->ClientLN }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Product:</label>
            <select name="ProductID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                @foreach($products as $product)
                    <option value="{{ $product->ProductID }}" {{ $order->ProductID == $product->ProductID ? 'selected' : '' }}>
                        {{ $product->ProductName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Quantity:</label>
            <input type="number" name="Quantity" min="1" value="{{ $order->Quantity }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Assigned Employee:</label>
            <select name="EmployeeID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                @foreach($employees as $employee)
                    <option value="{{ $employee->EmployeeID }}" {{ $order->EmployeeID == $employee->EmployeeID ? 'selected' : '' }}>
                        {{ $employee->EmployeeFN }} {{ $employee->EmployeeLN }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Update Order</button>
            <a href="{{ route('orders.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection
