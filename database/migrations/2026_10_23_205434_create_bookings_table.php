<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name'); // Tên trường học đặt tour
            $table->string('contact_person'); // Người đại diện / Giáo viên phụ trách
            $table->string('phone'); // Số điện thoại liên hệ
            $table->unsignedBigInteger('tour_id'); // ID chương trình tour được đặt
            $table->integer('number_of_students'); // Số lượng học sinh tham gia
            $table->decimal('total_price', 12, 2); // Tổng tiền thanh toán
            $table->string('status')->default('Chờ thanh toán'); // Trạng thái: Chờ thanh toán / Đã thanh toán
            $table->timestamps();

            // Khóa ngoại liên kết với bảng tours (nếu tour bị xóa thì các booking liên quan cũng được xử lý)
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
