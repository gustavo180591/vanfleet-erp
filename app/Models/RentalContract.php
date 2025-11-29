<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'price_per_day',
        'included_km',
        'max_km',
        'status',
        'signed_contract_path',
        'debt_assignment_pdf_path',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_day' => 'decimal:2',
        'included_km' => 'integer',
        'max_km' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
