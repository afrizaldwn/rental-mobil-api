<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use DateFormat;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'gender', 'birth_date',
        'id_card_number', 'driver_license_number', 'city', 'status',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}