<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sampah liar',          'slug' => 'sampah-liar',          'icon' => 'ti-trash',                  'color' => '#ef4444'],
            ['name' => 'Jalan berlubang',       'slug' => 'jalan-berlubang',       'icon' => 'ti-road',                   'color' => '#f97316'],
            ['name' => 'Fasilitas umum rusak',  'slug' => 'fasilitas-umum-rusak',  'icon' => 'ti-building',               'color' => '#8b5cf6'],
            ['name' => 'Lampu jalan mati',      'slug' => 'lampu-jalan-mati',      'icon' => 'ti-bulb',                   'color' => '#eab308'],
            ['name' => 'Banjir / drainase',     'slug' => 'banjir-drainase',       'icon' => 'ti-droplet',                'color' => '#3b82f6'],
            ['name' => 'Pohon tumbang',         'slug' => 'pohon-tumbang',         'icon' => 'ti-tree',                   'color' => '#22c55e'],
            ['name' => 'Vandalisme',            'slug' => 'vandalisme',            'icon' => 'ti-writing',                'color' => '#ec4899'],
            ['name' => 'Lainnya',               'slug' => 'lainnya',               'icon' => 'ti-dots-circle-horizontal', 'color' => '#6b7280'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore(array_merge($cat, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
