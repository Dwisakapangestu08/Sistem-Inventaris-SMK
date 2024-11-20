<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'title' => 'Dashboard'
        ]);
    }

    public function logout($id)
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('token'));
        return redirect('/');
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
