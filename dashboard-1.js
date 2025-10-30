// dashboard-1.js - cleaned minimal admin script
// Purpose: keep admin page behavior in a separate JS file. Replace Supabase keys to enable live data.

const SUPABASE_URL = '';
const SUPABASE_ANON_KEY = '';

let supabaseClient = null;
if (typeof supabase !== 'undefined' && supabase.createClient) {
  supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
  window.supabase = supabaseClient;
}

function showToast(msg, type = 'info') {
  const el = document.createElement('div');
  el.textContent = msg;
  el.className = `toast ${type}`;
  Object.assign(el.style, {
    position: 'fixed',
    top: '16px',
    right: '16px',
    padding: '8px 12px',
    background: '#333',
    color: '#fff',
    borderRadius: '6px',
    zIndex: 9999
  });
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 2600);
}

function setupNavigation() {
  document.querySelectorAll('.nav-links a').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    const tab = a.dataset.tab;
    if (!tab) return;
    document.querySelectorAll('.nav-links a').forEach(x => x.classList.remove('active'));
    a.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById(tab)?.classList.add('active');
  }));
}

async function loadCounts() {
  const counters = ['sermons', 'fellowships', 'events', 'media', 'youth', 'elders', 'songs'];
  if (window.supabase) {
    try {
      const results = await Promise.all(counters.map(t => supabase.from(t).select('id')));
      counters.forEach((t, i) => {
        const el = document.getElementById(`${t}Count`);
        if (el) el.textContent = results[i].data?.length ?? '0';
      });
      return;
    } catch (err) {
      console.warn('loadCounts supabase error', err);
      // fall through to fallback zeros
    }
  }
  // fallback: set zeros
  counters.forEach(t => {
    const el = document.getElementById(`${t}Count`);
    if (el) el.textContent = '0';
  });
}

document.addEventListener('DOMContentLoaded', () => {
  setupNavigation();
  loadCounts();
});

// Expose small API for pages
window.AdminHelpers = { showToast, setupNavigation, loadCounts };
