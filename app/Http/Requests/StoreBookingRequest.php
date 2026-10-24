<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    // Chỉ tài khoản Nhà trường mới được phép đặt tour
    public function authorize(): bool { 
        return $this->user() !== null && $this->user()->role === 'school'; 
    }

    public function rules(): array
    {
        return [
            'tour_id' => ['required', 'integer', Rule::exists('tours', 'id')->where('status', 'published')],
            'service_date' => ['required', 'date_format:Y-m-d', 'after:today'], // Ngày đi phải từ ngày mai trở đi
            'pax' => ['required', 'integer', 'min:30', 'max:300'], // Giới hạn số lượng học sinh/đoàn
        ];
    }

    public function messages(): array
    {
        return [
            'service_date.after' => 'Ngày khởi hành phải sau ngày hôm nay.',
            'pax.min' => 'Số lượng học sinh tối thiểu mỗi đoàn là 30.',
            'pax.max' => 'Số lượng học sinh tối đa mỗi lần đặt là 300.',
        ];
    }
}