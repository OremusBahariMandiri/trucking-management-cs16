/**
 * Master Data — generic page JS
 *
 * Di-load via @vite('resources/js/pages/master-data.js') di setiap
 * halaman index master data (lookup / referensi sederhana).
 *
 * Responsive desktop↔mobile ditangani MURNI oleh CSS media query
 * (@media max-width:767px) — tidak ada JS ResizeObserver / switch.
 * Tidak ada glitch saat resize.
 */

// ============================================================
// DELETE — konfirmasi hapus dengan SweetAlert2 truck animation
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

    $('#' + id).DataTable({
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
        },
        responsive: false,
        autoWidth: false,
    });
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
