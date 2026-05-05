<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest('ClientID')->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create(Request $request)
    {
        $redirectTo = $request->query('redirect_to');

        return view('clients.create', compact('redirectTo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ClientFN' => 'required|string|max:50',
            'ClientMN' => 'nullable|string|max:50',
            'ClientLN' => 'required|string|max:50',
            'ClientContact' => 'required|string|max:20',
            'ClientStreet' => 'required|string|max:120',
            'ClientBarangay' => 'nullable|string|max:120',
            'ClientCity' => 'required|string|max:120',
            'ClientProvince' => 'nullable|string|max:120',
        ]);

        Client::create($validated);

        return redirect($this->resolveRedirect($request->input('redirect_to')))
            ->with('success', 'Client added successfully!');
    }

    public function edit(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $redirectTo = $request->query('redirect_to');

        return view('clients.edit', compact('client', 'redirectTo'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ClientFN' => 'required|string|max:50',
            'ClientMN' => 'nullable|string|max:50',
            'ClientLN' => 'required|string|max:50',
            'ClientContact' => 'required|string|max:20',
            'ClientStreet' => 'required|string|max:120',
            'ClientBarangay' => 'nullable|string|max:120',
            'ClientCity' => 'required|string|max:120',
            'ClientProvince' => 'nullable|string|max:120',
        ]);

        $client = Client::findOrFail($id);
        $client->update($validated);

        return redirect($this->resolveRedirect($request->input('redirect_to')))
            ->with('success', 'Client updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('clients.index')->with('error', 'Cannot delete this client because it already has order records.');
            }

            return redirect()->route('clients.index')->with('error', 'An error occurred while deleting the client.');
        }

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }

    protected function resolveRedirect(?string $redirectTo): string
    {
        if (filled($redirectTo) && str_starts_with($redirectTo, '/')) {
            return $redirectTo;
        }

        return route('clients.index');
    }
}
