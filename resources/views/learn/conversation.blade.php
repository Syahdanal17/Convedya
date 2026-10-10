@extends('layouts.learn')

@section('title', 'Convedya — Percakapan')

@push('styles')
@vite(['resources/css/learn-conversation.css'])
@endpush

@section('content')
<header>
  <div class="brand">c<span class="o-mark"></span>nved<span class="hi">y</span>a</div>
  <div class="hdr-actions">
    <div class="icon-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg></div>
    <div class="icon-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12M7 8l5-5 5 5"/><path d="M5 21h14"/></svg></div>
    <div class="icon-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg></div>
    <button class="avatar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg></button>
  </div>
</header>

<main id="conversation-page" data-lesson-id="{{ $lesson->id }}" data-language-code="{{ $lesson->stage->language->code }}">
  <div class="top-row">
    <div>
      <div class="crumb">
        {{ $lesson->stage->level }}
        <span class="dot"></span>
        Stage {{ $lesson->stage->order }}
      </div>

      <h1>{{ $lesson->title }}</h1>
    </div>

    <div class="stepper">
    @php $orderedSteps = $lesson->lesson_steps->sortBy('step_number')->values(); @endphp
    @foreach ($orderedSteps as $i => $step)
        @if ($i > 0)
        <div class="connector {{ $orderedSteps[$i - 1]->status === 'completed' ? 'filled' : '' }}"></div>
        @endif

        <div
        class="step {{ $step->status === 'active' ? 'active' : '' }} {{ $step->status === 'completed' ? 'done' : '' }}"
        data-step-number="{{ $step->step_number }}"
        >
        <div class="circle">{{ $step->step_number }}</div>
        <span class="label">{{ $step->goal }}</span>
        </div>
    @endforeach
    </div>
  </div>

  <div class="stage">
    <div class="tip">
      <div class="veya-face">
        <img src="{{ asset('images/veya-face.png') }}" alt="Veya">
      </div>

      <div class="txt">
        <strong>Tip</strong>
        <span>{{ $lesson->objective }}</span>
      </div>

      <button class="close" aria-label="Tutup tip">&times;</button>
    </div>

    <div class="orb-wrap">
      <div class="orb"></div>
    </div>

    <div class="transcript"></div>

    <div class="controls">
      <button class="hold-btn">
        <svg
          class="mic-ic"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
        >
          <rect x="9" y="2" width="6" height="12" rx="3"/>
          <path d="M5 10a7 7 0 0014 0M12 19v3"/>
        </svg>

        <span class="txt">
          <strong>Push to talk</strong>
            <span>
            Bicaralah di bahasa {{ $lesson->stage->language->name }}
            </span>
        </span>
      </button>

      <div class="end-btn">
        <button
          type="button"
          class="end-circle"
          id="end-btn"
             data-href="{{ route('learn.greetings.show', \Illuminate\Support\Str::slug($lesson->stage->language->name)) }}"
          aria-label="Akhiri percakapan"
        >
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M6.6 10.8c1.6 3.2 4.4 6 7.6 7.6l2.5-2.5c.3-.3.8-.4 1.2-.3 1.3.4 2.7.7 4.1.7.6 0 1 .4 1 1V21c0 .6-.4 1-1 1C10.5 22 2 13.5 2 3c0-.6.4-1 1-1h3.7c.6 0 1 .4 1 1 0 1.4.2 2.8.7 4.1.1.4 0 .9-.3 1.2L6.6 10.8z"/>
          </svg>
        </button>

        <span class="label">End</span>
      </div>
    </div>
  </div>
</main>

