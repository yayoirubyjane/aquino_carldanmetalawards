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
        $materials = $this->getSelectableMaterials();

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

        $result = DB::transaction(function () use ($validated) {
            $materialId = $validated['Material_ID'] ?? null;
            $createdMaterial = false;
            $mergedStock = false;

            if ($validated['material_mode'] === 'new') {
                $material = Material::whereRaw('LOWER(MaterialName) = ?', [strtolower(trim($validated['MaterialName']))])
                    ->whereRaw('LOWER(MaterialType) = ?', [strtolower(trim($validated['MaterialType']))])
                    ->orderByDesc('updated_at')
                    ->orderByDesc('Material_ID')
                    ->first();

                if (! $material) {
                    $material = Material::create([
                        'MaterialName' => $validated['MaterialName'],
                        'MaterialType' => $validated['MaterialType'],
                        'UnitCost' => $validated['UnitCost'],
                    ]);

                    $createdMaterial = true;
                }

                $materialId = $material->Material_ID;
            }

            $stock = Stock::where('SupplierID', $validated['SupplierID'])
                ->where('Material_ID', $materialId)
                ->first();

            if ($stock) {
                $stock->update([
                    'StockIN' => $stock->StockIN + $validated['StockIN'],
                ]);

                $mergedStock = true;
            } else {
                Stock::create([
                    'SupplierID' => $validated['SupplierID'],
                    'Material_ID' => $materialId,
                    'StockIN' => $validated['StockIN'],
                    'StockOUT' => 0,
                ]);
            }

            return [
                'merged_stock' => $mergedStock,
                'created_material' => $createdMaterial,
            ];
        });

        $message = ($result['merged_stock'] ?? false)
            ? 'Inventory quantity added to the existing stock entry successfully!'
            : 'Inventory item saved successfully!';

        if (($result['created_material'] ?? false) && ! ($result['merged_stock'] ?? false)) {
            $message = 'New material and inventory item saved successfully!';
        }

        return redirect()->route('stocks.index')->with('success', $message);
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $suppliers = Supplier::orderBy('SupplierName')->get();
        $materials = $this->getSelectableMaterials($stock->Material_ID);

        return view('stocks.edit', compact('stock', 'suppliers', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'SupplierID' => 'required|exists:suppliers,SupplierID',
            'Material_ID' => 'required|exists:materials,Material_ID',
            'StockIN' => 'required|integer|min:0',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update([
            'SupplierID' => $validated['SupplierID'],
            'Material_ID' => $validated['Material_ID'],
            'StockIN' => $validated['StockIN'],
        ]);

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

    protected function getSelectableMaterials(?int $includeMaterialId = null)
    {
        return Material::withCount('stocks')
            ->orderBy('MaterialName')
            ->orderBy('MaterialType')
            ->orderByDesc('stocks_count')
            ->orderByDesc('updated_at')
            ->orderByDesc('Material_ID')
            ->get()
            ->unique(function (Material $material) use ($includeMaterialId) {
                if ($includeMaterialId && $material->Material_ID === $includeMaterialId) {
                    return 'include-'.$material->Material_ID;
                }

                return strtolower(trim($material->MaterialName)).'|'.strtolower(trim($material->MaterialType));
            })
            ->values();
    }
}
