(() => {
  const root = document.querySelector('.exp-slider');
  if (!root) return;

  const track = root.querySelector('.exp-slider__track');
  const slides = Array.from(track.children);
  const prev = root.querySelector('.exp-slider__arrow--prev');
  const next = root.querySelector('.exp-slider__arrow--next');
  const dotsWrap = root.querySelector('.exp-slider__dots');

  const gap = parseFloat(getComputedStyle(track).gap);
  const slideWidth = slides[0].getBoundingClientRect().width + gap;

  // Duplicera slides för infinite loop
  slides.forEach(s => track.appendChild(s.cloneNode(true)));
  slides.forEach(s => track.insertBefore(s.cloneNode(true), track.firstChild));

  let index = slides.length;
  const total = track.children.length;

  // Dots
// Dots
slides.forEach((_, i) => {
  const b = document.createElement('button');
  b.type = 'button';
  b.classList.add('exp-slider__dot');
  b.setAttribute('aria-label', `Gå till bild ${i + 1}`);
  if (i === 0) {
    b.classList.add('is-active');
    b.setAttribute('aria-current', 'true');
  }

  dotsWrap.appendChild(b);

  b.addEventListener('click', () => goTo(i + slides.length));
});


  const dots = Array.from(dotsWrap.children);

  function update(active = true) {
    track.style.transition = active ? '' : 'none';
    track.style.transform = `translate3d(${-index * slideWidth}px,0,0)`;

    const realIndex = (index - slides.length + slides.length) % slides.length;

    track.querySelectorAll('.exp-slide').forEach(s => s.classList.remove('is-active'));
    track.children[index].classList.add('is-active');

    dots.forEach(d => d.classList.remove('is-active'));
    dots[realIndex]?.classList.add('is-active');
    dots.forEach((d, j) => {
  d.classList.toggle('is-active', j === realIndex);
  d.setAttribute('aria-current', j === realIndex ? 'true' : 'false');
});

  }

  function goTo(i) {
    index = i;
    update();
  }

  next.onclick = () => { index++; update(); };
  prev.onclick = () => { index--; update(); };

  track.addEventListener('transitionend', () => {
    if (index >= total - slides.length) {
      index = slides.length;
      update(false);
    }
    if (index < slides.length) {
      index = total - slides.length * 2;
      update(false);
    }
  });

  update(false);
})();
