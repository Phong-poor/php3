<?php

namespace App\Http\Controllers;

use App\Models\danhmucModel;
use Illuminate\Http\Request;

class DanhMucController extends Controller
{
    public function index(){
        $danhmuc = danhmucModel::all();
        return response()->json(['thongbao' => 'thành công', 'data' => $danhmuc]);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'ten_danhmuc' => 'required|string|max:255',
            'trangthai'  => 'required|in:active,hidden'
        ]);

        $danhmuc = danhmucModel::create($validated);
        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Thêm danh mục thành công',
            'data' => $danhmuc
        ], 201);
    }
    public function show($id)
    {
        $danhMuc = danhmucModel::find($id);

        if (!$danhMuc) {
            return response()->json(['message' => 'Không tìm thấy danh mục'], 404);
        }

        return response()->json(['data' => $danhMuc], 200);
    }
    public function update(Request $request, $id)
    {
        $danhMuc = danhmucModel::find($id);

        if (!$danhMuc) {
            return response()->json(['message' => 'Không tìm thấy danh mục để sửa'], 404);
        }

        $validated = $request->validate([
            'ten_danhmuc' => 'sometimes|required|string|max:255',
            'trangthai'  => 'sometimes|required|in:active,hidden'
        ]);

        
        $danhMuc->update($validated);

        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Cập nhật thành công',
            'data' => $danhMuc
        ], 200);
        }
        public function destroy($id)
    {
        $danhMuc = danhmucModel::find($id);

        if (!$danhMuc) {
            return response()->json(['message' => 'Không tìm thấy danh mục để xóa'], 404);
        }

        $danhMuc->delete();

        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Đã xóa danh mục'
        ], 200);
    }

    }

    

