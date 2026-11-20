<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use DateFormat;

    protected $fillable = [
        'customer_id', 'car_brand', 'car_model', 'plate_number',
        'rental_date', 'return_date', 'total_price', 'status',
    ];

    protected $casts = ['total_price' => 'integer'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}