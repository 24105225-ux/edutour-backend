<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'contact_person',
        'phone',
        'tour_id',
        'number_of_students',
        'total_price',
        'status',
    ];

    // Thiết lập quan hệ để lấy thông tin Tour khi cần
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}