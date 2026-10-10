<?php

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageSelectionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('leaderboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('learn')->group(function () {

    Route::get('/', [LearnController::class, 'pick'])
        ->name('learn.greetings');

    Route::redirect('/greetings', '/learn');

    Route::get('/conversation/{lesson}', [LearnController::class, 'conversation'])
        ->name('learn.conversation');

    Route::get('/{language}', [LearnController::class, 'greetings'])
        ->name('learn.greetings.show');
});

Route::post('/language', [LanguageSelectionController::class, 'store'])
    ->middleware(['auth'])
    ->name('language.store');

require __DIR__.'/auth.php';
