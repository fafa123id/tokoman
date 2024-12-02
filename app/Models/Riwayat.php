<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';
    protected $fillable = ['nama_barang', 'jenis_riwayat', 'jumlah', 'tanggal', 'id_barang'];
}