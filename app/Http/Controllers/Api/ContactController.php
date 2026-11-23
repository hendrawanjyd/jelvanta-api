<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $message = ContactMessage::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
            'subjek' => $validated['subjek'],
            'pesan' => $validated['pesan'],
            'status' => 'Belum Dibaca',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim, tim kami akan segera menghubungi Anda',
            'data' => $message,
        ], 201);
    }
}