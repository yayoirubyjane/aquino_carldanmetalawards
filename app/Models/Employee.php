<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'EmployeeFN',
        'EmployeeMN',
        'EmployeeLN',
    ];

    protected $appends = [
        'full_name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'EmployeeID', 'EmployeeID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'EmployeeID', 'EmployeeID');
    }

    public function getFullNameAttribute(): string
    {
        return trim(collect([
            $this->EmployeeFN,
            $this->EmployeeMN,
            $this->EmployeeLN,
        ])->filter()->implode(' '));
    }
}
