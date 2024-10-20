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
        Schema::create('request__pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
            $table->boolean('isAccept')->nullable();
            $table->string('alasan_penolakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request__pengajuans');
    }
};
