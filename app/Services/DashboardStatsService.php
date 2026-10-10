<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserLanguage;
use App\Models\UserProgress;
use Illuminate\Support\Carbon;

class DashboardStatsService
{
    private const DAILY_GOAL_MINUTES = 10;

    public function for(User $user): array
    {
        $xp = (int) UserProgress::where('user_id', $user->id)->sum('xp');

        // Same rule as the profile page: streak only counts if touched since yesterday
        $streak = (int) UserProgress::where('user_id', $user->id)
            ->where('updated_at', '>=', now()->subDay()->startOfDay())
            ->max('streak');

        return [
            'xp'                => $xp,
            'streak'            => $streak,
            'conversationCount' => Conversation::where('user_id', $user->id)->count(),
            'practiceTime'      => $this->formatMinutes($this->totalMinutes($user)),
            'dailyGoal'         => self::DAILY_GOAL_MINUTES,
            ...$this->week($user),
            ...$this->path($user),
        ];
    }

    private function totalMinutes(User $user): int
    {
        return (int) Conversation::where('user_id', $user->id)
            ->whereNotNull('ended_at')
            ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(MINUTE, started_at, ended_at)), 0) as m')
            ->value('m');
    }

    private function formatMinutes(int $m): string
    {
        return intdiv($m, 60) > 0
            ? intdiv($m, 60) . 'j ' . ($m % 60) . 'm'
            : $m . 'm';
    }

    private function week(User $user): array
    {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $perDay = Conversation::where('user_id', $user->id)
            ->whereNotNull('ended_at')
            ->where('started_at', '>=', $start)
            ->selectRaw('DATE(started_at) as d, SUM(TIMESTAMPDIFF(MINUTE, started_at, ended_at)) as m')
            ->groupBy('d')
            ->pluck('m', 'd');

        $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $week = [];
        foreach ($labels as $i => $label) {
            $minutes = (int) ($perDay[$start->copy()->addDays($i)->toDateString()] ?? 0);
            $week[$label] = min(100, (int) round($minutes / self::DAILY_GOAL_MINUTES * 100));
        }

        $todayMinutes = (int) ($perDay[now()->toDateString()] ?? 0);

        return [
            'week'         => $week,
            'today'        => now()->dayOfWeekIso - 1,
            'todayMinutes' => $todayMinutes,
            'goalPercent'  => min(100, (int) round($todayMinutes / self::DAILY_GOAL_MINUTES * 100)),
        ];
    }


    private function path(User $user): array
    {
        $ul = UserLanguage::with(['current_stage', 'language'])
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->first();

        $current = $ul?->current_stage;

        if (! $ul || ! $current) {
            return [
                'hasLanguage'  => false,
                'languageName' => null,
                'stages'        => [],
                'currentStage'  => null,
                'stageLabel'    => 'Belum memilih bahasa',
                'stageProgress' => 0,
                'stageStep'     => 0,
                'stageTotal'    => 0,
            ];
        }

        $all = Stage::where('language_id', $ul->language_id)->where('level', $current->level)->orderBy('order')->get();

        $stages = $all->map(fn ($s) => [
            $s->name,
            $s->description,
            $s->order < $current->order ? 'done' : ($s->order === $current->order ? 'active' : 'locked'),
        ])->all();

        $total = max($all->count(), 1);

        return [
            'hasLanguage'   => true,
            'languageName'  => $ul->language?->name,
            'stages'        => $stages,
            'currentStage'  => $current,
            'stageLabel'    => ucfirst($current->level) . ' · Stage ' . $current->order,
            'stageStep'     => $current->order,
            'stageTotal'    => $total,
            'stageProgress' => (int) round($current->order / $total * 100),
        ];
    }
}
