/**
 * User Management — bilingual (ID / EN) switcher
 *
 * Mengikuti pola yang sama dengan vehicle.js:
 * - Baca preferensi bahasa dari localStorage key 'lang' ('id' atau 'en')
 * - Apply ke semua elemen yang punya atribut [data-en]
 * - Placeholder input juga di-support via [data-placeholder-en]
 * - Toggle otomatis dari tombol #langToggle (sama dengan halaman lain)
 */

(function () {
    const STORAGE_KEY = 'lang';
    const DEFAULT     = 'id';

    function getLang() {
        return localStorage.getItem(STORAGE_KEY) || DEFAULT;
    }

    function applyLang(lang) {
        // Teks biasa
        document.querySelectorAll('[data-en]').forEach(el => {
            const en = el.getAttribute('data-en');
            if (!el._originalText) {
                el._originalText = el.textContent.trim();
            }
            el.textContent = lang === 'en' ? en : el._originalText;
        });

        // Placeholder input/textarea
        document.querySelectorAll('[data-placeholder-en]').forEach(el => {
            const en = el.getAttribute('data-placeholder-en');
            if (!el._originalPlaceholder) {
                el._originalPlaceholder = el.placeholder;
            }
            el.placeholder = lang === 'en' ? en : el._originalPlaceholder;
        });

        // Update tombol toggle jika ada
        const btn = document.getElementById('langToggle');
        if (btn) {
            btn.textContent = lang === 'en' ? 'ID' : 'EN';
            btn.title       = lang === 'en' ? 'Switch to Indonesian' : 'Switch to English';
        }
    }

    // Terapkan saat halaman load
    applyLang(getLang());

    // Dengarkan klik tombol toggle (tombol yang sama di semua halaman)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('#langToggle');
        if (!btn) return;
        const next = getLang() === 'en' ? 'id' : 'en';
        localStorage.setItem(STORAGE_KEY, next);
        applyLang(next);
    });

    // Sinkronisasi antar-tab (kalau user ganti bahasa di tab lain)
    window.addEventListener('storage', function (e) {
        if (e.key === STORAGE_KEY) {
            applyLang(e.newValue || DEFAULT);
        }
    });
})();