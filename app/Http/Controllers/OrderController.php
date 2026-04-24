<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client', 'employee', 'product.materials'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::all();
        $employees = Employee::all();
        $products = Product::with('materials')->get();
        return view('orders.create', compact('clients', 'employees', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'ProductID' => 'required|exists:products,ProductID',
            'Quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('materials')->findOrFail($validated['ProductID']);

        if (! $product->canFulfillQuantity((int) $validated['Quantity'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'Quantity' => "Only {$product->available_stock} unit(s) of {$product->ProductName} can be produced from current material stocks.",
                ]);
        }

        Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $clients = Client::all();
        $employees = Employee::all();
        $products = Product::with('materials')->get();
        return view('orders.edit', compact('order', 'clients', 'employees', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'ProductID' => 'required|exists:products,ProductID',
            'Quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('materials')->findOrFail($validated['ProductID']);

        if (! $product->canFulfillQuantity((int) $validated['Quantity'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'Quantity' => "Only {$product->available_stock} unit(s) of {$product->ProductName} can be produced from current material stocks.",
                ]);
        }

        $order = Order::findOrFail($id);
        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('orders.index')->with('error', 'Cannot delete this order because it is already linked to a Production record. Please delete the Production record first.');
            }

            return redirect()->route('orders.index')->with('error', 'An error occurred while deleting the order.');
        }
    }
}
