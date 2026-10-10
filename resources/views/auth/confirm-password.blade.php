@extends('layouts.learn')

@section('title', 'Confirm password — Convedya')

@push('styles')
{{-- CSS dipakai bersama dengan halaman auth lainnya --}}
@vite(['resources/css/auth-register.css'])
@endpush

@section('content')
<div class="rg-page">

  {{-- ---------- Kiri: form ---------- --}}
  <main class="rg-form-side">
    <a href="{{ url('/') }}" class="rg-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

    <div class="rg-form-wrap">
      <h1>Confirm your password</h1>
      <p class="rg-sub">This part of your account is protected. Enter your password to continue.</p>

      <form method="POST" action="{{ route('password.confirm') }}" class="rg-form" novalidate>
        @csrf

        <div class="rg-field">
          <label for="password">Password</label>
          <div class="rg-pass">
            <input id="password" name="password" type="password"
                   autocomplete="current-password" autofocus required
                   data-hint="Go on, I won't peek."
                   @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
            <button type="button" class="rg-eye" aria-label="Show password" aria-pressed="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password') <p class="rg-err" id="password-err">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="rg-btn">Confirm</button>
      </form>
    </div>
  </main>

  {{-- ---------- Kanan: Veya ---------- --}}
  <aside class="rg-veya" aria-live="polite">
    <div class="rg-bubble" id="rg-bubble">
      <b>Veya</b>
      <span id="rg-say">Just a quick check before we go on.</span>
    </div>

    <div class="rg-orb" id="rg-orb" aria-hidden="true">
      <img src="{{ asset('images/veya.png') }}" alt="">
    </div>
  </aside>

</div>
@endsection

@push('scripts')
<script>
  (function () {
    var say = document.getElementById('rg-say');
    var orb = document.getElementById('rg-orb');
    var pw  = document.getElementById('password');

    pw.addEventListener('focus', function () {
      say.textContent = pw.dataset.hint;
      orb.classList.remove('nudge'); void orb.offsetWidth; orb.classList.add('nudge');
    });

    if (pw.getAttribute('aria-invalid') === 'true') {
      pw.focus();
      say.textContent = "That's not it. Try again?";
    }

    var eye = document.querySelector('.rg-eye');
    eye.addEventListener('click', function () {
      var show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      eye.setAttribute('aria-pressed', show);
      eye.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  })();
</script>
@endpush
