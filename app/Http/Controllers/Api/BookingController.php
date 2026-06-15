<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);

        if ($lapangan->status !== 'tersedia') {
            throw ValidationException::withMessages([
                'lapangan_id' => ['Lapangan sedang tidak tersedia untuk dipesan.'],
            ]);
        }

        $hasConflict = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $validated['tanggal'])
            ->where('status', '!=', 'ditolak')
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'jam_mulai' => ['Jadwal tersebut sudah dipesan. Pilih jam lain.'],
            ]);
        }

        $jam = (
            strtotime($validated['jam_selesai'])
            -
            strtotime($validated['jam_mulai'])
        ) / 3600;

        $total = $jam * $lapangan->harga_per_jam;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'lapangan_id' => $validated['lapangan_id'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'total_harga' => $total,
        ]);

        return response()->json([
            'message' => 'Booking berhasil dibuat',
            'booking' => $booking->load('lapangan'),
        ], 201);
    }

    public function riwayat()
    {
        return Booking::with('lapangan')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function upload(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('bukti_bayar');

        $namaFile = time().'.'.$file->extension();

        $file->move(
            public_path('bukti'),
            $namaFile
        );

        Pembayaran::create([
            'booking_id' => $booking->id,
            'bukti_bayar' => $namaFile,
            'tanggal_bayar' => now(),
        ]);

        $booking->update([
            'status' => 'menunggu_verifikasi',
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diupload',
        ]);
    }

    public function allBookings()
    {
        return Booking::with(['user', 'lapangan', 'pembayaran'])->latest()->get();
    }

    public function verifikasi(Request $request, Booking $booking): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status booking berhasil diubah menjadi ' . $validated['status'],
            'booking' => $booking->load(['user', 'lapangan', 'pembayaran']),
        ]);
    }

    public function stats(): JsonResponse
    {
        $totalLapangan = Lapangan::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'disetujui')->sum('total_harga');
        $pendingVerifications = Booking::where('status', 'menunggu_verifikasi')->count();
        $totalUsers = User::where('role', 'pelanggan')->count();

        return response()->json([
            'total_lapangan' => $totalLapangan,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue,
            'pending_verifications' => $pendingVerifications,
            'total_users' => $totalUsers,
        ]);
    }
}