<div class="complete" id="complete" hidden role="dialog" aria-modal="true" aria-labelledby="complete-title">
  <h2 id="complete-title">Lesson selesai!</h2>

  <div class="complete-art" aria-hidden="true">
    <span class="spark s1"></span><span class="spark s2"></span>
    <span class="spark s3"></span><span class="spark s4"></span>
    <div class="complete-orb"></div>
    <div class="complete-face">
      <img src="{{ asset('images/veya-face.png') }}" alt="">
    </div>
  </div>

  <div class="complete-stats">
    <div class="stat-row">
      <span class="stat-name">XP pelajaran</span>
      <span class="stat-val"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L4 14h7l-1 8 9-12h-7z"/></svg><b id="stat-lesson-xp">0</b></span>
    </div>
    <div class="stat-row">
      <span class="stat-name">Total XP</span>
      <span class="stat-val"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L4 14h7l-1 8 9-12h-7z"/></svg><b id="stat-total-xp">0</b></span>
    </div>
  </div>

  <button type="button" class="complete-btn" id="complete-continue">Lanjut</button>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const page = document.getElementById('conversation-page');
    const lessonId = page.dataset.lessonId;
    const languageName = @json($lesson->stage->language->name);
    const languageCode = page.dataset.languageCode;

    const SPEECH_LOCALES = {
        en: 'en-US',
        jp: 'ja-JP',
        ja: 'ja-JP',
        de: 'de-DE',
        ko: 'ko-KR',
        fr: 'fr-FR',
        es: 'es-ES',
        zh: 'zh-CN',
        it: 'it-IT',
        pt: 'pt-PT',
        hi: 'hi-IN',
        ar: 'ar-SA',
        ru: 'ru-RU',
        tr: 'tr-TR',
        vi: 'vi-VN',
        th: 'th-TH',
        nl: 'nl-NL',
        pl: 'pl-PL',
        sv: 'sv-SE',
        no: 'nb-NO',
        el: 'el-GR',
    };

    const btn = document.querySelector('.hold-btn');
    const label = btn.querySelector('.txt strong');
    const hint = btn.querySelector('.txt span');
    const transcript = document.querySelector('.transcript');
    const orb = document.querySelector('.orb');

    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;

    let conversationId = null;
    let conversationReady = false;

    let lessonCompleted = false;
    let resetSent = false;
    let sessionXp = 0;

    let currentAudio = null;
    let currentAudioCleanup = null;
    let ttsPending = false;

    const steps = Array.from(document.querySelectorAll('.stepper .step'));

    function updateStepper(data) {
        if (!data?.step_completed &&
            data?.lesson_status !== 'completed') {
            return;
        }

        // The currently active step has just been completed.
        const completedStep = steps.find((step) =>
            step.classList.contains('active')
        );

        if (completedStep) {
            completedStep.classList.remove('active');
            completedStep.classList.add('done');
        }

        // Activate the next step returned by Laravel.
        if (data.current_step?.step_number != null) {
            const nextStep = steps.find((step) =>
                Number(step.dataset.stepNumber) ===
                Number(data.current_step.step_number)
            );

            if (nextStep) {
                nextStep.classList.remove('done');
                nextStep.classList.add('active');
            }
        }

        // Mark connectors after completed steps.
        const connectors = Array.from(
            document.querySelectorAll('.stepper .connector')
        );

        connectors.forEach((connector, index) => {
            // connector.classList.toggle(
            //     'done',
            //     steps[index]?.classList.contains('done') ?? false
            // );
            connector.classList.toggle('filled', steps[index]?.classList.contains('done') ?? false);
        });
    }

    let line = null;
    let textEl = null;
    let finalText = '';
    let listening = false;


    function getCsrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');
    }

    function cleanDisplayText(text) {
        return text
            .replace(/\[[^\]]*\]\s*/g, '')
            .replace(/[ \t]{2,}/g, ' ')
            .replace(/[ \t]+([,.;!?])/g, '$1')
            .replace(/\n{3,}/g, '\n\n')
            .trim();
    }

    function trimTranscript(max = 2) {
        const lines = transcript.querySelectorAll('.line');

        lines.forEach((el, i) => {
            el.style.display = i < lines.length - max ? 'none' : '';
        });
    }

    function addAIMessage(text, correction = null) {
        const line = document.createElement('div');
        line.className = 'line ai';

        const who = document.createElement('span');
        who.className = 'who';
        who.textContent = 'Veya';

        const reply = document.createElement('p');
        reply.textContent = cleanDisplayText(text);

        line.append(who, reply);

        if (correction?.original && correction?.corrected) {
            const card = document.createElement('div');
            card.className = 'correction-note';

            const title = document.createElement('span');
            title.className = 'correction-title';
            title.textContent = 'Small correction';

            const original = document.createElement('span');
            original.className = 'correction-original';
            original.textContent = correction.original;

            const arrow = document.createElement('span');
            arrow.className = 'correction-arrow';
            arrow.textContent = ' → ';

            const corrected = document.createElement('span');
            corrected.className = 'correction-corrected';
            corrected.textContent = correction.corrected;

            const comparison = document.createElement('div');
            comparison.className = 'correction-comparison';
            comparison.append(original, arrow, corrected);

            card.append(title, comparison);
            line.appendChild(card);
        }

        transcript.appendChild(line);
        trimTranscript();
    }

    async function createConversation() {
        const response = await fetch('/api/conversations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({
                lesson_id: lessonId,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            console.error(data);
            throw new Error(
                data.message || 'Gagal membuat conversation.'
            );
        }

        if (!data.conversation_id) {
            throw new Error('Conversation ID tidak ditemukan.');
        }

        conversationId = data.conversation_id;
        conversationReady = true;



        return data;
    }

    async function sendMessage(message) {
        if (!conversationReady || !conversationId) {
            throw new Error('Conversation belum siap.');
        }

        const response = await fetch('/api/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({
                conversation_id: conversationId,
                message: message,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            console.error(data);
            throw new Error(
                data.message || 'AI request gagal.'
            );
        }

        return data;
    }

    function newLine() {
        line = document.createElement('div');
        line.className = 'line you';

        line.innerHTML = `
            <span class="who">You</span>
            <p></p>
            <span class="wave">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </span>
        `;

        textEl = line.querySelector('p');
        transcript.appendChild(line);
        trimTranscript();
        finalText = '';

        finalText = '';
    }

    function reset() {
        btn.classList.remove('is-listening');
        orb.classList.remove('is-listening');

        label.textContent = 'Push to talk';
        hint.textContent = 'Lepas tombol untuk mengirim';

        if (line) {
            const wave = line.querySelector('.wave');

            if (wave) {
                wave.remove();
            }

            if (!textEl.textContent.trim()) {
                line.remove();
                trimTranscript();
            }

            line = null;
            textEl = null;
        }
    }

    const endBtn = document.getElementById('end-btn');
    let ending = false;

    endBtn.addEventListener('click', async () => {
        const destination = endBtn.dataset.href;

        endBtn.disabled = true;

        try {
            holding = false;
            listening = false;

            try {
                rec.abort();
            } catch (error) {
                // Recognition may not have started.
            }

            stopCurrentAudio();
            window.speechSynthesis?.cancel();

            if (conversationId) {
                const response = await fetch(
                    `/api/conversations/${encodeURIComponent(conversationId)}/end`,
                    {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                        },
                    }
                );

                if (!response.ok) {
                    console.warn(
                        'Conversation could not be ended on the server.'
                    );
                }
            }
        } catch (error) {
            console.error('Failed to end conversation:', error);
        } finally {
            window.location.href = destination;
        }
    });

    if (!SR) {
        label.textContent = 'Browser tidak mendukung';
        hint.textContent = 'Pakai Chrome atau Edge untuk fitur suara';
        btn.disabled = true;
        return;
    }

    const rec = new SR();

    rec.lang = SPEECH_LOCALES[languageCode] || 'en-US';
    rec.interimResults = true;
    rec.continuous = true;

    function start() {
        stopCurrentAudio();
        if (listening || !conversationReady) {
            return;
        }

        try {
            rec.start();
        } catch (error) {
            console.error(error);
            return;
        }

        listening = true;
        newLine();

        btn.classList.add('is-listening');
        label.textContent = 'Listening…';
        hint.textContent = 'Lepas tombol untuk mengirim';
        orb.classList.add('is-listening');
    }

    function stop() {
        if (!listening) {
            return;
        }

        listening = false;
        rec.stop();
    }

    rec.onresult = (e) => {
        let interim = '';

        for (let i = e.resultIndex; i < e.results.length; i++) {
            const t = e.results[i][0].transcript;

            if (e.results[i].isFinal) {
                finalText += t + ' ';
            } else {
                interim += t;
            }
        }

        if (textEl) {
            textEl.textContent =
                (finalText + interim).trim();
        }
    };

    rec.onerror = (e) => {
        holding = false;

        console.log('STT ERROR:', e.error);
        console.log('STT EVENT:', e);

        listening = false;

        btn.classList.remove('is-listening');
        orb.classList.remove('is-listening');

        if (e.error === 'not-allowed' || e.error === 'service-not-allowed') {
            hint.textContent =
                'Izin mikrofon ditolak. Aktifkan di browser.';
        } else if (e.error === 'audio-capture') {
            hint.textContent =
                'Mikrofon tidak ditemukan.';
        } else if (e.error === 'network') {
            hint.textContent =
                'Speech service tidak dapat dihubungi.';
        } else if (e.error === 'no-speech') {
            hint.textContent =
                'Suara tidak terdeteksi. Coba lagi.';
        } else {
            hint.textContent =
                `Speech recognition error: ${e.error}`;
        }
    };



    function setSpeaking(on) {
        orb.classList.toggle('is-speaking', on);
    }

    async function speak(text) {
        if (!text) return;

        ttsPending = true;

        try {
            const response = await fetch('/api/ai/tts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({ text }),
            });

            if (!response.ok) {
                throw new Error('TTS request gagal.');
            }

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);
            const audio = new Audio(url);

            currentAudio = audio;
            orb.classList.add('is-speaking');

            await new Promise((resolve) => {
                let finished = false;

                const finish = () => {
                    if (finished) return;
                    finished = true;

                    orb.classList.remove('is-speaking');
                    URL.revokeObjectURL(url);

                    if (currentAudio === audio) {
                        currentAudio = null;
                    }

                    if (currentAudioCleanup === finish) {
                        currentAudioCleanup = null;
                    }

                    resolve();
                };

                currentAudioCleanup = finish;

                audio.addEventListener('ended', finish, { once: true });
                audio.addEventListener('error', finish, { once: true });

                audio.play().catch((error) => {
                    console.error('TTS playback error:', error);
                    finish();
                });
            });
        } catch (error) {
            console.error('TTS ERROR:', error);
        } finally {
            ttsPending = false;
            orb.classList.remove('is-speaking');
        }
    }

    function stopCurrentAudio() {
        if (currentAudio) {
            currentAudio.pause();
        }

        if (currentAudioCleanup) {
            currentAudioCleanup();
        }

        orb.classList.remove('is-speaking');
        ttsPending = false;
    }

    const completeEl = document.getElementById('complete');
    const continueBtn = document.getElementById('complete-continue');

    function countUp(el, target, signed = false) {
        const duration = 900;
        const startTime = performance.now();

        const tick = (now) => {
            const p = Math.min((now - startTime) / duration, 1);
            const v = Math.round(target * (1 - Math.pow(1 - p, 3)));
            el.textContent = signed && v > 0 ? `+${v}` : v;
            if (p < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    }

    function showComplete(data) {
        completeEl.hidden = false;
        countUp(document.getElementById('stat-lesson-xp'), sessionXp, true);
        countUp(document.getElementById('stat-total-xp'), Number(data.xp_total) || 0);
        continueBtn.focus();
    }

    continueBtn.addEventListener('click', () => {
        window.location.href = endBtn.dataset.href;
    });

    rec.onend = async () => {

        if (holding && listening) {
            try {
                rec.start();
                return;
            } catch (e) {}
        }

        listening = false;

        const message = finalText.trim();

        reset();

        if (!message) {
            return;
        }

        let data = null;

        try {
            btn.disabled = true;
            label.textContent = 'Thinking…';
            hint.textContent =
                'Veya sedang memikirkan jawaban...';

            data = await sendMessage(message);

            sessionXp += Number(data.xp_change) || 0;

            if (data.reply) {
                addAIMessage(data.reply, data.correction);
            }

            updateStepper(data);

            if (data.reply) {
                await speak(data.reply);
            }

               if (data.lesson_status === 'completed') {
                    label.textContent = 'Lesson selesai!';
                    hint.textContent = data.xp_change ? `+${data.xp_change} XP` : 'Selamat!';

                    btn.disabled = true;
                    showComplete(data);
                    return;
                }
        } catch (error) {
            console.error(error);

            label.textContent = 'Gagal mengirim';
            hint.textContent = error.message;
        } finally {
            if (data?.lesson_status !== 'completed') {
                btn.disabled = false;
                label.textContent = 'Push to talk';
                hint.textContent =
                    `Speak naturally in ${languageName}`;
            }
        }
    };

    let holding = false;

    btn.style.touchAction = 'none';
    btn.style.userSelect = 'none';
    btn.style.webkitUserSelect = 'none';

    btn.addEventListener('pointerdown', (e) => {
        if (btn.disabled) return;
        e.preventDefault();
        btn.setPointerCapture?.(e.pointerId); // pointerup still fires if the finger/cursor slides off
        holding = true;
        start();
    });

    ['pointerup', 'pointercancel'].forEach((evt) => {
        btn.addEventListener(evt, () => {
            holding = false;
            stop();
        });
    });

    btn.addEventListener('contextmenu', (e) => e.preventDefault());

    // Keyboard support (Space/Enter)
    btn.addEventListener('keydown', (e) => {
        if ((e.code === 'Space' || e.code === 'Enter') && !e.repeat) {
            e.preventDefault();
            holding = true;
            start();
        }
    });
    btn.addEventListener('keyup', (e) => {
        if (e.code === 'Space' || e.code === 'Enter') {
            holding = false;
            stop();
        }
    });

    createConversation()
        .then(async (data) => {
            if (data.reply) {
                addAIMessage(data.reply, data.correction);
                await speak(data.reply);
            }

            hint.textContent =
                `Speak naturally in ${languageName}`;
        })
        .catch((error) => {
            console.error(error);

            label.textContent = 'Gagal memulai';
            hint.textContent = error.message;
            btn.disabled = true;
        });

})();
</script>
@endpush
