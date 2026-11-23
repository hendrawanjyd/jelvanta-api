<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('destinasi_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('nama_pemesan');
            $table->string('email');
            $table->string('telepon', 20);
            $table->date('tanggal_berangkat');
            $table->unsignedInteger('jumlah_peserta');
            $table->unsignedBigInteger('total_harga');
            $table->string('metode_pembayaran', 50);
            $table->string('status', 30)->default('Menunggu Pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};