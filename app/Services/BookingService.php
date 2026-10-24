<?php
namespace App\Services;

use App\Models\{Availability, Booking, BookingStatusLog, Tour, School};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class BookingService
{
    public const HOLD_MINUTES = 15; // Giữ chỗ 15 phút chờ thanh toán[cite: 233]

    /**
     * @throws RuntimeException khi không còn đủ chỗ khả dụng
     */
    public function hold(int $userId, int $tourId, string $date, int $pax): Booking
    {
        return DB::transaction(function () use ($userId, $tourId, $date, $pax) {
            // KHÓA HÀNG: Tránh việc 2 user đặt cùng lúc làm âm số lượng chỗ[cite: 233]
            $av = Availability::where('tour_id', $tourId)
                ->where('service_date', $date)
                ->lockForUpdate() 
                ->first();

            if (! $av) {
                throw new RuntimeException('Ngày khởi hành không còn được mở bán.');
            }

            // Tính số suất còn trống thực tế[cite: 234]
            $free = $av->seats_total - $av->seats_held - $av->seats_sold;
            if ($free < $pax) {
                throw new RuntimeException("Chỉ còn {$free} chỗ khả dụng cho ngày {$date}.");
            }

            $tour = Tour::findOrFail($tourId);
            $unit = $av->price_override ?? $tour->base_price;
            
            // Cập nhật số ghế đang tạm giữ[cite: 234]
            $av->increment('seats_held', $pax);

            $school = School::where('user_id', $userId)->firstOrFail();

            // Tạo mã đơn hàng độc nhất[cite: 234]
            $booking = Booking::create([
                'code' => strtoupper(Str::random(12)),
                'school_id' => $school->id,
                'tour_id' => $tourId,
                'service_date' => $date,
                'pax' => $pax,
                'total_amount' => $unit * $pax,
                'status' => 'pending_payment',
                'hold_expires_at' => now()->addMinutes(self::HOLD_MINUTES),
            ]);

            // Ghi vết lịch sử chuyển trạng thái đơn[cite: 234]
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'from_status' => 'draft',
                'to_status' => 'pending_payment',
                'actor_id' => $userId,
            ]);

            return $booking;
        });
    }
}