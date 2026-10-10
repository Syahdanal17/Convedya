
@props(['active' => null])

@php

    $items = [
        'home'        => ['Home',        route('dashboard'),       'M3 11l9-8 9 8M5 10v10h14V10'],
        'learn'       => ['Learn',       route('learn.greetings'), 'M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z'],
        'leaderboard' => ['Leaderboard', route('leaderboard'),     'M8 21h8M12 17v4M7 4h10v5a5 5 0 01-10 0V4z'],
        'profile'     => ['Profile',     route('profile.edit'),    'M12 4a4 4 0 100 8 4 4 0 000-8zM4 21c0-4 4-6 8-6s8 2 8 6'],
    ];
@endphp

@once
    @push('styles')
        @vite(['resources/css/sidebar.css'])
    @endpush
@endonce

<aside class="app-sidebar" aria-label="Menu utama">
  <a href="{{ route('dashboard') }}" class="app-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

  <nav class="app-nav">
    @foreach ($items as $key => [$label, $url, $icon])
      <a href="{{ $url }}"
         class="app-nav-item {{ $active === $key ? 'active' : '' }}"
         title="{{ $label }}"
         @if ($active === $key) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icon }}"/></svg>
        <span class="label">{{ $label }}</span>
      </a>
    @endforeach
  </nav>

  <div class="app-sidebar-foot">
    @if (trim((string) $slot) !== '')
      <div class="app-sidebar-extra">{{ $slot }}</div>
    @endif

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="app-nav-item" title="Keluar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        <span class="label">Keluar</span>
      </button>
    </form>
  </div>
</aside>
