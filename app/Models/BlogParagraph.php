<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogParagraph extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title',
        'Image',
        'ImageCaption',
        'ImagePosition',
        'BlogID',
    ];

    public function blog()
    {
        return $this->belongsTo(blog::class, 'BlogID');
    }
}
