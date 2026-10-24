<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'status',
        'code',
        'created_at'
    ];

    // Khai báo liên kết đến tour nếu cần
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}