<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Production;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with(['order.client', 'product'])
            ->latest('updated_at')
            ->paginate(10);

        return view('productions.index', compact('productions'));
    }

    public function edit($id)
    {
        $production = Production::with(['order.client', 'product'])->findOrFail($id);
        $order = Order::with('productOrders.product')->findOrFail($production->OrderID);

        return view('productions.edit', compact('production', 'order'));
    }

    public function update(Request $request, $id)
    {
        $production = Production::findOrFail($id);

        $validated = $request->validate([
            'ProdStatus' => ['required', Rule::in(['Not Started', 'In Progress', 'Finished'])],
            'ProdNote' => 'nullable|string',
            'ProdStartDate' => 'required|date',
            'ProdFinishedDate' => 'nullable|date|after_or_equal:ProdStartDate',
        ]);

        $validTransitions = [
            'Not Started' => ['Not Started', 'In Progress'],
            'In Progress' => ['In Progress', 'Finished'],
            'Finished' => ['Finished'],
        ];

        if (! in_array($validated['ProdStatus'], $validTransitions[$production->ProdStatus] ?? [], true)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['ProdStatus' => "Cannot change status from {$production->ProdStatus} to {$validated['ProdStatus']}."]);
        }

        try {
            $production->update($validated);
        } catch (QueryException $e) {
            if ($e->getCode() === '45000') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['ProdStatus' => 'Production cannot continue because there is not enough stock for the required materials.']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['ProdStatus' => 'An error occurred while updating the production record.']);
        }

        return redirect()->route('productions.index')->with('success', 'Production record updated successfully!');
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);

        if ($production->ProdStatus !== 'Not Started') {
            return redirect()->route('productions.index')->with('error', 'Only not started production records can be deleted.');
        }

        $production->delete();

        return redirect()->route('productions.index')->with('success', 'Production record deleted successfully!');
    }
}
