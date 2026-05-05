<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client', 'employee', 'productOrders.product', 'payments'])
            ->latest('OrderDate')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::orderBy('ClientFN')->get();
        $employees = Employee::orderBy('EmployeeFN')->get();
        $products = Product::with('materials')->orderBy('ProductName')->get();
        $productOptions = $products->map(function (Product $product) {
            return [
                'ProductID' => $product->ProductID,
                'ProductName' => $product->ProductName,
                'Price' => $product->Price,
            ];
        })->values()->all();

        return view('orders.create', compact('clients', 'employees', 'products', 'productOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'OrderDate' => 'required|date',
            'DeliveryDate' => 'nullable|date|after_or_equal:OrderDate',
            'products' => 'required|array|min:1',
            'products.*.ProductID' => 'required|exists:products,ProductID',
            'products.*.Quantity' => 'required|integer|min:1',
            'products.*.Price' => 'required|numeric|min:0',
        ]);

        $items = $this->normalizeProducts($validated['products']);

        DB::transaction(function () use ($validated, $items) {
            $order = Order::create([
                'ClientID' => $validated['ClientID'],
                'EmployeeID' => $validated['EmployeeID'],
                'OrderStatus' => 'Pending',
                'OrderDate' => $validated['OrderDate'],
                'DeliveryDate' => $validated['DeliveryDate'],
            ]);

            foreach ($items as $item) {
                ProductOrder::create([
                    'OrderID' => $order->OrderID,
                    'ProductID' => $item['ProductID'],
                    'Quantity' => $item['Quantity'],
                    'Price' => $item['Price'],
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function edit($id)
    {
        $order = Order::with(['productOrders.product', 'payments', 'productions'])->findOrFail($id);
        $clients = Client::orderBy('ClientFN')->get();
        $employees = Employee::orderBy('EmployeeFN')->get();
        $products = Product::with('materials')->orderBy('ProductName')->get();
        $productOptions = $products->map(function (Product $product) {
            return [
                'ProductID' => $product->ProductID,
                'ProductName' => $product->ProductName,
                'Price' => $product->Price,
            ];
        })->values()->all();
        $existingOrderProducts = $order->productOrders->map(function (ProductOrder $item) {
            return [
                'ProductID' => $item->ProductID,
                'Quantity' => $item->Quantity,
                'Price' => $item->Price,
            ];
        })->values()->all();

        return view('orders.edit', compact('order', 'clients', 'employees', 'products', 'productOptions', 'existingOrderProducts'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with(['payments', 'productions'])->findOrFail($id);

        $validated = $request->validate([
            'ClientID' => 'required|exists:clients,ClientID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'OrderDate' => 'required|date',
            'DeliveryDate' => 'nullable|date|after_or_equal:OrderDate',
            'OrderStatus' => ['required', Rule::in(['Pending', 'In Production', 'Completed'])],
            'products' => 'required|array|min:1',
            'products.*.ProductID' => 'required|exists:products,ProductID',
            'products.*.Quantity' => 'required|integer|min:1',
            'products.*.Price' => 'required|numeric|min:0',
        ]);

        $items = $this->normalizeProducts($validated['products']);
        $newTotal = collect($items)->sum(fn (array $item) => $item['Quantity'] * $item['Price']);
        $protectedProductIds = $order->productions
            ->where('ProdStatus', '!=', 'Not Started')
            ->pluck('ProductID')
            ->unique()
            ->all();
        $incomingProductIds = collect($items)->pluck('ProductID')->all();

        if ($order->amount_paid > $newTotal) {
            throw ValidationException::withMessages([
                'products' => 'The updated order total cannot be less than the amount already paid.',
            ]);
        }

        foreach ($protectedProductIds as $productId) {
            if (! in_array($productId, $incomingProductIds, true)) {
                throw ValidationException::withMessages([
                    'products' => 'You cannot remove an item that already has an active production record.',
                ]);
            }
        }

        DB::transaction(function () use ($order, $validated, $items) {
            $order->update([
                'ClientID' => $validated['ClientID'],
                'EmployeeID' => $validated['EmployeeID'],
                'OrderStatus' => $validated['OrderStatus'],
                'OrderDate' => $validated['OrderDate'],
                'DeliveryDate' => $validated['DeliveryDate'],
            ]);

            $productIds = collect($items)->pluck('ProductID')->all();

            $order->productions()
                ->whereNotIn('ProductID', $productIds)
                ->where('ProdStatus', 'Not Started')
                ->delete();

            $order->productOrders()->delete();

            foreach ($items as $item) {
                ProductOrder::create([
                    'OrderID' => $order->OrderID,
                    'ProductID' => $item['ProductID'],
                    'Quantity' => $item['Quantity'],
                    'Price' => $item['Price'],
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('orders.index')->with('error', 'Cannot delete this order because it has associated production or payment records.');
            }

            return redirect()->route('orders.index')->with('error', 'An error occurred while deleting the order.');
        }
    }

    protected function normalizeProducts(array $products): array
    {
        return collect($products)
            ->filter(fn (array $item) => filled($item['ProductID'] ?? null))
            ->map(fn (array $item) => [
                'ProductID' => (int) $item['ProductID'],
                'Quantity' => (int) $item['Quantity'],
                'Price' => (float) $item['Price'],
            ])
            ->values()
            ->all();
    }
}
