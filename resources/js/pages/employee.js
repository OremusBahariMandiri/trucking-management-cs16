/**
 * Employee — page JS
 *
 * Usage in Blade:
 *   @push('scripts') @vite('resources/js/pages/employee.js') @endpush
 */

// ============================================================
// BOOTSTRAP GUARD
// ============================================================
function bsToast(el) {
    return window.bootstrap?.Toast?.getOrCreateInstance(el) ?? null;
}

// ============================================================
// LANGUAGE TOGGLE — data-en / data-id
// Sinkron dengan tombol toggle bahasa yang sudah ada di layout
// ============================================================
function initLanguage() {
    const toggle = document.getElementById('langToggle');
    if (!toggle) return;

    function applyLang(lang) {
        document.querySelectorAll('[data-en]').forEach(el => {
            if (lang === 'en') {
                el._idText = el._idText ?? el.textContent.trim();
                el.textContent = el.dataset.en;
            } else {
                if (el._idText) el.textContent = el._idText;
            }
        });
        localStorage.setItem('emp_lang', lang);
    }

    const saved = localStorage.getItem('emp_lang') ?? 'id';
    applyLang(saved);

    toggle.addEventListener('click', () => {
        const current = localStorage.getItem('emp_lang') ?? 'id';
        applyLang(current === 'id' ? 'en' : 'id');
    });
}

// ============================================================
// AUTO-GENERATE NRK
// ============================================================
function initAutoNrk() {
    const btn   = document.getElementById('btnGenerateNrk');
    const input = document.getElementById('nrk');
    if (!btn || !input) return;

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        const icon = btn.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-magic');
            icon.classList.add('fa-spinner', 'fa-spin');
        }

        try {
            const resp = await fetch(btn.dataset.url);
            if (resp.ok) {
                const data = await resp.json();
                input.value = data.nrk ?? '';
                input.focus();
            } else {
                showToast('Gagal generate NRK.', 'danger');
            }
        } catch (err) {
            console.error('[AutoNrk]', err);
            showToast('Terjadi kesalahan.', 'danger');
        } finally {
            btn.disabled = false;
            if (icon) {
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-magic');
            }
        }
    });
}

// ============================================================
// FORM VALIDATION — highlight field yang error
// ============================================================
function initFormValidation() {
    const form = document.getElementById('employeeForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Hapus state error saat user mulai mengetik
    form.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.addEventListener('input', () => {
            el.classList.remove('is-invalid');
        });
    });
}

// ============================================================
// SUBMIT BUTTON — disable saat form disubmit
// ============================================================
function initSubmitGuard() {
    const form = document.getElementById('employeeForm');
    const btn  = document.getElementById('btnSubmit');
    if (!form || !btn) return;

    form.addEventListener('submit', function () {
        if (form.checkValidity()) {
            btn.disabled = true;
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-spinner', 'fa-spin');
            }
        }
    });
}

// ============================================================
// TOAST HELPER
// ============================================================
function showToast(message, type = 'success') {
    const el = document.getElementById('empToast');
    if (!el) return;
    el.querySelector('.toast-body').textContent = message;
    el.className = `toast align-items-center text-bg-${type} border-0`;
    bsToast(el)?.show();
}

// ============================================================
// AUTO-DISMISS SUCCESS ALERT
// ============================================================
function initAutoDismissAlert() {
    const alerts = document.querySelectorAll('.alert.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = window.bootstrap?.Alert?.getOrCreateInstance(alert);
            bsAlert?.close();
        }, 4000);
    });
}

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const inits = [
        initLanguage,
        initAutoNrk,
        initFormValidation,
        initSubmitGuard,
        initAutoDismissAlert,
    ];

    inits.forEach(fn => {
        try { fn(); }
        catch (e) { console.error(`[employee.js] ${fn.name || 'anonymous'}:`, e); }
    });
});