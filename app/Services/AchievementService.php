<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserProgress;

class AchievementService
{
    /** Unlock every achievement whose requirement the user now meets. */
    public function check(int $userId): void
    {
        $user = User::find($userId);
        if (! $user) {
            return;
        }

        // "Selesaikan percakapan pertama" = finished conversations only.
        $stats = [
            'conversations' => Conversation::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'streak' => (int) UserProgress::where('user_id', $userId)->max('streak'),
            'xp' => (int) UserProgress::where('user_id', $userId)->sum('xp'),
            'lessons' => (int) UserProgress::where('user_id', $userId)->sum('completed_lessons'),
        ];

        foreach (Achievement::all() as $a) {
            [$type, $threshold] = array_pad(explode(':', (string) $a->requirement, 2), 2, null);

            if ($type === null || $threshold === null || ! isset($stats[$type])) {
                continue;
            }

            if ($stats[$type] >= (int) $threshold) {
                $user->achievements()->syncWithoutDetaching([
                    $a->id => ['unlocked_at' => now()],
                ]);
            }
        }
    }
}
