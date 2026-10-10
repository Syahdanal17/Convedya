<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();

        $xpSub = '(select coalesce(sum(xp), 0) from user_progress where user_progress.user_id = users.id)';

        $users = User::query()
            ->select('users.id', 'users.name')
            ->withSum('user_progresses as total_xp', 'xp')
            ->orderByRaw("$xpSub desc")
            ->orderBy('users.id')
            ->limit(50)
            ->get();


        $rank = 0;
        $prevXp = null;
        $players = $users->values()->map(function ($u, $i) use (&$rank, &$prevXp, $authUser) {
            $xp = (int) $u->total_xp;

            if ($xp !== $prevXp) {
                $rank = $i + 1;
                $prevXp = $xp;
            }

            return [
                'id'   => $u->id,
                'name' => $u->name,
                'xp'   => $xp,
                'rank' => $rank,
                'me'   => $u->id === $authUser->id,
            ];
        });

        $myXp = (int) UserProgress::where('user_id', $authUser->id)->sum('xp');

        $me = [
            'name' => $authUser->name,
            'xp'   => $myXp,
            'rank' => User::query()->whereRaw("$xpSub > ?", [$myXp])->count() + 1,
        ];

        $aboveUser = User::query()
            ->select('users.name')
            ->selectRaw("$xpSub as xp")
            ->whereRaw("$xpSub > ?", [$myXp])
            ->orderByRaw("$xpSub asc")
            ->first();

        $above = $aboveUser
            ? ['name' => $aboveUser->name, 'xp' => (int) $aboveUser->xp]
            : null;

        return view('leaderboard', compact('players', 'me', 'above'));
    }
}
