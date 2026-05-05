@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Production</h2>
            <p class="text-sm text-slate-500">Production records are created automatically from order items.</p>
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
                        <th>Order</th>
                        <th>Client</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Finish Date</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productions as $production)
                        <tr>
                            <td>{{ $production->ProductionID }}</td>
                            <td>PO-{{ str_pad($production->OrderID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $production->order?->client?->full_name ?? 'N/A' }}</td>
                            <td>{{ $production->product?->ProductName ?? 'N/A' }}</td>
                            <td>
                                <span class="status-pill {{ $production->ProdStatus === 'Finished' ? 'bg-emerald-100 text-emerald-700' : ($production->ProdStatus === 'In Progress' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $production->ProdStatus }}
                                </span>
                            </td>
                            <td>{{ optional($production->ProdStartDate)->format('M d, Y') ?? '-' }}</td>
                            <td>{{ optional($production->ProdFinishedDate)->format('M d, Y') ?? '-' }}</td>
                            <td>{{ $production->ProdNote ?: '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('productions.edit', $production->ProductionID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('productions.destroy', $production->ProductionID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this production record?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-slate-500">No production records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $productions->links() }}
        </div>
    </div>
@endsection
