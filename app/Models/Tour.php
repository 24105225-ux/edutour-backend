<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    // Tắt tự động quản lý timestamps vì bảng không có cột updated_at
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'target_level',
        'base_price',
        'duration_days',
        'capacity',
        'safety_protocols',
        'status',
        'created_at'
    ];
}