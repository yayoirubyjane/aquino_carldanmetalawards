@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Create New Order</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf

        @if ($errors->any())
            <div class="rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label class="block font-bold mb-1">Client:</label>
            <select name="ClientID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select a client...</option>
                @foreach($clients as $client)
                    <option value="{{ $client->ClientID }}" {{ old('ClientID') == $client->ClientID ? 'selected' : '' }}>{{ $client->ClientFN }} {{ $client->ClientLN }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Product:</label>
            <select name="ProductID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select a product...</option>
                @foreach($products as $product)
                    <option value="{{ $product->ProductID }}" {{ old('ProductID') == $product->ProductID ? 'selected' : '' }}>
                        {{ $product->ProductName }} (Available: {{ $product->available_stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Quantity:</label>
            <input type="number" name="Quantity" min="1" value="{{ old('Quantity') }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Assigned Employee:</label>
            <select name="EmployeeID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select an employee...</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->EmployeeID }}" {{ old('EmployeeID') == $employee->EmployeeID ? 'selected' : '' }}>{{ $employee->EmployeeFN }} {{ $employee->EmployeeLN }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Order</button>
            <a href="{{ route('orders.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection
