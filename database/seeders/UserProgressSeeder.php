<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProgressSeeder extends Seeder
{
    /**
     * Seed dummy progress (xp, lessons, streak) per user per language.
     * Run UserSeeder and your languages seeder first.
     */
    public function run(): void
    {
        $languageIds = DB::table('languages')->pluck('id')->all();

        if (empty($languageIds)) {
            $this->command?->warn('No languages found. Seed the languages table first.');
            return;
        }

        foreach (User::pluck('id') as $userId) {
            // Each user learns 1-2 languages
            $picked = collect($languageIds)
                ->shuffle()
                ->take(min(random_int(1, 2), count($languageIds)));

            foreach ($picked as $languageId) {
                $xp = random_int(0, 5000);
                $completedLessons = intdiv($xp, 50) + random_int(0, 5);
                $streak = random_int(0, 30);

                // Active streak -> last activity today/yesterday, otherwise older (or never)
                $lastActivity = $streak > 0
                    ? now()->subDays(random_int(0, 1))->toDateString()
                    : (random_int(0, 1) ? now()->subDays(random_int(2, 30))->toDateString() : null);

                DB::table('user_progress')->updateOrInsert(
                    ['user_id' => $userId, 'language_id' => $languageId],
                    [
                        'xp'                => $xp,
                        'completed_lessons' => $completedLessons,
                        'streak'            => $streak,
                        'last_activity_date'=> $lastActivity,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );
            }
        }
    }
}
