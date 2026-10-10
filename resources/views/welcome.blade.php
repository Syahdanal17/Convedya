@extends('layouts.learn')

@section('title', 'Convedya — Learn a language by speaking it')

@push('styles')
@vite(['resources/css/welcome.css'])
@endpush

@section('content')
<header class="wl-nav">
  <a href="{{ url('/') }}" class="wl-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

  <nav class="wl-links" aria-label="Menu utama">
    <a href="#features" class="wl-link wl-hide-sm">Features</a>
    <a href="#how" class="wl-link wl-hide-sm">How it works</a>

    @auth
      <a href="{{ route('dashboard') }}" class="wl-btn wl-btn-primary wl-btn-sm">Dashboard</a>
    @else
      @if (Route::has('login'))
        <a href="{{ route('login') }}" class="wl-link wl-strong">Log in</a>
      @endif
      @if (Route::has('register'))
        <a href="{{ route('register') }}" class="wl-btn wl-btn-primary wl-btn-sm">Get started</a>
      @endif
    @endauth
  </nav>
</header>

<main>
  {{-- ---------- Hero ---------- --}}
  <section class="wl-hero">
    <div class="wl-hero-text">
      <span class="wl-chip"><i></i>AI-powered language learning</span>
      <h1>Learn a language <span class="wl-grad">by speaking it.</span></h1>
      <p class="wl-lead">Convedya helps you practice real conversations with an AI language tutor — one conversation at a time.</p>

      <div class="wl-cta">
        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="wl-btn wl-btn-primary">Start learning</a>
        <a href="#how" class="wl-btn wl-btn-ghost">See how it works</a>
      </div>

      <ul class="wl-langs" aria-label="Bahasa yang tersedia">
        <li>English</li><li>Japanese</li><li>German</li><li class="more">+ More coming</li>
      </ul>
    </div>

    <div class="wl-visual" aria-hidden="true">
      <div class="wl-orb">
        <img src="{{ asset('images/veya.png') }}" alt="">
        <small>Your AI tutor</small>
        <strong>Ready to talk</strong>
      </div>
      <div class="wl-bubble wl-bubble-you"><b>You</b>"Nice to meet you too."</div>
      <div class="wl-bubble wl-bubble-ai"><b>AI Tutor</b>"Nice to meet you!"</div>
    </div>
  </section>

  {{-- ---------- Features ---------- --}}
  <section id="features" class="wl-section">
    <h2>Practice that feels like a real conversation</h2>
    <p class="wl-sub">No flashcards. You talk, Veya answers, and you get better every session.</p>

    <div class="wl-grid">
      <article class="wl-card">
        <div class="wl-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0014 0M12 19v3"/></svg></div>
        <h3>Speak out loud</h3>
        <p>Hold to talk and answer naturally. Your voice is the lesson, not a keyboard.</p>
      </article>
      <article class="wl-card">
        <div class="wl-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
        <h3>Answers in real time</h3>
        <p>Your AI tutor replies right away, asks follow-up questions, and keeps the conversation going.</p>
      </article>
      <article class="wl-card">
        <div class="wl-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20V10M12 20V4M21 20v-7"/></svg></div>
        <h3>Clear stages</h3>
        <p>Start with greetings, then move on to daily routines, ordering food, and asking directions.</p>
      </article>
    </div>
  </section>

  {{-- ---------- How it works ---------- --}}
  <section id="how" class="wl-section">
    <h2>How it works</h2>
    <p class="wl-sub">Three steps, about ten minutes a day.</p>

    <ol class="wl-steps">
      <li><span class="wl-no">1</span><div><h3>Pick a stage</h3><p>Choose where you are, from Beginner onward.</p></div></li>
      <li><span class="wl-no">2</span><div><h3>Talk with Veya</h3><p>Hold to talk and respond to each prompt out loud.</p></div></li>
      <li><span class="wl-no">3</span><div><h3>Keep your streak</h3><p>Earn XP, finish the stage, and unlock the next one.</p></div></li>
    </ol>
  </section>

  {{-- ---------- CTA ---------- --}}
  <section class="wl-final">
    <h2>Ready to say hello?</h2>
    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="wl-btn wl-btn-primary">Start learning</a>
  </section>
</main>

<footer class="wl-foot">© {{ date('Y') }} Convedya</footer>
@endsection
