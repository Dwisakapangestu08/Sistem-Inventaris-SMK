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

    public function logout()
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('token'));
        return redirect('/');
    }

    public function pengajuan_barang()
    {
        return view('users.pengajuan_barang.main', [
            'title' => 'Pengajuan'
        ]);
    }
}
