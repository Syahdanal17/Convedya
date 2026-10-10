<?php

    namespace App\Services;

    use App\Models\Achievement;
    use App\Models\Conversation;
    use App\Models\User;
    use App\Models\UserLanguage;
    use App\Models\UserProgress;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    class ProfileStatsService
    {
        public function for(User $user): array
        {
            // --- Totals across all languages ---
            $totals = UserProgress::where('user_id', $user->id)
                ->selectRaw('COALESCE(SUM(xp), 0) as xp')
                ->first();
            $xp = (int) $totals->xp;

            // Streak only counts if progress was touched since yesterday
            $streak = (int) UserProgress::where('user_id', $user->id)
                ->where('updated_at', '>=', now()->subDay()->startOfDay())
                ->max('streak');

            // Rank = 1 + number of users with strictly more total XP (ties share a rank)
            $rank = 1 + DB::table('user_progress')
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('SUM(xp) > ?', [$xp])
                ->get()
                ->count();

            $conversationCount = Conversation::where('user_id', $user->id)->count();

            return [
                'xp'                => $xp,
                'rank'              => $rank,
                'streak'            => $streak,
                'conversationCount' => $conversationCount,
                'stageLabel'        => $this->stageLabel($user),
                ...$this->activity($user),
                ...$this->badges($user),
            ];
        }

        private function stageLabel(User $user): string
        {
            $ul = UserLanguage::with('current_stage')
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->first();

            $stage = $ul?->current_stage;

            return $stage
                ? ucfirst($stage->level) . ' · Stage ' . $stage->order
                : 'Belum memilih bahasa';
        }

        /** 28 cells, Monday-aligned so they match the Sen..Min labels in the view. */
        private function activity(User $user): array
        {
            $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeeks(3);

            $perDay = Conversation::where('user_id', $user->id)
                ->where('started_at', '>=', $start)
                ->selectRaw('DATE(started_at) as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd');

            $activity = [];
            for ($i = 0; $i < 28; $i++) {
                $key = $start->copy()->addDays($i)->toDateString();
                $activity[] = min((int) ($perDay[$key] ?? 0), 3);
            }

            return [
                'activity'   => $activity,
                'activeDays' => count(array_filter($activity)),
            ];
        }

        private function badges(User $user): array
        {
            $unlocked = $user->achievements()->pluck('achievements.id')->all();

            $badges = Achievement::orderBy('id')->get()->map(fn ($a) => [
                $a->name,
                $a->description,
                in_array($a->id, $unlocked),
                $a->icon ?: 'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z',
            ])->all();

            return [
                'badges'      => $badges,
                'earnedCount' => count($unlocked),
            ];
        }
    }
