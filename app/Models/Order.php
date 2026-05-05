<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderID';

    protected $fillable = [
        'EmployeeID',
        'ClientID',
        'OrderStatus',
        'OrderDate',
        'DeliveryDate',
    ];

    protected $casts = [
        'OrderDate' => 'date',
        'DeliveryDate' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientID', 'ClientID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID', 'EmployeeID');
    }

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class, 'OrderID', 'OrderID');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_orders', 'OrderID', 'ProductID')
            ->withPivot('Quantity', 'Price')
            ->withTimestamps();
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'OrderID', 'OrderID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'OrderID', 'OrderID');
    }

    public function getTotalAmountAttribute(): float
    {
        if ($this->relationLoaded('productOrders')) {
            return (float) $this->productOrders->sum(fn (ProductOrder $item) => $item->total);
        }

        return (float) $this->productOrders()
            ->selectRaw('COALESCE(SUM(Quantity * Price), 0) as total_amount')
            ->value('total_amount');
    }

    public function getAmountPaidAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('Amount');
        }

        return (float) $this->payments()->sum('Amount');
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0, round($this->total_amount - $this->amount_paid, 2));
    }

    public function getExpectedPaymentsAttribute(): array
    {
        $firstHalf = round($this->total_amount / 2, 2);

        return [$firstHalf, round($this->total_amount - $firstHalf, 2)];
    }
}
