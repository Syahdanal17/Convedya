<?php

namespace App\Http\Controllers;

use App\Models\UserLanguage;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProgressController extends Controller
{
    /**
     * Progres user di semua bahasa yang sedang dipelajari.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $userLanguages = UserLanguage::with(['language', 'currentStage'])
            ->where('user_id', $user->id)
            ->get();

        $progressByLanguage = UserProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('language_id');

        $result = $userLanguages->map(function ($ul) use ($progressByLanguage) {
            $progress = $progressByLanguage->get($ul->language_id);

            return $this->payload($ul, $progress);
        });

        return response()->json($result);
    }

    /**
     * Progres user di satu bahasa (dipakai polling progres live).
     */
    public function show(Request $request, string $language)
    {
        $userLanguage = $this->findUserLanguage($request, $language);

        if (! $userLanguage) {
            return response()->json(['message' => 'User has not started this language'], 404);
        }

        $progress = UserProgress::where('user_id', $request->user()->id)
            ->where('language_id', $userLanguage->language_id)
            ->first();

        return response()
            ->json($this->payload($userLanguage, $progress))
            ->header('Cache-Control', 'no-store');
    }

    /**
     * Catat satu lesson selesai: tambah XP dan jumlah lesson selesai.
     * Panggil via POST, misalnya saat tombol End / lesson selesai.
     *
     * Body (opsional): xp (integer, default 10)
     */
    public function complete(Request $request, string $language)
    {
        $data = $request->validate([
            'xp' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);

        $userLanguage = $this->findUserLanguage($request, $language);

        if (! $userLanguage) {
            return response()->json(['message' => 'User has not started this language'], 404);
        }

        $xp = $data['xp'] ?? 10;

        $progress = DB::transaction(function () use ($request, $userLanguage, $xp) {
            $progress = UserProgress::where('user_id', $request->user()->id)
                ->where('language_id', $userLanguage->language_id)
                ->lockForUpdate()
                ->first();

            if (! $progress) {
                $progress = new UserProgress();
                $progress->user_id = $request->user()->id;
                $progress->language_id = $userLanguage->language_id;
                $progress->xp = 0;
                $progress->completed_lessons = 0;
                $progress->streak = 0;
            }

            $progress->xp += $xp;
            $progress->completed_lessons += 1;
            $progress->save();

            return $progress;
        });

        return response()
            ->json($this->payload($userLanguage, $progress))
            ->header('Cache-Control', 'no-store');
    }

    /**
     * Leaderboard total XP semua bahasa.
     */
    public function leaderboard(Request $request)
    {
        $limit = max(1, min(100, (int) $request->query('limit', 20)));

        $results = DB::table('user_progress')
            ->join('users', 'users.id', '=', 'user_progress.user_id')
            ->select('users.id as user_id', 'users.name')
            ->selectRaw('SUM(user_progress.xp) as total_xp')
            ->selectRaw('SUM(user_progress.completed_lessons) as total_completed_lessons')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_xp')
            ->take($limit)
            ->get();

        $leaderboard = $results->values()->map(fn ($row, $index) => [
            'rank'              => $index + 1,
            'name'              => $row->name,
            'xp'                => (int) $row->total_xp,
            'completed_lessons' => (int) $row->total_completed_lessons,
        ]);

        return response()->json($leaderboard);
    }

    /* ---------- helper ---------- */

    private function findUserLanguage(Request $request, string $code): ?UserLanguage
    {
        return UserLanguage::with(['language', 'currentStage'])
            ->whereHas('language', fn ($q) => $q->where('code', $code))
            ->where('user_id', $request->user()->id)
            ->first();
    }

    private function payload(UserLanguage $ul, ?UserProgress $progress): array
    {
        return [
            'language'          => $ul->language->name ?? null,
            'language_code'     => $ul->language->code ?? null,
            'current_stage'     => $ul->currentStage->name ?? null,
            'xp'                => (int) ($progress->xp ?? 0),
            'completed_lessons' => (int) ($progress->completed_lessons ?? 0),
            'streak'            => (int) ($progress->streak ?? 0),
        ];
    }

    /* Method resource yang tidak dipakai sudah dihapus (create/store/edit/update/destroy). */
}
