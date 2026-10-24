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

    // Khai báo quan hệ tới bảng schools
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Khai báo quan hệ tới bảng tours
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}