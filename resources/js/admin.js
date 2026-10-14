/* ── Theme ───────────────────────────────────────────────────────────────────── */
const THEMES = ['light', 'system', 'dark'];
let currentTheme = (() => { try { return localStorage.getItem('theme') || 'system'; } catch { return 'system'; } })();

function applyTheme(t) {
    const root = document.documentElement;
    if (t === 'system') root.removeAttribute('data-theme');
    else root.setAttribute('data-theme', t);
    THEMES.forEach(th => document.getElementById('t-' + th)?.classList.toggle('active', th === t));
    try { localStorage.setItem('theme', t); } catch { }
    currentTheme = t;
}

window.setTheme = applyTheme;
applyTheme(currentTheme);

/* ── Language ────────────────────────────────────────────────────────────────── */
let lang = (() => { try { return localStorage.getItem('lang') || 'id'; } catch { return 'id'; } })();

function applyLang(l) {
    document.querySelectorAll('[data-en]').forEach(el => {
        if (l === 'en') {
            if (!el.dataset.id) el.dataset.id = el.textContent.trim();
            el.textContent = el.dataset.en;
        } else {
            if (el.dataset.id) el.textContent = el.dataset.id;
        }
    });
    document.getElementById('btn-en')?.classList.toggle('active', l === 'en');
    document.getElementById('btn-id')?.classList.toggle('active', l === 'id');
    try { localStorage.setItem('lang', l); } catch { }
    lang = l;
}

window.setLang = applyLang;
if (lang === 'en') applyLang('en');

/* ── Sidebar — satu fungsi untuk desktop & mobile ────────────────────────────── */
const MOBILE_BP = 768;

// State desktop
let minimized = (() => { try { return localStorage.getItem('sb-min') === '1'; } catch { return false; } })();

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('overlay');
    const isMobile = window.innerWidth <= MOBILE_BP;

    if (isMobile) {
        // Mobile: geser sidebar dari kiri (overlay)
        const open = sb.classList.toggle('mobile-open');
        ov?.classList.toggle('show', open);
    } else {
        // Desktop: minimize / expand
        minimized = !minimized;
        sb.classList.toggle('minimized', minimized);
        // Tutup semua submenu saat minimize
        if (minimized) {
            document.querySelectorAll('.n-sub.open').forEach(s => s.classList.remove('open'));
            document.querySelectorAll('.n-arrow.open').forEach(a => a.classList.remove('open'));
        }
        try { localStorage.setItem('sb-min', minimized ? '1' : '0'); } catch { }
    }
}

window.toggleSidebar = toggleSidebar;

// Terapkan state minimize saat load (desktop saja)
if (minimized && window.innerWidth > MOBILE_BP) {
    document.getElementById('sidebar')?.classList.add('minimized');
}

// Saat resize dari mobile ke desktop: bersihkan state mobile
window.addEventListener('resize', () => {
    if (window.innerWidth > MOBILE_BP) {
        const sb = document.getElementById('sidebar');
        const ov = document.getElementById('overlay');
        sb?.classList.remove('mobile-open');
        ov?.classList.remove('show');
        // Terapkan ulang state minimize
        sb?.classList.toggle('minimized', minimized);
    }
});

/* ── Submenu accordion ───────────────────────────────────────────────────────── */
window.toggleSub = function (el) {
    // Jangan buka submenu saat sidebar diminimize
    if (document.getElementById('sidebar')?.classList.contains('minimized')) return;
    const sub = el.nextElementSibling;
    const arrow = el.querySelector('.n-arrow');
    if (!sub) return;
    const open = sub.classList.toggle('open');
    arrow?.classList.toggle('open', open);
};

window.toggleSubGroup = function (el) {
    const body = el.nextElementSibling;
    const arrow = el.querySelector('.sub-arrow');
    if (!body) return;
    const open = body.classList.toggle('open');
    el.classList.toggle('open', open);
    arrow?.classList.toggle('open', open);
};

/* ── User dropdown ───────────────────────────────────────────────────────────── */
window.toggleDropdown = function (e) {
    e.stopPropagation();
    document.getElementById('userDrop')?.classList.toggle('open');
};

document.addEventListener('click', () => {
    document.getElementById('userDrop')?.classList.remove('open');
});