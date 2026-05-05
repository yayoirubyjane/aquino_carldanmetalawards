<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $primaryKey = 'StockID';

    protected $fillable = [
        'SupplierID',
        'Material_ID',
        'StockIN',
        'StockOUT',
    ];

    protected $appends = [
        'quantity',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID', 'SupplierID');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'Material_ID', 'Material_ID');
    }

    public function getQuantityAttribute()
    {
        return (int) ($this->attributes['Quantity'] ?? ($this->StockIN - $this->StockOUT));
    }
}
