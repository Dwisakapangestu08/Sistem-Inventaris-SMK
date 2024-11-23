<?php

use App\Http\Middleware\IsUser;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::middleware(IsGuest::class)->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/register', [AuthController::class, 'register']);
});


Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/logout/{id}', [HomeController::class, 'logout']);
    Route::prefix('admin')->group(function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::get('/akun', [HomeController::class, 'akun']);
        Route::get('/barang', [HomeController::class, 'barang']);
        Route::get('/kategori-barang', [HomeController::class, 'kategori_barang']);
        Route::get('/penanggung-jawab', [HomeController::class, 'penanggung_jawab']);
        Route::get('/pengajuan-barang', [HomeController::class, 'pengajuan_barang']);
    });
});

Route::middleware(IsUser::class)->group(function () {
    Route::get('/logout/{id}', [UserController::class, 'logout']);
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/pengajuan-barang', [UserController::class, 'pengajuan_barang']);
    });
});
