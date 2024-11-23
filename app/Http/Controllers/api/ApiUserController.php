<?php

namespace App\Http\Controllers\api;

use App\Models\Pengajuan;
use App\Models\Request_Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
        $member = Pengajuan::where('user_id', auth()->user()->id)->orderBy('id', 'desc');

        // Jika ada parameter search, maka query akan di-filter berdasarkan parameter search
        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('name_barang_pengajuan', 'like', "%$search%");
                $query->orWhere('harga_perkiraan', 'like', "%$search%");
                $query->orWhere('tujuan_pengajuan', 'like', "%$search%");
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

    public function tambah_pengajuan(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name_barang_pengajuan' => 'required',
                'jumlah_barang_pengajuan' => 'required',
                'harga_perkiraan' => 'required',
                'tujuan_pengajuan' => 'required',
                'kondisi' => 'required',
                'status' => 'nullable',
            ],
            [
                'name_barang_pengajuan.required' => 'Nama barang harus diisi',
                'jumlah_barang_pengajuan.required' => 'Jumlah barang harus diisi',
                'harga_perkiraan.required' => 'Harga perkiraan harus diisi',
                'tujuan_pengajuan.required' => 'Tujuan pengajuan harus diisi',
                'kondisi.required' => 'Kondisi barang harus diisi',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        DB::beginTransaction();

        try {
            $data = [
                'user_id' => auth()->user()->id,
                'name_barang_pengajuan' => $request->name_barang_pengajuan,
                'jumlah_barang_pengajuan' => $request->jumlah_barang_pengajuan,
                'harga_perkiraan' => $request->harga_perkiraan,
                'tujuan_pengajuan' => $request->tujuan_pengajuan,
                'kondisi' => $request->kondisi,
                'total_harga' => $request->jumlah_barang_pengajuan * $request->harga_perkiraan,
                'status' => 0
            ];

            $pengajuan = Pengajuan::create($data);

            if (!$pengajuan) {
                throw new \Exception('Pengajuan gagal ditambahkan');
            }

            $req_pengajuan = Request_Pengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'isAccept' => null,
                'alasan_penolakan' => null
            ]);

            if (!$req_pengajuan) {
                throw new \Exception('Request pengajuan gagal ditambahkan');
            }

            DB::commit();

            if ($pengajuan) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan berhasil ditambahkan',
                    'data_pengajuan' => $pengajuan,
                    'data_request_pengajuan' => $req_pengajuan
                ], 201);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function alasan_penolakan($id)
    {
        $data = Request_Pengajuan::where('pengajuan_id', $id)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "Request pengajuan tidak ditemukan",
        ], 400);
    }

    public function edit_pengajuan($id)
    {
        $data = Pengajuan::where('id', $id)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "Pengajuan Tidak Ditemukan"
        ]);
    }

    public function update_pengajuan($id, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name_barang_pengajuan' => "required",
            "jumlah_barang_pengajuan" => "required",
            "harga_perkiraan" => "required",
            "kondisi" => "required",
            "tujuan_pengajuan" => "required",
            'status' => "nullable"
        ], [
            "name_barang_pengajuan.required" => "Nama barang harus diisi",
            "jumlah_barang_pengajuan.required" => "Jumlah barang harus diisi",
            "harga_perkiraan.required" => "Harga perkiraan harus diisi",
            "tujuan_pengajuan.required" => "Tujuan pengajuan harus diisi",
            "kondisi.required" => "Kondisi barang harus diisi",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validate->errors(),
            ], 400);
        }

        $update = [
            "name_barang_pengajuan" => $request->name_barang_pengajuan,
            "jumlah_barang_pengajuan" => $request->jumlah_barang_pengajuan,
            "harga_perkiraan" => $request->harga_perkiraan,
            "tujuan_pengajuan" => $request->tujuan_pengajuan,
            "kondisi" => $request->kondisi,
            "total_harga" => $request->jumlah_barang_pengajuan * $request->harga_perkiraan
        ];

        $pengajuan = Pengajuan::where('id', $id)->update($update);

        if ($pengajuan) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil diupdate'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pengajuan gagal diupdate'
        ], 400);
    }

    public function delete_pengajuan($id)
    {
        $pengajuan = Pengajuan::where('id', $id)->delete();
        if ($pengajuan) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pengajuan gagal dihapus'
        ], 400);
    }
}
