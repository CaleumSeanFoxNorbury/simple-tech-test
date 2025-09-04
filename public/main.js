const form = document.getElementById('search-form');
const resultsEl = document.getElementById('results');
const clientFilter = document.getElementById('clientFilter');

const pager = document.getElementById('pager');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const pageInfo = document.getElementById('pageInfo');

let lastQuery = { postcode: '', name: '', roleKey: 'PrimaryRoleId', roleVal: '' };
let page = 0;
const pageSize = 50;

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  page = 0;
  lastQuery = {
    postcode: document.getElementById('postcode').value.trim(),
    name: document.getElementById('name').value.trim(),
    roleKey: document.getElementById('roleKey').value,
    roleVal: document.getElementById('roleVal').value.trim()
  };
  await runSearch();
});

prevBtn.addEventListener('click', () => { if (page > 0) { page -= 1; runSearch(); } });
nextBtn.addEventListener('click', () => { page += 1; runSearch(); });

clientFilter.addEventListener('input', () => {
  const q = clientFilter.value.trim().toLowerCase();
  for (const card of resultsEl.querySelectorAll('.card')) {
    const name = card.getAttribute('data-name') || '';
    card.style.display = name.includes(q) ? '' : 'none';
  }
});

async function runSearch() {
  showLoading();
  try {
    
  } catch (err) {
    showError('Search failed');
    console.error(err);
  }
}

function renderCards(items) {
  if (items.length === 0) {
    // empty?
    return;
  }

  resultsEl.innerHTML = items.map(toCard).join('');
}

function toCard(o) {
  return `<div>CARD</div>`;
}

function showLoading() {
  resultsEl.innerHTML = `<div class="loading">Loading results...</div>`;
  pager.hidden = true;
}

function showError(msg) {
  resultsEl.innerHTML = `<div class="error">${escapeHtml(msg)}</div>`;
  pager.hidden = true;
}

// is this enough security?
function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
function escapeAttr(s) { return escapeHtml(s).replace(/"/g, '&quot;'); }

// Optional: prefill for a quick manual test
window.addEventListener('DOMContentLoaded', () => {
  document.getElementById('postcode').value = '';
  document.getElementById('name').value = '';
});