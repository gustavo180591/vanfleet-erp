<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

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
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'current_km' => 'integer',
        'daily_rate' => 'decimal:2',
        'km_included_per_day' => 'integer',
        'extra_km_price' => 'decimal:2',
    ];

    public function rentalContracts()
    {
        return $this->hasMany(RentalContract::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function invoices()
    {
        // Ingresos asociados a esta furgoneta vía contratos
        return $this->hasManyThrough(Invoice::class, RentalContract::class);
    }
}
