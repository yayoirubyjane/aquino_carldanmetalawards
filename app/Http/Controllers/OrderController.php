<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Eager load all three relationships to display names in the table
        $orders = Order::with(['client', 'employee', 'product'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::all();
        $employees = Employee::all();
        $products = Product::all();
        return view('orders.create', compact('clients', 'employees', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'ProductID' => 'required|exists:products,ProductID',
            'Quantity' => 'required|integer|min:1',
        ]);

        Order::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $clients = Client::all();
        $employees = Employee::all();
        $products = Product::all();
        return view('orders.edit', compact('order', 'clients', 'employees', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'ProductID' => 'required|exists:products,ProductID',
            'Quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        try {
            // Try to find and delete the order
            $order = Order::findOrFail($id);
            $order->delete();
            
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // If the database blocks the deletion because of a foreign key, catch the error (Code 23000)
            if($e->getCode() == "23000"){
                return redirect()->route('orders.index')->with('error', 'Cannot delete this order because it is already linked to a Production record. Please delete the Production record first.');
            }
            
            // Catch any other random database errors just in case
            return redirect()->route('orders.index')->with('error', 'An error occurred while deleting the order.');
        }
    }
}