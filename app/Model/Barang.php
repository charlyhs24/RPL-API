<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    //
    protected $table = 'barang';
    protected $primaryKey = 'id_barang'; 
    public $timestamps = false;
    
    protected $fillable = [
        'nama_barang', 'stok','address_picture',
    ];
}
