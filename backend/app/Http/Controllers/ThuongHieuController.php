<?php

namespace App\Http\Controllers;

use App\Models\ThuongHieu;
use Illuminate\Http\Request;

class ThuongHieuController extends Controller
{
    
    public function index(){
        $thuonghieu = ThuongHieu::all();
        return response()->json(['thongbao' => 'thành công', 'data' => $thuonghieu]);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'ten_thuonghieu' => 'required|string|max:255'
            
        ]);

        $danhmuc = ThuongHieu::create($validated);
        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Thêm danh mục thành công',
            'data' => $danhmuc
        ], 201);
    }
    public function show($id)
    {
        $thuonghieu = ThuongHieu::find($id);

        if (!$thuonghieu) {
            return response()->json(['message' => 'Không tìm thấy danh mục'], 404);
        }

        return response()->json(['data' => $thuonghieu], 200);
    }
    public function update(Request $request, $id)
    {
        $thuonghieu = ThuongHieu::find($id);

        if (!$thuonghieu) {
            return response()->json(['message' => 'Không tìm thấy danh mục để sửa'], 404);
        }

        $validated = $request->validate([
            'ten_thuonghieu' => 'sometimes|required|string|max:255'
            
        ]);

        
        $thuonghieu->update($validated);

        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Cập nhật thành công',
            'data' => $thuonghieu
        ], 200);
        }
        public function destroy($id)
    {
        $thuonghieu = ThuongHieu::find($id);

        if (!$thuonghieu) {
            return response()->json(['message' => 'Không tìm thấy danh mục để xóa'], 404);
        }

        $thuonghieu->delete();

        return response()->json([
            'thongbao' => 'thành công',
            'message' => 'Đã xóa danh mục'
        ], 200);
    }

}
