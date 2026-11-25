<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = ContactMessage::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesan kontak berhasil diambil',
            'data' => $messages,
        ]);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (! $message) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['Belum Dibaca', 'Sudah Dibaca', 'Dibalas']),
            ],
        ]);

        $message->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesan berhasil diperbarui',
            'data' => $message->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (! $message) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak ditemukan',
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus',
        ]);
    }
}