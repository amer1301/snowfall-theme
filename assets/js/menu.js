/**
 * Mobilmeny – hanterar öppning och stängning av navigationsmeny.
 *
 * Funktionalitet:
 * - Öppnar/stänger menyn via toggle-knapp
 * - Uppdaterar ARIA-attribut (`aria-expanded`) för tillgänglighet
 * - Stänger menyn vid:
 *   - klick på länk i menyn
 *   - klick utanför menyn
 *   - Escape-tangent
 *   - växling till desktop-läge
 *
 * Initieras när DOM:en är laddad och körs endast om nödvändiga element finns.
 */

document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.querySelector(".menu-toggle");
  const menu = document.querySelector(".menu");
  const menuList = document.querySelector(".menu__list");

  if (!toggle || !menu || !menuList) return;

  const openMenu = () => {
    menu.classList.add("menu--open");
    toggle.classList.add("is-open");
    toggle.setAttribute("aria-expanded", "true");
  };

  const closeMenu = () => {
    menu.classList.remove("menu--open");
    toggle.classList.remove("is-open");
    toggle.setAttribute("aria-expanded", "false");
  };

  const isOpen = () => menu.classList.contains("menu--open");

  toggle.addEventListener("click", () => {
    isOpen() ? closeMenu() : openMenu();
  });

  menuList.addEventListener("click", (e) => {
    if (e.target.closest("a")) closeMenu();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeMenu();
  });

  document.addEventListener("click", (e) => {
    if (!isOpen()) return;
    const clickedInside = menu.contains(e.target) || toggle.contains(e.target);
    if (!clickedInside) closeMenu();
  });

  const mq = window.matchMedia("(max-width: 900px)");
  mq.addEventListener?.("change", (e) => {
    if (!e.matches) closeMenu();
  });
});
