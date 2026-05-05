<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest('SupplierID')->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:100',
            'SupplierContact' => 'required|string|max:20',
            'SupplierStreet' => 'required|string|max:120',
            'SupplierBarangay' => 'nullable|string|max:120',
            'SupplierCity' => 'required|string|max:120',
            'SupplierProvince' => 'nullable|string|max:120',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully!');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:100',
            'SupplierContact' => 'required|string|max:20',
            'SupplierStreet' => 'required|string|max:120',
            'SupplierBarangay' => 'nullable|string|max:120',
            'SupplierCity' => 'required|string|max:120',
            'SupplierProvince' => 'nullable|string|max:120',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('suppliers.index')->with('error', 'Cannot delete this supplier because it already has stock records.');
            }

            return redirect()->route('suppliers.index')->with('error', 'An error occurred while deleting the supplier.');
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }
}
