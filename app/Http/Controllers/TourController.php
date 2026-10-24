<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    // 1. Lấy danh sách toàn bộ Tour (Read All)
    public function index()
    {
        $tours = Tour::orderBy('id', 'desc')->get();
        return response()->json($tours, 200);
    }

    // 2. Thêm mới một Tour (Create)
    public function store(Request $request)
    {
        $tour = Tour::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm chương trình thành công!',
            'data' => $tour
        ], 201);
    }

    // 3. Xem chi tiết 1 Tour (Read One)
    public function show($id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Không tìm thấy chương trình!'], 404);
        }
        return response()->json($tour, 200);
    }

    // 4. Cập nhật thông tin Tour (Update)
    public function update(Request $request, $id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Không tìm thấy chương trình!'], 404);
        }

        $tour->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công!',
            'data' => $tour
        ], 200);
    }

    // 5. Xóa Tour (Delete)
    public function destroy($id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Không tìm thấy chương trình!'], 404);
        }

        $tour->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa chương trình thành công!'
        ], 200);
    }
}