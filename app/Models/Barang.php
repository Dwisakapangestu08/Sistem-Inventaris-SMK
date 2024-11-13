<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'name_barang',
        'kategori_id',
        'merk_barang',
        'ukuran_barang',
        'bahan_barang',
        'tahun_perolehan',
        'jumlah_barang',
        'kondisi_barang',
        'keadaan_barang',
        'harga_barang',
        'lokasi_barang',
    ];

    public function penanggung_jawab()
    {
        return $this->hasOne(Penanggung_Jawab::class, 'barang_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}
