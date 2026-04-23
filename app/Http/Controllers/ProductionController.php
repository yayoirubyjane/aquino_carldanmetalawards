<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        // Eager load the order data
        $productions = Production::with('order')->get();
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        // Fetch orders so the user can select one from a dropdown
        $orders = Order::all();
        return view('productions.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'OrderID' => 'required|exists:orders,OrderID',
            'ProductionNote' => 'nullable|string',
            'ProdStartDate' => 'required|date',
            'ProdFinishedDate' => 'nullable|date|after_or_equal:ProdStartDate',
        ]);

        Production::create($request->all());

        return redirect()->route('productions.index')->with('success', 'Production record created!');
    }

    public function edit($id)
    {
        $production = Production::findOrFail($id);
        $orders = Order::all();
        return view('productions.edit', compact('production', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'OrderID' => 'required|exists:orders,OrderID',
            'ProductionNote' => 'nullable|string',
            'ProdStartDate' => 'required|date',
            'ProdFinishedDate' => 'nullable|date|after_or_equal:ProdStartDate',
        ]);

        $production = Production::findOrFail($id);
        $production->update($request->all());

        return redirect()->route('productions.index')->with('success', 'Production record updated!');
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete();

        return redirect()->route('productions.index')->with('success', 'Production record deleted!');
    }
}