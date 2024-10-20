<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['name_kategori', 'deskripsi'];

    public function barang()
    {
        return $this->hasOne(Barang::class, 'kategori_id', 'id');
    }
}
