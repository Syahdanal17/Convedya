<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProgressService
{
    /** XP removed each time the AI has to correct the learner. */
    public const CORRECTION_PENALTY = 5;

    /** XP given when the learner completes a lesson. */
    public const LESSON_REWARD = 100;

    /**
     * Learner made a mistake that the AI corrected: XP goes down (never below 0).
     * Returns the XP change that was actually applied (0 or negative).
     */
    public function penalizeCorrection(int $userId, int $languageId): int
    {
        return $this->change($userId, $languageId, -self::CORRECTION_PENALTY);
    }

    /**
     * Learner finished a lesson: +100 XP and completed_lessons + 1.
     * Returns the XP change that was applied.
     */
    public function rewardLessonCompleted(int $userId, int $languageId): int
    {
        return $this->change($userId, $languageId, self::LESSON_REWARD, lessonCompleted: true);
    }

    public function totalXp(int $userId, int $languageId): int
    {
        return (int) DB::table('user_progress')
            ->where('user_id', $userId)
            ->where('language_id', $languageId)
            ->value('xp');
    }

    private function change(int $userId, int $languageId, int $delta, bool $lessonCompleted = false): int
    {
        return DB::transaction(function () use ($userId, $languageId, $delta, $lessonCompleted) {
            $row = DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('language_id', $languageId)
                ->lockForUpdate()
                ->first();

            $now = now();

            if (! $row) {
                $xp = max(0, $delta);

                DB::table('user_progress')->insert([
                    'user_id' => $userId,
                    'language_id' => $languageId,
                    'xp' => $xp,
                    'completed_lessons' => $lessonCompleted ? 1 : 0,
                    'streak' => $lessonCompleted ? 1 : 0,
                    'last_activity_date' => $lessonCompleted ? $now->toDateString() : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                return $xp;
            }

            $newXp = max(0, (int) $row->xp + $delta);

            $data = [
                'xp' => $newXp,
                'completed_lessons' => (int) $row->completed_lessons + ($lessonCompleted ? 1 : 0),
                'updated_at' => $now,
            ];

            if ($lessonCompleted) {
                $data['streak'] = $this->nextStreak($row->last_activity_date, (int) $row->streak, $now);
                $data['last_activity_date'] = $now->toDateString();
            }

            DB::table('user_progress')->where('id', $row->id)->update($data);

            return $newXp - (int) $row->xp;
        });
    }

    private function nextStreak(?string $lastDate, int $current, Carbon $now): int
    {
        if (! $lastDate) {
            return 1;
        }

        $last = Carbon::parse($lastDate);

        if ($last->isSameDay($now)) {
            return max(1, $current);          // already counted today
        }

        if ($last->isSameDay($now->copy()->subDay())) {
            return $current + 1;              // consecutive day
        }

        return 1;                             // missed a day: restart
    }
}
