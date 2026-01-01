/**
 * Bookingbar – hanterar kategorival, filtrering av aktiviteter och inladdning av bokningskalender.
 *
 * Funktionalitet:
 * - Växlar mellan kategorier (turer, boende, restaurang) via tabs
 * - Filtrerar dropdown-alternativ baserat på vald kategori
 * - Validerar formulär (kategori, aktivitet, datumintervall)
 * - Visar tillgänglighetskalender i iframe vid giltigt val
 * - Visar inline-felmeddelanden med tillgänglighetsstöd (ARIA)
 * - Aktiverar/inaktiverar submit-knapp baserat på formulärstatus
 * - Döljer kalendern automatiskt vid ogiltiga val
 *
 * Kalendern laddas dynamiskt via iframe och scrollas in mjukt vid visning.
 * Scriptet stödjer flera bookingbars på samma sida.
 */

const calendarCats = ['turer', 'boende', 'restaurang'];

function qs(root, sel) {
  return root.querySelector(sel);
}

/* -----------------------------
 * Inline error
 * ----------------------------- */
function ensureInlineErrorEl(form) {
  let el = form.querySelector('[data-bb-error]');
  if (el) return el;

  el = document.createElement('p');
  el.setAttribute('data-bb-error', '1');
  el.setAttribute('role', 'status');
  el.setAttribute('aria-live', 'polite');
  el.style.marginTop = '10px';
  el.style.fontSize = '14px';
  el.style.display = 'none';

  const btn = form.querySelector('button[type="submit"]');
  if (btn && btn.parentNode) {
    btn.insertAdjacentElement('afterend', el);
  } else {
    form.appendChild(el);
  }
  return el;
}

function showInlineError(form, message) {
  const el = ensureInlineErrorEl(form);
  el.textContent = message;
  el.style.display = 'block';
  form.classList.add('has-error');
}

function clearInlineError(form) {
  const el = form.querySelector('[data-bb-error]');
  if (el) {
    el.textContent = '';
    el.style.display = 'none';
  }
  form.classList.remove('has-error');
}

/* -----------------------------
 * Calendar helpers
 * ----------------------------- */
function hideCalendar() {
  const calWrap = document.querySelector('#booking-calendar');
  const iframe = document.querySelector('#booking-calendar-iframe');
  if (!calWrap) return;

  calWrap.classList.add('is-hidden');
  calWrap.setAttribute('aria-hidden', 'true');
  if (iframe) iframe.removeAttribute('src');
}

/**
 * Laddar kalendern i iframe för valt event (ID) och startmånad.
 * OBS: eventId = värdet från select (ska vara eventets ID)
 */
function showCalendarFor({ startDate, cat, eventId }) {
  const calWrap = document.querySelector('#booking-calendar');
  const iframe = document.querySelector('#booking-calendar-iframe');
  if (!calWrap || !iframe) return false;

  calWrap.classList.remove('is-hidden');
  calWrap.setAttribute('aria-hidden', 'false');

  const params = new URLSearchParams({
    snowfall_embed: '1',
    'tribe-bar-date': startDate,
    'tribe_event_category': cat,
    'bb_event_id': String(eventId || ''),
  });

  const base = (window.SNOWFALL_BOOKINGBAR && window.SNOWFALL_BOOKINGBAR.monthUrl) || '/events/month/';
iframe.src = `${base}?${params.toString()}`;
  return true;
}

/* -----------------------------
 * State / validation
 * ----------------------------- */
function getFormState(form) {
  const cat = qs(form, 'input[name="bb_cat"]')?.value || '';
  const activity = qs(form, '[data-activity-select]')?.value || '';
  const start = qs(form, 'input[name="start_date"]')?.value || '';
  const end = qs(form, 'input[name="end_date"]')?.value || '';
  return { cat, activity, start, end };
}

function isValidDateRange(start, end) {
  if (!start || !end) return false;
  return end >= start;
}

function updateSubmitEnabled(form) {
  const btn = qs(form, 'button[type="submit"]');
  if (!btn) return;

  const { cat, activity, start, end } = getFormState(form);

  const ok =
    calendarCats.includes(cat) &&
    !!activity &&
    isValidDateRange(start, end);

  btn.disabled = !ok;
  btn.setAttribute('aria-disabled', String(!ok));
}

