@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Inventory</h2>
            <p class="text-sm text-slate-500">Manage stock movement here. Stock out is deducted automatically based on production usage.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('stocks.create') }}" class="page-button-primary">Add Inventory Item</a>
        </div>
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

    <div class="page-card">
        <div class="mb-4">
            <h3 class="text-xl font-bold text-slate-900">Stock Entries</h3>
            <p class="text-sm text-slate-500">Each row represents a material in inventory, linked to its supplier and current stock balance.</p>
        </div>

        <div class="page-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Material</th>
                        <th>Type</th>
                        <th>Unit Cost</th>
                        <th>Supplier</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stock)
                        <tr>
                            <td>{{ $stock->StockID }}</td>
                            <td class="font-semibold">{{ $stock->material?->MaterialName ?? 'N/A' }}</td>
                            <td>{{ $stock->material?->MaterialType ?? 'N/A' }}</td>
                            <td>PHP {{ number_format($stock->material?->UnitCost ?? 0, 2) }}</td>
                            <td>{{ $stock->supplier?->SupplierName ?? 'N/A' }}</td>
                            <td>{{ $stock->StockIN }}</td>
                            <td>{{ $stock->StockOUT }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('stocks.edit', $stock->StockID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('stocks.destroy', $stock->StockID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this stock entry?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-slate-500">No stock entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $stocks->links() }}
        </div>
    </div>
@endsection
