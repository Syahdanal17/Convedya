<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserProgress;

class AchievementService
{
    public function check(User $user): void
    {
        $metrics = [
            'streak'        => (int) UserProgress::where('user_id', $user->id)->max('streak'),
            'conversations' => Conversation::where('user_id', $user->id)->count(),
            'lessons'       => (int) UserProgress::where('user_id', $user->id)->sum('completed_lessons'),
            'xp'            => (int) UserProgress::where('user_id', $user->id)->sum('xp'),
        ];

        $have = $user->achievements()->pluck('achievements.id')->all();

        foreach (Achievement::whereNotIn('id', $have)->get() as $a) {
            [$type, $value] = array_pad(explode(':', $a->requirement, 2), 2, null);

            if (isset($metrics[$type]) && $metrics[$type] >= (int) $value) {
                $user->achievements()->syncWithoutDetaching([
                    $a->id => ['unlocked_at' => now()],
                ]);
            }
        }
    }
}
