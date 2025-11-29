<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'rental_contract_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rentalContract()
    {
        return $this->belongsTo(RentalContract::class);
    }
}
