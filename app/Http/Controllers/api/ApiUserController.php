<?php

namespace App\Http\Controllers\api;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiUserController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function daftar_pengajuan(Request $request)
    {
        // Mendapatkan parameter draw, start, length, dan search dari request
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        // Membuat query untuk mengambil data pengajuan
        $member = Pengajuan::where('user_id', Auth()->user->id)->orderBy('id', 'desc');

        // Jika ada parameter search, maka query akan di-filter berdasarkan parameter search
        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('user_id', 'like', "%$search%");
                $query->orWhere('kondisi', 'like', "%$search%");
            });
        }

        // Menghitung total data yang ada di database
        $total = $member->count();

        // Jika tidak ada data, maka akan dikembalikan response dengan status false dan message "Data tidak ditemukan"
        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        // Mengambil data pengajuan berdasarkan parameter draw, start, dan length
        $datas = $member->offset($start)->limit($length)->get();

        // Mengisi array result dengan data yang telah diambil
        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        // $result['data']             = $data;
        $result['data']             = [];
        foreach ($datas as $data) {
            $result['data'][] = [
                'id' => $data->id,
                'nama' => $data->user->name,
                'barang' => $data->name_barang_pengajuan,
                'jumlah' => $data->jumlah_barang_pengajuan,
                'harga' => $data->harga_perkiraan,
                'kondisi' => $data->kondisi,
                'total' => $data->jumlah_barang_pengajuan * $data->harga_perkiraan,
                'tujuan' => $data->tujuan_pengajuan,
                'status' => $data->status,
                // 'alasan' => $data->request_pengajuan->alasan_penolakan
            ];
        }
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }
}
