@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Start New Production</h2>

    <form action="{{ route('productions.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        
        <div>
            <label class="block font-bold mb-1">Select Order:</label>
            <select name="OrderID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled selected>Select an order...</option>
                @foreach($orders as $order)
                    <option value="{{ $order->OrderID }}">Order #{{ $order->OrderID }} (Qty: {{ $order->Quantity }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Start Date:</label>
            <input type="date" name="ProdStartDate" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Finished Date:</label>
            <input type="date" name="ProdFinishedDate" class="w-full border border-gray-300 p-2 rounded">
            <small class="text-gray-500">Leave blank if still in progress</small>
        </div>

        <div>
            <label class="block font-bold mb-1">Production Notes:</label>
            <textarea name="ProductionNote" rows="3" class="w-full border border-gray-300 p-2 rounded" placeholder="e.g., Prototype approved..."></textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Record</button>
            <a href="{{ route('productions.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection