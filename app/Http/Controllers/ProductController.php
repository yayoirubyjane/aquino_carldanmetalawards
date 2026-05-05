<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('materials')->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $materials = Material::with('stocks')
            ->whereHas('stocks')
            ->orderBy('MaterialName')
            ->get();

        return view('products.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ProductName' => 'required|string|max:100',
            'ProductType' => 'required|string|max:50',
            'Price' => 'required|numeric',
            'materials' => 'required|array',
            'materials.*' => 'nullable|integer|min:0',
        ]);

        $materialData = $this->validatedMaterialQuantities($validated['materials'] ?? []);

        DB::transaction(function () use ($validated, $materialData) {
            $product = Product::create([
                'ProductName' => $validated['ProductName'],
                'ProductType' => $validated['ProductType'],
                'Price' => $validated['Price'],
            ]);

            $product->materials()->sync($materialData);
        });

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::with('materials')->findOrFail($id);
        $materials = Material::with('stocks')
            ->where(function ($query) use ($product) {
                $query->whereHas('stocks')
                    ->orWhereHas('products', function ($productQuery) use ($product) {
                        $productQuery->where('products.ProductID', $product->ProductID);
                    });
            })
            ->orderBy('MaterialName')
            ->get();

        return view('products.edit', compact('product', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ProductName' => 'required|string|max:100',
            'ProductType' => 'required|string|max:50',
            'Price' => 'required|numeric',
            'materials' => 'required|array',
            'materials.*' => 'nullable|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $materialData = $this->validatedMaterialQuantities($validated['materials'] ?? []);

        DB::transaction(function () use ($product, $validated, $materialData) {
            $product->update([
                'ProductName' => $validated['ProductName'],
                'ProductType' => $validated['ProductType'],
                'Price' => $validated['Price'],
            ]);

            $product->materials()->sync($materialData);
        });

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('products.index')->with('error', 'Cannot delete this product because it is already used in orders or production records.');
            }

            return redirect()->route('products.index')->with('error', 'An error occurred while deleting the product.');
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    protected function validatedMaterialQuantities(array $materials): array
    {
        $existingMaterialIds = Material::whereHas('stocks')
            ->orWhereHas('products')
            ->pluck('Material_ID')
            ->all();

        $materialData = collect($materials)
            ->filter(fn ($quantity) => (int) $quantity > 0)
            ->mapWithKeys(function ($quantity, $materialId) use ($existingMaterialIds) {
                if (! in_array((int) $materialId, $existingMaterialIds, true)) {
                    return [];
                }

                return [
                    (int) $materialId => ['RequiredQuantity' => (int) $quantity],
                ];
            })
            ->all();

        if (empty($materialData)) {
            throw ValidationException::withMessages([
                'materials' => 'Add at least one material with a required quantity.',
            ]);
        }

        return $materialData;
    }
}
