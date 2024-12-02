<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang');// ID barang, auto increment
            $table->string('jenis_tutup');
            $table->string('nama_barang');
            $table->integer('stok');
            $table->integer('bal');
            $table->string('harga_beli');
            $table->string('harga_jual');
            $table->string('ukuran');
            $table->string('pathImg1')->nullable();
            $table->string('pathImg2')->nullable();
            $table->string('fileName1')->nullable();
            $table->string('fileName2')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_barangs');
    }
};