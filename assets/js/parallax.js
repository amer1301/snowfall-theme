/**
 * Pan / parallax-effekter för hero och banner.
 *
 * Funktionalitet:
 * - Skapar vertikal pan/parallax-effekt på bilder vid scroll
 * - Förflyttar bilden proportionellt baserat på scroll-position
 * - Begränsar rörelsen så att bilden aldrig lämnar sin container
 * - Använder `requestAnimationFrame` för mjuk och prestandavänlig uppdatering
 *
 * Hero:
 * - Bilden panoreras när hero-sektionen scrollas in i viewporten
 *
 * Banner:
 * - Sticky-innehåll med bakgrundsbild som panoreras under scroll
 * - Beräknar maximal förflyttning baserat på bild- och containerhöjd
 * - Väntar på att bilden ska vara laddad innan mått tas
 *
 * Effekterna initieras automatiskt om respektive element finns på sidan
 * och körs inkapslade i IIFE:er för att undvika globala variabler.
 */

(() => {
  const hero = document.querySelector(".hero-pan");
  const img = document.querySelector(".hero-pan__img");
  if (!hero || !img) return;

  const update = () => {
    const rect = hero.getBoundingClientRect();
    const viewH = window.innerHeight;
    const progress = Math.min(1, Math.max(0, (viewH - rect.top) / rect.height));
    const extra = img.getBoundingClientRect().height - viewH;
    const y = -progress * Math.max(0, extra);
    img.style.transform = `translate3d(0, ${y}px, 0)`;
  };

  window.addEventListener("scroll", () => requestAnimationFrame(update), { passive: true });
  window.addEventListener("resize", () => requestAnimationFrame(update));
  update();
})();

(() => {
  const banner = document.querySelector(".pan-banner");
  const sticky = document.querySelector(".pan-banner__sticky");
  const img = document.querySelector(".pan-banner__img");
  if (!banner || !sticky || !img) return;

  let maxShift = 0;

  const measure = () => {
    maxShift = Math.max(0, img.offsetHeight - sticky.offsetHeight);
  };

  const update = () => {
    const rect = banner.getBoundingClientRect();
    const viewH = window.innerHeight;
    const total = rect.height - sticky.offsetHeight;
    const scrolled = Math.min(Math.max(-rect.top, 0), total);
    const progress = total > 0 ? scrolled / total : 0;
    const y = -progress * maxShift;
    img.style.transform = `translate3d(0, ${y}px, 0)`;
  };

  const onScroll = () => requestAnimationFrame(update);

  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", () => {
    measure();
    update();
  });

  if (img.complete) {
    measure();
    update();
  } else {
    img.addEventListener("load", () => {
      measure();
      update();
    });
  }
})();

