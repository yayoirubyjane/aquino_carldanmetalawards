@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Suppliers</h2>
                <p class="text-sm text-slate-500">Maintain supplier contact details for inventory purchases.</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="page-button-primary">Add Supplier</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->SupplierID }}</td>
                            <td class="font-semibold">{{ $supplier->SupplierName }}</td>
                            <td>{{ $supplier->SupplierContact }}</td>
                            <td>{{ $supplier->full_address ?: '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('suppliers.edit', $supplier->SupplierID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier->SupplierID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this supplier?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">No suppliers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection
