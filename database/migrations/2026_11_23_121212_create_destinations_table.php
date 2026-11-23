<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nama');
            $table->string('lokasi');
            $table->string('kategori');
            $table->string('durasi');
            $table->unsignedBigInteger('harga');
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('kuota')->default(10);
            $table->string('gambar');
            $table->json('galeri')->nullable();
            $table->text('deskripsi');
            $table->json('fasilitas');
            $table->json('itinerary');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};