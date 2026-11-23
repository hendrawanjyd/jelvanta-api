<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'kode_booking',
        'user_id',
        'destinasi_id',
        'nama_pemesan',
        'email',
        'telepon',
        'tanggal_berangkat',
        'jumlah_peserta',
        'total_harga',
        'metode_pembayaran',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berangkat' => 'date:Y-m-d',
            'jumlah_peserta' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destinasi_id');
    }
}