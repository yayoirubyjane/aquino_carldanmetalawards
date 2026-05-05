<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['supplier', 'material'])
            ->latest('StockID')
            ->paginate(10);

        return view('stocks.index', compact('stocks'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('SupplierName')->get();
        $materials = Material::orderBy('MaterialName')->get();

        return view('stocks.create', compact('suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'SupplierID' => 'required|exists:suppliers,SupplierID',
            'material_mode' => 'required|in:existing,new',
            'Material_ID' => 'nullable|exists:materials,Material_ID',
            'MaterialName' => 'nullable|string|max:100',
            'MaterialType' => 'nullable|string|max:50',
            'UnitCost' => 'nullable|numeric|min:0',
            'StockIN' => 'required|integer|min:0',
            'StockOUT' => 'required|integer|min:0',
        ]);

        if ($validated['material_mode'] === 'existing' && empty($validated['Material_ID'])) {
            return back()->withInput()->withErrors([
                'Material_ID' => 'Select an existing material to continue.',
            ]);
        }

        if ($validated['material_mode'] === 'new') {
            $request->validate([
                'MaterialName' => 'required|string|max:100',
                'MaterialType' => 'required|string|max:50',
                'UnitCost' => 'required|numeric|min:0',
            ]);
        }

        DB::transaction(function () use ($validated) {
            $materialId = $validated['Material_ID'] ?? null;

            if ($validated['material_mode'] === 'new') {
                $material = Material::create([
                    'MaterialName' => $validated['MaterialName'],
                    'MaterialType' => $validated['MaterialType'],
                    'UnitCost' => $validated['UnitCost'],
                ]);

                $materialId = $material->Material_ID;
            }

            Stock::create([
                'SupplierID' => $validated['SupplierID'],
                'Material_ID' => $materialId,
                'StockIN' => $validated['StockIN'],
                'StockOUT' => $validated['StockOUT'],
            ]);
        });

        return redirect()->route('stocks.index')->with('success', 'Inventory item saved successfully!');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $suppliers = Supplier::orderBy('SupplierName')->get();
        $materials = Material::orderBy('MaterialName')->get();

        return view('stocks.edit', compact('stock', 'suppliers', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'SupplierID' => 'required|exists:suppliers,SupplierID',
            'Material_ID' => 'required|exists:materials,Material_ID',
            'StockIN' => 'required|integer|min:0',
            'StockOUT' => 'required|integer|min:0',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock entry updated successfully!');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);

        DB::transaction(function () use ($stock) {
            $material = $stock->material;
            $stock->delete();

            if (! $material) {
                return;
            }

            $material->loadCount(['stocks', 'products']);

            if ($material->stocks_count === 0 && $material->products_count === 0) {
                $material->delete();
            }
        });

        return redirect()->route('stocks.index')->with('success', 'Stock entry deleted successfully!');
    }
}
