<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;


    protected $fillable = [
        'Name',
        'SPrice',
        'MPrice',
        'LPrice',
        'Image',
        'Type'
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'Type');
    }
}
