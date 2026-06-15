<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        $lapangans = [
            [
                'nama_lapangan' => 'Futsal A Jakabaring',
                'jenis_olahraga' => 'Futsal',
                'harga_per_jam' => 100000,
                'deskripsi' => 'Lapangan vinyl indoor dengan tribun mini dan pencahayaan malam.',
                'status' => 'tersedia',
            ],
            [
                'nama_lapangan' => 'Badminton 1',
                'jenis_olahraga' => 'Badminton',
                'harga_per_jam' => 50000,
                'deskripsi' => 'Karpet standar latihan dengan net dan shuttlecock cadangan.',
                'status' => 'tersedia',
            ],
            [
                'nama_lapangan' => 'Basket Indoor',
                'jenis_olahraga' => 'Basket',
                'harga_per_jam' => 150000,
                'deskripsi' => 'Court indoor full ring, cocok untuk latihan tim dan sparing.',
                'status' => 'tersedia',
            ],
            [
                'nama_lapangan' => 'Tenis Outdoor Center',
                'jenis_olahraga' => 'Tenis',
                'harga_per_jam' => 120000,
                'deskripsi' => 'Lapangan outdoor dengan permukaan keras dan area tunggu.',
                'status' => 'tersedia',
            ],
            [
                'nama_lapangan' => 'Mini Soccer B',
                'jenis_olahraga' => 'Mini Soccer',
                'harga_per_jam' => 220000,
                'deskripsi' => 'Rumput sintetis luas untuk 7 lawan 7, tersedia lampu malam.',
                'status' => 'maintenance',
            ],
        ];

        foreach ($lapangans as $lapangan) {
            Lapangan::updateOrCreate(
                ['nama_lapangan' => $lapangan['nama_lapangan']],
                $lapangan
            );
        }
    }
}
