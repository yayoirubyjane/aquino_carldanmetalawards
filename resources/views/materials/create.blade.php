@extends('layouts.app')

@section('content')
    <div class="page-card max-w-3xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Add Material</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('materials.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Material Name</label>
                    <input type="text" name="MaterialName" value="{{ old('MaterialName') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Material Type</label>
                    <input type="text" name="MaterialType" value="{{ old('MaterialType') }}" class="page-input" required>
                </div>
            </div>

            <div class="max-w-sm">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Unit Cost</label>
                <input type="number" step="0.01" min="0" name="UnitCost" value="{{ old('UnitCost') }}" class="page-input" required>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Material</button>
                <a href="{{ route('materials.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
