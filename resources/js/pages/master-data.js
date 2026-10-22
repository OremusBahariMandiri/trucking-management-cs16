/**
 * Master Data — generic page JS
 * Responsive desktop↔mobile: CSS @media (max-width:767px) — no JS switch.
 */

// ============================================================
// DELETE — konfirmasi hapus dengan SweetAlert2
// ============================================================
function initMasterDataDelete() {
    document.querySelectorAll('.js-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var name = form.dataset.name || 'data ini';
            var isEn = (typeof lang !== 'undefined' ? lang : (localStorage.getItem('lang') || 'id')) === 'en';
            truckConfirm({
                text: isEn
                    ? 'Delete <strong>' + name + '</strong>? This action cannot be undone.'
                    : 'Hapus <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
}

// ============================================================
// MOBILE SYNC — sinkronisasi kartu mobile dengan DataTables
// (pagination, search, sort)
// ============================================================
function syncMobileCards(api) {
    // Kumpulkan index baris yang sedang ditampilkan di halaman ini
    var visibleSet = new Set();
    api.rows({ page: 'current' }).nodes().each(function (tr) {
        var idx = tr.getAttribute('data-row');
        if (idx !== null) visibleSet.add(idx);
    });

    // Tampilkan / sembunyikan kartu mobile sesuai index
    document.querySelectorAll('.lkp-mob-card').forEach(function (card) {
        card.style.display = visibleSet.has(card.getAttribute('data-row')) ? '' : 'none';
    });

    // Update info teks
    var pi       = api.page.info();
    var infoEl   = document.querySelector('.lkp-mob-pagination-info');
    var pageEl   = document.querySelector('.lkp-mob-page-num');
    var prevBtn  = document.querySelector('.lkp-mob-prev');
    var nextBtn  = document.querySelector('.lkp-mob-next');

    if (infoEl) {
        if (pi.recordsDisplay === 0) {
            infoEl.textContent = 'Tidak ada data';
        } else {
            infoEl.textContent =
                'Menampilkan ' + (pi.start + 1) + '–' + pi.end +
                ' dari ' + pi.recordsDisplay + ' data';
        }
    }
    if (pageEl)  pageEl.textContent  = (pi.page + 1) + ' / ' + (pi.pages || 1);
    if (prevBtn) prevBtn.disabled    = pi.page === 0;
    if (nextBtn) nextBtn.disabled    = pi.page >= pi.pages - 1;
}

function initMobilePaginationButtons(dtApi) {
    var prevBtn = document.querySelector('.lkp-mob-prev');
    var nextBtn = document.querySelector('.lkp-mob-next');
    if (prevBtn) prevBtn.addEventListener('click', function () {
        dtApi.page('previous').draw('page');
    });
    if (nextBtn) nextBtn.addEventListener('click', function () {
        dtApi.page('next').draw('page');
    });
}

// ============================================================
// DATATABLE — inisialisasi generik via data-lookup-table
// ============================================================
function initLookupTable() {
    var table = document.querySelector('[data-lookup-table]');
    if (!table || !window.$) return;

    var id = table.id;
    if (!id) return;

    var targets = [0, 5];
    if (table.dataset.colTargets) {
        try { targets = JSON.parse(table.dataset.colTargets); } catch (_) {}
    }

    var order = table.dataset.noOrder ? [] : [[2, 'asc']];

    var dt = $('#' + id).DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], ['5', '10', '25', '50', 'Semua']],
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        language: {
            lengthMenu:        'Tampilkan _MENU_ data',
            zeroRecords:       'Tidak ada data ditemukan',
            info:              'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty:         'Menampilkan 0 dari 0 data',
            infoFiltered:      '(difilter dari _MAX_ total data)',
            search:            '',
            searchPlaceholder: 'Cari...',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' },
        },
        columnDefs: [
            { orderable: false, targets: targets },
        ],
        order: order,
        drawCallback: function (settings) {
            var api   = this.api();
            var start = api.context[0]._iDisplayStart;
            api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = start + i + 1;
            });
            syncMobileCards(api);
        },
        responsive: false,
        autoWidth: false,
    });

    initMobilePaginationButtons(dt);
}

// ============================================================
// MOBILE CARD EXPAND — chevron toggle detail
// ============================================================
function initMobileCardExpand() {
    document.querySelectorAll('.lkp-mob-card').forEach(function (card) {
        var header  = card.querySelector('.lkp-mob-header');
        var details = card.querySelector('.lkp-mob-details');
        if (!header || !details) return;

        header.addEventListener('click', function () {
            var open = card.classList.toggle('lkp-mob-card--open');
            details.style.display = open ? 'block' : 'none';
        });
    });
}

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    [initMasterDataDelete, initLookupTable, initMobileCardExpand].forEach(function (fn) {
        try { fn(); } catch (e) { console.error('[master-data.js] ' + fn.name + ':', e); }
    });
});
