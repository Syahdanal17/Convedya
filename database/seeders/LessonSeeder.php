<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $stage = Stage::first();

        Lesson::create([
            "stage_id" => $stage->id,
            "user_id" => $user->id,
            "title" => "Greetings and Self Introduction",
            "summary" => "Practice greeting someone and introducing yourself.",
            "objective" => "Introduce yourself and say where you are from.",
            "status" => "active",
        ]);
    }
}
