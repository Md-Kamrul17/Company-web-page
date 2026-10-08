// ── MAF Shoes Limited — Main JS ───────────────────────────────

// ── Nav scroll ────────────────────────────────────────────────
const nav = document.getElementById('mainNav');
if (nav) {
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60));
}

// ── Mobile hamburger ──────────────────────────────────────────
const hbg = document.getElementById('navToggle');
const menu = document.getElementById('navMenu');
if (hbg && menu) {
  hbg.addEventListener('click', () => {
    hbg.classList.toggle('open');
    menu.classList.toggle('open');
  });
  // Mobile dropdown toggles
  document.querySelectorAll('.has-dropdown > a').forEach(a => {
    a.addEventListener('click', function(e) {
      if (window.innerWidth <= 1100) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
      }
    });
  });
}

// ── Hero Slider ───────────────────────────────────────────────
(function initSlider() {
  const slides = document.querySelectorAll('.slide');
  const dots   = document.querySelectorAll('.slider-dot');
  if (!slides.length) return;

  let cur = 0, timer;

  function goTo(idx) {
    slides[cur].classList.remove('active');
    dots[cur]?.classList.remove('active');
    cur = (idx + slides.length) % slides.length;
    slides[cur].classList.add('active');
    dots[cur]?.classList.add('active');
  }

  function next() { goTo(cur + 1); }
  function prev() { goTo(cur - 1); }
  function startAuto() { timer = setInterval(next, 5500); }
  function resetAuto()  { clearInterval(timer); startAuto(); }

  slides[0].classList.add('active');
  dots[0]?.classList.add('active');
  startAuto();

  document.querySelector('.slider-next')?.addEventListener('click', () => { next(); resetAuto(); });
  document.querySelector('.slider-prev')?.addEventListener('click', () => { prev(); resetAuto(); });
  dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); resetAuto(); }));

  // Touch/swipe
  let tx = 0;
  const hero = document.querySelector('.hero-slider');
  if (hero) {
    hero.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend',   e => {
      const dx = tx - e.changedTouches[0].clientX;
      if (Math.abs(dx) > 50) { dx > 0 ? next() : prev(); resetAuto(); }
    });
  }
})();

// ── Counter Animation ─────────────────────────────────────────
function animateCounter(el) {
  const target   = parseFloat(el.dataset.target.replace(/,/g, ''));
  const isInt    = Number.isInteger(target);
  const duration = 2000;
  const start    = performance.now();
  const run = now => {
    const p = Math.min((now - start) / duration, 1);
    const v = (1 - Math.pow(1 - p, 3)) * target;
    el.textContent = isInt ? Math.round(v).toLocaleString() : v.toFixed(1);
    if (p < 1) requestAnimationFrame(run);
  };
  requestAnimationFrame(run);
}

// ── Intersection Observer (fade-up + counters) ───────────────
const io = new IntersectionObserver(entries => {
  entries.forEach(en => {
    if (!en.isIntersecting) return;
    const el = en.target;
    if (el.classList.contains('fu'))      el.classList.add('show');
    if (el.classList.contains('counter')) animateCounter(el);
    io.unobserve(el);
  });
}, { threshold: 0.15 });

document.querySelectorAll('.fu, .counter').forEach(el => io.observe(el));

// ── Product / News filter ─────────────────────────────────────
document.querySelectorAll('[data-filter]').forEach(btn => {
  btn.addEventListener('click', function () {
    const group = this.dataset.filterGroup || 'default';
    document.querySelectorAll(`[data-filter][data-filter-group="${group}"]`)
      .forEach(b => b.classList.remove('active'));
    this.classList.add('active');

    const val = this.dataset.filter;
    document.querySelectorAll(`[data-cat][data-filter-parent="${group}"]`).forEach(card => {
      card.style.display = (val === 'all' || card.dataset.cat === val) ? '' : 'none';
    });
  });
});

// ── Lightbox ──────────────────────────────────────────────────
const lb     = document.getElementById('lightbox');
const lbImg  = document.getElementById('lightboxImg');
if (lb && lbImg) {
  document.querySelectorAll('[data-lightbox]').forEach(el => {
    el.addEventListener('click', () => {
      lbImg.src = el.dataset.lightbox;
      lb.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  });
  lb.querySelector('.lightbox-close')?.addEventListener('click', closeLb);
  lb.addEventListener('click', e => { if (e.target === lb) closeLb(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLb(); });
  function closeLb() { lb.classList.remove('open'); document.body.style.overflow = ''; }
}

// ── Contact form feedback ─────────────────────────────────────
const cf = document.getElementById('contactForm');
if (cf) {
  cf.addEventListener('submit', function (e) {
    const em = this.querySelector('[name="email"]');
    if (em && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value)) {
      e.preventDefault();
      em.style.borderColor = '#CC1E1E';
      em.focus();
    }
  });
}

// ── Job keyword search ────────────────────────────────────────
const js = document.getElementById('jobSearch');
if (js) {
  js.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.job-card').forEach(c => {
      const txt = c.textContent.toLowerCase();
      c.style.display = txt.includes(q) ? '' : 'none';
    });
  });
}

// ── Auto-hide flash messages ──────────────────────────────────
const fl = document.querySelector('.alert');
if (fl) {
  setTimeout(() => { fl.style.opacity = '0'; fl.style.transition = 'opacity .5s'; }, 4000);
  setTimeout(() => fl.remove(), 4600);
}
