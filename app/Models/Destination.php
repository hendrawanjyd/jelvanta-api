<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    protected $fillable = [
        'slug',
        'nama',
        'lokasi',
        'kategori',
        'durasi',
        'harga',
        'rating',
        'kuota',
        'gambar',
        'galeri',
        'deskripsi',
        'fasilitas',
        'itinerary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'rating' => 'float',
            'kuota' => 'integer',
            'galeri' => 'array',
            'fasilitas' => 'array',
            'itinerary' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'destinasi_id');
    }
}