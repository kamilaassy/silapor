<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // 1. Role & permission dulu
            CategorySeeder::class,  // 2. Kategori laporan
            StatusSeeder::class,    // 3. Status laporan
            UserSeeder::class,      // 4. User demo (butuh role sudah ada)
        ]);
    }
}