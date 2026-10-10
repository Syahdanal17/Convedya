@extends('layouts.learn')

@section('title', 'Verify your email — Convedya')

@push('styles')
{{-- CSS dipakai bersama dengan halaman auth lainnya --}}
@vite(['resources/css/auth-register.css'])
@endpush

@section('content')
<div class="rg-page">

  {{-- ---------- Kiri ---------- --}}
  <main class="rg-form-side">
    <a href="{{ url('/') }}" class="rg-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

    <div class="rg-form-wrap">
      <h1>Check your inbox</h1>
      <p class="rg-sub">We sent a verification link to your email. Open it to activate your account.</p>

      @if (session('status') == 'verification-link-sent')
        <p class="rg-status" role="status">A new link is on its way to the email you registered with.</p>
      @endif

      <form method="POST" action="{{ route('verification.send') }}" class="rg-form">
        @csrf
        <button type="submit" class="rg-btn rg-btn-block">Resend verification email</button>
      </form>

      <p class="rg-note">Can't find it? Check your spam folder first.</p>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="rg-linkbtn">Log out</button>
      </form>
    </div>
  </main>

  {{-- ---------- Kanan: Veya ---------- --}}
  <aside class="rg-veya" aria-live="polite">
    <div class="rg-bubble" id="rg-bubble">
      <b>Veya</b>
      <span id="rg-say">{{ session('status') == 'verification-link-sent' ? 'Sent again! Give it a minute.' : 'I sent you a link. Tap it and we can start.' }}</span>
    </div>

    <div class="rg-orb" id="rg-orb" aria-hidden="true">
      <img src="{{ asset('images/veya.png') }}" alt="">
    </div>
  </aside>

</div>
@endsection
