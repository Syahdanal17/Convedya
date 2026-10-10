<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        Language::create([
            "name" => "Inggris",
            "code" => "en",
            "icon" => "🇬🇧",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Jepang",
            "code" => "jp",
            "icon" => "🇯🇵",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Jerman",
            "code" => "de",
            "icon" => "🇩🇪",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Korea",
            "code" => "ko",
            "icon" => "🇰🇷",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Prancis",
            "code" => "fr",
            "icon" => "🇫🇷",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Spanyol",
            "code" => "es",
            "icon" => "🇪🇸",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Mandarin",
            "code" => "zh",
            "icon" => "🇨🇳",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Italia",
            "code" => "it",
            "icon" => "🇮🇹",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Portugis",
            "code" => "pt",
            "icon" => "🇵🇹",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Hindi",
            "code" => "hi",
            "icon" => "🇮🇳",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Arab",
            "code" => "ar",
            "icon" => "🇸🇦",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Rusia",
            "code" => "ru",
            "icon" => "🇷🇺",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Turki",
            "code" => "tr",
            "icon" => "🇹🇷",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Vietnam",
            "code" => "vi",
            "icon" => "🇻🇳",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Thailand",
            "code" => "th",
            "icon" => "🇹🇭",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Belanda",
            "code" => "nl",
            "icon" => "🇳🇱",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Polandia",
            "code" => "pl",
            "icon" => "🇵🇱",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Swedia",
            "code" => "sv",
            "icon" => "🇸🇪",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Norwegia",
            "code" => "no",
            "icon" => "🇳🇴",
            "is_active" => 1,
        ]);

        Language::create([
            "name" => "Yunani",
            "code" => "el",
            "icon" => "🇬🇷",
            "is_active" => 1,
        ]);
    }
}
