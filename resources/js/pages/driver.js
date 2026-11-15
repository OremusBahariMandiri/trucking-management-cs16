/** placeholder **/
/**
 * Driver — page JS
 *
 * Usage in Blade:
 *   @push('scripts') @vite('resources/js/pages/driver.js') @endpush
 */

// ============================================================
// BOOTSTRAP GUARD — aman dipakai di mana saja
// ============================================================
function bsTab(el)   { return window.bootstrap?.Tab?.getOrCreateInstance(el) ?? null; }
function bsToast(el) { return window.bootstrap?.Toast?.getOrCreateInstance(el) ?? null; }

// ============================================================
// TAB: simpan posisi tab aktif ke sessionStorage
// ============================================================
function initTabMemory(navId = 'driverTabs') {
    const nav = document.getElementById(navId);
    if (!nav) return;

    const key = `drv_tab_${window.location.pathname}`;

    const saved = sessionStorage.getItem(key);
    if (saved) {
        const target = nav.querySelector(`[data-bs-target="${saved}"]`);
        if (target) bsTab(target)?.show();
    }

    nav.addEventListener('shown.bs.tab', e => {
        sessionStorage.setItem(key, e.target.dataset.bsTarget);
    });

    if (location.hash) {
        const t = nav.querySelector(`[data-bs-target="${location.hash}"]`);
        if (t) bsTab(t)?.show();
    }
}

// ============================================================
// PHOTO UPLOAD — preview foto profil
// ============================================================
function initPhotoUpload() {
    const input   = document.getElementById('photoInput');
    const preview = document.getElementById('photoPreviewImg');
    const def     = document.getElementById('photoDefault');
    if (!input || !preview) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            // Pakai style langsung — lebih reliable dari attribute hidden
            preview.style.display = 'block';
            preview.removeAttribute('hidden');
            if (def) {
                def.style.display = 'none';
                def.setAttribute('hidden', '');
            }
        };
        reader.readAsDataURL(file);
    });
}

// ============================================================
// DOMICILE SAME-AS-KTP TOGGLE
// ============================================================
function initDomicileToggle() {
    const checkbox = document.getElementById('domicileSameAsKtp');
    const fields   = document.getElementById('domicileFields');
    if (!checkbox || !fields) return;

    const ktpMap = {
        domicile_address:     'ktp_address',
        domicile_village:     'ktp_village',
        domicile_district:    'ktp_district',
        domicile_city:        'ktp_city',
        domicile_province:    'ktp_province',
        domicile_postal_code: 'ktp_postal_code',
            domicile_rt_rw:        'ktp_rt_rw',
    };

    function syncFromKtp() {
        Object.entries(ktpMap).forEach(([domId, ktpId]) => {
            const domEl = document.getElementById(domId);
            const ktpEl = document.getElementById(ktpId);
            if (domEl && ktpEl) domEl.value = ktpEl.value;
        });
    }

    function toggle(checked) {
        fields.querySelectorAll('input, textarea').forEach(el => {
            el.disabled = checked;
            el.style.opacity = checked ? '.5' : '';
        });
        if (checked) syncFromKtp();
    }

    checkbox.addEventListener('change', () => toggle(checkbox.checked));

    Object.values(ktpMap).forEach(ktpId => {
        const ktpEl = document.getElementById(ktpId);
        if (!ktpEl) return;
        ktpEl.addEventListener('input', () => {
            if (!checkbox.checked) return;
            const domId = Object.keys(ktpMap).find(k => ktpMap[k] === ktpId);
            const domEl = document.getElementById(domId);
            if (domEl) domEl.value = ktpEl.value;
        });
    });

    toggle(checkbox.checked);
}

// ============================================================
// JS STRING TEMPLATES — menghindari ketergantungan <template>
// ============================================================
function buildDocumentRow(idx) {
    return `
    <div class="drv-doc-row drv-doc-new">
        <div class="drv-doc-row-header">
            <span class="drv-doc-row-num">Dokumen</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label form-label-sm">
                    <span data-en="Document Name">Nama Dokumen</span>
                    <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="documents[${idx}][document_name]"
                       class="form-control form-control-sm"
                       required
                       placeholder="SIM, SKCK, dll.">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Number">Nomor</label>
                <input type="text"
                       name="documents[${idx}][document_number]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Issued">Terbit</label>
                <input type="date"
                       name="documents[${idx}][issued_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Expiry">Kadaluarsa</label>
                <input type="date"
                       name="documents[${idx}][expiry_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="Description">Keterangan</label>
                <input type="text"
                       name="documents[${idx}][description]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="File (optional)">File (opsional)</label>
                <input type="file"
                       name="documents[${idx}][file]"
                       class="form-control form-control-sm"
                       accept="image/*,.pdf">
            </div>
        </div>
    </div>`;
}

