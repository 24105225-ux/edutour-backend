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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên chương trình (VD: Khám phá Thành Cổ Loa)
            $table->string('subject'); // Môn học (VD: LỊCH SỬ)
            $table->string('level'); // Cấp học (VD: THCS, THPT)
            $table->text('description'); // Mô tả nội dung / Mục tiêu
            $table->string('location'); // Địa điểm (VD: Cổ Loa, Hà Nội)
            $table->string('safety'); // Giao thức an toàn (VD: Có nhân viên y tế)
            $table->decimal('price', 15, 2); // Giá tiền (VD: 450000)
            $table->string('image_url')->nullable(); // Link ảnh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
