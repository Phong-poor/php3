<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Các trường cho phép nhập liệu hàng loạt
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
    ];

    // Ép kiểu dữ liệu khi lấy ra (tùy chọn)
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}