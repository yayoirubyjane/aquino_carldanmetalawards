@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Materials Inventory</h2>
        <a href="{{ route('materials.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Add New Material</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 border">ID</th>
                <th class="p-3 border">Name</th>
                <th class="p-3 border">Type</th>
                <th class="p-3 border">Stocks</th>
                <th class="p-3 border">Price</th>
                <th class="p-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border">{{ $material->Material_ID }}</td>
                <td class="p-3 border font-semibold">{{ $material->MaterialName }}</td>
                <td class="p-3 border">{{ $material->MaterialType }}</td>
                <td class="p-3 border">{{ $material->Stocks }}</td>
                <td class="p-3 border">₱{{ number_format($material->Price, 2) }}</td>
                <td class="p-3 border flex gap-3">
                    <a href="{{ route('materials.edit', $material->Material_ID) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('materials.destroy', $material->Material_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?');">
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