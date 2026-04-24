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
        'Stocks',
        'Price',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_material', 'Material_ID', 'ProductID')
            ->withPivot('RequiredQuantity')
            ->withTimestamps();
    }
}
