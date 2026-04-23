<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    // Define the custom primary key
    protected $primaryKey = 'Material_ID';

    // Allow mass assignment for these columns
    protected $fillable = [
        'MaterialName',
        'MaterialType',
        'Stocks',
        'Price',
    ];
}