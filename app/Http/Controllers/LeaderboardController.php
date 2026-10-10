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
            ->select('users.id', 'users.name', 'users.profile_picture_url')
            ->withSum('user_progresses as total_xp', 'xp')
            ->orderByRaw("$xpSub desc")
            ->orderBy('users.id')
            ->limit(50)
            ->get();


        $rank = 0;
        $prevXp = null;
        $players = $users->values()->map(function ($u, $i) use ($authUser) {
            return [
                'id'   => $u->id,
                'name' => $u->name,
                'xp'   => (int) $u->total_xp,
                'rank' => $i + 1,
                'me'   => $u->id === $authUser->id,
                'avatar' => $u->avatar,
            ];
        });

        $myXp = (int) UserProgress::where('user_id', $authUser->id)->sum('xp');

        $me = [
            'name' => $authUser->name,
            'xp'   => $myXp,
            'rank' => User::query()
                ->whereRaw("$xpSub > ?", [$myXp])
                ->orWhere(function ($q) use ($xpSub, $myXp, $authUser) {
                    $q->whereRaw("$xpSub = ?", [$myXp])
                    ->where('users.id', '<', $authUser->id);
                })
                ->count() + 1,
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
