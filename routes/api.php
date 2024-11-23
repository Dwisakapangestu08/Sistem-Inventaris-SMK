<?php

use App\Http\Controllers\api\ApiAdminController;
use App\Http\Middleware\IsApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiAuthController;
use App\Http\Controllers\api\ApiUserController;

Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);


Route::middleware(IsApi::class)->group(function () {
    Route::prefix('v1')->group(function () {

        // Admin
        Route::prefix('admin')->group(function () {
            // User
            Route::post('/daftar-user', [ApiAdminController::class, 'daftar_user']);
            Route::post('/status-user', [ApiAdminController::class, 'status_user']);
            // Kategori
            Route::post('/list-kategori', [ApiAdminController::class, 'list_kategori']);
            Route::post('/tambah-kategori', [ApiAdminController::class, 'tambah_kategori']);
            Route::get('/edit-kategori/{id}', [ApiAdminController::class, 'edit_kategori']);
            Route::post('/update-kategori/{id}', [ApiAdminController::class, 'update_kategori']);
            Route::get('/hapus-kategori/{id}', [ApiAdminController::class, 'hapus_kategori']);
            // Barang
            Route::post('/list-barang', [ApiAdminController::class, 'list_barang']);
            Route::post('/tambah-barang', [ApiAdminController::class, 'tambah_barang']);
            Route::get('/edit-barang/{id}', [ApiAdminController::class, 'edit_barang']);
            Route::post('/update-barang/{id}', [ApiAdminController::class, 'update_barang']);
            Route::get('/hapus-barang/{id}', [ApiAdminController::class, 'hapus_barang']);
            // Penanggung Jawab
            Route::post('/daftar-penanggung-jawab', [ApiAdminController::class, 'daftar_penanggung_jawab']);
            Route::post('/tambah-penanggung-jawab', [ApiAdminController::class, 'tambah_penanggung_jawab']);
            Route::get('/edit-penanggung-jawab/{id}', [ApiAdminController::class, 'edit_penanggung_jawab']);
            Route::post('/update-penanggung-jawab/{id}', [ApiAdminController::class, 'update_penanggung_jawab']);
            Route::get('/hapus-penanggung-jawab/{id}', [ApiAdminController::class, 'hapus_penanggung_jawab']);
            // Pengajuan
            Route::post('/daftar-pengajuan', [ApiAdminController::class, 'daftar_pengajuan']);
            Route::post('/status-pengajuan', [ApiAdminController::class, 'status_pengajuan']);
            Route::get('/reject/{id}', [ApiAdminController::class, 'reject_get']);
            Route::post('/penolakan-pengajuan/{id}', [ApiAdminController::class, 'penolakan_pengajuan']);
        });

        Route::prefix('user')->group(function () {
            Route::post('/daftar-pengajuan', [ApiUserController::class, 'daftar_pengajuan']);
            Route::post('/tambah-pengajuan', [ApiUserController::class, 'tambah_pengajuan']);
            Route::get('/alasan-penolakan/{id}', [ApiUserController::class, 'alasan_penolakan']);
            Route::get('/edit-pengajuan/{id}', [ApiUserController::class, "edit_pengajuan"]);
            Route::post('/update-pengajuan/{id}', [ApiUserController::class, "update_pengajuan"]);
            Route::get('/hapus-pengajuan/{id}', [ApiUserController::class, "delete_pengajuan"]);
        });
    });
});
