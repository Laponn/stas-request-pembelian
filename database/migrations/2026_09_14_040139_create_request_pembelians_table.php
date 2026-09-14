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
        Schema::create('request_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_riset_id')->constrained('jenis_risets')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('jumlah_barang');
            $table->integer('harga_satuan');
            $table->integer('harga_total');
            $table->text('link_barang');
            $table->text('keterangan')->nullable();
            $table->enum('status_pembelian', ['Menunggu', 'Sudah Dibeli', 'Dalam Perjalanan', 'Sudah Sampai'])->default('Menunggu');
            $table->date('tanggal_invoice')->nullable();
            $table->text('link_invoice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_pembelians');
    }
};
