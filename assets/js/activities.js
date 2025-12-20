function initActivitiesHex() {
  const detail = document.querySelector("#activity-detail");
  if (!detail) return;

  const buttons = document.querySelectorAll(".js-activity-open");
  if (!buttons.length) return;

  const getTemplateById = (id) =>
    document.querySelector(`template.js-activity-data[data-activity-id="${CSS.escape(id)}"]`);

  const openActivity = (id) => {
    const tpl = getTemplateById(id);
    if (!tpl) return;

    detail.innerHTML = "";
    detail.appendChild(tpl.content.cloneNode(true));

    detail.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-activity-id");
      openActivity(id);
    });
  });
}

document.addEventListener("DOMContentLoaded", initActivitiesHex);
