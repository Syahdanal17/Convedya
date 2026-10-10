@extends('layouts.learn')

@section('title', 'Convedya — Profil')

@push('styles')
@vite(['resources/css/dashboard.css', 'resources/css/profile.css'])
@endpush

@php
    // $user, $xp, $rank, $streak, $conversationCount, $stageLabel,
    // $activity, $activeDays, $badges, $earnedCount
    // all come from ProfileController@edit → ProfileStatsService.

    $stats = [
        ['Total XP',    number_format($xp),   'purple', 'M13 2L3 14h7l-1 8 11-14h-7l0-6z'],
        ['Peringkat',   '#' . $rank,          'gold',   'M8 21h8M12 17v4M7 4h10v5a5 5 0 01-10 0V4z'],
        ['Streak',      $streak . ' hari',    'orange', 'M12 3c1 4 5 5 5 10a5 5 0 01-10 0c0-2 1-3 2-4 0 2 1 3 2 3 0-3-1-6 1-9z'],
        ['Percakapan',  $conversationCount,   'blue',   'M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z'],
    ];

    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
@endphp

@section('content')
<div class="db-shell">

  <x-sidebar active="profile">
  </x-sidebar>

  <main class="db-main">

    <header class="db-top">
      <div>
        <p class="db-eyebrow">Akun kamu</p>
        <h1>Profil</h1>
      </div>
    </header>

    {{-- Hero profil --}}
    <section class="pf-hero">
      <div class="pf-cover">
        <img src="{{ asset('images/veya.png') }}" alt="" class="pf-cover-img">
      </div>
      <div class="pf-hero-body">
        <span class="pf-avatar-ring">
          <span class="pf-avatar">
            @if ($user->avatar)
              <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
            @else
              {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
          </span>
        </span>

        <div class="pf-id">
          <h2>{{ $user->name }}</h2>
          <p>{{ $user->email }}</p>
          <div class="pf-chips">
            <span class="pf-chip">{{ $stageLabel }}</span>
            @if ($streak > 0)
              <span class="pf-chip warm">{{ $streak }} hari beruntun</span>
            @endif
            @if ($user->created_at)
              <span class="pf-chip muted">Sejak {{ $user->created_at->translatedFormat('F Y') }}</span>
            @endif
          </div>
        </div>

        <div class="pf-hero-actions">
          <a href="{{ route('learn.greetings') }}" class="db-btn">Lanjutkan belajar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="{{ route('leaderboard') }}" class="pf-ghost">Lihat peringkat</a>
        </div>
      </div>
    </section>

    {{-- Statistik --}}
    <section class="db-stats pf-stats">
      @foreach ($stats as [$label, $value, $tone, $icon])
        <div class="db-card db-stat pf-stat {{ $tone }}">
          <span class="pf-stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg></span>
          <div><span>{{ $label }}</span><strong>{{ $value }}</strong></div>
        </div>
      @endforeach
    </section>

    <div class="pf-cols">

      <div class="pf-col">

        {{-- Aktivitas --}}
        <section class="db-card">
          <div class="pf-card-head">
            <h3>Aktivitas 4 minggu terakhir</h3>
            <small>{{ $activeDays }} hari aktif</small>
          </div>
          <div class="pf-heat-days" aria-hidden="true">
            @foreach ($days as $d) <span>{{ $d }}</span> @endforeach
          </div>
          <div class="pf-heat" role="img" aria-label="{{ $activeDays }} hari aktif dalam 28 hari terakhir">
            @foreach ($activity as $level)
              <i class="l{{ $level }}"></i>
            @endforeach
          </div>
          <div class="pf-legend"><small>Sedikit</small><i class="l0"></i><i class="l1"></i><i class="l2"></i><i class="l3"></i><small>Banyak</small></div>
        </section>

        {{-- Informasi profil --}}
        <section class="db-card">
          <h3>Informasi profil</h3>
          <form method="POST" action="{{ route('profile.update') }}" class="pf-form" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="pf-field">
              <span>Foto profil</span>
              <div class="pf-photo-row">
                <div class="pf-photo-preview" id="pfPhotoPreview">
                  @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="">
                  @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  @endif
                </div>
                <div class="pf-photo-ctrl">
                  <label class="pf-ghost pf-photo-btn" for="profile_picture">Pilih foto</label>
                  <input type="file" id="profile_picture" name="profile_picture"
                        accept="image/png,image/jpeg,image/webp" hidden>
                  <small>JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
                  @if ($user->profile_picture_url)
                    <label class="pf-check">
                      <input type="checkbox" name="remove_profile_picture" value="1"> Hapus foto saat ini
                    </label>
                  @endif
                </div>
              </div>
              @error('profile_picture') <em class="pf-err">{{ $message }}</em> @enderror
            </div>

            <label class="pf-field">
              <span>Nama</span>
              <div class="pf-input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 4a4 4 0 100 8 4 4 0 000-8zM4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
              </div>
              @error('name') <em class="pf-err">{{ $message }}</em> @enderror
            </label>

            <label class="pf-field">
              <span>Email</span>
              <div class="pf-input">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
              </div>
              @error('email') <em class="pf-err">{{ $message }}</em> @enderror
            </label>

            <div class="pf-actions">
              <button type="submit" class="db-btn">Simpan perubahan</button>
              @if (session('status') === 'profile-updated')
                <span class="pf-ok">Tersimpan.</span>
              @endif
            </div>
          </form>


        </section>

        {{-- Ubah password --}}
        <section class="db-card">
          <h3>Ubah password</h3>
          <form method="POST" action="{{ route('password.update') }}" class="pf-form">
            @csrf
            @method('put')

            @foreach ([
              ['current_password', 'Password saat ini', 'current-password'],
              ['password', 'Password baru', 'new-password'],
              ['password_confirmation', 'Ulangi password baru', 'new-password'],
            ] as [$field, $label, $auto])
              <label class="pf-field">
                <span>{{ $label }}</span>
                <div class="pf-input">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                  <input type="password" name="{{ $field }}" autocomplete="{{ $auto }}">
                </div>
                @error($field, 'updatePassword') <em class="pf-err">{{ $message }}</em> @enderror
              </label>
            @endforeach

            <div class="pf-actions">
              <button type="submit" class="db-btn">Perbarui password</button>
              @if (session('status') === 'password-updated')
                <span class="pf-ok">Password diperbarui.</span>
              @endif
            </div>
          </form>
        </section>
      </div>

      <div class="pf-col">

        {{-- Lencana --}}
        <section class="db-card">
          <div class="pf-card-head">
            <h3>Lencana</h3>
            <small>{{ $earnedCount }} dari {{ count($badges) }}</small>
          </div>
          <ul class="pf-badges">
            @foreach ($badges as [$title, $desc, $earned, $icon])
              <li class="pf-badge {{ $earned ? 'earned' : '' }}">
                <span class="pf-badge-ic">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $earned ? $icon : 'M5 11h14v10H5zM8 11V8a4 4 0 018 0v3' }}"/></svg>
                </span>
                <strong>{{ $title }}</strong>
                <small>{{ $desc }}</small>
              </li>
            @endforeach
          </ul>
        </section>

        {{-- Hapus akun --}}
        <section class="db-card pf-danger">
          <h3>Hapus akun</h3>
          <p class="pf-note">Akun dan semua progres belajarmu akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>

          <details @if ($errors->userDeletion->isNotEmpty()) open @endif>
            <summary class="pf-del-btn">Hapus akun saya</summary>
            <form method="POST" action="{{ route('profile.destroy') }}" class="pf-form">
              @csrf
              @method('delete')

              <label class="pf-field">
                <span>Masukkan password untuk konfirmasi</span>
                <div class="pf-input">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                  <input type="password" name="password" autocomplete="current-password">
                </div>
                @error('password', 'userDeletion') <em class="pf-err">{{ $message }}</em> @enderror
              </label>

              <button type="submit" class="pf-del-btn solid">Ya, hapus akun</button>
            </form>
          </details>
        </section>
      </div>

    </div>
  </main>
</div>

<script>
  document.getElementById('profile_picture')?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const box = document.getElementById('pfPhotoPreview');
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    box.replaceChildren(img);
  });
</script>
@endsection
