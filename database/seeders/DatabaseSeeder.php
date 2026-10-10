<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            StageSeeder::class,
            LessonSeeder::class,
            LeaderboardSeeder::class,
            UserProgressSeeder::class,
            UserSeeder::class,
        ]);
    }
}
