<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email'    => 'admin@silapor.test',
                'name'     => 'Admin SiLapor',
                'phone'    => '081234567890',
                'role'     => 'admin',
            ],
            [
                'email'    => 'petugas@silapor.test',
                'name'     => 'Budi Petugas',
                'phone'    => '081234567891',
                'role'     => 'petugas',
            ],
            [
                'email'    => 'warga@silapor.test',
                'name'     => 'Siti Warga',
                'phone'    => '081234567892',
                'role'     => 'warga',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );

            $user->assignRole($role);
        }
    }
}
