<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'level',
        'description',
        'location',
        'safety',
        'price',
        'image_url',
    ];
}
