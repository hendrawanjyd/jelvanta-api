<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::with('destination:id,nama,lokasi,slug')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $bookings = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pemesanan berhasil diambil',
            'data' => $bookings,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $booking = Booking::with(['destination', 'user:id,name,email,telepon'])->find($id);

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemesanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pemesanan berhasil diambil',
            'data' => $booking,
        ]);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $booking = Booking::with('destination')->find($id);

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemesanan tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['Menunggu Pembayaran', 'Terkonfirmasi', 'Selesai', 'Dibatalkan']),
            ],
        ]);

        $statusLama = $booking->status;
        $statusBaru = $validated['status'];

        if ($statusLama !== 'Dibatalkan' && $statusBaru === 'Dibatalkan' && $booking->destination) {
            $booking->destination->increment('kuota', $booking->jumlah_peserta);
        } elseif ($statusLama === 'Dibatalkan' && $statusBaru !== 'Dibatalkan' && $booking->destination) {
            if ($booking->jumlah_peserta > $booking->destination->kuota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota destinasi tidak mencukupi untuk mengaktifkan kembali pesanan ini',
                ], 422);
            }

            $booking->destination->decrement('kuota', $booking->jumlah_peserta);
        }

        $booking->update([
            'status' => $statusBaru,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pemesanan berhasil diperbarui',
            'data' => $booking->fresh('destination'),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemesanan tidak ditemukan',
            ], 404);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pemesanan berhasil dihapus',
        ]);
    }
}