/* -----------------------------
 * UI: category tabs + select filtering
 * ----------------------------- */
function setCategoryUI(bar, cat) {
  const form = qs(bar, '[data-bookingbar-form]');
  if (!form) return;

  const hiddenCat = qs(form, 'input[name="bb_cat"]');
  if (hiddenCat) hiddenCat.value = cat;

  const labelEl = qs(bar, '[data-select-label]');
if (labelEl) {
  labelEl.textContent =
    cat === 'boende' ? 'Logi' :
    cat === 'restaurang' ? 'Boka bord' :
    'Aktivitet';
}

  const sel = qs(form, 'select[name="bb_event"]');
  if (sel) {
    const options = Array.from(sel.options);

    options.forEach((opt) => {
      if (!opt.value) { opt.hidden = false; return; }
      const cats = (opt.dataset.cats || '').split(',').filter(Boolean);

      opt.hidden = !cats.includes(cat);
    });

    if (sel.selectedOptions[0] && sel.selectedOptions[0].hidden) {
      sel.value = '';
    }
  }

  clearInlineError(form);
  updateSubmitEnabled(form);

  if (cat && !calendarCats.includes(cat)) {
    hideCalendar();
  }
}

function initBookingbar(bar) {
  const form = qs(bar, '[data-bookingbar-form]');
  if (!form) return;

  const cat = qs(form, 'input[name="bb_cat"]')?.value || '';
  setCategoryUI(bar, cat);

  hideCalendar();

  updateSubmitEnabled(form);
}

/* -----------------------------
 * INIT
 * ----------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.bookingbar').forEach(initBookingbar);
});

/* -----------------------------
 * TABS
 * ----------------------------- */
document.addEventListener('click', (e) => {
  const tabBtn = e.target.closest('.bookingbar [data-tab]');
  if (!tabBtn) return;

  const bar = tabBtn.closest('.bookingbar');
  const form = qs(bar, '[data-bookingbar-form]');
  if (!form) return;

bar.querySelectorAll('[data-tab]').forEach((b) => {
  b.classList.remove('is-active');
  b.setAttribute('aria-pressed', 'false');
});
tabBtn.classList.add('is-active');
tabBtn.setAttribute('aria-pressed', 'true');

  const cat = tabBtn.dataset.cat || '';
  setCategoryUI(bar, cat);
});

/* -----------------------------
 * SUBMIT
 * ----------------------------- */
document.addEventListener('submit', (e) => {
  const form = e.target.closest('[data-bookingbar-form]');
  if (!form) return;

  e.preventDefault();
  clearInlineError(form);

  const { cat, activity: eventId, start, end } = getFormState(form);

  if (!calendarCats.includes(cat)) {
    showInlineError(form, 'Välj en kategori för att se tillgänglighet i kalendern.');
    hideCalendar();
    updateSubmitEnabled(form);
    return;
  }

  if (!eventId) {
    showInlineError(form, 'Välj ett alternativ i listan för att se tillgänglighet.');
    hideCalendar();
    updateSubmitEnabled(form);
    return;
  }

  if (!start || !end) {
    showInlineError(form, 'Välj både från- och till-datum för att se tillgänglighet.');
    hideCalendar();
    updateSubmitEnabled(form);
    return;
  }

  if (!isValidDateRange(start, end)) {
    showInlineError(form, 'Till-datum kan inte vara tidigare än från-datum.');
    hideCalendar();
    updateSubmitEnabled(form);
    return;
  }

  const ok = showCalendarFor({ startDate: start, cat, eventId });
  if (!ok) {
    showInlineError(form, 'Kalendern kunde inte hittas på sidan.');
    return;
  }

  document.querySelector('#booking-calendar')?.scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  });

  updateSubmitEnabled(form);
});

/* -----------------------------
 * CHANGE: göm kalender om datumintervallet blir ogiltigt,
 * samt håll submit-knappen i sync.
 * ----------------------------- */
document.addEventListener('change', (e) => {
  const form = e.target.closest?.('[data-bookingbar-form]');
  if (!form) return;

  clearInlineError(form);

  const { start, end } = getFormState(form);

  if (!isValidDateRange(start, end)) {
    hideCalendar();
  }

  updateSubmitEnabled(form);
});
