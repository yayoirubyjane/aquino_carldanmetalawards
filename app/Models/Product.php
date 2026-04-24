<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

                return intdiv((int) $material->Stocks, $requiredQuantity);
            })
            ->min();
    }

    public function canFulfillQuantity(int $quantity): bool
    {
        return $quantity > 0 && $quantity <= $this->available_stock;
    }
}
