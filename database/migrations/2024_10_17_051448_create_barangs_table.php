<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kategori_id');
            $table->string('name_barang');
            $table->string("merk_barang")->nullable();
            $table->string('ukuran_barang')->nullable();
            $table->string('bahan_barang')->nullable();
            $table->string('tahun_perolehan')->nullable();
            $table->string('jumlah_barang')->nullable();
            $table->string('harga_barang')->nullable();
            $table->string('kondisi_barang')->nullable();
            $table->string('keadaan_barang',)->nullable();
            $table->string('lokasi_barang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
