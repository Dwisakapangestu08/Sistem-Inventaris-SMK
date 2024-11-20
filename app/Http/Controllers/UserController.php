<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'title' => 'Dashboard'
        ]);
    }

    public function daftar_pengajuan()
    {
        echo "DAFTAR PENGAJUAN";
    }

    public function pengajuan_pembelian()
    {
        echo "PENGAJUAN PEMBELIAN";
    }
}
