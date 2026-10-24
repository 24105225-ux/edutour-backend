<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // Tìm user theo email
    $user = User::where('email', $request->email)->first();

    // Kiểm tra tồn tại và so khớp mật khẩu (Dùng đúng cột password_hash)
    if (! $user || ! Hash::check($request->password, $user->password_hash)) {
        return response()->json([
            'message' => 'Email hoặc mật khẩu không chính xác'
        ], 401);
    }

    // Tạo token Sanctum
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Đăng nhập thành công',
        'access_token' => $token,
        'user' => $user
    ]);
}

    public function register(Request $request)
{
    // Validate dữ liệu gửi lên từ Vue
    $validated = $request->validate([
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    // Lưu vào database (khớp chính xác các cột của bảng users hiện tại)
    $user = User::create([
        'email' => $validated['email'],
        'password_hash' => Hash::make($validated['password']),
        'role' => 'parent', // Gán quyền mặc định cho khách đăng ký
        'status' => 'active',
    ]);

    return response()->json([
        'message' => 'Đăng ký thành công!',
        'user' => $user
    ], 201);
}
}
