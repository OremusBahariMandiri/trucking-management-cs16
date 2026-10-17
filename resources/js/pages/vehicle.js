/**
 * Vehicle — page JS
 *
 * Di-load via @vite('resources/js/pages/vehicle.js')
 *
 * Bootstrap diambil dari window.bootstrap (CDN).
 * Tidak pakai static/dynamic import agar tidak ada top-level await
 * yang menyebabkan race condition dengan DOMContentLoaded.
 */

// ── Bootstrap resolver (runtime, bukan import) ────────────────────────────────
// CDN Bootstrap meng-set window.bootstrap. Dibaca saat dibutuhkan.
function getBS() { return window.bootstrap ?? null; }

// ============================================================
// TAB: simpan posisi tab aktif ke sessionStorage
// ============================================================
function initTabMemory(navId = 'vehicleTabs') {
    const bs  = getBS();
    if (!bs) return;                           // butuh Bootstrap Tab API
    const nav = document.getElementById(navId);
    if (!nav) return;

    const key = `veh_tab_${window.location.pathname}`;

    // Restore tab dari sesi sebelumnya
    const saved = sessionStorage.getItem(key);
    if (saved) {
        const target = nav.querySelector(`[data-bs-target="${saved}"]`);
        if (target) bs.Tab.getOrCreateInstance(target).show();
    }

    // Simpan saat berubah
    nav.addEventListener('shown.bs.tab', e => {
        sessionStorage.setItem(key, e.target.dataset.bsTarget);
    });

    // Jika ada hash di URL, aktifkan tab yang sesuai
    if (location.hash) {
        const t = nav.querySelector(`[data-bs-target="${location.hash}"]`);
        if (t) bs.Tab.getOrCreateInstance(t).show();
    }
}

// ============================================================
// DEPENDENT SELECT: brand → model
// ============================================================
function initBrandModelSelect() {
    const brandSel = document.getElementById('vehicle_brand_id');
    const modelSel = document.getElementById('vehicle_model_id');
    if (!brandSel || !modelSel) return;

    // Data di-embed dari Blade via data-models attribute
    const allModels = JSON.parse(modelSel.dataset.models || '[]');

    function filterModels(brandId) {
        const current = modelSel.value;
        modelSel.innerHTML = '<option value="">-- Pilih Model --</option>';
        allModels
            .filter(m => !brandId || String(m.brand_id) === String(brandId))
            .forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.text = m.name;
                opt.selected = String(m.id) === current;
                modelSel.appendChild(opt);
            });
    }

    brandSel.addEventListener('change', () => filterModels(brandSel.value));
    filterModels(brandSel.value); // init
}

// ============================================================
// PHOTO UPLOAD — pola sama persis seperti tab dokumen:
//   • Slot 0 sudah hardcoded di blade (selalu ada di DOM)
//   • Slot tambahan dibuat JS, pakai real <input type="file">
//   • Tidak ada DataTransfer — file submit natural oleh browser
// ============================================================
function initPhotoUpload() {
    const grid   = document.getElementById('photoPreviewGrid');
    const tmpl   = document.getElementById('photoItemTemplate');
    const btnAdd = document.getElementById('btnAddPhotoSlot');
    if (!grid || !tmpl || !btnAdd) return;

    // Slot 0 sudah ada (index 0).
    // Slot tambahan mulai dari index 1 (gunakan waktu ms agar unik & tidak tabrakan).
    // Memakai timestamp memastikan index tidak pernah duplikat meski user hapus-tambah.
    let nextIdx = 1;

    // ── Pasang preview listener pada slot 0 yang sudah ada di DOM ────────────
    const slot0 = grid.querySelector('[data-slot-index="0"]');
    if (slot0) {
        attachFilePreview(slot0);
        // Tombol clear slot 0 (bukan hapus — slot 0 tidak boleh dihapus)
        slot0.querySelector('.btn-clear-photo')?.addEventListener('click', () => {
            const fi = slot0.querySelector('.photo-file-input');
            if (fi) fi.value = '';
            const placeholder = slot0.querySelector('.photo-thumb-placeholder');
            const img         = slot0.querySelector('.photo-thumb-img');
            const info        = slot0.querySelector('.photo-file-info');
            if (placeholder) placeholder.style.display = '';
            if (img) { img.src = ''; img.style.display = 'none'; }
            if (info) info.classList.add('d-none');
        });
    }

    // ── Tambah Foto Lagi button ───────────────────────────────────────────────
    btnAdd.addEventListener('click', () => {
        const idx  = nextIdx++;
        const frag = tmpl.content.cloneNode(true);
        const card = frag.querySelector('.photo-upload-card');
        if (!card) return;

        // Badge nomor (1-based, hitung semua slot termasuk slot 0)
        const totalBefore = grid.querySelectorAll('.photo-upload-card').length;
        const badge = card.querySelector('.photo-index-badge');
        if (badge) badge.textContent = `#${totalBefore + 1}`;

        // Set name attributes — ini yang membuat file ter-submit ke server
        const fileInput = card.querySelector('.photo-file-input');
        const catSel    = card.querySelector('.category-select');
        const capInput  = card.querySelector('.caption-input');
        const radioBtn  = card.querySelector('.primary-check');

        if (fileInput) fileInput.name = `photos[${idx}][file]`;
        if (catSel)    catSel.name    = `photos[${idx}][category]`;
        if (capInput)  capInput.name  = `photos[${idx}][caption]`;
        if (radioBtn)  radioBtn.value = idx;

        // Preview listener
        attachFilePreview(card);

        // Hapus slot tambahan ini
        card.querySelector('.btn-remove-photo')?.addEventListener('click', () => {
            card.remove();
            renumberBadges();
        });

        grid.appendChild(frag);
    });

    // ── Helper: pasang FileReader preview pada sebuah card ────────────────────
    function attachFilePreview(card) {
        const fileInput = card.querySelector('.photo-file-input');
        if (!fileInput) return;

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;

            // Preview gambar via FileReader (tidak menyentuh fileInput.files)
            const reader = new FileReader();
            reader.onload = e => {
                const placeholder = card.querySelector('.photo-thumb-placeholder');
                const img         = card.querySelector('.photo-thumb-img');
                if (placeholder) placeholder.style.display = 'none';
                if (img) { img.src = e.target.result; img.style.display = 'block'; }
            };
            reader.onerror = () => console.error('[vehicle.js] FileReader error:', file.name);
            reader.readAsDataURL(file);

            // Info nama + ukuran
            const infoEl = card.querySelector('.photo-file-info');
            const nameEl = card.querySelector('.photo-file-name');
            const sizeEl = card.querySelector('.photo-file-size');
            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) {
                const kb = file.size / 1024;
                sizeEl.textContent = kb >= 1024
                    ? `${(kb / 1024).toFixed(1)} MB`
                    : `${kb.toFixed(0)} KB`;
            }
            if (infoEl) infoEl.classList.remove('d-none');
        });
    }

    // Perbarui badge #N setelah hapus slot tambahan
    function renumberBadges() {
        grid.querySelectorAll('.photo-upload-card').forEach((card, i) => {
            const badge = card.querySelector('.photo-index-badge');
            if (badge) badge.textContent = `#${i + 1}`;
        });
    }
}

