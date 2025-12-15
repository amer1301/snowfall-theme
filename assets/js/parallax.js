(() => {
  const hero = document.querySelector(".hero-pan");
  const img = document.querySelector(".hero-pan__img");
  if (!hero || !img) return;

  const update = () => {
    const rect = hero.getBoundingClientRect();
    const viewH = window.innerHeight;

    // 0..1 när du scrollar igenom hela hero-pan (200vh -> 100vh “extra”)
    const progress = Math.min(1, Math.max(0, (viewH - rect.top) / rect.height));

    // Hur mycket extra höjd bilden har (px) jämfört med viewport
    const extra = img.getBoundingClientRect().height - viewH;

    // Flytta bilden uppåt när du scrollar ner => botten syns mer
    const y = -progress * Math.max(0, extra);

    img.style.transform = `translate3d(0, ${y}px, 0)`;
  };

  window.addEventListener("scroll", () => requestAnimationFrame(update), { passive: true });
  window.addEventListener("resize", () => requestAnimationFrame(update));
  update();
})();
