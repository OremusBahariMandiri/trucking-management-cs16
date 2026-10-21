/**
 * Master Data — generic page JS
 *
 * Di-load via @vite('resources/js/pages/master-data.js') di setiap
 * halaman index master data (lookup / referensi sederhana).
 *
 * Fungsi generik:
 *  - initMasterDataDelete()  → konfirmasi hapus dengan truckConfirm()
 *  - initLookupTable()       → inisialisasi DataTables via data-lookup-table
 */

// ============================================================
// DELETE — konfirmasi hapus dengan SweetAlert2 truck animation
// ============================================================
function initMasterDataDelete() {
    document.querySelectorAll('.js-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var name  = form.dataset.name || 'data ini';
            var isEn  = (typeof lang !== 'undefined' ? lang : (localStorage.getItem('lang') || 'id')) === 'en';
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
// Tambahkan data-lookup-table pada elemen <table>.
// Gunakan data-no-order="true" agar tidak ada default order.
// Gunakan data-col-targets="[0,7]" untuk custom columnDefs targets
// (default: [0,5]).
// ============================================================
function initLookupTable() {
    var table = document.querySelector('[data-lookup-table]');
    if (!table || !window.$) return;

    var id      = table.id;
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
        responsive: true,
        autoWidth: false,
    });
}

// ============================================================
// INIT — setiap fungsi dibungkus try/catch agar satu error
// tidak menghentikan fungsi lainnya
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    [
        initMasterDataDelete,
        initLookupTable,
        initMobileCardSwitch,
    ].forEach(fn => {
        try { fn(); } catch (e) { console.error(`[master-data.js] ${fn.name}:`, e); }
    });
});

// ============================================================
// MOBILE CARD SWITCH — ResizeObserver pattern (seperti user mgmt)
// Sembunyikan tabel desktop saat overflow, tampilkan card mobile
// ============================================================
function initMobileCardSwitch() {
    var tableCard  = document.querySelector('.veh-table-card');
    var desktopDiv = document.getElementById('lkpDesktopTable');
    var mobileDiv  = document.getElementById('lkpMobileList');

    if (!tableCard || !desktopDiv || !mobileDiv) return;

    function check() {
        var innerTable = desktopDiv.querySelector('table');
        if (!innerTable) return;
        var overflows = innerTable.scrollWidth > tableCard.clientWidth + 2;
        desktopDiv.style.display = overflows ? 'none' : 'block';
        mobileDiv.style.display  = overflows ? 'flex'  : 'none';
    }

    // hide mobile by default first
    mobileDiv.style.display = 'none';

    check();

    if (window.ResizeObserver) {
        new ResizeObserver(check).observe(tableCard);
    } else {
        window.addEventListener('resize', check);
    }
}
