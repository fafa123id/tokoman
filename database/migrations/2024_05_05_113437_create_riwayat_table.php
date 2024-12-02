<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->string('jenis_riwayat', 100);
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->bigInteger('id_barang')->unsigned();
            $table->foreign('id_barang')->references('id')->on('stok_barangs')->onDelete('cascade');
            $table->timestamps();
        });
        $triggerSQL = "
        CREATE TRIGGER trigger_modal
        AFTER INSERT ON riwayat
        FOR EACH ROW
        BEGIN
        DECLARE total DECIMAL(10, 2);
    
        -- Calculate total modal
        SELECT (r.jumlah * sb.harga_beli) 
        INTO total
        FROM riwayat r
        LEFT JOIN stok_barangs sb ON r.id_barang = sb.id
        WHERE r.jenis_riwayat = 'masuk'
        ORDER BY r.id DESC -- Urutkan berdasarkan tanggal secara descending
        LIMIT 1; -- Batasi hasil ke satu record terbaru
    
        -- Insert total into Modal table
        INSERT INTO Modal (Total_modal, Tanggal, created_at, updated_at)
        VALUES (total, NOW(), NOW(), NOW());
        END;
        ";
        DB::unprepared($triggerSQL);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $triggerSQL = "DROP TRIGGER IF EXISTS trigger_modal;";
        DB::unprepared($triggerSQL);
        Schema::dropIfExists('riwayat');
    }
};
