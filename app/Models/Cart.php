<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'Quantity',
        'Size',
        'Price',
        'Status',
        'CustomerID',
        'ItemID',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'CustomerID');
    }
    public function item()
    {
        return $this->belongsTo(item::class, 'ItemID');
    }
}
