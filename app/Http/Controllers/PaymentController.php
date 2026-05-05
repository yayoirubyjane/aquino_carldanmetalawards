<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['employee', 'order.client', 'order.productOrders'])
            ->latest('PaymentDate')
            ->paginate(10);

        $salesOrders = Order::with(['client', 'productOrders', 'payments'])
            ->latest('OrderDate')
            ->get();

        $totalRevenue = (float) $salesOrders->sum(fn (Order $order) => $order->total_amount);
        $amountCollected = (float) $salesOrders->sum(fn (Order $order) => $order->amount_paid);
        $pendingBalance = max(0, round($totalRevenue - $amountCollected, 2));

        $paymentDetails = DB::table('vw_PaymentDetails')
            ->orderByDesc('PaymentDate')
            ->limit(8)
            ->get();

        $orderItems = DB::table('vw_OrderItems')
            ->orderByDesc('OrderID')
            ->limit(12)
            ->get();

        return view('payments.index', compact(
            'payments',
            'salesOrders',
            'totalRevenue',
            'amountCollected',
            'pendingBalance',
            'paymentDetails',
            'orderItems'
        ));
    }

    public function create()
    {
        $orders = Order::with(['client', 'productOrders', 'payments'])->orderByDesc('OrderDate')->get();
        $employees = Employee::orderBy('EmployeeFN')->get();

        return view('payments.create', compact('orders', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'OrderID' => 'required|exists:orders,OrderID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'PaymentMethod' => 'required|string|max:50',
            'PaymentDate' => 'required|date',
            'Amount' => 'required|numeric|min:0.01',
            'ReferenceNumber' => 'nullable|string|max:50',
        ]);

        $order = Order::with(['productOrders', 'payments'])->findOrFail($validated['OrderID']);
        $this->validateInstallmentAmount($order, (float) $validated['Amount']);

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $orders = Order::with(['client', 'productOrders', 'payments'])->orderByDesc('OrderDate')->get();
        $employees = Employee::orderBy('EmployeeFN')->get();

        return view('payments.edit', compact('payment', 'orders', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'OrderID' => 'required|exists:orders,OrderID',
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'PaymentMethod' => 'required|string|max:50',
            'PaymentDate' => 'required|date',
            'Amount' => 'required|numeric|min:0.01',
            'ReferenceNumber' => 'nullable|string|max:50',
        ]);

        $payment = Payment::findOrFail($id);
        $order = Order::with(['productOrders', 'payments'])->findOrFail($validated['OrderID']);
        $this->validateOrderPaymentCount($order, $payment);

        $otherPaymentsTotal = (float) $order->payments
            ->where('PaymentID', '!=', $payment->PaymentID)
            ->sum('Amount');

        if ($otherPaymentsTotal + (float) $validated['Amount'] > $order->total_amount) {
            throw ValidationException::withMessages([
                'Amount' => 'Payment exceeds the total order amount.',
            ]);
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }

    protected function validateInstallmentAmount(Order $order, float $amount): void
    {
        $this->validateOrderPaymentCount($order);

        $expectedPayments = $order->expected_payments;
        $paymentIndex = $order->payments->count();
        $expectedAmount = $expectedPayments[$paymentIndex] ?? null;

        if ($expectedAmount === null) {
            throw ValidationException::withMessages([
                'OrderID' => 'This order already has the allowed payment entries.',
            ]);
        }

        if (round($amount, 2) !== round($expectedAmount, 2)) {
            throw ValidationException::withMessages([
                'Amount' => 'Payment must match the expected 50/50 installment amount for this order.',
            ]);
        }
    }

    protected function validateOrderPaymentCount(Order $order, ?Payment $currentPayment = null): void
    {
        $payments = $order->payments;

        if ($currentPayment) {
            $payments = $payments->where('PaymentID', '!=', $currentPayment->PaymentID);
        }

        if ($payments->count() >= 2) {
            throw ValidationException::withMessages([
                'OrderID' => 'Each order only supports two payment entries.',
            ]);
        }
    }
}
