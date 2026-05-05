<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'SupplierID';

    protected $fillable = [
        'SupplierName',
        'SupplierContact',
        'SupplierStreet',
        'SupplierBarangay',
        'SupplierCity',
        'SupplierProvince',
    ];

    protected $appends = [
        'full_address',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'SupplierID', 'SupplierID');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->SupplierStreet,
            $this->SupplierBarangay,
            $this->SupplierCity,
            $this->SupplierProvince,
        ])->filter()->implode(', ');
    }
}
