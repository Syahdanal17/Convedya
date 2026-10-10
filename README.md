**DISCLAIMER UNTUK PARA JURI**

Pengembangan proyek Convedya dilakukan melalui lingkungan server lokal milik salah satu anggota tim kami, dengan memanfaatkan koneksi ZeroTier dan ekstensi Visual Studio Code Live Share. Dengan metode ini, seluruh anggota tim dapat bekerja pada lingkungan pengembangan yang sama, sehingga perubahan kode dapat dilihat secara langsung oleh anggota lainnya tanpa harus melakukan Git pull dan push untuk setiap perubahan atau menyiapkan lingkungan pengembangan secara terpisah di masing-masing perangkat.

Kami memilih metode ini untuk memaksimalkan efisiensi waktu, mempermudah kolaborasi, dan mempercepat proses diskusi serta brainstorming selama pengerjaan proyek. Kami juga menyadari bahwa metode ini dapat memengaruhi bagaimana riwayat Git mencerminkan proses pengembangan dan kontribusi masing-masing anggota tim.

Oleh karena itu, kami ingin menjelaskan hal ini terlebih dahulu agar tidak terjadi kesalahpahaman saat proses penilaian, khususnya ketika juri meninjau riwayat repositori Git kami. Kami tetap menghargai proses penilaian yang berlaku dan berharap penjelasan ini dapat menjadi konteks tambahan bagi para juri dalam memahami alur kerja tim kami.

Terima kasih atas waktu dan perhatian para juri.

<h1 align="center">Convedya</h1>
<p align="center">
  <strong>Belajar Bahasa Melalui Percakapan Interaktif dengan AI</strong>
</p>

<p align="center">
  Platform pembelajaran bahasa berbasis web yang menggabungkan percakapan interaktif, kecerdasan buatan, dan progression pembelajaran yang terstruktur.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-FF2D20?logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Status-In%20Development-orange" alt="Status Pengembangan">
  <img src="https://img.shields.io/badge/Language-Indonesia-red" alt="Bahasa Indonesia">
</p>

---

## Tentang Convedya

**Convedya** merupakan gabungan dari kata *Conversation* dan *Vidya*, yang merepresentasikan pembelajaran melalui percakapan.

Convedya dirancang sebagai platform pembelajaran bahasa yang membantu pengguna berlatih menggunakan bahasa target melalui percakapan dengan AI Tutor. Pengguna dapat mengikuti tahapan pembelajaran, mencapai tujuan pada setiap lesson, dan memantau perkembangan kemampuan mereka.

Tidak hanya berfungsi sebagai chatbot, Convedya menggabungkan AI dengan sistem pembelajaran terstruktur yang dikelola oleh backend aplikasi.

## Fitur Utama

* **Pembelajaran Multibahasa** — Memilih bahasa yang ingin dipelajari.
* **Generic Learning Progression** — Mengikuti tahapan pembelajaran berdasarkan tingkat kemampuan.
* **AI-Generated Lessons** — Menghasilkan materi pembelajaran secara dinamis.
* **AI Conversation** — Berlatih percakapan dengan AI Tutor.
* **Speech-to-Text (STT)** — Mengubah ucapan pengguna menjadi teks.
* **Text-to-Speech (TTS)** — Mengubah respons AI menjadi suara.
* **Grammar Feedback** — Mendapatkan umpan balik dan saran perbaikan tata bahasa.
* **Learning Progress** — Melacak penyelesaian lesson dan perkembangan pengguna.
* **Gamifikasi** — Mendukung XP, achievement, dan leaderboard.

*Catatan: daftar tersebut mencakup fitur yang direncanakan dalam ruang lingkup proyek. Implementasi setiap fitur mengikuti status pengembangan terbaru.*

## Alur Pembelajaran

```text
Register / Login
    |
Pilih Bahasa
    |
Learning Progression
    |
Generate Lesson
    |
AI Conversation
    |
Speech-to-Text
    |
AI Tutor
    |
Text-to-Speech
    |
Lesson Evaluation
    |
Update Progress & XP
    |
  Next Lesson
```

## Teknologi yang Digunakan

| Teknologi                                                 | Kegunaan                       |
| --------------------------------------------------------- | ------------------------------ |
| [Laravel](https://laravel.com)                            | Framework backend utama        |
| [PHP](https://www.php.net)                                | Bahasa pemrograman backend     |
| [Laravel Breeze](https://laravel.com/docs/starter-kits)   | Autentikasi pengguna           |
| [Blade](https://laravel.com/docs/blade)                   | Template antarmuka             |
| [Alpine.js](https://alpinejs.dev)                         | Interaksi antarmuka            |
| [Tailwind CSS](https://tailwindcss.com)                   | Styling antarmuka              |
| [Vite](https://vite.dev)                                  | Pengelolaan aset frontend      |
| MySQL / SQLite                                            | Penyimpanan data               |
| AI Services                                               | Generasi materi dan percakapan |
| STT / TTS                                                 | Pemrosesan suara               |
| [Git](https://git-scm.com) & [GitHub](https://github.com) | Version control dan kolaborasi |

Teknologi yang digunakan dapat disesuaikan dengan implementasi dan konfigurasi aktual proyek.

## Tim Pengembang

Convedya dikembangkan oleh tim dengan pembagian tanggung jawab:

* **Frontend / UX** — Antarmuka dan pengalaman pengguna.
* **Backend / Database** — Logika aplikasi, autentikasi, database, dan progression.
* **AI / Integration** — Integrasi AI, pemrosesan suara, dan percakapan interaktif.

---

<p align="center">
  <strong>Convedya — Learn Through Conversation.</strong>
</p>