function buildCertificateRow(idx) {
    return `
    <div class="drv-doc-row drv-doc-new">
        <div class="drv-doc-row-header">
            <span class="drv-doc-row-num">Sertifikat</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-12">
                <label class="form-label form-label-sm">
                    <span data-en="Certificate Name">Nama Sertifikat</span>
                    <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="certificates[${idx}][certificate_name]"
                       class="form-control form-control-sm"
                       required
                       placeholder="SIM B2, Sertifikat Keselamatan, dll.">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Certificate Number">Nomor</label>
                <input type="text"
                       name="certificates[${idx}][certificate_number]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Issuer">Penerbit</label>
                <input type="text"
                       name="certificates[${idx}][issuer]"
                       class="form-control form-control-sm"
                       placeholder="Instansi penerbit">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Issued">Tanggal Terbit</label>
                <input type="date"
                       name="certificates[${idx}][issued_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Expiry">Kadaluarsa</label>
                <input type="date"
                       name="certificates[${idx}][expiry_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-12">
                <label class="form-label form-label-sm" data-en="Description">Keterangan</label>
                <input type="text"
                       name="certificates[${idx}][description]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="File (optional)">File (opsional)</label>
                <input type="file"
                       name="certificates[${idx}][file]"
                       class="form-control form-control-sm"
                       accept="image/*,.pdf">
            </div>
        </div>
    </div>`;
}

// ============================================================
// DYNAMIC ROWS — dokumen & sertifikat (versi string-template)
// ============================================================
function initDynamicRows({ addBtnId, containerId, emptyId, buildRow, labelPrefix }) {
    const addBtn    = document.getElementById(addBtnId);
    const container = document.getElementById(containerId);
    const empty     = document.getElementById(emptyId);

    if (!addBtn) {
        console.warn(`[DynamicRows] Button #${addBtnId} tidak ditemukan.`);
        return;
    }
    if (!container) {
        console.warn(`[DynamicRows] Container #${containerId} tidak ditemukan.`);
        return;
    }

    let rowIndex = 0;

    // Scope pencarian "existing" ke tab-pane yang sama, bukan seluruh dokumen
    function updateEmpty() {
        if (!empty) return;
        const tabPane     = container.closest('.tab-pane') ?? document;
        const hasNew      = container.querySelectorAll('.drv-doc-new').length > 0;
        const hasExisting = tabPane.querySelectorAll('.drv-doc-existing').length > 0;
        empty.style.display = (hasNew || hasExisting) ? 'none' : '';
    }

    function updateNumbers() {
        container.querySelectorAll('.drv-doc-row-num').forEach((el, i) => {
            el.textContent = `${labelPrefix} ${i + 1}`;
        });
    }

    addBtn.addEventListener('click', () => {
        // Buat elemen dari string HTML
        const wrapper = document.createElement('div');
        wrapper.innerHTML = buildRow(rowIndex).trim();
        const row = wrapper.firstElementChild;

        if (!row) {
            console.error('[DynamicRows] Gagal membuat row dari template.');
            return;
        }

        // Pasang event hapus
        row.querySelector('.btn-remove-row')?.addEventListener('click', () => {
            row.remove();
            updateNumbers();
            updateEmpty();
        });

        container.appendChild(row);
        rowIndex++;
        updateNumbers();
        updateEmpty();
    });

    updateEmpty();
}

// ============================================================
// DELETE EXISTING DOC / CERT via AJAX
// ============================================================
function initExistingRowDelete(selector) {
    document.querySelectorAll(selector).forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('Hapus item ini?')) return;

            const url = this.dataset.url;
            const row = this.closest('.drv-doc-row');

            try {
                const resp = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                if (resp.ok) {
                    row.remove();
                    showToast('Item dihapus.', 'success');

                    // Cek empty state dokumen
                    const docEmpty = document.getElementById('documentEmpty');
                    if (docEmpty) {
                        const tabPane = docEmpty.closest('.tab-pane') ?? document;
                        const hasAny  = tabPane.querySelectorAll('.drv-doc-row').length > 0;
                        docEmpty.style.display = hasAny ? 'none' : '';
                    }

                    // Cek empty state sertifikat
                    const certEmpty = document.getElementById('certificateEmpty');
                    if (certEmpty) {
                        const tabPane = certEmpty.closest('.tab-pane') ?? document;
                        const hasAny  = tabPane.querySelectorAll('.drv-doc-row').length > 0;
                        certEmpty.style.display = hasAny ? 'none' : '';
                    }
                } else {
                    showToast('Gagal menghapus.', 'danger');
                }
            } catch (err) {
                console.error('[Delete]', err);
                showToast('Terjadi kesalahan.', 'danger');
            }
        });
    });
}

