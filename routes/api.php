<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserAchievementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TTSController;

Route::get('/languages', [LanguageController::class, "index"]);
Route::get('/languages/{language}/stages', [LanguageController::class, "stages"]);

Route::middleware(["web", "auth"])->group(function () {
    Route::post("/lessons/generate", [LessonController::class, "generate"]);

    Route::post('/ai/tts', [TTSController::class, 'speak']);
    Route::post('/ai/chat', [AIController::class, 'chat']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::post("/conversations/{conversation}/end", [ConversationController::class, "end"]);
    Route::get('/progress', [UserProgressController::class, 'index']);
    Route::get('/progress/{language}', [UserProgressController::class, 'show']);
    Route::get('/leaderboard', [UserProgressController::class, 'leaderboard']);
    Route::get('/me/stats', [ProfileController::class, 'stats']);
    Route::get('/me/achievements', [UserAchievementController::class, 'index']);
    Route::get('/achievements', [AchievementController::class, 'index']);
    Route::post("/conversations/{conversation}/abandon", [ConversationController::class, "abandon"]);
});
