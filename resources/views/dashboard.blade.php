@extends('layouts.learn')

@section('title', 'Convedya — Dashboard')

@push('styles')
@vite(['resources/css/dashboard.css'])
@endpush

@php
    $stats = [
        ['Streak',        $streak . ' hari'],
        ['Total XP',      number_format($xp)],
        ['Percakapan',    $conversationCount],
        ['Waktu latihan', $practiceTime],
    ];
@endphp

@section('content')
<div class="db-shell">

<x-sidebar active="home">
</x-sidebar>

  <main class="db-main">

    <header class="db-top">
      <div>
        <p class="db-eyebrow">Selamat datang kembali</p>
        <h1>Halo, {{ $user->name }}</h1>
      </div>
      <a href="{{ route('profile.edit') }}" class="db-avatar" aria-label="Profil">{{ strtoupper(substr($user->name, 0, 1)) }}</a>
    </header>

    {{-- Lanjutkan belajar --}}
    <section class="db-hero">
      <div class="db-hero-text">
        <span class="db-chip">{{ $stageLabel }}</span>
        <h2>{{ $currentStage?->name ?? 'Pilih bahasa dulu' }}</h2>
        <p>{{ $currentStage?->description ?? 'Pilih bahasa untuk mulai belajar.' }}</p>

        <div class="db-hero-progress">
          <div class="db-bar"><span style="width:{{ $stageProgress }}%"></span></div>
          <small>Tahap {{ $stageStep }} dari {{ $stageTotal }}</small>
        </div>

        @if ($hasLanguage)
          <a href="{{ route('learn.greetings') }}" class="db-btn">Lanjutkan belajar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        @else
          <a href="{{ route('learn.greetings') }}" class="db-btn">pilih bahasa dulu
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        @endif
      </div>
      <img src="{{ asset('images/veya.png') }}" alt="Veya" class="db-hero-img">
    </section>

    {{-- Statistik --}}
    <section class="db-stats">
      @foreach ($stats as [$label, $value])
        <div class="db-card db-stat">
          <span>{{ $label }}</span>
          <strong>{{ $value }}</strong>
        </div>
      @endforeach
    </section>

    <div class="db-cols">

      {{-- Jalur belajar --}}
      <section class="db-card">
        <h3>Jalur belajar</h3>
        <ul class="db-path">
          @foreach ($stages as $i => [$title, $desc, $status])
            <li class="db-stage {{ $status }}">
              <span class="db-stage-no">
                @if ($status === 'locked')
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                @else
                  {{ $i + 1 }}
                @endif
              </span>
              <div>
                <strong>{{ $title }}</strong>
                <small>{{ $desc }}</small>
              </div>
              @if ($status === 'active')
                <a href="{{ route('learn.greetings') }}" class="db-stage-go">Mulai</a>
              @endif
            </li>
          @endforeach
        </ul>
      </section>

      <div class="db-side-col">

        {{-- Target harian --}}
        <section class="db-card db-goal">
          <h3>Target harian</h3>
          <div class="db-ring" style="--p: {{ $goalPercent }}">
            <div><strong>6</strong><small>dari 10 menit</small></div>
          </div>
        </section>

        {{-- Aktivitas mingguan --}}
        <section class="db-card">
          <h3>Aktivitas minggu ini</h3>
          <div class="db-week">
            @foreach ($week as $day => $height)
              <div class="db-day {{ $loop->index === $today ? 'today' : '' }}">
                <span class="db-col"><i style="height: {{ max($height, 4) }}%"></i></span>
                <small>{{ $day }}</small>
              </div>
            @endforeach
          </div>
        </section>

      </div>
    </div>


    @unless ($hasLanguage)
    <dialog id="langPicker" class="db-modal">
      <form method="POST" action="{{ route('language.store') }}">
        @csrf
        <h3>Pilih bahasa yang mau kamu pelajari</h3>
        <ul class="db-lang-list">
          @foreach ($languages as $lang)
            <li>
              <button type="submit" name="language_id" value="{{ $lang->id }}">
                <strong>{{ $lang->name }}</strong>
                <small>{{ strtoupper($lang->code) }}</small>
              </button>
            </li>
          @endforeach
        </ul>
        <button type="button" class="db-modal-close" onclick="this.closest('dialog').close()">Batal</button>
      </form>
    </dialog>
    @endunless

  </main>
</div>
@endsection
