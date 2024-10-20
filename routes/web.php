<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;



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
    });
});
