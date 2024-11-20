<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.main', [
            'title' => 'Dashboard'
        ]);
    }

    public function logout($id)
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('token'));
        return redirect('/');
    }

    public function akun()
    {

        return view('home.akun.main', [
            'title' => 'Aktivasi Akun'
        ]);
    }

    public function barang()
    {
        return view('home.barang.main', [
            'title' => 'Barang'
        ]);
    }

    public function kategori_barang()
    {
        return view('home.barang.kategori', [
            'title' => 'Kategori Barang'
        ]);
    }

    public function penanggung_jawab()
    {
        return view('home.penanggung_jawab.main', [
            'title' => 'Penanggung Jawab'
        ]);
    }

    public function pengajuan_barang()
    {
        return view('home.pengajuan_barang.main', [
            'title' => 'Pengajuan Barang'
        ]);
    }
}
