@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Create New Order</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        
        <div>
            <label class="block font-bold mb-1">Client:</label>
            <select name="ClientID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select a client...</option>
                @foreach($clients as $client)
                    <option value="{{ $client->ClientID }}">{{ $client->ClientFN }} {{ $client->ClientLN }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Product:</label>
            <select name="ProductID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select a product...</option>
                @foreach($products as $product)
                    <option value="{{ $product->ProductID }}">{{ $product->ProductName }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Quantity:</label>
            <input type="number" name="Quantity" min="1" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Assigned Employee:</label>
            <select name="EmployeeID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select an employee...</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->EmployeeID }}">{{ $employee->EmployeeFN }} {{ $employee->EmployeeLN }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Order</button>
            <a href="{{ route('orders.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection
