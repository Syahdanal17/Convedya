@extends('layouts.learn')

@section('title', 'Create your account — Convedya')

@push('styles')
@vite(['resources/css/auth-register.css'])
@endpush

@section('content')
<div class="rg-page">

  {{-- ---------- Kiri: form ---------- --}}
  <main class="rg-form-side">
    <a href="{{ url('/') }}" class="rg-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

    <div class="rg-form-wrap">
      <h1>Create your account</h1>
      <p class="rg-sub">Takes a minute. Then you can start talking.</p>

      <form method="POST" action="{{ route('register') }}" class="rg-form" novalidate>
        @csrf

        <div class="rg-field">
          <label for="name">Your name</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}"
                 autocomplete="name" autofocus required
                 data-hint="What should I call you?"
                 @error('name') aria-invalid="true" aria-describedby="name-err" @enderror>
          @error('name') <p class="rg-err" id="name-err">{{ $message }}</p> @enderror
        </div>

        <div class="rg-field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}"
                 autocomplete="username" required
                 data-hint="Where should I send your first hello?"
                 @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
          @error('email') <p class="rg-err" id="email-err">{{ $message }}</p> @enderror
        </div>

        <div class="rg-field">
          <label for="password">Password</label>
          <div class="rg-pass">
            <input id="password" name="password" type="password"
                   autocomplete="new-password" required minlength="8"
                   data-hint="Make it a good one. At least 8 characters."
                   @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
            <button type="button" class="rg-eye" aria-label="Show password" aria-pressed="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password') <p class="rg-err" id="password-err">{{ $message }}</p> @enderror
        </div>

        <div class="rg-field">
          <label for="password_confirmation">Confirm password</label>
          <input id="password_confirmation" name="password_confirmation" type="password"
                 autocomplete="new-password" required
                 data-hint="Once more, just to be sure."
                 @error('password_confirmation') aria-invalid="true" aria-describedby="confirm-err" @enderror>
          @error('password_confirmation') <p class="rg-err" id="confirm-err">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="rg-btn">Create account</button>
      </form>

      <p class="rg-alt">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </div>
  </main>

  {{-- ---------- Kanan: Veya ---------- --}}
  <aside class="rg-veya" aria-live="polite">
    <div class="rg-bubble" id="rg-bubble">
      <b>Veya</b>
      <span id="rg-say">Hi, I'm Veya. Let's get you set up.</span>
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

    // Kalau ada error dari server, Veya yang menyampaikan
    var firstBad = document.querySelector('.rg-form [aria-invalid="true"]');
    if (firstBad) {
      firstBad.focus();
      say.textContent = "Hmm, something needs another look.";
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
