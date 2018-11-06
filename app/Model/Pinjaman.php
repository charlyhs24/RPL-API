<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    //
    protected $table = 'pinjaman';
    protected $primarykey = 'id_pinjaman'; 
    
    protected $fillable = [
        'nim_peminjam','nama_peminjam','asal_organisasi', 'tanggal_peminjaman', 'status', 'url_surat', 'url_ktm', 'status_pengajuan',
    ];
}
