<?php

namespace App\Http\Controllers;

use App\Models\UserAchievement;
use Illuminate\Http\Request;

class UserAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $unlocked = UserAchievement::with('achievement')->where('user_id', $user->id)->orderByDesc('Unlocked_at')->get()->map(function ($ua) {
            return [
                    'id'          => $ua->achievement->id,
                    'name'        => $ua->achievement->name,
                    'description' => $ua->achievement->description,
                    'icon'        => $ua->achievement->icon,
                    'unlocked_at' => $ua->unlocked_at,
                    ];
            });

        return response()->json($unlocked);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAchievement $userAchievement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAchievement $userAchievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAchievement $userAchievement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAchievement $userAchievement)
    {
        //
    }
}
