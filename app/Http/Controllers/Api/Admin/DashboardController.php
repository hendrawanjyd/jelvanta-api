<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalPendapatan = Booking::whereIn('status', ['Terkonfirmasi', 'Selesai'])
            ->sum('total_harga');

        $recentBookings = Booking::with('destination:id,nama,lokasi')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data ringkasan dashboard berhasil diambil',
            'data' => [
                'stats' => [
                    'total_pendapatan' => (int) $totalPendapatan,
                    'total_booking' => Booking::count(),
                    'booking_menunggu' => Booking::where('status', 'Menunggu Pembayaran')->count(),
                    'total_destinasi_aktif' => Destination::where('is_active', true)->count(),
                    'total_pelanggan' => User::where('role', 'customer')->count(),
                    'pesan_belum_dibaca' => ContactMessage::where('status', 'Belum Dibaca')->count(),
                ],
                'recent_bookings' => $recentBookings,
            ],
        ]);
    }
}