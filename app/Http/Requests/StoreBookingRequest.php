<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    // Mở khóa cho cả admin và nhà trường (hoặc ai đã đăng nhập) đều được test đặt tour
    public function authorize(): bool { 
        return $this->user() !== null; 
    }

    public function rules(): array
    {
        // Điều chỉnh lại rule để khớp 100% với dữ liệu từ form Vue.js gửi lên
        return [
            'tour_id'            => ['required', 'integer', Rule::exists('tours', 'id')],
            'school_name'        => ['required', 'string', 'max:255'],
            'contact_person'     => ['required', 'string', 'max:255'],
            'phone'              => ['required', 'string', 'max:20'],
            'number_of_students' => ['required', 'integer', 'min:1', 'max:1000'],
            'total_price'        => ['required', 'numeric'],
            'status'             => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'number_of_students.min' => 'Số lượng học sinh tham gia tối thiểu là 1.',
            'number_of_students.max' => 'Số lượng học sinh vượt quá giới hạn cho phép.',
            'tour_id.exists'         => 'Chương trình tour không tồn tại trên hệ thống.',
        ];
    }
}