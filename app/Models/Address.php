<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'Value',
        'UserID'
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'UserID');
    }
}
