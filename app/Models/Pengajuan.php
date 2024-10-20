<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'user_id',
        'name_barang_pengajuan',
        'jumlah_barang_pengajuan',
        'harga_perkiraan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function request_pengajuan()
    {
        return $this->hasMany(Request_Pengajuan::class, 'pengajuan_id', 'id');
    }
}
