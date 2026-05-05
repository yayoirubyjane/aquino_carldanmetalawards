<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'ClientID';

    protected $fillable = [
        'ClientFN',
        'ClientMN',
        'ClientLN',
        'ClientContact',
        'ClientStreet',
        'ClientBarangay',
        'ClientCity',
        'ClientProvince',
    ];

    protected $appends = [
        'full_name',
        'full_address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'ClientID', 'ClientID');
    }

    public function getFullNameAttribute(): string
    {
        return trim(collect([
            $this->ClientFN,
            $this->ClientMN,
            $this->ClientLN,
        ])->filter()->implode(' '));
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->ClientStreet,
            $this->ClientBarangay,
            $this->ClientCity,
            $this->ClientProvince,
        ])->filter()->implode(', ');
    }
}
