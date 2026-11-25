<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DestinationController extends Controller
{
    public function index(): JsonResponse
    {
        $destinations = Destination::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Semua data destinasi berhasil diambil',
            'data' => $destinations,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug',
            'lokasi' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'durasi' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'kuota' => 'required|integer|min:0',
            'gambar' => 'required|string',
            'galeri' => 'nullable|array',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|array',
            'itinerary' => 'required|array',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['nama']);

        if (Destination::where('slug', $slug)->exists()) {
            $slug .= '-' . strtolower(Str::random(4));
        }

        $destination = Destination::create([
            'slug' => $slug,
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi'],
            'kategori' => $validated['kategori'],
            'durasi' => $validated['durasi'],
            'harga' => $validated['harga'],
            'rating' => $validated['rating'] ?? 5.0,
            'kuota' => $validated['kuota'],
            'gambar' => $validated['gambar'],
            'galeri' => $validated['galeri'] ?? [],
            'deskripsi' => $validated['deskripsi'],
            'fasilitas' => $validated['fasilitas'],
            'itinerary' => $validated['itinerary'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Destinasi baru berhasil ditambahkan',
            'data' => $destination,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $destination = Destination::find($id);

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

    public function update(Request $request, string $id): JsonResponse
    {
        $destination = Destination::find($id);

        if (! $destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('destinations', 'slug')->ignore($destination->id),
            ],
            'lokasi' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:255',
            'durasi' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|integer|min:0',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
            'kuota' => 'sometimes|required|integer|min:0',
            'gambar' => 'sometimes|required|string',
            'galeri' => 'sometimes|nullable|array',
            'deskripsi' => 'sometimes|required|string',
            'fasilitas' => 'sometimes|required|array',
            'itinerary' => 'sometimes|required|array',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $destination->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Destinasi berhasil diperbarui',
            'data' => $destination->fresh(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $destination = Destination::find($id);

        if (! $destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan',
            ], 404);
        }

        $destination->delete();

        return response()->json([
            'success' => true,
            'message' => 'Destinasi berhasil dihapus',
        ]);
    }
}