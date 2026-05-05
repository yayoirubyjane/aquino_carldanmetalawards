@extends('layouts.app')

@section('content')
    <div class="page-card">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Clients</h2>
                <p class="text-sm text-slate-500">Maintain customer contact and address details for orders.</p>
            </div>
            <a href="{{ route('clients.create') }}" class="page-button-primary">Add Client</a>
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
                        <th>Client Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $client->ClientID }}</td>
                            <td class="font-semibold">{{ $client->full_name }}</td>
                            <td>{{ $client->ClientContact }}</td>
                            <td>{{ $client->full_address ?: '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('clients.edit', $client->ClientID) }}" class="page-button-secondary">Edit</a>
                                    <form action="{{ route('clients.destroy', $client->ClientID) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-button-danger" onclick="return confirm('Delete this client?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    </div>
@endsection
