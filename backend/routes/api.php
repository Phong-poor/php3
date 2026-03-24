<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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