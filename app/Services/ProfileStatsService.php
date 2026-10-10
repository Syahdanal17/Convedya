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
            $row = UserProgress::where('user_id', $user->id)
                ->orderByDesc('last_activity_date')
                ->first();

            $streak = 0;
            if ($row && $row->last_activity_date) {
                $last = Carbon::parse($row->last_activity_date);
                if ($last->isToday() || $last->isYesterday()) {
                    $streak = (int) $row->streak;
                }
            }

            // Rank = 1 + number of users with strictly more total XP (ties share a rank)
            $rank = $this->rankFor($user, $xp);

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

        public function rankFor(User $user, int $xp): int
        {
            $xpSub = '(select coalesce(sum(xp), 0) from user_progress where user_progress.user_id = users.id)';

            return 1 + User::query()
                ->where(function ($q) use ($xpSub, $xp, $user) {
                    $q->whereRaw("$xpSub > ?", [$xp])
                    ->orWhere(fn ($q2) => $q2->whereRaw("$xpSub = ?", [$xp])->where('users.id', '<', $user->id));
                })
                ->count();
        }
    }
