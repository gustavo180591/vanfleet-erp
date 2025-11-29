<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'plate',
        'brand',
        'model',
        'purchase_date',
        'current_km',
        'status',
        'daily_rate',
        'km_included_per_day',
        'extra_km_price',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'current_km' => 'integer',
        'daily_rate' => 'decimal:2',
        'km_included_per_day' => 'integer',
        'extra_km_price' => 'decimal:2'
    ];
}
