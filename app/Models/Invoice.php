<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_contract_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'amount_subtotal',
        'amount_tax',
        'amount_total',
        'status',
        'pdf_path',
        'verifactu_status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount_subtotal' => 'decimal:2',
        'amount_tax' => 'decimal:2',
        'amount_total' => 'decimal:2',
    ];

    public function rentalContract()
    {
        return $this->belongsTo(RentalContract::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, RentalContract::class);
    }

    public function vehicle()
    {
        return $this->hasOneThrough(Vehicle::class, RentalContract::class);
    }
}
