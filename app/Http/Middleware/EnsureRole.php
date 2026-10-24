<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Kiểm tra xem người dùng có đúng vai trò được phép truy cập hay không.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Kiểm tra đăng nhập và đối chiếu vai trò
        if (! $user || ! in_array($user->role, $roles, true)) {
            // Ghi log cảnh báo bảo mật khi có hành vi vượt quyền
            Log::warning('Truy cập trái phép bị từ chối', [
                'user_id' => $user?->id,
                'role' => $user?->role,
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền truy cập chức năng này.'
            ], 403);
        }

        return $next($request);
    }
}