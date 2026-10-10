@extends('layouts.learn')

@section('title', 'Convedya — Leaderboard')

@push('styles')
@vite(['resources/css/dashboard.css', 'resources/css/leaderboard.css'])
@endpush

@php
    $user = Auth::user();

    $maxXp = max($players->max('xp'), 1);
    $podium = $players->take(3);
    $rest = $players->slice(3);
@endphp

@section('content')
<div class="db-shell">

  <x-sidebar active="leaderboard">
  </x-sidebar>

  <main class="db-main">

    <header class="db-top">
      <div>
        <p class="db-eyebrow">Peringkat berdasarkan XP</p>
        <h1>Leaderboard</h1>
      </div>
      30  <a href="{{ route('profile.edit') }}" class="db-avatar" aria-label="Profil">
      31    @if ($user->avatar)
      32      <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
      33    @else
      34      {{ strtoupper(substr($user->name, 0, 1)) }}
      35    @endif
      36  </a>
    </header>

    {{-- Peringkat kamu --}}
    @if ($me)
      <section class="lb-me">
        <div class="lb-me-rank"><small>Peringkat kamu</small><strong>#{{ $me['rank'] }}</strong></div>
        <div class="lb-me-xp"><small>Total XP</small><strong>{{ number_format($me['xp'], 0, ',', '.') }}</strong></div>
        <p class="lb-me-hint">
          @if ($above)
            {{ number_format($above['xp'] - $me['xp'] + 1, 0, ',', '.') }} XP lagi untuk melewati {{ $above['name'] }}.
          @else
            Kamu ada di puncak. Pertahankan!
          @endif
        </p>
        <a href="{{ route('learn.greetings') }}" class="db-btn">Kumpulkan XP
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </section>
    @endif

    {{-- Podium: tampil berurutan 2 - 1 - 3 --}}
    <section class="lb-podium" aria-label="Tiga peringkat teratas">
      @foreach ([1, 0, 2] as $i)
        @php $p = $podium->get($i); @endphp
        @if ($p)
          <div class="lb-pod place-{{ $i + 1 }} {{ !empty($p['me']) ? 'me' : '' }}">
            @if ($i === 0)
              <svg class="lb-crown" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 18l-1-11 5 4 5-8 5 8 5-4-1 11H3z"/></svg>
            @endif
              <span class="lb-pod-avatar">
                @if (!empty($p['avatar']))
                  <img src="{{ $p['avatar'] }}" alt="{{ $p['name'] }}" loading="lazy">
                @else
                  {{ strtoupper(substr($p['name'], 0, 1)) }}
                @endif
                <em>{{ $p['rank'] }}</em>
              </span>
            <strong class="lb-pod-name">{{ $p['name'] }}{{ !empty($p['me']) ? ' (Kamu)' : '' }}</strong>
            <span class="lb-pod-xp">{{ number_format($p['xp'], 0, ',', '.') }} XP</span>
          </div>
        @endif
      @endforeach
    </section>

    {{-- Peringkat berikutnya --}}
    @if ($rest->isNotEmpty())
      <section class="db-card lb-list-card">
        <h3>Peringkat lainnya</h3>
        <ol class="lb-list">
          @foreach ($rest as $p)
            <li class="lb-row {{ !empty($p['me']) ? 'me' : '' }}">
              <span class="lb-rank">{{ $p['rank'] }}</span>
                <span class="lb-avatar">
                  @if (!empty($p['avatar']))
                    <img src="{{ $p['avatar'] }}" alt="" loading="lazy">
                  @else
                    {{ strtoupper(substr($p['name'], 0, 1)) }}
                  @endif
                </span>
              <span class="lb-name">{{ $p['name'] }}@if (!empty($p['me'])) <b class="lb-you">Kamu</b>@endif</span>
              <span class="lb-bar"><i style="width: {{ round($p['xp'] / $maxXp * 100) }}%"></i></span>
              <span class="lb-xp">{{ number_format($p['xp'], 0, ',', '.') }} <small>XP</small></span>
            </li>
          @endforeach
        </ol>
      </section>
    @endif

  </main>
</div>
@endsection
