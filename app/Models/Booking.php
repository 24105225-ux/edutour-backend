<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Tắt timestamps vì bảng chỉ có created_at, không có updated_at
    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'status',
        'created_at'
    ];

    // Khai báo liên kết đến tour nếu cần
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}