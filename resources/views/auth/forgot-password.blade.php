@extends('layouts.learn')

@section('title', 'Forgot password — Convedya')

@push('styles')
{{-- CSS dipakai bersama dengan halaman register & login --}}
@vite(['resources/css/auth-register.css'])
@endpush

@section('content')
<div class="rg-page">

  {{-- ---------- Kiri: form ---------- --}}
  <main class="rg-form-side">
    <a href="{{ url('/') }}" class="rg-brand" aria-label="Convedya">c<span class="o-mark"></span>nved<span class="hi">y</span>a</a>

    <div class="rg-form-wrap">
      <h1>Forgot your password?</h1>
      <p class="rg-sub">Happens to everyone. Enter your email and we'll send you a link to set a new one.</p>

      @if (session('status'))
        <p class="rg-status" role="status">{{ session('status') }}</p>
      @endif

      <form method="POST" action="{{ route('password.email') }}" class="rg-form" novalidate>
        @csrf

        <div class="rg-field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}"
                 autocomplete="username" @unless (session('status')) autofocus @endunless required
                 data-hint="Which email did you sign up with?"
                 @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
          @error('email') <p class="rg-err" id="email-err">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="rg-btn">Send reset link</button>
      </form>

      @if (Route::has('login'))
        <p class="rg-alt">Remembered it? <a href="{{ route('login') }}">Back to log in</a></p>
      @endif
    </div>
  </main>

  {{-- ---------- Kanan: Veya ---------- --}}
  <aside class="rg-veya" aria-live="polite">
    <div class="rg-bubble" id="rg-bubble">
      <b>Veya</b>
      <span id="rg-say">{{ session('status') ? 'Check your inbox. I sent it!' : "No worries, it happens. Let's get you back in." }}</span>
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

    inputs.forEach(function (el) {
      el.addEventListener('focus', function () {
        say.textContent = el.dataset.hint;
        orb.classList.remove('nudge'); void orb.offsetWidth; orb.classList.add('nudge');
      });
    });

    var firstBad = document.querySelector('.rg-form [aria-invalid="true"]');
    if (firstBad) {
      firstBad.focus();
      say.textContent = "I couldn't find that email. Check it and try again.";
    }
  })();
</script>
@endpush
