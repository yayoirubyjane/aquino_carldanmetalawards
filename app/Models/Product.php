<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductName',
        'ProductType',
        'Price',
    ];

    protected $appends = [
        'available_stock',
    ];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_material', 'ProductID', 'Material_ID')
            ->withPivot('RequiredQuantity')
            ->withTimestamps();
    }

    // Get orders for this product through ProductOrder pivot
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_orders', 'ProductID', 'OrderID')
            ->withPivot('Quantity', 'Price')
            ->withTimestamps();
    }

    // Get productions for this product
    public function productions()
    {
        return $this->hasMany(Production::class, 'ProductID', 'ProductID');
    }

    public function getAvailableStockAttribute(): int
    {
        if (! $this->relationLoaded('materials')) {
            $this->load('materials');
        }

        if ($this->materials->isEmpty()) {
            return 0;
        }

        return (int) $this->materials
            ->map(function (Material $material) {
                $requiredQuantity = (int) $material->pivot->RequiredQuantity;

                if ($requiredQuantity <= 0) {
                    return 0;
                }

                // Get total available quantity from stocks table for this material
                $totalStockQuantity = Stock::where('Material_ID', $material->Material_ID)
                    ->sum(DB::raw('Quantity'));

                if ($totalStockQuantity <= 0) {
                    return 0;
                }

                return intdiv($totalStockQuantity, $requiredQuantity);
            })
            ->min();
    }

    public function canFulfillQuantity(int $quantity): bool
    {
        return $quantity > 0 && $quantity <= $this->available_stock;
    }
}
