<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penanggung_Jawab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Request_Pengajuan;
use Illuminate\Support\Facades\Validator;

class ApiAdminController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function daftar_user(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $find_active = [
            'role' => '2',
            'status' => '1',
        ];

        $find_not_active = [
            'role' => '2',
            'status' => '0',
        ];

        $member = User::where($find_active)->orWhere($find_not_active)->orderBy('id', 'desc');

        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
                $query->orWhere('email', 'like', "%$search%");
                $query->orWhere('jabatan', 'like', "%$search%");
            });
        }

        $total = $member->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $member->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function status_user(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        if ($type == 'approved') {
            $approved = User::where('id', $id)->update([
                'status' => '1'
            ]);
            if ($approved) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Diaktifkan',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Diaktifkan',
            ]);
        } else if ($type == 'banned') {
            $banned = User::where('id', $id)->update([
                'status' => '3'
            ]);
            if ($banned) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Dibanned',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Dibanned',
            ]);
        } else if ($type == "rejected") {
            $rejected = User::where('id', $id)->update([
                'status' => '4'
            ]);
            if ($rejected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Ditolak',
                ]);
            }
        } else {
            $unbanned = User::where('id', $id)->update([
                'status' => '1'
            ]);
            if ($unbanned) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Diaktifkan',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Diaktifkan',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Akun Gagal Diaktifkan',
        ]);
    }

    public function list_kategori(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $member = Kategori::orderBy('id', 'desc');

        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('name_kategori', 'like', "%$search%");
            });
        }

        $total = $member->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $member->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function tambah_kategori(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'deskripsi' => 'required',
            ],
            [
                'kategori.required' => 'Kategori harus diisi',
                'deskripsi.required' => 'Deskripsi harus diisi',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $kategori = Kategori::create([
            'name_kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi
        ]);

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori Ditambahkan',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kategori Gagal Ditambahkan',
        ], 400);
    }

    public function edit_kategori($id)
    {
        $data = Kategori::where('id', $id)->first();
        // dd($data);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kategori Tidak Ditemukan',
        ], 400);
    }

    public function update_kategori($id, Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'deskripsi' => 'required',
            ],
            [
                'kategori.required' => 'Kategori harus diisi',
                'deskripsi.required' => 'Deskripsi harus diisi',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $kategori = Kategori::where('id', $id)->update([
            'name_kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi
        ]);

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori Diupdate',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kategori Gagal Diupdate',
        ], 400);
    }

    public function hapus_kategori($id)
    {
        $kategori = Kategori::where('id', $id)->delete();

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori Dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kategori Gagal Dihapus',
        ], 400);
    }

    public function list_barang(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $member = Barang::orderBy('id', 'desc');

        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('name_barang', 'like', "%$search%");
                $query->orWhere('lokasi_barang', 'like', "%$search%");
                $query->orWhere('keadaan_barang', 'like', "%$search%");
            });
        }

        $total = $member->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $member->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        // $result['data']             = $data;
        $result['data']             = [];
        foreach ($data as $barang) {
            $result['data'][] = [
                'id' => $barang->id,
                'name_barang' => $barang->name_barang,
                'name_kategori' => $barang->kategori->name_kategori,
                'merk_barang' => $barang->merk_barang,
                'ukuran_barang' => $barang->ukuran_barang,
                'bahan_barang' => $barang->bahan_barang,
                'tahun_perolehan' => $barang->tahun_perolehan,
                'jumlah' => $barang->jumlah_barang,
                'kondisi' => $barang->kondisi_barang,
                'harga' => $barang->harga_barang,
                'keadaan_barang' => $barang->keadaan_barang,
                'lokasi' => $barang->lokasi_barang,
                'keterangan' => $barang->keterangan,
            ];
        }
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function tambah_barang(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'kategori' => 'required',
                'merk_barang' => 'nullable',
                'ukuran_barang' => 'nullable',
                'bahan_barang' => 'nullable',
                'tahun_perolehan' => 'nullable',
                'lokasi' => 'nullable',
                'kondisi' => 'nullable',
                'harga' => 'nullable',
                'jumlah' => 'nullable',
                'keadaan_barang' => 'nullable',
                'keterangan' => 'nullable',
            ],
            [
                'name.required' => 'Nama barang harus diisi',
                'kategori.required' => 'Kategori barang harus diisi',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $barang = Barang::create([
            'name_barang' => $request->name,
            'kategori_id' => $request->kategori,
            'lokasi_barang' => $request->lokasi,
            'kondisi_barang' => $request->kondisi,
            'harga_barang' => $request->harga,
            'jumlah_barang' => $request->jumlah,
            'keadaan_barang' => $request->keadaan_barang,
            'merk_barang' => $request->merk_barang,
            'ukuran_barang' => $request->ukuran_barang,
            'bahan_barang' => $request->bahan_barang,
            'tahun_perolehan' => $request->tahun_perolehan,
        ]);

        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Barang Berhasil Ditambahkan',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang Gagal Ditambahkan',
        ], 400);
    }

    public function edit_barang($id)
    {
        $barang = Barang::where('id', $id)->first();
        // dd($barang);

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => $barang,
                'kategori' => Kategori::all(),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang Tidak Ditemukan',
        ], 400);
    }

    public function update_barang($id, Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'kategori' => 'required',
                'merk_barang' => 'nullable',
                'ukuran_barang' => 'nullable',
                'bahan_barang' => 'nullable',
                'tahun_perolehan' => 'nullable',
                'lokasi' => 'nullable',
                'kondisi' => 'nullable',
                'harga' => 'nullable',
                'jumlah' => 'nullable',
                'keadaan_barang' => 'nullable',
                'keterangan' => 'nullable',
            ],
            [
                'name.required' => 'Nama harus diisi',
                'kategori.required' => 'Kategori harus diisi',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $barang = Barang::where('id', $id)->update([
            'name_barang' => $request->name,
            'kategori_id' => $request->kategori,
            'lokasi_barang' => $request->lokasi,
            'kondisi_barang' => $request->kondisi,
            'harga_barang' => $request->harga,
            'jumlah_barang' => $request->jumlah,
            'keadaan_barang' => $request->keadaan_barang,
            'merk_barang' => $request->merk_barang,
            'ukuran_barang' => $request->ukuran_barang,
            'bahan_barang' => $request->bahan_barang,
            'tahun_perolehan' => $request->tahun_perolehan,
            'keterangan' => $request->keterangan
        ]);

        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Barang Berhasil Diupdate',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang Gagal Diupdate',
        ], 400);
    }

    public function hapus_barang($id)
    {
        $barang = Barang::where('id', $id)->delete();

        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Barang Berhasil Dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang Gagal Dihapus',
        ], 400);
    }

    public function daftar_penanggung_jawab(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $member = Penanggung_Jawab::orderBy('id', 'desc');

        if (!empty($search)) {
            $member = $member->where(function ($query) use ($search) {
                $query->where('user_id', 'like', "%$search%");
                $query->orWhere('barang_id', 'like', "%$search%");
            });
        }

        $total = $member->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $member->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        // $result['data']             = $data;
        $result['data']             = [];
        foreach ($data as $user) {
            $result['data'][] = [
                'id' => $user->id,
                'nama' => $user->user->name,
                'barang' => $user->barang->name_barang
            ];
        }
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function tambah_penanggung_jawab(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'guru' => 'required',
            'barang' => 'required|unique:penanggung__jawabs,barang_id',
        ], [
            'guru.required' => 'Guru harus diisi',
            'barang.required' => 'Barang harus diisi',
            'barang.unique' => 'Barang sudah ada penanggung jawab',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $penanggung_jawab = Penanggung_Jawab::create([
            'user_id' => $request->guru,
            'barang_id' => $request->barang
        ]);

        if ($penanggung_jawab) {
            return response()->json([
                'success' => true,
                'message' => 'Penanggung Jawab Berhasil Ditambahkan',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Penanggung Jawab Gagal Ditambahkan',
        ], 400);
    }

    public function edit_penanggung_jawab($id)
    {
        $penanggung_jawab = Penanggung_Jawab::where('id', $id)->first();
        if ($penanggung_jawab) {
            return response()->json([
                'success' => true,
                'data' => $penanggung_jawab,
                'guru' => User::where('role', '0')->get(),
                'barang' => Barang::all(),
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Penanggung Jawab Tidak Ditemukan',
        ], 400);
    }

    public function update_penanggung_jawab(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'guru' => 'required',
            'barang' => 'required|unique:penanggung__jawabs,barang_id',
        ], [
            'guru.required' => 'Guru harus diisi',
            'barang.required' => 'Barang harus diisi',
            'barang.unique' => 'Barang sudah ada penanggung jawab',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'validation' => true,
                'message' => $validation->errors(),
            ], 400);
        }

        $penanggung_jawab = Penanggung_Jawab::where('id', $id)->update([
            'user_id' => $request->guru,
            'barang_id' => $request->barang
        ]);

        if ($penanggung_jawab) {
            return response()->json([
                'success' => true,
                'message' => 'Penanggung Jawab Berhasil Diupdate',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Penanggung Jawab Gagal Diupdate',
        ]);
    }

    /**
     * Menghapus penanggung jawab berdasarkan id yang diberikan
     *
     * @param int $id id penanggung jawab yang akan dihapus
     * @return \Illuminate\Http\Response
     */
    public function hapus_penanggung_jawab($id)
    {
        $penanggung_jawab = Penanggung_Jawab::where('id', $id)->delete();

        if ($penanggung_jawab) {
            return response()->json([
                'success' => true,
                'message' => 'Penanggung Jawab Berhasil Dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Penanggung Jawab Gagal Dihapus',
        ], 400);
    }

    /**
     * Mengambil daftar pengajuan berdasarkan parameter yang diberikan
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function daftar_pengajuan(Request $request)
    {
        // Mendapatkan parameter draw, start, length, dan search dari request
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        // Membuat query untuk mengambil data pengajuan
        $member = Pengajuan::orderBy('id', 'desc');

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
                'tujuan' => $data->tujuan->pengajuan,
                'status' => $data->status
            ];
        }
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }


    /**
     * Membuat status pengajuan menjadi approved, banned, rejected, atau unbanned
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function status_pengajuan(Request $request)
    {
        // Mendapatkan parameter id dan type dari request
        $id = $request->id;
        $type = $request->type;

        // Membuat status pengajuan menjadi approved
        if ($type == 'approved') {
            // Membuat query untuk mengupdate status user menjadi approved
            $approved = User::where('id', $id)->update([
                'status' => '1'
            ]);

            // Jika status berhasil diupdate, maka akan dikembalikan response dengan status true dan message "Akun Diaktifkan"
            if ($approved) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Diaktifkan',
                ]);
            }

            // Jika status gagal diupdate, maka akan dikembalikan response dengan status false dan message "Akun Gagal Diaktifkan"
            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Diaktifkan',
            ]);
        }

        // Membuat status pengajuan menjadi banned
        if ($type == 'banned') {
            // Membuat query untuk mengupdate status user menjadi banned
        } else if ($type == 'banned') {
            $banned = User::where('id', $id)->update([
                'status' => '3'
            ]);

            // Jika status berhasil diupdate, maka akan dikembalikan response dengan status true dan message "Akun Dibanned"
            if ($banned) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Dibanned',
                ]);
            }

            // Jika status gagal diupdate, maka akan dikembalikan response dengan status false dan message "Akun Gagal Dibanned"
            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Dibanned',
            ]);
        }

        // Membuat status pengajuan menjadi rejected
        if ($type == "rejected") {
            // Membuat query untuk mengupdate status user menjadi rejected
        } else if ($type == "rejected") {
            $rejected = User::where('id', $id)->update([
                'status' => '4'
            ]);

            // Jika status berhasil diupdate, maka akan dikembalikan response dengan status true dan message "Akun Ditolak"
            if ($rejected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Ditolak',
                ]);
            }
        }

        // Membuat status pengajuan menjadi unbanned
        if ($type == "unbanned") {
            // Membuat query untuk mengupdate status user menjadi unbanned
        } else {
            $unbanned = User::where('id', $id)->update([
                'status' => '1'
            ]);

            // Jika status berhasil diupdate, maka akan dikembalikan response dengan status true dan message "Akun Diaktifkan"
            if ($unbanned) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun Diaktifkan',
                ]);
            }

            // Jika status gagal diupdate, maka akan dikembalikan response dengan status false dan message "Akun Gagal Diaktifkan"
            return response()->json([
                'success' => false,
                'message' => 'Akun Gagal Diaktifkan',
            ]);
        }

        // Jika tidak ada parameter type yang sesuai, maka akan dikembalikan response dengan status false dan message "Akun Gagal Diaktifkan"
        return response()->json([
            'success' => false,
            'message' => 'Akun Gagal Diaktifkan',
        ]);
    }
}
