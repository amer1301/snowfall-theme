function initActivitiesHex() {
  const detail = document.querySelector("#activity-detail");
  if (!detail) return;

  // Viktigt: gör den tom vid start
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

    // rendera innehåll
    detail.innerHTML = "";
    detail.appendChild(tpl.content.cloneNode(true));

    // valfritt: markera aktiv knapp
    setActiveButton(clickedBtn);

    // scrolla till detaljen
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
