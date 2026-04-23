<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    // Define the custom primary key
    protected $primaryKey = 'ProductionID';

    // Allow mass assignment
    protected $fillable = [
        'OrderID',
        'ProductionNote',
        'ProdStartDate',
        'ProdFinishedDate',
    ];

    // Establish the relationship: A Production record belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }
}