// ============================================================
// AUTO-GENERATE KODE SUPIR
// ============================================================
function initAutoCode() {
    const btn   = document.getElementById('btnGenerateCode');
    const input = document.getElementById('driver_code');
    if (!btn || !input) return;

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        try {
            const resp = await fetch(btn.dataset.url);
            if (resp.ok) {
                const data = await resp.json();
                input.value = data.code ?? data;
            }
        } catch (err) {
            console.error('[AutoCode]', err);
        } finally {
            btn.disabled = false;
        }
    });
}

// ============================================================
// PHOTO LIGHTBOX — pure JS, tanpa Bootstrap Modal
// ============================================================
function initPhotoModal() {
    const thumb = document.getElementById('driverPhotoThumb');
    if (!thumb) return;

    // Buat elemen lightbox sekali, inject ke body
    let lb = document.getElementById('drvLightbox');
    if (!lb) {
        lb = document.createElement('div');
        lb.id = 'drvLightbox';
        lb.style.cssText = [
            'display:none',
            'position:fixed',
            'inset:0',
            'z-index:99999',
            'background:rgba(0,0,0,.85)',
            'align-items:center',
            'justify-content:center',
            'cursor:zoom-out',
        ].join(';');

        lb.innerHTML = `
          <div id="drvLightboxBox"
               style="position:relative;max-width:92vw;max-height:92vh;cursor:default"
               onclick="event.stopPropagation()">
            <button id="drvLightboxClose"
                    style="position:absolute;top:-14px;right:-14px;
                           width:32px;height:32px;border-radius:50%;
                           border:none;background:#fff;color:#333;
                           font-size:1.1rem;line-height:1;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;
                           box-shadow:0 2px 8px rgba(0,0,0,.4)"
                    aria-label="Tutup">&#x2715;</button>
            <img id="drvLightboxImg"
                 style="display:block;max-width:100%;max-height:88vh;
                        border-radius:6px;box-shadow:0 4px 24px rgba(0,0,0,.6)"
                 src="" alt="">
            <div id="drvLightboxCaption"
                 style="text-align:center;color:#ddd;font-size:.85rem;margin-top:8px"></div>
          </div>`;

        document.body.appendChild(lb);
    }

    const img      = lb.querySelector('#drvLightboxImg');
    const caption  = lb.querySelector('#drvLightboxCaption');
    const closeBtn = lb.querySelector('#drvLightboxClose');

    function openLightbox() {
        const src  = thumb.dataset.src  || thumb.querySelector('img')?.src || thumb.src || '';
        const name = thumb.dataset.name || '';
        if (!src) return;

        img.src             = src;
        caption.textContent = name;
        lb.style.display    = 'flex';
        document.body.style.overflow = 'hidden';

        // Fade-in
        lb.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 180, fill: 'forwards' });
    }

    function closeLightbox() {
        lb.animate([{ opacity: 1 }, { opacity: 0 }], { duration: 140, fill: 'forwards' })
          .finished.then(() => {
              lb.style.display = 'none';
              img.src = '';
              document.body.style.overflow = '';
          });
    }

    thumb.addEventListener('click', openLightbox);
    lb.addEventListener('click', closeLightbox);          // klik backdrop
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeLightbox();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && lb.style.display !== 'none') closeLightbox();
    });
}

// ============================================================
// TOAST HELPER
// ============================================================
function showToast(message, type = 'success') {
    const el = document.getElementById('drvToast');
    if (!el) return;
    el.querySelector('.toast-body').textContent = message;
    el.className = `toast align-items-center text-bg-${type} border-0`;
    bsToast(el)?.show();
}

// ============================================================
// FORM VALIDATION — highlight tab yang ada error
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
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    // Tiap init dibungkus try/catch agar satu error tidak menghentikan yang lain
    const inits = [
        initTabMemory,
        initPhotoUpload,
        initPhotoModal,
        initDomicileToggle,
        initAutoCode,
        highlightErrorTabs,
        () => initDynamicRows({
            addBtnId:    'btnAddDocument',
            containerId: 'documentRows',
            emptyId:     'documentEmpty',
            buildRow:    buildDocumentRow,
            labelPrefix: 'Dokumen',
        }),
        () => initDynamicRows({
            addBtnId:    'btnAddCertificate',
            containerId: 'certificateRows',
            emptyId:     'certificateEmpty',
            buildRow:    buildCertificateRow,
            labelPrefix: 'Sertifikat',
        }),
        () => initExistingRowDelete('.btn-delete-doc'),
        () => initExistingRowDelete('.btn-delete-cert'),
    ];

    inits.forEach(fn => {
        try { fn(); }
        catch (e) { console.error(`[driver.js] ${fn.name || 'anonymous'}:`, e); }
    });
});
// ============================================================
// DRIVER INDEX — DataTables + Mobile Responsive
// ============================================================

