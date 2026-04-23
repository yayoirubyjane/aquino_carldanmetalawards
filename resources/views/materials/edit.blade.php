@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Material: {{ $material->MaterialName }}</h2>

    <form action="{{ route('materials.update', $material->Material_ID) }}" method="POST" class="flex flex-col gap-4 max-w-md">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block font-bold mb-1">Material Name:</label>
            <input type="text" name="MaterialName" value="{{ $material->MaterialName }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Material Type:</label>
            <input type="text" name="MaterialType" value="{{ $material->MaterialType }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Stocks:</label>
            <input type="number" name="Stocks" value="{{ $material->Stocks }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block font-bold mb-1">Price (₱):</label>
            <input type="number" step="0.01" name="Price" value="{{ $material->Price }}" required class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Update Material</button>
            <a href="{{ route('materials.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
@endsection