<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Use 'with' to eagerly load the linked material data so we can display the material name
        $products = Product::with('material')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Fetch all materials to populate the dropdown menu
        $materials = Material::all();
        return view('products.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Material_ID' => 'required|exists:materials,Material_ID',
            'ProductName' => 'required|string|max:100',
            'ProductType' => 'required|string|max:50',
            'Price' => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $materials = Material::all(); // Need materials for the dropdown again
        return view('products.edit', compact('product', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Material_ID' => 'required|exists:materials,Material_ID',
            'ProductName' => 'required|string|max:100',
            'ProductType' => 'required|string|max:50',
            'Price' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}