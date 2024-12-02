<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $table = 'stok_barangs'; // Nama tabel di database

    protected $fillable = [
        'id_barang', 'nama_barang', 'stok','bal','harga_beli','harga_jual','jenis_tutup','ukuran','pathImg1','pathImg2','fileName1','fileName2',
    ];
}