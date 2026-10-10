// resources/js/progress-live.js  (import dari app.js, atau tempel di <script> view)
// Mengambil data dari UserProgressController@show (JSON) lalu memperbarui tampilan tanpa reload.
(function () {
  const root = document.querySelector('[data-progress-url]');
  if (!root) return;

  const url   = root.dataset.progressUrl;
  const total = parseInt(root.dataset.progressTotal || '0', 10); // total lesson, diisi dari server
  const INTERVAL = 5000;

  const set = (sel, val) => {
    const el = root.querySelector(sel);
    if (el && el.textContent !== String(val)) el.textContent = val;
  };

  function render(d) {
    const done = d.completed_lessons ?? 0;
    const pct  = total ? Math.min(100, Math.round((done / total) * 100)) : 0;

    set('[data-progress-percent]', pct + '%');
    set('[data-progress-done]',    done);
    set('[data-progress-xp]',      d.xp ?? 0);
    set('[data-progress-streak]',  d.streak ?? 0);
    if (d.current_stage) set('[data-progress-stage]', d.current_stage);

    const bar = root.querySelector('[data-progress-bar]');
    if (bar) bar.style.width = pct + '%';
  }

  async function refresh() {
    try {
      const res = await fetch(url, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        cache: 'no-store',
      });
      if (res.ok) render(await res.json());
    } catch (_) { /* coba lagi di putaran berikutnya */ }
  }

  let timer = setInterval(refresh, INTERVAL);
  document.addEventListener('visibilitychange', () => {
    clearInterval(timer);
    if (!document.hidden) { refresh(); timer = setInterval(refresh, INTERVAL); }
  });
  window.addEventListener('pageshow', (e) => { if (e.persisted) refresh(); });
  refresh();
})();
