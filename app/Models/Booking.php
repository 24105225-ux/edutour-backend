<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'school_id',
        'tour_id',
        'service_date',
        'pax',
        'total_amount',
        'status',
        'created_at'
    ];


    // Khai báo liên kết đến tour nếu cần
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}