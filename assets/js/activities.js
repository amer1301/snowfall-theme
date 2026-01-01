/**
 * Initierar klickbara aktiviteter och visar tillhörande detaljinformation.
 *
 * - Lyssnar på klick på element med `.js-activity-open`
 * - Hämtar rätt <template> baserat på `data-activity-id`
 * - Renderar innehållet i `#activity-detail`
 * - Markerar aktiv knapp
 * - Scrollar mjukt till detaljsektionen
 *
 * Körs automatiskt när DOM:en är redo.
 */

function initActivitiesHex() {
  const detail = document.querySelector("#activity-detail");
  if (!detail) return;

  detail.innerHTML = "";

  const buttons = document.querySelectorAll(".js-activity-open");
  if (!buttons.length) return;

  const getTemplateById = (id) =>
    document.querySelector(
      `template.js-activity-data[data-activity-id="${CSS.escape(id)}"]`
    );

  const setActiveButton = (activeBtn) => {
    buttons.forEach((b) => b.classList.remove("is-active"));
    if (activeBtn) activeBtn.classList.add("is-active");
  };

  const openActivity = (id, clickedBtn) => {
    const tpl = getTemplateById(id);
    if (!tpl) return;

    detail.innerHTML = "";
    detail.appendChild(tpl.content.cloneNode(true));

    setActiveButton(clickedBtn);

    detail.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-activity-id");
      openActivity(id, btn);
    });
  });
}

document.addEventListener("DOMContentLoaded", initActivitiesHex);
