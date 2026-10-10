{{--
  Animasi loading: kepala Veya menggelinding bolak-balik.

  Pakai di mana saja:
      <x-veya-loader />                              {{-- ukuran sedang, tulisan "Memuat..." --}}
      <x-veya-loader size="sm" label="Veya sedang berpikir" />
      <x-veya-loader size="lg" :label="false" />     {{-- tanpa tulisan --}}

  size: sm | md | lg
  Gambar: public/images/veya-face.png
--}}
@props(['label' => 'Memuat', 'size' => 'md'])

@once
<style>
  .veya-loader{
    --s:72px;
    --run:calc(var(--s) * 1.5);                 /* jarak gelinding ke tiap sisi */
    display:inline-flex; flex-direction:column; align-items:center; gap:14px;
    color:#B9BAD1; font-family:'Inter',sans-serif;
  }
  .veya-loader.vl-sm{ --s:44px; gap:10px; }
  .veya-loader.vl-lg{ --s:96px; gap:18px; }

  .veya-loader .vl-stage{
    position:relative; width:calc(var(--s) * 4); max-width:100%; height:calc(var(--s) * 1.2);
    display:flex; justify-content:center;
  }
  .veya-loader .vl-head{
    position:relative; display:block; width:var(--s); height:var(--s); border-radius:50%; overflow:hidden;
    background:radial-gradient(circle at 35% 28%, #C79BFF 0%, #8F3CF0 45%, #6A18D6 100%);
    box-shadow:0 0 0 2px rgba(255,255,255,.12), 0 10px 26px -8px rgba(127,0,255,.8);
    animation:vl-roll 1.4s ease-in-out infinite alternate;
  }
  .veya-loader .vl-head img{
    position:absolute; left:43%; top:52%; display:block;      /* geser sedikit supaya kedua mata ada di tengah bola */
    width:122%; max-width:none; height:auto; transform:translate(-50%,-50%);
    -webkit-mask-image:linear-gradient(to bottom, transparent 0, #000 22%, #000 78%, transparent 100%);   /* haluskan tepi atas/bawah gambar */
            mask-image:linear-gradient(to bottom, transparent 0, #000 22%, #000 78%, transparent 100%);
  }
  /* bayangan di lantai ikut bergeser */
  .veya-loader .vl-shadow{
    position:absolute; bottom:0; left:50%; width:calc(var(--s) * .8); height:calc(var(--s) * .16);
    margin-left:calc(var(--s) * -.4); border-radius:50%;
    background:radial-gradient(closest-side, rgba(0,0,0,.5), transparent);
    animation:vl-slide 1.4s ease-in-out infinite alternate;
  }
  /* jarak total = 3 x s; keliling = pi x s  ->  putaran = 3/pi ~ 0.95 putaran (~344 derajat) */
  @keyframes vl-roll{
    from{ transform:translateX(calc(var(--run) * -1)) rotate(-172deg); }
    to  { transform:translateX(var(--run))            rotate(172deg); }
  }
  @keyframes vl-slide{
    from{ transform:translateX(calc(var(--run) * -1)) scaleX(1); }
    50% { transform:translateX(0) scaleX(1.12); }
    to  { transform:translateX(var(--run)) scaleX(1); }
  }

  .veya-loader .vl-label{ margin:0; font-size:14px; letter-spacing:.02em; }
  .veya-loader.vl-sm .vl-label{ font-size:12.5px; }
  .veya-loader .vl-dots i{ font-style:normal; animation:vl-dot 1.2s infinite both; }
  .veya-loader .vl-dots i:nth-child(2){ animation-delay:.2s; }
  .veya-loader .vl-dots i:nth-child(3){ animation-delay:.4s; }
  @keyframes vl-dot{ 0%,80%,100%{ opacity:.2; } 40%{ opacity:1; } }

  @media (prefers-reduced-motion:reduce){
    .veya-loader .vl-head{ animation:vl-pulse 1.6s ease-in-out infinite; }
    .veya-loader .vl-shadow{ animation:none; }
    @keyframes vl-pulse{ 50%{ transform:scale(.92); opacity:.8; } }
  }
</style>
@endonce

<div {{ $attributes->class(['veya-loader', 'vl-' . $size]) }} role="status" aria-live="polite">
  <div class="vl-stage">
    <span class="vl-shadow" aria-hidden="true"></span>
    <span class="vl-head" aria-hidden="true"><img src="{{ asset('images/veya-face.png') }}" alt=""></span>
  </div>
  @if ($label)
    <p class="vl-label">{{ $label }}<span class="vl-dots" aria-hidden="true"><i>.</i><i>.</i><i>.</i></span></p>
  @else
    <span class="sr-only" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Memuat</span>
  @endif
</div>
