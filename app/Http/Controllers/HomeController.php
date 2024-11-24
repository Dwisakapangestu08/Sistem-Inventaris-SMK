<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportBarang;
use App\Exports\ImportBarang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateImportBarang;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.main', [
            'title' => 'Dashboard'
        ]);
    }

    public function logout()
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

    public function export()
    {
        return Excel::download(new ExportBarang, 'barang.xlsx');
    }

    public function template()
    {
        return Excel::download(new TemplateImportBarang, 'template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('data_barang', $nama_file);

        Excel::import(new ImportBarang, public_path('/data_barang/' . $nama_file));

        return redirect('/admin/barang')->with('success', 'Data Berhasil Diimport');
    }
}
