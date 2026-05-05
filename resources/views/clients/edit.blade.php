@extends('layouts.app')

@section('content')
    <div class="page-card max-w-3xl">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Edit Client</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('clients.update', $client->ClientID) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $redirectTo) }}">

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">First Name</label>
                    <input type="text" name="ClientFN" value="{{ old('ClientFN', $client->ClientFN) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Middle Name</label>
                    <input type="text" name="ClientMN" value="{{ old('ClientMN', $client->ClientMN) }}" class="page-input">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Last Name</label>
                    <input type="text" name="ClientLN" value="{{ old('ClientLN', $client->ClientLN) }}" class="page-input" required>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Contact</label>
                <input type="text" name="ClientContact" value="{{ old('ClientContact', $client->ClientContact) }}" class="page-input" required>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Street</label>
                    <input type="text" name="ClientStreet" value="{{ old('ClientStreet', $client->ClientStreet) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Barangay</label>
                    <input type="text" name="ClientBarangay" value="{{ old('ClientBarangay', $client->ClientBarangay) }}" class="page-input">
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                    <input type="text" name="ClientCity" value="{{ old('ClientCity', $client->ClientCity) }}" class="page-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Province</label>
                    <input type="text" name="ClientProvince" value="{{ old('ClientProvince', $client->ClientProvince) }}" class="page-input">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="page-button-primary">Update Client</button>
                <a href="{{ $redirectTo ?: route('clients.index') }}" class="page-button-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
