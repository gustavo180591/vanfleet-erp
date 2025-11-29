<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'dni',
        'email',
        'phone',
        'address',
        'city',
        'notes',
    ];

    public function rentalContracts()
    {
        return $this->hasMany(RentalContract::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
