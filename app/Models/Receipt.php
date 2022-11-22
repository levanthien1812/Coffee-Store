<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'PhoneNumber',
        'Address',
        'CustomerType',
        'TotalAmount',
        'CustomerID'
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'CustomerID');
    }
}
