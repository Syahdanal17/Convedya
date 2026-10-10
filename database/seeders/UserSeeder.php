<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed dummy users.
     * All dummy accounts use the password: password
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Budi Santoso',      'email' => 'budi@example.com'],
            ['name' => 'Siti Aminah',       'email' => 'siti@example.com'],
            ['name' => 'Andi Pratama',      'email' => 'andi@example.com'],
            ['name' => 'Dewi Lestari',      'email' => 'dewi@example.com'],
            ['name' => 'Rizky Ramadhan',    'email' => 'rizky@example.com'],
            ['name' => 'Putri Wulandari',   'email' => 'putri@example.com'],
            ['name' => 'Fajar Nugroho',     'email' => 'fajar@example.com'],
            ['name' => 'Maya Anggraini',    'email' => 'maya@example.com'],
            ['name' => 'Joko Prayogo',      'email' => 'joko@example.com'],
            ['name' => 'Rina Marlina',      'email' => 'rina@example.com'],
            ['name' => 'Agus Setiawan',     'email' => 'agus@example.com'],
            ['name' => 'Lestari Handayani', 'email' => 'lestari@example.com'],
            ['name' => 'Hendra Gunawan',    'email' => 'hendra@example.com'],
            ['name' => 'Nadia Safitri',     'email' => 'nadia@example.com'],
            ['name' => 'Bayu Saputra',      'email' => 'bayu@example.com'],
            ['name' => 'Citra Kirana',      'email' => 'citra@example.com'],
            ['name' => 'Eko Prasetyo',      'email' => 'eko@example.com'],
            ['name' => 'Wulan Sari',        'email' => 'wulan@example.com'],
            ['name' => 'Dimas Aditya',      'email' => 'dimas@example.com'],

        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'email_verified_at' => now(),
                    'password'          => Hash::make('password'),
                    'remember_token'    => Str::random(10),
                ]
            );
        }
    }
}
