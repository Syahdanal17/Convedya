# Convedya

<p align="center">
  <img src="https://placehold.co/180x180/6366f1/ffffff?text=Convedya" width="160" alt="Logo Convedya">
</p>

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
       v
  Pilih Bahasa
       |
       v
Learning Progression
       |
       v
 Generate Lesson
       |
       v
  AI Conversation
       |
       +----> Speech-to-Text
       |
       +----> AI Tutor
       |
       +----> Text-to-Speech
       |
       v
Lesson Evaluation
       |
       v
Update Progress & XP
       |
       v
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

## Arsitektur Sistem

Convedya menggunakan Laravel sebagai pusat pengelolaan aplikasi dan database. AI berperan sebagai layanan pendukung pembelajaran.

```text
             USER
              |
              v
        CONVEDYA WEB
              |
              v
           LARAVEL
              |
       +------+------+
       |      |      |
       v      v      v
      Auth  Learning API
                     |
                     v
                 AI SERVICE
                     |
              +------+------+
              |      |      |
              v      v      v
             STT    LLM    TTS
                     |
                     v
                 AI RESPONSE

         LARAVEL <--> DATABASE
```

Laravel bertanggung jawab atas autentikasi, otorisasi, progression, status lesson, XP, dan penyimpanan data. AI digunakan untuk membantu menghasilkan materi, menjalankan percakapan, memproses suara, serta memberikan umpan balik.

## Instalasi

### Persyaratan

Pastikan perangkat telah memiliki:

* PHP dengan versi yang sesuai dengan proyek.
* Composer.
* Node.js dan npm.
* Database yang didukung konfigurasi proyek.
* Git.

### 1. Clone Repository

```bash
git clone <URL_REPOSITORY>
cd Convedya
```

Ganti `<URL_REPOSITORY>` dengan URL repository Convedya.

### 2. Instal Dependensi

```bash
composer install
npm install
```

### 3. Siapkan Environment

```bash
cp .env.example .env
php artisan key:generate
```

Pada Windows Command Prompt, salin `.env.example` menjadi `.env` secara manual jika perintah `cp` tidak tersedia.

### 4. Konfigurasi Database

Sesuaikan konfigurasi database pada file `.env`.

Contoh untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=convedya
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database sudah dibuat sebelum menjalankan migration.

### 5. Jalankan Migration

```bash
php artisan migrate
```

Jika proyek menyediakan seeder untuk data awal:

```bash
php artisan db:seed
```

### 6. Jalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite pada terminal terpisah:

```bash
npm run dev
```

Buka alamat lokal yang ditampilkan oleh Laravel, biasanya:

http://127.0.0.1:8000

## Konfigurasi AI

Konfigurasi layanan AI dilakukan melalui environment variable sesuai implementasi provider yang digunakan.

Contoh:

```env
AI_API_KEY=
AI_BASE_URL=
AI_MODEL=
```

Nama variabel tersebut merupakan contoh dan perlu disesuaikan dengan kode aplikasi.

**Jangan mengunggah API key, token, password, atau file `.env` ke repository publik.**

## Riwayat Pengembangan dan Git

Selama tahap awal pengembangan, tim mengerjakan source code secara lokal. Namun, karena kelalaian dalam melakukan push secara berkala, perubahan yang telah dikerjakan belum seluruhnya tercatat pada riwayat commit di repository GitHub.

Akibatnya, Git history pada repository remote masih kosong atau belum merepresentasikan proses pengembangan yang sebenarnya. Kondisi ini tidak berarti bahwa pengembangan proyek baru dimulai pada saat repository dipublikasikan.

Sebagai tindak lanjut, tim akan menerapkan workflow Git yang lebih konsisten agar setiap perubahan dapat terdokumentasi dengan baik.

Contoh format commit yang digunakan:

```text
feat: add language selection
feat: implement lesson progression
feat: integrate AI conversation
fix: prevent duplicate lesson completion
docs: update project documentation
```

## Kontribusi

Convedya dikembangkan secara kolaboratif oleh tim.

Kontribusi dilakukan melalui tahapan berikut:

1. Sinkronkan branch dengan repository terbaru.
2. Buat branch untuk fitur atau perbaikan.
3. Implementasikan perubahan.
4. Lakukan pengujian.
5. Buat commit dengan pesan yang deskriptif.
6. Push perubahan ke repository remote.
7. Lakukan peninjauan melalui pull request apabila digunakan oleh tim.

Contoh:

```bash
git switch -c feature/nama-fitur
git add .
git commit -m "feat: add nama fitur"
git push -u origin feature/nama-fitur
```

## Keamanan

Untuk menjaga keamanan aplikasi:

* Simpan kredensial pada environment variable.
* Jangan commit file `.env`.
* Validasi input pada backend.
* Terapkan authorization untuk data setiap pengguna.
* Lindungi endpoint dan kredensial layanan AI.
* Pastikan `.gitignore` telah dikonfigurasi dengan benar.

## Tim Pengembang

Convedya dikembangkan oleh tim dengan pembagian tanggung jawab:

* **Frontend / UX** — Antarmuka dan pengalaman pengguna.
* **Backend / Database** — Logika aplikasi, autentikasi, database, dan progression.
* **AI / Integration** — Integrasi AI, pemrosesan suara, dan percakapan interaktif.

## Lisensi

Lisensi proyek akan ditentukan oleh tim pengembang. Informasi lisensi akan diperbarui setelah keputusan mengenai distribusi dan penggunaan source code ditetapkan.

---

<p align="center">
  <strong>Convedya — Learn Through Conversation.</strong>
</p>
