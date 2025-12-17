(() => {
  const root = document.querySelector('.news-hero');
  if (!root) return;

  const viewport = root.querySelector('.news-hero__viewport');
  const track = root.querySelector('.news-hero__track');
  const slides = Array.from(track.children);
  const prev = root.querySelector('.news-hero__arrow--prev');
  const next = root.querySelector('.news-hero__arrow--next');
  const dotsWrap = root.querySelector('.news-hero__dots');

  let index = 0;
  let timer = null;
  let isPaused = false;

  // --- dots
  slides.forEach((_, i) => {
    const b = document.createElement('button');
    if (i === 0) b.classList.add('is-active');
    b.setAttribute('aria-label', `Gå till nyhet ${i + 1}`);
    dotsWrap.appendChild(b);
    b.addEventListener('click', () => goTo(i));
  });

  const dots = Array.from(dotsWrap.children);

  function update() {
    const w = viewport.getBoundingClientRect().width;
    track.style.transform = `translate3d(${-index * w}px,0,0)`;
    dots.forEach(d => d.classList.remove('is-active'));
    dots[index]?.classList.add('is-active');
  }

  function goTo(i) {
    index = (i + slides.length) % slides.length;
    update();
    restartAutoplay();
  }

  function nextSlide() { goTo(index + 1); }
  function prevSlide() { goTo(index - 1); }

  next?.addEventListener('click', nextSlide);
  prev?.addEventListener('click', prevSlide);

  // --- autoplay
  const AUTOPLAY_MS = 5500;

  function startAutoplay() {
    stopAutoplay();
    if (slides.length <= 1) return;
    timer = window.setInterval(() => {
      if (!isPaused) nextSlide();
    }, AUTOPLAY_MS);
  }

  function stopAutoplay() {
    if (timer) window.clearInterval(timer);
    timer = null;
  }

  function restartAutoplay() {
    startAutoplay();
  }

  // paus vid hover
  root.addEventListener('mouseenter', () => { isPaused = true; });
  root.addEventListener('mouseleave', () => { isPaused = false; });

  root.addEventListener('focusin', () => { isPaused = true; });
  root.addEventListener('focusout', () => { isPaused = false; });

  // paus när ej aktiv
  document.addEventListener('visibilitychange', () => {
    isPaused = document.hidden;
  });

  window.addEventListener('resize', update);

  update();
  startAutoplay();
})();
