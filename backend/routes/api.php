<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\ThuongHieuController;
use App\Http\Controllers\UserController;



Route::get('/danhmuc',             [DanhMucController::class, 'index']);
Route::post('/danhmuc',         [DanhMucController::class, 'store']);
Route::get('/danhmuc/{id_danhmuc}',        [DanhMucController::class, 'show']);
Route::put('/danhmuc/{id_danhmuc}',        [DanhMucController::class, 'update']);
Route::delete('/danhmuc/{id_danhmuc}',     [DanhMucController::class, 'destroy']);

Route::get('/thuonghieu',             [ThuongHieuController::class, 'index']);
Route::post('/thuonghieu',         [ThuongHieuController::class, 'store']);
Route::get('/thuonghieu/{id_thuonghieu}',        [ThuongHieuController::class, 'show']);
Route::put('/thuonghieu/{id_thuonghieu}',        [ThuongHieuController::class, 'update']);
Route::delete('/thuonghieu/{id_thuonghieu}',     [ThuongHieuController::class, 'destroy']);

Route::post('/register',         [UserController::class, 'store']);
Route::get('/users',             [UserController::class, 'index']);
Route::get('/users/{id}',        [UserController::class, 'show']);
Route::put('/users/{id}',        [UserController::class, 'update']);
Route::delete('/users/{id}',     [UserController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/test', function () {
    return 'OK API';
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Welcome admin'
        ]);
    });





});