<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paragraph extends Model
{
    use HasFactory;

    protected $fillable = [
        'Content',
        'BlogParagraphID',
    ];

    public function paragraph_blog()
    {
        return $this->belongsTo(paragraph_blog::class, 'BlogID');
    }
}
