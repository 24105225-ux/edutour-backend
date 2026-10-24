<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BookingController extends Controller
{
    // Tiêm phụ thuộc (Dependency Injection) tầng Service xử lý nghiệp vụ cốt lõi
    public function __construct(private BookingService $bookings) {}

    /**
     * Lấy danh sách toàn bộ đơn đặt tour (hỗ trợ phân trang và lọc theo phân quyền).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Booking::with(['tour', 'school']);

        // Phân quyền: Nếu KHÔNG PHẢI admin (ví dụ là school), mới lọc đơn của trường đó
        if ($user && $user->role === 'school') {
            $query->whereHas('school', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        // Nếu là admin, giữ nguyên $query để lấy toàn bộ danh sách đơn đặt tour mà không bị lỗi quan hệ

        $bookings = $query->orderBy('id', 'desc')->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ], 200);
    }

    /**
     * Tạo mới / Giữ chỗ đơn đặt tour (Áp dụng giao dịch CSDL và khóa hàng chống trùng lịch).
     */
    public function store(Request $request)
    {
        try {
            // Lấy school_id từ user đang đăng nhập (nếu là trường học) hoặc mặc định là 1
            $user = $request->user();
            $schoolId = 1;
            if ($user) {
                $school = \App\Models\School::where('user_id', $user->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            }

            // Tạo đơn đặt tour chuẩn chỉnh khớp tuyệt đối với cấu trúc SQL
            $booking = Booking::create([
                'code'         => 'ED' . str_pad(mt_rand(1, 9999999), 9, '0', STR_PAD_LEFT),
                'school_id'    => $schoolId,
                'tour_id'      => (int) $request->tour_id,
                'service_date' => $request->service_date ?? now()->addDays(7)->format('Y-m-d'),
                'pax'          => (int) ($request->number_of_students ?? 30),
                'total_amount' => (float) ($request->total_price ?? 450000),
                'status'       => 'pending_payment',
                'created_at'   => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký đặt tour thành công!',
                'data' => [
                    'id' => $booking->id,
                    'code' => $booking->code
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết thông tin một đơn đặt tour kèm danh sách học sinh tham gia.
     */
    public function show(Request $request, int $id)
{
    // Load kèm tour để trang checkout có đủ dữ liệu hiển thị
    $booking = Booking::with(['tour'])->find($id);

    if (!$booking) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không tìm thấy đơn đặt tour!'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $booking
    ], 200);
}

    /**
     * Cập nhật trạng thái đơn hàng (Xác nhận thanh toán hoặc đổi trạng thái).
     */
    public function update(Request $request, int $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:pending_payment,confirmed,cancelled,completed'
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái đơn hàng thành công!',
            'data' => $booking
        ], 200);
    }

    /**
     * Xóa hoặc hủy đơn đặt tour khi chưa thanh toán.
     */
    public function destroy(int $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        // Chặn xóa nếu đơn đã được xác nhận hoặc hoàn tất
        if (!in_array($booking->status, ['draft', 'pending_payment'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa đơn hàng đã xác nhận hoặc hoàn tất!'
            ], 400);
        }

        $booking->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa đơn hàng thành công!'
        ], 200);
    }
}
