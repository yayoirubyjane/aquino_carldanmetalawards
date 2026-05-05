@extends('layouts.app')

@section('content')
    <div class="page-card max-w-3xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Add Supplier</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Supplier Name</label>
                <input type="text" name="SupplierName" value="{{ old('SupplierName') }}" class="page-input" required>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Contact</label>
                    <input type="text" name="SupplierContact" value="{{ old('SupplierContact') }}" class="page-input" required>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Street</label>
                    <input type="text" name="SupplierStreet" value="{{ old('SupplierStreet') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Barangay</label>
                    <input type="text" name="SupplierBarangay" value="{{ old('SupplierBarangay') }}" class="page-input">
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                    <input type="text" name="SupplierCity" value="{{ old('SupplierCity') }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Province</label>
                    <input type="text" name="SupplierProvince" value="{{ old('SupplierProvince') }}" class="page-input">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Save Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
