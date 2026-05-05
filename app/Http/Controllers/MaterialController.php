<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        $this->ensureMaterialIsUnique($validated['MaterialName'], $validated['MaterialType']);

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
        $this->ensureMaterialIsUnique($validated['MaterialName'], $validated['MaterialType'], $material->Material_ID);
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

    protected function ensureMaterialIsUnique(string $name, string $type, ?int $ignoreId = null): void
    {
        $existing = Material::query()
            ->when($ignoreId, fn ($query) => $query->where('Material_ID', '!=', $ignoreId))
            ->whereRaw('LOWER(MaterialName) = ?', [strtolower(trim($name))])
            ->whereRaw('LOWER(MaterialType) = ?', [strtolower(trim($type))])
            ->exists();

        if ($existing) {
            throw ValidationException::withMessages([
                'MaterialName' => 'A material with the same name and type already exists.',
            ]);
        }
    }
}
