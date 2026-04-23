<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the custom primary key
    protected $primaryKey = 'ProductID';

    // Allow mass assignment
    protected $fillable = [
        'Material_ID',
        'ProductName',
        'ProductType',
        'Price',
    ];

    // Establish the relationship: A Product belongs to a Material
    public function material()
    {
        return $this->belongsTo(Material::class, 'Material_ID', 'Material_ID');
    }
}