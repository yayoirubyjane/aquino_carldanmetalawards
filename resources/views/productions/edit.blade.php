@extends('layouts.app')

@section('content')
    <div class="page-card max-w-4xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Edit Production #{{ $production->ProductionID }}</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-6 grid gap-4 rounded-xl border border-sky-100 bg-sky-50/50 p-4 md:grid-cols-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Order</p>
                <p class="font-semibold text-slate-900">PO-{{ str_pad($production->OrderID, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Client</p>
                <p class="font-semibold text-slate-900">{{ $production->order?->client?->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Product</p>
                <p class="font-semibold text-slate-900">{{ $production->product?->ProductName ?? 'N/A' }}</p>
            </div>
        </div>

        <form action="{{ route('productions.update', $production->ProductionID) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Production Status</label>
                <select name="ProdStatus" class="page-select" required>
                    @foreach (['Not Started', 'In Progress', 'Finished'] as $status)
                        <option value="{{ $status }}" @selected(old('ProdStatus', $production->ProdStatus) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Start Date</label>
                    <input type="date" name="ProdStartDate" value="{{ old('ProdStartDate', optional($production->ProdStartDate)->format('Y-m-d')) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Finished Date</label>
                    <input type="date" name="ProdFinishedDate" value="{{ old('ProdFinishedDate', optional($production->ProdFinishedDate)->format('Y-m-d')) }}" class="page-input">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Production Note</label>
                <textarea name="ProdNote" rows="4" class="page-textarea">{{ old('ProdNote', $production->ProdNote) }}</textarea>
            </div>

            <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-4">
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Related Order Items</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    @foreach ($order->productOrders as $item)
                        <div>{{ $item->product?->ProductName }} x{{ $item->Quantity }} at PHP {{ number_format($item->Price, 2) }}</div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Update Production</button>
                <a href="{{ route('productions.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
