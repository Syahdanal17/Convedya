<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        // Format progress: [language_id, xp, completed_lessons, streak]
        // User A, B, C punya beberapa bahasa untuk mengetes penjumlahan (Opsi A)
        $users = [
            ['User A', 'user.a@example.com', [[1, 800, 20, 5], [2, 450, 10, 9]]], // total 1250
            ['User B', 'user.b@example.com', [[1, 980, 22, 6]]],                  // total 980
            ['User C', 'user.c@example.com', [[1, 500, 12, 3], [3, 340, 6, 4]]],  // total 840
            ['User D', 'user.d@example.com', [[2, 500, 9, 2]]],                   // total 500
        ];

        // 12 user tambahan (XP 450 sampai 120) supaya pagination terlihat
        for ($i = 1; $i <= 12; $i++) {
            $xp = 480 - ($i * 30);
            $users[] = ["Filler $i", "filler$i@example.com", [[1, $xp, intdiv($xp, 20), $i]]];
        }

        // 1 user tanpa progress sama sekali (harus tampil dengan XP 0)
        $users[] = ['User Zero', 'user.zero@example.com', []];

        foreach ($users as [$name, $email, $progress]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            foreach ($progress as [$languageId, $xp, $lessons, $streak]) {
                UserProgress::updateOrCreate(
                    ['user_id' => $user->id, 'language_id' => $languageId],
                    ['xp' => $xp, 'completed_lessons' => $lessons, 'streak' => $streak]
                );
            }
        }
    }
}
