<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'Quantity',
        'Size',
        'Price',
        'ReceiptID',
        'ItemID'
    ];

    public function receipt()
    {
        return $this->belongsTo(receipt::class, 'ReceiptID');
    }
    public function item()
    {
        return $this->belongsTo(item::class, 'ItemID');
    }
}
