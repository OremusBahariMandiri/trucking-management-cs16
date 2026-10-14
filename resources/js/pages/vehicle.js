/**
 * Vehicle — page JS
 *
 * Cara pakai di Blade:
 *   @push('scripts') <script type="module" src="{{ asset('js/pages/vehicle.js') }}"></script> @endpush
 * atau tambahkan di vite.config.js sebagai entry point tambahan.
 */

// ============================================================
// TAB: simpan posisi tab aktif ke sessionStorage
// ============================================================
function initTabMemory(navId = 'vehicleTabs') {
    const nav = document.getElementById(navId);
    if (!nav) return;

    const key = `veh_tab_${window.location.pathname}`;

    // Restore tab dari sesi sebelumnya
    const saved = sessionStorage.getItem(key);
    if (saved) {
        const target = nav.querySelector(`[data-bs-target="${saved}"]`);
        if (target) bootstrap.Tab.getOrCreateInstance(target).show();
    }

    // Simpan saat berubah
    nav.addEventListener('shown.bs.tab', e => {
        sessionStorage.setItem(key, e.target.dataset.bsTarget);
    });

    // Jika ada hash di URL, aktifkan tab yang sesuai
    if (location.hash) {
        const t = nav.querySelector(`[data-bs-target="${location.hash}"]`);
        if (t) bootstrap.Tab.getOrCreateInstance(t).show();
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
// PHOTO UPLOAD — drag & drop + preview
// ============================================================
function initPhotoUpload() {
    const zone = document.getElementById('photoDropZone');
    const input = document.getElementById('photoFileInput');
    const grid = document.getElementById('photoPreviewGrid');
    const template = document.getElementById('photoItemTemplate');
    if (!zone || !input || !grid || !template) return;

    let fileIndex = grid.querySelectorAll('.photo-item').length; // existing photos offset

    // Click zone → trigger file input
    zone.addEventListener('click', e => {
        if (e.target === input) return;   // cegah loop
        input.click();
    });

    // Drag over
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });

    input.addEventListener('change', () => { handleFiles(input.files); input.value = ''; });

    function handleFiles(files) {
        [...files].forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = e => addPreview(file, e.target.result, fileIndex++);
            reader.readAsDataURL(file);
        });
    }

    function addPreview(file, src, idx) {
        const clone = template.content.cloneNode(true);
        const item = clone.querySelector('.photo-item');

        // Set image
        item.querySelector('img').src = src;

        // Hidden file input
        const fileInput = item.querySelector('input[type="file"]');
        fileInput.name = `photos[${idx}][file]`;

        // DataTransfer trick: assign file to input
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;

        // Caption input name
        item.querySelector('.caption-input').name = `photos[${idx}][caption]`;

        // Category select name
        item.querySelector('.category-select').name = `photos[${idx}][category]`;

        // Remove button
        item.querySelector('.btn-remove-photo').addEventListener('click', () => item.remove());

        grid.appendChild(clone);
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

            const url = this.dataset.photoDelete;
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
                badge.className = 'photo-badge-primary';
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
    const btn = document.getElementById('btnGenerateCode');
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
    bootstrap.Toast.getOrCreateInstance(el).show();
}

// ============================================================
// FORM VALIDATION FEEDBACK — highlight tab yang ada error
// ============================================================
function highlightErrorTabs() {
    document.querySelectorAll('.tab-pane').forEach(pane => {
        const hasError = pane.querySelector('.is-invalid');
        if (!hasError) return;

        const id = pane.id;
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
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    initTabMemory();
    initBrandModelSelect();
    initPhotoUpload();
    initExistingPhotoActions();
    initAutoCode();
    highlightErrorTabs();
});