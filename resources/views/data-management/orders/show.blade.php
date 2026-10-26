@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .ord-tab-nav{display:flex;gap:.25rem;flex-wrap:wrap;border-bottom:2px solid var(--border,rgba(0,0,0,.1));margin-bottom:1.5rem}
        .ord-tab-btn{background:none;border:none;padding:.55rem 1rem;font-size:.82rem;font-weight:600;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;border-radius:.5rem .5rem 0 0;transition:all .15s}
        .ord-tab-btn.active{color:var(--primary,#0d6efd);border-bottom-color:var(--primary,#0d6efd);background:rgba(13,110,253,.07)}
        .ord-tab-btn:hover:not(.active){color:var(--text);background:var(--surface-alt)}
        .ord-tab-pane{display:none}
        .ord-tab-pane.active{display:block}
        .ord-section-title{font-size:.77rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);padding-bottom:.5rem;border-bottom:1px solid var(--border);margin-bottom:1rem}
        .ord-rows-wrap{display:flex;flex-direction:column;gap:.75rem}
        .ord-row-item{background:var(--surface-alt,#f8f9fa);border:1px solid var(--border);border-radius:.6rem;padding:1rem}
        .ord-row-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem}
        .ord-row-num{font-size:.72rem;font-weight:700;color:var(--text-muted);letter-spacing:.05em;text-transform:uppercase}
        .ord-cost-summary{background:var(--surface-card,#fff);border:1px solid var(--border);border-radius:.6rem;padding:1rem 1.25rem;margin-top:.75rem}
        .ord-cost-total-row{display:flex;justify-content:space-between;align-items:center;padding:.25rem 0;font-size:.85rem}
        .ord-cost-total-row.grand{border-top:1px solid var(--border);margin-top:.4rem;padding-top:.6rem;font-size:1rem;font-weight:700;color:var(--primary,#0d6efd)}
        /* read-only style */
        .form-control:disabled,.form-control[readonly],.form-select:disabled{
            background:var(--surface-alt,#f8f9fa);
            color:var(--text);
            border-color:var(--border);
            opacity:1;
            cursor:default;
        }
        .ord-status-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .75rem;border-radius:2rem;font-size:.8rem;font-weight:600}
        .ord-status-badge.pending{background:rgba(255,193,7,.15);color:#b45309}
        .ord-status-badge.approved{background:rgba(25,135,84,.12);color:#146c43}
        .ord-status-badge.rejected{background:rgba(220,53,69,.12);color:#b02a37}
    </style>
@endpush

@section('content')
<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title" data-en="Order Detail">Detail Pesanan — {{ $order->po_number }}</div>
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('data-management.orders.index') }}" data-en="Orders">Pesanan</a></li>
            <li class="breadcrumb-item active">{{ $order->po_number }}</li>
        </ol></nav>
    </div>
    <div class="d-flex gap-2 align-items-center">
        @can('data-management.orders.edit')
        <a href="{{ route('data-management.orders.edit', $order) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i><span data-en="Edit">Edit</span>
        </a>
        @endcan
        @can('data-management.orders.delete')
        <form method="POST" action="{{ route('data-management.orders.destroy', $order) }}"
              class="js-delete-form" data-name="{{ $order->po_number }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash me-1"></i><span data-en="Delete">Hapus</span>
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="lkp-form-card">
    <div class="lkp-form-body" style="padding-bottom:0">
        <nav class="ord-tab-nav">
            <button type="button" class="ord-tab-btn active" data-tab="t-cust"><i class="fas fa-user me-1"></i><span data-en="A. Customer">A. Pelanggan</span></button>
            <button type="button" class="ord-tab-btn" data-tab="t-ship"><i class="fas fa-truck me-1"></i><span data-en="B. Shipment">B. Pemesanan</span></button>
            <button type="button" class="ord-tab-btn" data-tab="t-cost"><i class="fas fa-receipt me-1"></i><span data-en="C. Cost">C. Biaya</span></button>
            <button type="button" class="ord-tab-btn" data-tab="t-note"><i class="fas fa-sticky-note me-1"></i><span data-en="D. Notes">D. Catatan</span></button>
            <button type="button" class="ord-tab-btn" data-tab="t-appr"><i class="fas fa-check-circle me-1"></i><span data-en="E. Approval">E. Persetujuan</span></button>
        </nav>
    </div>

    {{-- ═══ TAB A: PELANGGAN ═══ --}}
    <div class="lkp-form-body ord-tab-pane active" id="t-cust">
        <div class="ord-section-title" data-en="A. Customer Information">A. Informasi Pelanggan</div>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label" data-en="PO Number">Nomor PO</label>
                <input type="text" class="form-control" value="{{ $order->po_number }}" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" data-en="PO Date">Tanggal PO</label>
                <input type="date" class="form-control" value="{{ optional($order->po_date)->toDateString() }}" disabled>
            </div>
            <div class="col-12 col-md-12">
                <label class="form-label" data-en="Customer">Pelanggan</label>
                <select class="form-select" disabled>
                    <option selected>{{ $order->customer?->name ?? '—' }}</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" data-en="Address">Alamat</label>
                <textarea class="form-control" rows="2" disabled>{{ $order->customer_address }}</textarea>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">PIC</label>
                <input type="text" class="form-control" value="{{ $order->pic_name }}" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" data-en="PIC Phone">Telp PIC</label>
                <input type="text" class="form-control" value="{{ $order->pic_phone }}" disabled>
            </div>
        </div>
    </div>

    {{-- ═══ TAB B: PEMESANAN ANGKUTAN ═══ --}}
    <div class="lkp-form-body ord-tab-pane" id="t-ship">
        <div class="ord-section-title" data-en="B. Shipment Order">B. Pemesanan Angkutan</div>
        <div class="ord-rows-wrap">
            @forelse($order->shipments as $si => $s)
            <div class="ord-row-item">
                <div class="ord-row-header">
                    <span class="ord-row-num" data-en="Shipment #">Angkutan #{{ $si + 1 }}</span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Goods Type">Jenis Barang</label>
                        <select class="form-select form-select-sm" disabled>
                            <option selected>{{ $s->goods_type_display }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Goods Category">Kategori Barang</label>
                        <select class="form-select form-select-sm" disabled>
                            <option selected>{{ $s->goods_category_display }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Packaging Type">Jenis Kemasan</label>
                        <select class="form-select form-select-sm" disabled>
                            <option selected>{{ $s->packaging_type_display }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label" data-en="Vehicle Type">Jenis Kendaraan</label>
                        <select class="form-select form-select-sm" disabled>
                            <option selected>{{ $s->vehicle_type_display }}</option>
                        </select>
                    </div>
                    <div class="col-4 col-md-1">
                        <label class="form-label">Unit</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $s->vehicle_quantity }}" disabled>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Loading Address">Alamat Muat</label>
                        <textarea class="form-control form-control-sm" rows="2" disabled>{{ $s->loading_address }}</textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Unloading Address">Alamat Bongkar</label>
                        <textarea class="form-control form-control-sm" rows="2" disabled>{{ $s->unloading_address }}</textarea>
                    </div>
                    <div class="col-6 col-md-6">
                        <label class="form-label" data-en="Loading Date">Tgl Muat</label>
                        <input type="date" class="form-control form-control-sm" value="{{ optional($s->loading_date)->toDateString() }}" disabled>
                    </div>
                    <div class="col-6 col-md-6">
                        <label class="form-label" data-en="Unloading Date">Tgl Bongkar</label>
                        <input type="date" class="form-control form-control-sm" value="{{ optional($s->unloading_date)->toDateString() }}" disabled>
                    </div>
                    <div class="col-4 col-md-6">
                        <label class="form-label" data-en="Capacity">Kapasitas Kendaraan</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $s->vehicle_capacity }}" disabled>
                    </div>
                    <div class="col-4 col-md-5">
                        <label class="form-label" data-en="Volume">Total Volume Angkut</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $s->total_volume }}" disabled>
                    </div>
                    <div class="col-4 col-md-1">
                        <label class="form-label" data-en="Unit">Satuan Angkutan</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $s->measurement_unit }}" disabled>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted small fst-italic" data-en="No shipment data.">Belum ada data angkutan.</p>
            @endforelse
        </div>
    </div>

    {{-- ═══ TAB C: BIAYA ═══ --}}
    <div class="lkp-form-body ord-tab-pane" id="t-cost">
        <div class="ord-section-title" data-en="C. Transport Cost">C. Rincian Biaya Angkutan</div>
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label" data-en="Payment Method">Metode Pembayaran</label>
                <select class="form-select" disabled>
                    <option selected>{{ $order->payment_method ?? '—' }}</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" data-en="Due Date">Jatuh Tempo</label>
                <input type="date" class="form-control" value="{{ optional($order->payment_due_date)->toDateString() }}" disabled>
            </div>
        </div>
        <div class="ord-rows-wrap">
            @forelse($order->costs as $ci => $c)
            <div class="ord-row-item">
                <div class="ord-row-header">
                    <span class="ord-row-num" data-en="Cost Line #">Biaya #{{ $ci + 1 }}</span>
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Description">Uraian</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $c->cost_description }}" disabled>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Qty</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $c->cost_qty }}" disabled>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label" data-en="Unit Price">Harga Satuan</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $c->cost_unit_price }}" disabled>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">PPN %</label>
                        <input type="number" class="form-control form-control-sm" value="{{ $c->ppn_percent }}" disabled>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label" data-en="Total">Total</label>
                        <input type="text" class="form-control form-control-sm" value="Rp {{ number_format($c->total_amount, 0, ',', '.') }}" disabled>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted small fst-italic" data-en="No cost data.">Belum ada data biaya.</p>
            @endforelse
        </div>
        @if($order->costs->count())
        @php
            $subTotal = $order->costs->sum('cost_subtotal');
            $ppnTotal = $order->costs->sum('ppn_amount');
            $grandTotal = $order->costs->sum('total_amount');
        @endphp
        <div class="ord-cost-summary">
            <div class="ord-cost-total-row"><span data-en="Subtotal">Subtotal</span><span>Rp {{ number_format($subTotal, 0, ',', '.') }}</span></div>
            <div class="ord-cost-total-row"><span>PPN</span><span>Rp {{ number_format($ppnTotal, 0, ',', '.') }}</span></div>
            <div class="ord-cost-total-row grand"><span data-en="Grand Total">Total</span><span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span></div>
        </div>
        @endif
    </div>

    {{-- ═══ TAB D: CATATAN ═══ --}}
    <div class="lkp-form-body ord-tab-pane" id="t-note">
        <div class="ord-section-title" data-en="D. Notes">D. Catatan Khusus</div>
        <textarea class="form-control" rows="6" disabled>{{ $order->special_notes }}</textarea>
    </div>

    {{-- ═══ TAB E: PERSETUJUAN ═══ --}}
    <div class="lkp-form-body ord-tab-pane" id="t-appr">
        <div class="ord-section-title" data-en="E. Approval">E. Persetujuan</div>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label" data-en="Submission Date">Tanggal Pengajuan</label>
                <input type="date" class="form-control" value="{{ optional($order->submission_date)->toDateString() }}" disabled>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" data-en="Status">Status</label>
                <div class="pt-1">
                    @php
                        $statusMap = [
                            'pending'  => ['label' => 'Menunggu',  'en' => 'Pending',  'cls' => 'pending'],
                            'approved' => ['label' => 'Disetujui', 'en' => 'Approved', 'cls' => 'approved'],
                            'rejected' => ['label' => 'Ditolak',   'en' => 'Rejected', 'cls' => 'rejected'],
                        ];
                        $st = $statusMap[$order->approval_status] ?? ['label' => $order->approval_status, 'en' => $order->approval_status, 'cls' => 'pending'];
                    @endphp
                    <span class="ord-status-badge {{ $st['cls'] }}" data-en="{{ $st['en'] }}">
                        <i class="fas fa-circle" style="font-size:.45rem;vertical-align:middle"></i>
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" data-en="Approval Date">Tanggal Persetujuan</label>
                <input type="date" class="form-control" value="{{ optional($order->approval_date)->toDateString() }}" disabled>
            </div>
            <div class="col-12">
                <label class="form-label" data-en="Approval Notes">Catatan</label>
                <textarea class="form-control" rows="3" disabled>{{ $order->approval_notes }}</textarea>
            </div>
        </div>
    </div>

    <div class="lkp-form-actions">
        <a href="{{ route('data-management.orders.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i><span data-en="Back">Kembali</span>
        </a>
        @can('data-management.orders.edit')
        <a href="{{ route('data-management.orders.edit', $order) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i><span data-en="Edit">Edit Pesanan</span>
        </a>
        @endcan
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.ord-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.ord-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ord-tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // Delete confirmation
    document.querySelectorAll('.js-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var name = form.dataset.name || 'pesanan ini';
            var isEn = (localStorage.getItem('lang') || 'id') === 'en';
            truckConfirm({
                text: isEn
                    ? 'Delete order <strong>' + name + '</strong>? This action cannot be undone.'
                    : 'Hapus pesanan <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush
