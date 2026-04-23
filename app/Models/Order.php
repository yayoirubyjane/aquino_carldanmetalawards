<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Define the custom primary key
    protected $primaryKey = 'OrderID';

    // Allow mass assignment
    protected $fillable = [
        'EmployeeID',
        'ProductID',
        'ClientID',
        'Quantity',
    ];

    // Relationship: An Order belongs to a Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientID', 'ClientID');
    }

    // Relationship: An Order belongs to an Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID', 'EmployeeID');
    }

    // Relationship: An Order belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}