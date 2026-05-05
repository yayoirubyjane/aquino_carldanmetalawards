<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return redirect()->route('stocks.index');
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaterialName' => 'required|string|max:100',
            'MaterialType' => 'required|string|max:50',
            'UnitCost' => 'required|numeric|min:0',
        ]);

        Material::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Material added successfully!');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'MaterialName' => 'required|string|max:100',
            'MaterialType' => 'required|string|max:50',
            'UnitCost' => 'required|numeric|min:0',
        ]);

        $material = Material::findOrFail($id);
        $material->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Material updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            $material->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('stocks.index')->with('error', 'Cannot delete this material because it is already used in stock or product records.');
            }

            return redirect()->route('stocks.index')->with('error', 'An error occurred while deleting the material.');
        }

        return redirect()->route('stocks.index')->with('success', 'Material deleted successfully!');
    }
}
