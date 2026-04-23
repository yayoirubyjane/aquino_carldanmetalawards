@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Add New Material</h2>

    <form action="{{ route('materials.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        
        <div>
            <label class="block font-bold mb-1">Material Name:</label>
            <input type="text" name="MaterialName" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Material Type:</label>
            <input type="text" name="MaterialType" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Stocks:</label>
            <input type="number" name="Stocks" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Price (₱):</label>
            <input type="number" step="0.01" name="Price" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded">Save Material</button>
            <a href="{{ route('materials.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection