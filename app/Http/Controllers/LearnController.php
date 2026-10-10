<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Lesson;
use App\Models\Stage;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LearnController extends Controller
{
    /**
     * GET /learn/greetings
     * Flag picker only. Clicking a flag links to /learn/greetings/{slug}.
     */
    public function pick(Request $request)
    {
        $languages = Language::where('is_active', 1)
            ->orderBy('name')
            ->get()
            ->each(fn ($l) => $l->slug = Str::slug($l->name));

        // The most recently touched user_languages row is the active language.
        $currentId = $request->user()->user_languages()
            ->orderByDesc('updated_at')
            ->value('language_id');

        return view('learn.pick-language', compact('languages', 'currentId'));
    }

    /**
     * GET /learn/greetings/{language}
     * Selecting a language here also switches the active language in user_languages.
     */
    public function greetings(Request $request, string $language)
    {
        $user = $request->user();

        $language = $this->findActiveLanguageBySlug($language);

        $firstStage = Stage::where('language_id', $language->id)
            ->orderBy('order')
            ->orderBy('id')
            ->first();

        abort_if(! $firstStage, 404, 'Bahasa ini belum memiliki stage.');

        $userLanguage = DB::transaction(function () use ($user, $language, $firstStage) {
            // A user only keeps one language: erase every other row.
            UserLanguage::where('user_id', $user->id)
                ->where('language_id', '!=', $language->id)
                ->delete();

            // First time: create the row at stage 1. Same language again: keep stage and progress.
            $ul = UserLanguage::firstOrCreate(
                ['user_id' => $user->id, 'language_id' => $language->id],
                ['current_stage_id' => $firstStage->id, 'progress' => 0]
            );

            $ul->touch();

            return $ul;
        });

        $current = $userLanguage->current_stage()->first();

        $conversations = [];
        $currentLesson = null;

        if ($current) {
            $currentLesson = $user->lessons()
                ->where('stage_id', $current->id)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            $conversations = Stage::where('language_id', $language->id)
                ->where('level', $current->level)
                ->orderBy('order')
                ->get()
                ->map(fn ($s) => [
                    'title' => $s->name,
                    'desc' => $s->description,
                    'status' => $s->order < $current->order
                        ? 'done'
                        : ($s->order === $current->order ? 'current' : 'locked'),
                    'score' => null,
                    'xp' => null,
                ])
                ->all();
        }

        $progress = $user->user_progresses()->where('language_id', $language->id);

        $xpEarned = (int) (clone $progress)->sum('xp');

        $row = (clone $progress)->first();
        $streak = 0;

        if ($row && $row->last_activity_date) {
            $last = \Illuminate\Support\Carbon::parse($row->last_activity_date);
            // Still alive if the learner practiced today or yesterday.
            if ($last->isToday() || $last->isYesterday()) {
                $streak = (int) $row->streak;
            }
        }

        return view('learn.greetings', compact(
            'conversations',
            'streak',
            'xpEarned',
            'currentLesson',
            'current',
            'language'
        ));
    }

    public function conversation(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->user_id === $request->user()->id, 403);

        $lesson->load([
            'stage.language',
            'lesson_steps',
        ]);

        if ($lesson->status === 'completed') {
            return redirect()
                ->route('learn.greetings.show', Str::slug($lesson->stage->language->name))
                ->with('message', 'Lesson ini sudah selesai.');
        }

        return view('learn.conversation', compact('lesson'));
    }

    /**
     * Resolve "english" / "japanese" to an active Language, or 404.
     */
    private function findActiveLanguageBySlug(string $slug): Language
    {
        $language = Language::where('is_active', 1)
            ->get()
            ->first(fn ($l) => Str::slug($l->name) === Str::slug($slug));

        abort_if(! $language, 404);

        return $language;
    }
}