// ============================================================
// EXISTING PHOTO ACTIONS (show/edit page)
// ============================================================
function initExistingPhotoActions() {
    // Delete photo via AJAX
    document.querySelectorAll('[data-photo-delete]').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('Hapus foto ini?')) return;

            const url  = this.dataset.photoDelete;
            const card = this.closest('.photo-item');

            const resp = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            if (resp.ok) {
                card.remove();
                showToast('Foto dihapus.', 'success');
            } else {
                showToast('Gagal menghapus foto.', 'danger');
            }
        });
    });

    // Set primary photo
    document.querySelectorAll('[data-photo-primary]').forEach(btn => {
        btn.addEventListener('click', async function () {
            const url = this.dataset.photoPrimary;

            const resp = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            if (resp.ok) {
                document.querySelectorAll('.photo-badge-primary').forEach(b => b.remove());
                const badge = document.createElement('span');
                badge.className   = 'photo-badge-primary';
                badge.textContent = 'UTAMA';
                this.closest('.photo-item').prepend(badge);
                showToast('Foto utama diubah.', 'success');
            }
        });
    });
}

// ============================================================
// AUTO-GENERATE KODE KENDARAAN
// ============================================================
function initAutoCode() {
    const btn   = document.getElementById('btnGenerateCode');
    const input = document.getElementById('vehicle_code');
    if (!btn || !input) return;

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        const resp = await fetch(btn.dataset.url);
        if (resp.ok) {
            const data = await resp.json();
            input.value = data.code ?? data;
        }
        btn.disabled = false;
    });
}

// ============================================================
// TOAST HELPER
// ============================================================
function showToast(message, type = 'success') {
    const el = document.getElementById('vehToast');
    if (!el) return;
    el.querySelector('.toast-body').textContent = message;
    el.className = `toast align-items-center text-bg-${type} border-0`;
    getBS()?.Toast?.getOrCreateInstance(el)?.show();
}

// ============================================================
// FORM VALIDATION FEEDBACK — highlight tab yang ada error
// ============================================================
function highlightErrorTabs() {
    document.querySelectorAll('.tab-pane').forEach(pane => {
        const hasError = pane.querySelector('.is-invalid');
        if (!hasError) return;

        const id  = pane.id;
        const nav = document.querySelector(`[data-bs-target="#${id}"]`);
        if (nav) {
            nav.classList.add('text-danger');
            let badge = nav.querySelector('.tab-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'tab-badge';
                nav.appendChild(badge);
            }
            badge.textContent = pane.querySelectorAll('.is-invalid').length;
        }
    });
}

// ============================================================
// INIT — setiap fungsi dibungkus try/catch agar satu error
// tidak menghentikan fungsi lainnya
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    [
        initTabMemory,
        initBrandModelSelect,
        initPhotoUpload,
        initExistingPhotoActions,
        initAutoCode,
        highlightErrorTabs,
    ].forEach(fn => {
        try { fn(); } catch (e) { console.error(`[vehicle.js] ${fn.name}:`, e); }
    });
});