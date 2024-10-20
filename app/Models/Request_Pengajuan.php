<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request_Pengajuan extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'isAccept',
        'alasan_penolakan'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }
}
