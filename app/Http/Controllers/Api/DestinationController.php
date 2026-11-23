<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;

class DestinationController extends Controller
{
    public function index(): JsonResponse
    {
        $destinations = Destination::where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar destinasi berhasil diambil',
            'data' => $destinations,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $destination = Destination::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail destinasi berhasil diambil',
            'data' => $destination,
        ]);
    }
}