/**
 * Sync visibility of mobile cards dengan halaman DataTables saat ini.
 * Cards yg row-indexnya tidak di halaman ini disembunyikan.
 */
function syncMobileCards(api) {
    const pageInfo  = api.page.info();
    const start     = pageInfo.start;
    const end       = pageInfo.end;          // exclusive
    const allCards  = document.querySelectorAll('.lkp-mob-card');

    allCards.forEach(card => {
        const idx = parseInt(card.dataset.row, 10);
        card.style.display = (idx >= start && idx < end) ? '' : 'none';
    });

    // Update pagination info text
    const infoEl = document.querySelector('.lkp-mob-pagination-info');
    if (infoEl) {
        infoEl.textContent = pageInfo.recordsTotal
            ? `Menampilkan ${start + 1}–${Math.min(end, pageInfo.recordsDisplay)} dari ${pageInfo.recordsDisplay} data`
            : 'Tidak ada data';
    }

    // Update page number
    const pageNumEl = document.querySelector('.lkp-mob-page-num');
    if (pageNumEl) {
        pageNumEl.textContent = `${pageInfo.page + 1} / ${pageInfo.pages}`;
    }

    // Enable/disable prev-next
    const prevBtn = document.querySelector('.lkp-mob-prev');
    const nextBtn = document.querySelector('.lkp-mob-next');
    if (prevBtn) prevBtn.disabled = (pageInfo.page === 0);
    if (nextBtn) nextBtn.disabled = (pageInfo.page >= pageInfo.pages - 1);
}

function initMobilePaginationButtons(dtApi) {
    const prevBtn = document.querySelector('.lkp-mob-prev');
    const nextBtn = document.querySelector('.lkp-mob-next');
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            dtApi.page('previous').draw('page');
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            dtApi.page('next').draw('page');
        });
    }
}

function initDriverTable() {
    const tableEl = document.getElementById('driverTable');
    if (!tableEl || !window.$ || !$.fn || !$.fn.DataTable) return;

    const colTargetsRaw = tableEl.dataset.colTargets;
    const ordDisabled   = tableEl.dataset.noOrder;
    const columnDefs    = [];

    if (colTargetsRaw) {
        try {
            const targets = JSON.parse(colTargetsRaw);
            columnDefs.push({ orderable: false, searchable: false, targets });
        } catch (e) { /* ignore */ }
    }

    const dtApi = $(tableEl).DataTable({
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            lengthMenu:    'Tampilkan _MENU_ data',
            search:        '',
            searchPlaceholder: 'Cari supir…',
            info:          'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty:     'Tidak ada data',
            infoFiltered:  '(difilter dari _MAX_ total data)',
            paginate: {
                first:    '«',
                last:     '»',
                next:     '›',
                previous: '‹',
            },
            emptyTable:    'Tidak ada data supir.',
            zeroRecords:   'Data tidak ditemukan.',
        },
        columnDefs,
        drawCallback(settings) {
            const api = this.api();
            // Re-number row pertama (No)
            api.column(0, { page: 'current', search: 'applied' }).nodes().each((td, i) => {
                td.textContent = api.page.info().start + i + 1;
            });
            syncMobileCards(api);
        },
    });

    initMobilePaginationButtons(dtApi);

    // Expand / collapse mobile cards
    document.querySelectorAll('.lkp-mob-header').forEach(header => {
        header.addEventListener('click', () => {
            const card    = header.closest('.lkp-mob-card');
            const details = card.querySelector('.lkp-mob-details');
            const chevron = card.querySelector('.lkp-mob-chevron');
            const open    = details.style.display !== 'none' && details.style.display !== '';
            details.style.display = open ? 'none' : 'block';
            if (chevron) chevron.style.transform = open ? '' : 'rotate(180deg)';
        });
    });

    // Photo preview via show.bs.modal (Bootstrap data-API)
    const photoModal = document.getElementById('drvPhotoModal');
    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function (e) {
            const trigger = e.relatedTarget;
            if (!trigger) return;
            const img  = document.getElementById('drvPhotoModalImg');
            const name = document.getElementById('drvPhotoModalName');
            if (img)  img.src        = trigger.dataset.previewSrc  || '';
            if (name) name.textContent = trigger.dataset.previewName || '';
        });
    }

    // Delete confirmation
    document.querySelectorAll('.drv-delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const name = this.dataset.name || 'supir ini';
            if (confirm(`Hapus data "${name}"? Tindakan ini tidak dapat dibatalkan.`)) {
                this.submit();
            }
        });
    });
}

// Tambahkan initDriverTable ke dalam DOMContentLoaded yang sudah ada
// (fungsi ini dipanggil terpisah lewat event listener sendiri agar aman)
document.addEventListener('DOMContentLoaded', () => {
    try { initDriverTable(); }
    catch (e) { console.error('[driver.js] initDriverTable:', e); }
});
