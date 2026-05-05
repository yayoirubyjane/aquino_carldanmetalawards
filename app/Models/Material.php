<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'Material_ID';

    protected $fillable = [
        'MaterialName',
        'MaterialType',
        'UnitCost',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_material', 'Material_ID', 'ProductID')
            ->withPivot('RequiredQuantity')
            ->withTimestamps();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'Material_ID', 'Material_ID');
    }

    public function getCurrentQuantityAttribute(): int
    {
        if ($this->relationLoaded('stocks')) {
            return (int) $this->stocks->sum(fn (Stock $stock) => $stock->Quantity);
        }

        return (int) $this->stocks()->sum('Quantity');
    }
}
