{{--
  Layar loading satu halaman penuh. Dimasukkan sekali di layout (tepat setelah <body>).
  - Tampil sejak halaman mulai dimuat, hilang setelah semua selesai dimuat.
  - Tampil lagi saat user klik link internal / kirim form (tertunda 120 ms supaya tidak berkedip di halaman cepat).
  - Aman tanpa JavaScript (lewat <noscript> di layout) dan otomatis hilang setelah 6 detik kalau ada yang macet.
  Lama minimal tampil: ubah MIN_MS di script.
--}}
<div id="veya-page-loader" class="veya-page-loader">
  <x-veya-loader size="lg" />
</div>

<style>
  .veya-page-loader{
    position:fixed; inset:0; z-index:9999;
    display:flex; align-items:center; justify-content:center;
    background:radial-gradient(60% 50% at 50% 45%, rgba(127,0,255,.26), transparent 70%), #0A0A12;
    transition:opacity .35s ease, visibility .35s ease;
    animation:vl-failsafe .01s 6s forwards;          /* jaring pengaman */
  }
  .veya-page-loader.is-hidden{ opacity:0; visibility:hidden; pointer-events:none; }
  @keyframes vl-failsafe{ to{ opacity:0; visibility:hidden; pointer-events:none; } }
</style>

<script>
  (function () {
    var el = document.getElementById('veya-page-loader');
    if (!el) return;

    var MIN_MS = 400;          // minimal tampil, supaya animasinya sempat terlihat
    var shownAt = Date.now();
    var timer;

    function hide() {
      clearTimeout(timer);
      var wait = Math.max(0, MIN_MS - (Date.now() - shownAt));
      setTimeout(function () { el.classList.add('is-hidden'); }, wait);
    }
    function show() {
      el.style.animation = 'none';                    // matikan jaring pengaman lama
      shownAt = Date.now();
      el.classList.remove('is-hidden');
    }

    if (document.readyState === 'complete') hide();
    else window.addEventListener('load', hide);

    // Tombol "back" dari cache browser: pastikan layar loading tidak menempel
    window.addEventListener('pageshow', function (e) { if (e.persisted) { clearTimeout(timer); el.classList.add('is-hidden'); } });

    // Pindah halaman lewat link internal
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href]');
      if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      if ((a.target && a.target !== '_self') || a.hasAttribute('download')) return;
      var u = new URL(a.href, location.href);
      if (u.origin !== location.origin) return;
      if (u.pathname === location.pathname && u.search === location.search) return;   // anchor / halaman yang sama
      timer = setTimeout(show, 120);
    });

    // Kirim form (login, simpan profil, keluar, dst.)
    document.addEventListener('submit', function (e) {
      if (!e.defaultPrevented) timer = setTimeout(show, 120);
    });
  })();
</script>
