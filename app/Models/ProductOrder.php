<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProductOrderID';

    protected $fillable = [
        'OrderID',
        'ProductID',
        'Quantity',
        'Price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function getTotalAttribute(): float
    {
        return (float) $this->Quantity * (float) $this->Price;
    }
}
