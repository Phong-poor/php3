<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm (Read)
     */
    public function index()
    {
        // Lấy sản phẩm mới nhất và phân trang 10 mục mỗi trang
        $products = Product::latest()->paginate(10);
        
        return view('products.index', compact('products'));
    }

    /**
     * Hiển thị form tạo mới sản phẩm (Create)
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Lưu sản phẩm mới (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable'
        ]);

        Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name), // iphone-15-pro
            'price'       => $request->price,
            'stock'       => $request->stock,
            'description' => $request->description,
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa (Edit)
     */
    public function edit(Product $product)
    {
        // Laravel tự động tìm Product theo ID nhờ Route Model Binding
        return view('products.edit', compact('product'));
    }

    /**
     * Cập nhật sản phẩm (Update)
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm (Delete)
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Sản phẩm đã bị xóa!');
    }
}