<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Stage;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageSelectionController extends Controller
{
   public function store(Request $request)
    {
        $data = $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
        ]);

        $language = Language::where('id', $data['language_id'])
            ->where('is_active', 1)
            ->firstOrFail();

        $firstStage = Stage::where('language_id', $language->id)
            ->orderBy('order')
            ->orderBy('id')
            ->firstOrFail();

        $ul = UserLanguage::firstOrCreate(
            ['user_id' => $request->user()->id, 'language_id' => $language->id],
            ['current_stage_id' => $firstStage->id, 'progress' => 0]
        );

        // Existing row: mark it as the latest one
        $ul->touch();

        return redirect()->route('learn.greetings.show', Str::slug($language->name));
    }
}
