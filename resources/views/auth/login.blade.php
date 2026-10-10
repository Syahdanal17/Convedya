@extends('layouts.learn')

@section('title', 'Log in — Convedya')

@push('styles')
{{-- CSS dipakai bersama dengan halaman register --}}
@vite(['resources/css/auth-register.css'])
@endpush

@section('content')
<div class="rg-page">

  {{-- ---------- Kiri: form ---------- --}}
  <main class="rg-form-side">
    <a href="{{ url('/') }}" class="rg-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

    <div class="rg-form-wrap">
      <h1>Welcome back</h1>
      <p class="rg-sub">Log in and pick up where you left off.</p>

      @if (session('status'))
        <p class="rg-status" role="status">{{ session('status') }}</p>
      @endif

      <form method="POST" action="{{ route('login') }}" class="rg-form" novalidate>
        @csrf

        <div class="rg-field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}"
                 autocomplete="username" autofocus required
                 data-hint="Which email did you sign up with?"
                 @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
          @error('email') <p class="rg-err" id="email-err">{{ $message }}</p> @enderror
        </div>

        <div class="rg-field">
          <div class="rg-label-row">
            <label for="password">Password</label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="rg-forgot">Forgot password?</a>
            @endif
          </div>
          <div class="rg-pass">
            <input id="password" name="password" type="password"
                   autocomplete="current-password" required
                   data-hint="Go on, I won't peek."
                   @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
            <button type="button" class="rg-eye" aria-label="Show password" aria-pressed="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password') <p class="rg-err" id="password-err">{{ $message }}</p> @enderror
        </div>

        <label class="rg-check" for="remember">
          <input id="remember" name="remember" type="checkbox" @checked(old('remember'))
                 data-hint="I'll keep your seat warm.">
          <span>Keep me logged in</span>
        </label>

        <button type="submit" class="rg-btn">Log in</button>
      </form>

      @if (Route::has('register'))
        <p class="rg-alt">New here? <a href="{{ route('register') }}">Create an account</a></p>
      @endif
    </div>
  </main>

  {{-- ---------- Kanan: Veya ---------- --}}
  <aside class="rg-veya" aria-live="polite">
    <div class="rg-bubble" id="rg-bubble">
      <b>Veya</b>
      <span id="rg-say">You're back! Ready to talk?</span>
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
    var say   = document.getElementById('rg-say');
    var orb   = document.getElementById('rg-orb');
    var inputs = document.querySelectorAll('.rg-form input[data-hint]');

    // Veya ganti kalimat sesuai kolom yang sedang diisi
    inputs.forEach(function (el) {
      el.addEventListener('focus', function () {
        say.textContent = el.dataset.hint;
        orb.classList.remove('nudge'); void orb.offsetWidth; orb.classList.add('nudge');
      });
    });

    // Login gagal: Veya yang menyampaikan
    var firstBad = document.querySelector('.rg-form [aria-invalid="true"]');
    if (firstBad) {
      firstBad.focus();
      say.textContent = "That didn't match. Check your email and password.";
    }

    // Tampilkan / sembunyikan password
    var eye = document.querySelector('.rg-eye');
    var pw  = document.getElementById('password');
    if (eye && pw) {
      eye.addEventListener('click', function () {
        var show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        eye.setAttribute('aria-pressed', show);
        eye.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      });
    }
  })();
</script>
@endpush
