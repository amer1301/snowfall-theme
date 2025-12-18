document.addEventListener('click', (e) => {
  const tabBtn = e.target.closest('.bookingbar [data-tab]');
  if (!tabBtn) return;

  const bar = tabBtn.closest('.bookingbar');
  const form = bar.querySelector('[data-bookingbar-form]');
  const hiddenCat = form.querySelector('input[name="bb_cat"]');

  bar.querySelectorAll('[data-tab]').forEach(b => {
    b.classList.remove('is-active');
    b.setAttribute('aria-selected', 'false');
  });
  tabBtn.classList.add('is-active');
  tabBtn.setAttribute('aria-selected', 'true');

  const cat = tabBtn.dataset.cat || '';
  hiddenCat.value = cat;

  const labelEl = bar.querySelector('[data-select-label]');
  if (labelEl) labelEl.textContent = (cat === 'boende') ? 'Logi' : 'Aktivitet';

  const sel = form.querySelector('select[name="bb_event"]');
  const options = Array.from(sel.options);

  options.forEach(opt => {
    if (!opt.value) { opt.hidden = false; return; } // "Välj…"
    const cats = (opt.dataset.cats || '').split(',').filter(Boolean);
    opt.hidden = cat ? !cats.includes(cat) : false;
  });

  if (sel.selectedOptions[0] && sel.selectedOptions[0].hidden) {
    sel.value = '';
  }
});
