@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Production #{{ $production->ProductionID }}</h2>

    <form action="{{ route('productions.update', $production->ProductionID) }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block font-bold mb-1">Linked Order:</label>
            <select name="OrderID" required class="w-full border border-gray-300 p-2 rounded bg-white">
                <option value="" disabled>Select an order...</option>
                @foreach($orders as $order)
                    <option value="{{ $order->OrderID }}" {{ $production->OrderID == $order->OrderID ? 'selected' : '' }}>
                        Order #{{ $order->OrderID }} (Qty: {{ $order->Quantity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-bold mb-1">Start Date:</label>
            <input type="date" name="ProdStartDate" value="{{ $production->ProdStartDate }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Finished Date:</label>
            <input type="date" name="ProdFinishedDate" value="{{ $production->ProdFinishedDate }}" class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Production Notes:</label>
            <textarea name="ProductionNote" rows="3" class="w-full border border-gray-300 p-2 rounded">{{ $production->ProductionNote }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Update Record</button>
            <a href="{{ route('productions.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection