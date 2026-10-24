<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <-- 2. Thêm HasApiTokens vào đây
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'password_hash', // Đảm bảo có cột này trong fillable
        'role',
    ];

    protected $hidden = [
        'password_hash', // Ẩn cột mật khẩu khi trả về JSON
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
