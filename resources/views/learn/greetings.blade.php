  @extends('layouts.learn')

  @section('title', 'Convedya — Greetings')

  @push('styles')
  @vite(['resources/css/learn-greetings.css'])
  @endpush

  @php
      $conversations = collect($conversations);

      $total    = $conversations->count();
      $finished = $conversations->where('status', 'done');
      $done     = $finished->count();
      $percent  = $total ? (int) round($done / $total * 100) : 0;

      $scores   = $finished->pluck('score')->filter(fn ($s) => $s !== null);
      $avgScore = $scores->isNotEmpty() ? (int) round($scores->avg()) : null;
  @endphp

  @section('content')
  <x-sidebar active="learn">
    <div class="streak-card">
      <div class="badge"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M13 2L3 14h7l-1 8 11-14h-7l0-6z" fill="#fff"/></svg></div>
      <h4>{{ $streak }} hari beruntun</h4>
      <p>{{ $streak > 0 ? 'Pertahankan, latihan hari ini!' : 'Mulai latihan hari ini untuk membangun streak.' }}</p>
    </div>
  </x-sidebar>

  <main>
    <div class="topbar">
    <div>
      <div class="crumb">
        {{ $current ? ucfirst($current->level) : 'Pilih bahasa' }}
        @if ($current)<span class="dot"></span>Stage {{ $current->order }}@endif
      </div>
      <h1>{{ $current?->name ?? 'Greetings' }}</h1>
      <p class="subtitle">{{ $current?->description ?? 'Belum ada bahasa yang dipilih.' }}</p>
    </div>
  </div>

    <div class="stage">
      <div class="mascot-wrap">
        <div class="bars"><span></span><span></span><span></span><span></span></div>

        <div class="mascot-orb">
          <img src="{{ asset('images/veya.png') }}" alt="Veya, maskot Convedya">
          <div class="bubble"><span></span><span></span><span></span></div>
        </div>

        <div class="bars right"><span></span><span></span><span></span><span></span></div>
      </div>

      <h2 class="talk-heading">Ngobrol dengan <em>Veya</em></h2>
      <p class="talk-sub">Latih bahasamu, satu percakapan setiap saat.</p>

      <div class="mic-wrap">
      @if ($currentLesson)
          <a href="{{ route('learn.conversation', $currentLesson) }}" class="mic-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <rect x="9" y="2" width="6" height="12" rx="3"/>
                  <path d="M5 10a7 7 0 0014 0M12 19v3"/>
              </svg>
              Tekan untuk bicara
          </a>
      @else
          <a href="#" class="mic-btn generate-lesson" data-stage-id="{{ $current?->id }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <rect x="9" y="2" width="6" height="12" rx="3"/>
                  <path d="M5 10a7 7 0 0014 0M12 19v3"/>
              </svg>
              Tekan untuk bicara
          </a>
      @endif
      </div>
    </div>

    {{-- Progres Greetings --}}
    <section class="progress-section" aria-labelledby="progress-title">
      <div class="pg-head">
        <div>
          <h2 id="progress-title">Progres {{ $current?->name ?? 'Greetings' }}</h2>
          <p>{{ $done }} dari {{ $total }} percakapan selesai</p>
        </div>
        <span class="pg-percent">{{ $percent }}%</span>
      </div>

      <div class="pg-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}" aria-label="Progres Greetings">
        <i style="width: {{ $percent }}%"></i>
      </div>

      <div class="pg-stats">
        <div><span>XP didapat</span><strong>{{ $xpEarned }}</strong></div>
        <div><span>Rata-rata skor</span><strong>{{ $avgScore !== null ? $avgScore . '%' : '-' }}</strong></div>
        <div><span>Tersisa</span><strong>{{ $total - $done }} <small>percakapan</small></strong></div>
      </div>

      <ol class="pg-list">
        @foreach ($conversations as $i => $c)
          <li class="pg-item {{ $c['status'] }}">
            <span class="pg-no" aria-hidden="true">
            @if ($c['status'] === 'done')
              @if ($c['score'] !== null)<span class="pg-score">{{ $c['score'] }}%</span>@endif
              <span class="pg-xp">+{{ $c['xp'] }} XP</span>
            @elseif ($c['status'] === 'locked')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 11h14v10H5zM8 11V8a4 4 0 018 0v3"/></svg>
              @else
                {{ $i + 1 }}
              @endif
            </span>

            <div class="pg-info">
              <strong>{{ $c['title'] }}</strong>
              <small>{{ $c['desc'] }}</small>
            </div>

            <div class="pg-meta">
              @if ($c['status'] === 'done')
                <span class="pg-score">{{ $c['score'] }}%</span>
                <span class="pg-xp">+{{ $c['xp'] }} XP</span>
              @elseif ($c['status'] === 'current')
                  @if ($currentLesson)
                      <a href="{{ route('learn.conversation', $currentLesson) }}" class="pg-go">
                          {{ $currentLesson->status === 'active' ? 'Lanjutkan' : 'Mulai' }}
                      </a>
                  @else
                      <button
                          type="button"
                          class="pg-go generate-lesson"
                          id="generate-lesson-btn"
                          data-stage-id="{{ $current?->id }}"
                      >
                          Mulai
                      </button>
                  @endif
              @else
                <span class="pg-lock">Terkunci</span>
              @endif
            </div>
          </li>
        @endforeach
      </ol>
    </section>

    <div class="floor-wave" aria-hidden="true"></div>
  </main>
  @endsection

  @push('scripts')
  <script>
  (function () {
      const buttons = document.querySelectorAll('.generate-lesson, #generate-lesson-btn');

      buttons.forEach((btn) => {
          btn.addEventListener('click', async (event) => {
              event.preventDefault();

              const stageId = btn.dataset.stageId;

              if (!stageId) {
                  alert('Stage tidak ditemukan.');
                  return;
              }

              buttons.forEach((button) => {
                  button.classList.add('is-loading');
                  button.style.pointerEvents = 'none';
              });

              const originalText = btn.textContent.trim();
              btn.textContent = 'Membuat lesson...';

              try {
                  const csrf = document
                      .querySelector('meta[name="csrf-token"]')
                      ?.getAttribute('content');

                  const response = await fetch('/api/lessons/generate', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': csrf,
                      },
                      body: JSON.stringify({
                          stage_id: stageId,
                      }),
                  });

                  const data = await response.json();

                  if (!response.ok) {
                      throw new Error(
                          data.message || 'Gagal membuat lesson.'
                      );
                  }

                  if (!data.lesson?.id) {
                      throw new Error('Lesson ID tidak ditemukan.');
                  }

                  window.location.href =
                      `/learn/conversation/${data.lesson.id}`;
              } catch (error) {
                  console.error(error);
                  alert(error.message || 'Gagal membuat lesson.');

                  buttons.forEach((button) => {
                      button.classList.remove('is-loading');
                      button.style.pointerEvents = '';
                  });

                  btn.textContent = originalText;
              }
          });
      });
  })();
  </script>
  @endpush
