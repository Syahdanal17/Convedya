<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        //
    }

    public function generate(Request $request)
    {
        $request->validate([
            "stage_id" => ["required", "integer", "exists:stages,id"],
        ]);

        $stage = Stage::with("language")->findOrFail($request->stage_id);

        $prompt = "You are Convedya's lesson generator.
Your job is to create one personalized conversational language-learning lesson.

TEACHING CONTEXT
Target language: {$stage->language->name}
Learner level: {$stage->level}
Stage: {$stage->name}

Stage description:
{$stage->description}

LESSON REQUIREMENTS
- Create a lesson appropriate for the learner's level.
- Keep the lesson focused on the current stage.
- The lesson must be useful for conversational speaking practice.
- Create between 3 and 6 conversation goals.
- Goals should progress naturally from easier to slightly more challenging.
- Goals should be practical and directly related to the stage.
- Each goal should be independently understandable.
- Do not introduce topics that do not belong to the current stage.
- Keep the lesson suitable for a voice conversation.
- Do not include grammar lectures.
- Do not include XP, achievements, completion states, or application state.
- Descriptions should provide enough context for the conversation teacher to understand what the learner should practice.

OUTPUT FORMAT
Return only valid JSON.
Do not use Markdown.
Do not wrap the JSON in code fences.
Do not include any explanation before or after the JSON.

Use exactly this structure:

{
    \"title\": \"string\",
    \"summary\": \"string\",
    \"objective\": \"string\",
    \"steps\": [
        {
            \"goal\": \"string\"
            \"description\": \"string\"
        }
    ]
}

For each step:
- goal should be a short, clear description of the skill being practiced.
- description should explain what the learner should practice for that goal.
- Keep goals concise and suitable for displaying in a lesson progress interface.
- Keep descriptions practical and specific.

The title, summary, objective, and goals should be concise and clear.";

        $response = Http::withToken(config("services.zytro.api_key"))
            ->timeout(60)
            ->post(
                config("services.zytro.url") . "/api/v1/chat/completions",
                [
                    "stream" => false,
                    "model" => config("services.zytro.model"),
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => $prompt,
                        ],
                    ],
                ]
            );

        if (!$response->successful()) {
            return response()->json([
                "message" => "AI provider request failed.",
            ], 502);
        }

        $content = trim($response->json("choices.0.message.content", ""));

        try {
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $start = strpos($content, "{");
            $end = strrpos($content, "}");

            if ($start === false || $end === false || $end <= $start) {
                return response()->json([
                    "message" => "AI provider returned invalid JSON.",
                ], 502);
            }

            $json = substr($content, $start, $end - $start + 1);

            try {
                $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                return response()->json([
                    "message" => "AI provider returned invalid JSON.",
                ], 502);
            }
        }

        $validator = Validator::make($data, [
            "title" => ["required", "string", "max:255"],
            "summary" => ["required", "string", "max:2000"],
            "objective" => ["required", "string", "max:2000"],
            "steps" => ["required", "array"],
            "steps.*" => ["required", "array:goal,description"],
            "steps.*.goal" => ["required", "string", "max:255"],
            "steps.*.description" => ["required", "string", "max:1000"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "AI provider returned an invalid lesson structure.",
                "errors" => $validator->errors(),
            ], 502);
        }

        if (count($data["steps"]) < 3 || count($data["steps"]) > 6) {
            return response()->json([
                "message" => "AI provider returned an invalid number of lesson steps.",
            ], 502);
        }

        $lesson = DB::transaction(function () use ($request, $stage, $data) {
            $lesson = Lesson::create([
                "stage_id" => $stage->id,
                "user_id" => $request->user()->id,
                "title" => $data["title"],
                "summary" => $data["summary"],
                "objective" => $data["objective"],
                "status" => "active",
            ]);

            foreach ($data["steps"] as $index => $step) {
                $lesson->lesson_steps()->create([
                    "step_number" => $index + 1,
                    "goal" => $step["goal"],
                    "description" => $step["description"],
                    "required" => true,
                    "status" => $index === 0 ? "active" : "pending",
                ]);
            }

            return $lesson->load("lesson_steps");
        });

        return response()->json([
            "lesson" => $lesson,
        ], 201);
    }
}
