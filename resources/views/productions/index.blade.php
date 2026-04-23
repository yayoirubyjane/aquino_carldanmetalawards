@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Production Tracking</h2>
        <a href="{{ route('productions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Start New Production</a>
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
                <th class="p-3 border">Order Ref</th>
                <th class="p-3 border">Start Date</th>
                <th class="p-3 border">Finish Date</th>
                <th class="p-3 border">Notes</th>
                <th class="p-3 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productions as $production)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border">{{ $production->ProductionID }}</td>
                <td class="p-3 border font-semibold">Order #{{ $production->OrderID }}</td>
                <td class="p-3 border">{{ $production->ProdStartDate }}</td>
                <td class="p-3 border">
                    @if($production->ProdFinishedDate)
                        <span class="text-green-600 font-bold">{{ $production->ProdFinishedDate }}</span>
                    @else
                        <span class="text-yellow-600 font-bold italic">In Progress</span>
                    @endif
                </td>
                <td class="p-3 border">{{ $production->ProductionNote }}</td>
                <td class="p-3 border flex gap-3">
                    <a href="{{ route('productions.edit', $production->ProductionID) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('productions.destroy', $production->ProductionID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
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