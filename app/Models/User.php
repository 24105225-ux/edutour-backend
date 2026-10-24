<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password_hash', // Giữ nguyên tên cột theo database
        'role',
        'status',
    ];

    protected $hidden = [
        'password_hash', // Ẩn trường này khi trả về JSON
        'remember_token',
    ];

    /**
     * Báo cho Laravel biết cột nào dùng để lưu mật khẩu xác thực
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }
}