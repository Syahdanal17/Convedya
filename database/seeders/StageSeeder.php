<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::all();

        $stages = [
            // Beginner
            [
                "name" => "Salaman",
                "description" => "Pelajari cara menyapa orang dan memberikan tanggapan yang tepat dalam situasi umum.",
                "level" => "Beginner",
                "order" => 1,
            ],
            [
                "name" => "Perkenalan diri",
                "description" => "Pelajari cara memperkenalkan diri dan membagikan informasi tentang diri sendiri.",
                "level" => "Beginner",
                "order" => 2,
            ],
            [
                "name" => "Kehidupan Sehari-hari",
                "description" => "Pelajari cara untuk membicarakan rutinitas, aktivitas, dan situasi sehari-hari.",
                "level" => "Beginner",
                "order" => 3,
            ],
            [
                "name" => "Hobi-hobimu",
                "description" => "Pelajari cara mendeskripsikan hobi, minat, dan preferensi anda.",
                "level" => "Beginner",
                "order" => 4,
            ],
            [
                "name" => "Keluarga",
                "description" => "Pelajari cara membicarakan anggota keluarga dan hubunganmu.",
                "level" => "Beginner",
                "order" => 5,
            ],
            [
                "name" => "Berbelanja",
                "description" => "Pelajari ungkapan yang digunakan saat berbelanja dan bertanya tentang produk.",
                "level" => "Beginner",
                "order" => 6,
            ],

            // Intermediate
            [
                "name" => "Bepergian",
                "description" => "Pelajari cara bertanya arah, memesan tiket, dan berkomunikasi saat bepergian.",
                "level" => "Intermediate",
                "order" => 7,
            ],
            [
                "name" => "Di Restoran",
                "description" => "Pelajari cara memesan makanan, bertanya tentang menu, dan meminta tagihan.",
                "level" => "Intermediate",
                "order" => 8,
            ],
            [
                "name" => "Kesehatan",
                "description" => "Pelajari cara menjelaskan keluhan, berkonsultasi dengan dokter, dan membeli obat.",
                "level" => "Intermediate",
                "order" => 9,
            ],
            [
                "name" => "Pekerjaan dan Pendidikan",
                "description" => "Pelajari cara membicarakan pekerjaan, sekolah, dan rencana karier.",
                "level" => "Intermediate",
                "order" => 10,
            ],
            [
                "name" => "Rencana dan Pengalaman",
                "description" => "Pelajari cara menceritakan pengalaman masa lalu dan rencana di masa depan.",
                "level" => "Intermediate",
                "order" => 11,
            ],
            [
                "name" => "Menyampaikan Pendapat",
                "description" => "Pelajari cara menyatakan setuju, tidak setuju, dan memberikan alasan dengan sopan.",
                "level" => "Intermediate",
                "order" => 12,
            ],

            // Expert
            [
                "name" => "Diskusi dan Debat",
                "description" => "Pelajari cara menyusun argumen, menanggapi lawan bicara, dan berdiskusi secara mendalam.",
                "level" => "Expert",
                "order" => 13,
            ],
            [
                "name" => "Budaya dan Tradisi",
                "description" => "Pelajari cara membahas adat istiadat, tradisi, dan perbedaan budaya.",
                "level" => "Expert",
                "order" => 14,
            ],
            [
                "name" => "Berita dan Media",
                "description" => "Pelajari cara memahami dan membicarakan berita, opini, dan isu terkini.",
                "level" => "Expert",
                "order" => 15,
            ],
            [
                "name" => "Komunikasi Profesional",
                "description" => "Pelajari cara berkomunikasi dalam rapat, presentasi, dan surel resmi.",
                "level" => "Expert",
                "order" => 16,
            ],
            [
                "name" => "Idiom dan Ungkapan",
                "description" => "Pelajari idiom, peribahasa, dan ungkapan khas agar terdengar lebih alami.",
                "level" => "Expert",
                "order" => 17,
            ],
            [
                "name" => "Penulisan Formal",
                "description" => "Pelajari cara menulis esai, laporan, dan surat resmi dengan struktur yang tepat.",
                "level" => "Expert",
                "order" => 18,
            ],
        ];

        foreach ($languages as $language) {
            foreach ($stages as $stage) {
                $language->stages()->updateOrCreate(
                    ['name' => $stage['name']],
                    $stage
                );
            }
        }
    }
}
