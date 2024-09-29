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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();            
            $table->string('nama'); 
            $table->string('nim');
            $table->string('prodi');
            $table->string('nama_barang');
            $table->integer('jml_barang');
            $table->date('tggl_kembali');
            $table->date('tggl_pinjam');
            $table->string('status');
            $table->string('image')->nullable();
            $table->foreignId('petugas_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
