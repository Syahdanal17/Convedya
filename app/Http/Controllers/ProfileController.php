<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\UserProgress;
use App\Services\ProfileStatsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, ProfileStatsService $stats): View
    {
        $user = $request->user();

        return view('profile.edit', array_merge(
            ['user' => $user],
            $stats->for($user)
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->validate([
            'profile_picture'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_profile_picture' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_picture')) {
            $this->deletePicture($user);
            $user->profile_picture_url = $request->file('profile_picture')->store('avatars', 'public');
        } elseif ($request->boolean('remove_profile_picture')) {
            $this->deletePicture($user);
            $user->profile_picture_url = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $this->deletePicture($user);
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /** Delete the stored file, but never touch external (http) URLs. */
    private function deletePicture($user): void
    {
        $path = $user->profile_picture_url;

        if ($path && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }

    public function stats(Request $request)
    {
        $user =$request->user();

        $totals = UserProgress::where('user_id', $user->id)->selectRaw('SUM(xp) as total_xp')->selectRaw('SUM(completed_lessons) as total_lessons_completed')->selectRaw('MAX(streak) as current_best_streak')->first();

        $languagesLearning = UserProgress::where('user_id', $user->id)->count();

        return response()->json([
            'total_xp'                 => (int) ($totals->total_xp ?? 0),
            'total_lessons_completed'  => (int) ($totals->total_lessons_completed ?? 0),
            'current_best_streak'      => (int) ($totals->current_best_streak ?? 0),
            'languages_learning'       => $languagesLearning,
        ]);
    }
}
