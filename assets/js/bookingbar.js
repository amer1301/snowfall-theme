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
    embed: '1',
    'tribe-bar-date': startDate,
    'tribe_event_category': cat, // valfritt, men bra om du vill
    'bb_event_id': String(eventId || ''),
  });

  iframe.src = `/events/month/?${params.toString()}`;
  return true;
}

/* -----------------------------
 * State / validation
 * ----------------------------- */
function getFormState(form) {
  const cat = qs(form, 'input[name="bb_cat"]')?.value || '';
  const activity = qs(form, '[data-activity-select]')?.value || ''; // eventId
  const start = qs(form, 'input[name="start_date"]')?.value || '';
  const end = qs(form, 'input[name="end_date"]')?.value || '';
  return { cat, activity, start, end };
}

function isValidDateRange(start, end) {
  if (!start || !end) return false;
  return end >= start; // ISO yyyy-mm-dd kan jämföras som sträng
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

  // set hidden cat
  const hiddenCat = qs(form, 'input[name="bb_cat"]');
  if (hiddenCat) hiddenCat.value = cat;

  // label
  const labelEl = qs(bar, '[data-select-label]');
  if (labelEl) labelEl.textContent = (cat === 'boende') ? 'Logi' : 'Aktivitet';

  // filter select options by data-cats
  const sel = qs(form, 'select[name="bb_event"]');
  if (sel) {
    const options = Array.from(sel.options);

    options.forEach((opt) => {
      if (!opt.value) { opt.hidden = false; return; } // "Välj…"
      const cats = (opt.dataset.cats || '').split(',').filter(Boolean);

      // STRIKT filtrering: måste matcha aktuell kategori
      opt.hidden = !cats.includes(cat);
    });

    // om nuvarande val är dolt -> nollställ
    if (sel.selectedOptions[0] && sel.selectedOptions[0].hidden) {
      sel.value = '';
    }
  }

  clearInlineError(form);
  updateSubmitEnabled(form);

  // Om kategori är okänd (ska inte hända) så göm kalender
  if (cat && !calendarCats.includes(cat)) {
    hideCalendar();
  }
}

function initBookingbar(bar) {
  const form = qs(bar, '[data-bookingbar-form]');
  if (!form) return;

  // Init: filtrera dropdown för default tab
  const cat = qs(form, 'input[name="bb_cat"]')?.value || '';
  setCategoryUI(bar, cat);

  // Startläge: kalender ska vara dold
  hideCalendar();

  // Knappen i sync
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

  // tab UI
  bar.querySelectorAll('[data-tab]').forEach((b) => {
    b.classList.remove('is-active');
    b.setAttribute('aria-selected', 'false');
  });
  tabBtn.classList.add('is-active');
  tabBtn.setAttribute('aria-selected', 'true');

  // Apply category + filter
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
