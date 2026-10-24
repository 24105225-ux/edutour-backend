<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;


// Thêm dòng route đăng ký này vào file routes/api.php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); // Nếu có sẵn route đăng nhập thì giữ nguyên
Route::apiResource('tours', TourController::class);




Route::apiResource('bookings', BookingController::class);

Route::prefix('v1')->group(function () {
    
    // Nhóm các tuyến đường yêu cầu người dùng phải đăng nhập
    Route::middleware('auth:sanctum')->group(function () {
        
        // Chỉ tài khoản vai trò 'school' (Nhà trường) mới được xem và đặt tour
        Route::middleware('role:school,admin')->group(function () {
            Route::get('/bookings', [BookingController::class, 'index']);
            Route::post('/bookings', [BookingController::class, 'store']);
            Route::get('/bookings/{id}', [BookingController::class, 'show']);
        });

        // Tuyến đường quản lý chung cho phép admin hoặc operator cập nhật/hủy đơn
        Route::middleware('role:admin,operator')->group(function () {
            Route::put('/bookings/{id}', [BookingController::class, 'update']);
            Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
        });
    });

});