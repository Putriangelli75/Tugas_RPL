<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LapanganController extends Controller
{
    public function index()
    {
        return Lapangan::latest()->get();
    }

    public function store(Request $request): JsonResponse
    {
        $lapangan = Lapangan::create($this->validatedData($request));

        return response()->json($lapangan, 201);
    }

    public function show(Lapangan $lapangan): Lapangan
    {
        return $lapangan;
    }

    public function update(Request $request, Lapangan $lapangan): JsonResponse
    {
        $lapangan->update($this->validatedData($request));

        return response()->json($lapangan);
    }

    public function destroy(Lapangan $lapangan): JsonResponse
    {
        $lapangan->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis_olahraga' => 'required|string|max:100',
            'harga_per_jam' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['tersedia', 'maintenance'])],
        ]);
    }
}
