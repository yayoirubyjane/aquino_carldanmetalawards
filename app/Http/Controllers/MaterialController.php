<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Read: Show all materials
    public function index()
    {
        $materials = Material::all();
        return view('materials.index', compact('materials'));
    }

    // Create: Show the form to add a new material
    public function create()
    {
        return view('materials.create');
    }

    // Create: Save the new material to the database
    public function store(Request $request)
    {
        $request->validate([
            'MaterialName' => 'required|string|max:100',
            'MaterialType' => 'required|string|max:50',
            'Stocks' => 'required|integer',
            'Price' => 'required|numeric',
        ]);

        Material::create($request->all());

        return redirect()->route('materials.index')->with('success', 'Material added successfully!');
    }

    // Update: Show the form to edit an existing material
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    // Update: Save the updated material to the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'MaterialName' => 'required|string|max:100',
            'MaterialType' => 'required|string|max:50',
            'Stocks' => 'required|integer',
            'Price' => 'required|numeric',
        ]);

        $material = Material::findOrFail($id);
        $material->update($request->all());

        return redirect()->route('materials.index')->with('success', 'Material updated successfully!');
    }

    // Delete: Remove the material from the database
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Material deleted successfully!');
    }
}