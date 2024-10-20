<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penanggung_Jawab extends Model
{
    protected $fillable = [
        'user_id',
        'barang_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
