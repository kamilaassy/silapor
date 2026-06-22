<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name'        => 'Baru masuk',
                'slug'        => 'baru-masuk',
                'color_hex'   => '#1d4ed8',
                'bg_hex'      => '#dbeafe',
                'description' => 'Laporan baru diterima, belum diverifikasi',
                'order'       => 1,
            ],
            [
                'name'        => 'Diverifikasi',
                'slug'        => 'diverifikasi',
                'color_hex'   => '#92400e',
                'bg_hex'      => '#fef3c7',
                'description' => 'Laporan valid dan sudah diverifikasi admin',
                'order'       => 2,
            ],
            [
                'name'        => 'Petugas ke lapangan',
                'slug'        => 'petugas-ke-lapangan',
                'color_hex'   => '#6d28d9',
                'bg_hex'      => '#ede9fe',
                'description' => 'Petugas sedang menangani di lokasi',
                'order'       => 3,
            ],
            [
                'name'        => 'Dalam proses',
                'slug'        => 'dalam-proses',
                'color_hex'   => '#065f46',
                'bg_hex'      => '#d1fae5',
                'description' => 'Perbaikan sedang berjalan',
                'order'       => 4,
            ],
            [
                'name'        => 'Selesai',
                'slug'        => 'selesai',
                'color_hex'   => '#14532d',
                'bg_hex'      => '#bbf7d0',
                'description' => 'Laporan telah ditangani dan selesai',
                'order'       => 5,
            ],
            [
                'name'        => 'Ditolak',
                'slug'        => 'ditolak',
                'color_hex'   => '#991b1b',
                'bg_hex'      => '#fee2e2',
                'description' => 'Laporan tidak valid atau duplikat',
                'order'       => 6,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insertOrIgnore(array_merge($status, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
