// IIFE: kör direkt och läcker inga variabler till global scope
(() => {
  // Hämta wrappern och bilden som ska panoreras
  const hero = document.querySelector(".hero-pan");
  const img = document.querySelector(".hero-pan__img");
  // Om elementen saknas på sidan så avbryt
  if (!hero || !img) return;

  // Räknar ut hur mycket bilden ska flyttas vertikalt baserat på scroll-position
  const update = () => {
    // Position och storlek för hero-sektionen relativt viewport
    const rect = hero.getBoundingClientRect();
    const viewH = window.innerHeight;

    
    // Progress 0..1: 0 när hero precis är utanför/över viewport,
    // 1 när hero har "passerat" så långt att vi betraktar animationen som klar.
    // (Clampa för att undvika värden <0 eller >1.)
    const progress = Math.min(1, Math.max(0, (viewH - rect.top) / rect.height));

    
    // Hur mycket högre bilden är än viewport (om den är högre).
    // Det är den maximala "extra"-höjden som den kan panorera genom.
    const extra = img.getBoundingClientRect().height - viewH;

    
    // Flytta bilden uppåt i takt med progress.
    // Math.max(0, extra) undviker negativa värden om bilden är mindre än viewport.
    const y = -progress * Math.max(0, extra);

    // translate3d triggar GPU-acceleration och ger mjukare scroll
    img.style.transform = `translate3d(0, ${y}px, 0)`;
  };

  // Scroll kan trigga: använder drf requestAnimationFrame för att samordna uppdateringar per frame
  window.addEventListener("scroll", () => requestAnimationFrame(update), { passive: true }); // preventDefault kallas ej pga bättre scroll-prestanda
  window.addEventListener("resize", () => requestAnimationFrame(update)); // På resize ändras viewport-höjd => räkna om
  update(); // Kör en gång direkt så läget är korrekt vid sidladdning
})();

// IIFE: separat banner-variant där en sticky-yta bestämmer scroll-sträckan
(() => {
  // Hämta bannerns wrapper, sticky-container och bilden som ska panoreras
  const banner = document.querySelector(".pan-banner");
  const sticky = document.querySelector(".pan-banner__sticky");
  const img = document.querySelector(".pan-banner__img");
  // Avbryt om något element saknas
  if (!banner || !sticky || !img) return;

  // Maximal förskjutning räknas ut efter mätning
  let maxShift = 0;

  // Mät skillnaden mellan bildens höjd och sticky-ytans höjd
  // => hur mycket av bilden som "kan scrollas fram"
  const measure = () => {
    maxShift = Math.max(0, img.offsetHeight - sticky.offsetHeight);
  };

  // Uppdatera bildens position utifrån hur långt bannern scrollat igenom
  const update = () => {
    // Bannerns position i viewport
    const rect = banner.getBoundingClientRect();
    const viewH = window.innerHeight; // Vid påbyggning...

    // Total "scroll-sträcka" som ska mappas till 0..1:
    // bannerns höjd minus sticky-höjd
    const total = rect.height - sticky.offsetHeight;

    // Hur mycket som scrollats i bannern:
    // -rect.top (när bannern går uppåt blir rect.top negativ).
    // Clampa 0..total så ingen värden hamnar utanför.
    const scrolled = Math.min(Math.max(-rect.top, 0), total);

    // Normaliserad progress 0..1
    const progress = total > 0 ? scrolled / total : 0;

    // Flytta bilden uppåt proportionellt mot progress
    const y = -progress * maxShift;
    img.style.transform = `translate3d(0, ${y}px, 0)`;
  };

  // rAF-wrapper för scroll, uppdatering sker en gång per render-frame
  const onScroll = () => requestAnimationFrame(update);

  window.addEventListener("scroll", onScroll, { passive: true });
  // Mät om vid resize och uppdatera direkt
  window.addEventListener("resize", () => {
    measure();
    update();
  });

  // Om bilden redan är laddad kan vi mäta direkt,
  // annars väntar vi på load så att offsetHeight blir korrekt.
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

