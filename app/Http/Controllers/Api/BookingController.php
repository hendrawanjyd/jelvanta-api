<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destinasi_id' => 'required|exists:destinations,id',
            'nama_pemesan' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_berangkat' => 'required|date|after_or_equal:today',
            'jumlah_peserta' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string|max:50',
            'catatan' => 'nullable|string',
        ]);

        $destination = Destination::where('id', $validated['destinasi_id'])
            ->where('is_active', true)
            ->first();

        if (! $destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak tersedia',
            ], 404);
        }

        if ($validated['jumlah_peserta'] > $destination->kuota) {
            return response()->json([
                'success' => false,
                'message' => "Kuota tidak mencukupi. Sisa kuota tersedia: {$destination->kuota} orang",
            ], 422);
        }

        $booking = Booking::create([
            'kode_booking' => 'JLV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => $request->user('sanctum')?->id,
            'destinasi_id' => $destination->id,
            'nama_pemesan' => $validated['nama_pemesan'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'jumlah_peserta' => $validated['jumlah_peserta'],
            'total_harga' => $destination->harga * $validated['jumlah_peserta'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => 'Menunggu Pembayaran',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $destination->decrement('kuota', $validated['jumlah_peserta']);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dibuat',
            'data' => $booking->load('destination'),
        ], 201);
    }

    public function show(string $kodeBooking): JsonResponse
    {
        $booking = Booking::with('destination')
            ->where('kode_booking', $kodeBooking)
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Kode booking tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pemesanan berhasil diambil',
            'data' => $booking,
        ]);
    }

    public function myBookings(Request $request): JsonResponse
    {
        $bookings = Booking::with('destination:id,slug,nama,lokasi,durasi,gambar')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pemesanan berhasil diambil',
            'data' => $bookings,
        ]);
    }
}