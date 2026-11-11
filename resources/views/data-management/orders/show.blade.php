@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .ord-tab-nav{display:flex;gap:.25rem;flex-wrap:wrap;border-bottom:2px solid var(--border,rgba(0,0,0,.1));margin-bottom:1.5rem}
        .ord-tab-btn{background:none;border:none;padding:.55rem 1rem;font-size:.82rem;font-weight:600;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;border-radius:.5rem .5rem 0 0;transition:all .15s}
        .ord-tab-btn.active{color:var(--primary,#0d6efd);border-bottom-color:var(--primary,#0d6efd);background:rgba(13,110,253,.07)}
        .ord-tab-btn:hover:not(.active){color:var(--text);background:var(--surface-2)}
        .ord-tab-pane{display:none}
        .ord-tab-pane.active{display:block}
        .ord-section-title{font-size:.77rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);padding-bottom:.5rem;border-bottom:1px solid var(--border);margin-bottom:1rem}
        .ord-rows-wrap{display:flex;flex-direction:column;gap:.75rem}
        .ord-row-item{background:var(--surface-2,#f8fafc);border:1px solid var(--border);border-radius:.6rem;padding:1rem}
        .ord-row-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem}
        .ord-row-num{font-size:.72rem;font-weight:700;color:var(--text-muted);letter-spacing:.05em;text-transform:uppercase}
        .ord-cost-summary{background:var(--surface,#fff);border:1px solid var(--border);border-radius:.6rem;padding:1rem 1.25rem;margin-top:.75rem}
        .ord-cost-total-row{display:flex;justify-content:space-between;align-items:center;padding:.25rem 0;font-size:.85rem}
        .ord-cost-total-row.grand{border-top:1px solid var(--border);margin-top:.4rem;padding-top:.6rem;font-size:1rem;font-weight:700;color:var(--primary,#0d6efd)}
        /* read-only style */
        .form-control:disabled,.form-control[readonly],.form-select:disabled{
            background:var(--surface-2,#f8fafc);
            color:var(--text);
            border-color:var(--border);
            opacity:1;
            cursor:default;
        }
        .ord-status-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .75rem;border-radius:2rem;font-size:.8rem;font-weight:600}
        .ord-status-badge.pending{background:rgba(255,193,7,.15);color:#b45309}
        .ord-status-badge.approved{background:rgba(25,135,84,.12);color:#146c43}
        .ord-status-badge.rejected{background:rgba(220,53,69,.12);color:#b02a37}

        /* ── Customer Info Button & Modal ─────────────────────── */
        .cust-info-btn {
            width: 2rem; height: 2rem; border-radius: 50%;
            border: none; background: #0d6efd; color: #fff;
            font-size: .75rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: background .15s, transform .1s;
            box-shadow: 0 2px 6px rgba(13,110,253,.35)
        }
        .cust-info-btn:hover { background: #0b5ed7; transform: scale(1.08) }
        .cust-info-btn:disabled { background: #6c757d; cursor: not-allowed; box-shadow: none; transform: none }

        /* Customer read-only strip */
        .cust-ro-strip {
            background: var(--surface-2, #f8fafc); border: 1px solid var(--border);
            border-radius: .5rem; padding: .75rem 1rem
        }
        .cust-ro-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(180px,1fr)); gap: .5rem .75rem
        }
        .cust-ro-field label {
            font-size: .68rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: .15rem
        }
        .cust-ro-field .cust-ro-val {
            font-size: .85rem; color: var(--text); font-weight: 500; word-break: break-word
        }
        .cust-ro-field .cust-ro-val:empty::after { content: "—"; color: var(--text-muted); font-weight: 400 }

        /* Modal */
        .ci-modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.45);
            z-index: 1055; display: none; align-items: center; justify-content: center; padding: 1rem
        }
        .ci-modal-backdrop.show { display: flex }
        .ci-modal {
            background: var(--surface, #fff); border-radius: .75rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            width: 100%; max-width: 680px; max-height: 90vh;
            display: flex; flex-direction: column; overflow: hidden
        }
        .ci-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
            background: var(--surface-2, #f8fafc)
        }
        .ci-modal-title {
            font-size: .9rem; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: .5rem
        }
        .ci-modal-title .badge-code {
            font-size: .7rem; background: rgba(13,110,253,.12); color: #0d6efd;
            border-radius: 1rem; padding: .15rem .6rem; font-weight: 600
        }
        .ci-close-btn {
            background: none; border: none; color: var(--text-muted);
            font-size: 1.1rem; cursor: pointer; padding: .2rem .4rem;
            border-radius: .3rem; line-height: 1; transition: color .15s
        }
        .ci-close-btn:hover { color: var(--text) }
        .ci-modal-body { flex: 1; overflow-y: auto; padding: 0 }
        .ci-tab-nav {
            display: flex; gap: 0; border-bottom: 1px solid var(--border);
            padding: 0 1.25rem; background: var(--surface-2, #f8fafc)
        }
        .ci-tab-btn {
            background: none; border: none; padding: .55rem .85rem; font-size: .8rem;
            font-weight: 600; color: var(--text-muted); cursor: pointer;
            border-bottom: 2px solid transparent; margin-bottom: -1px; transition: color .15s, border-color .15s
        }
        .ci-tab-btn.active { color: #0d6efd; border-bottom-color: #0d6efd }
        .ci-tab-btn:hover:not(.active) { color: var(--text) }
        .ci-tab-pane { display: none; padding: 1.25rem }
        .ci-tab-pane.active { display: block }
        .ci-info-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr));
            gap: .65rem 1rem; margin-bottom: 1rem
        }
        .ci-info-item label {
            font-size: .68rem; font-weight: 700; letter-spacing: .07em;
            text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: .2rem
        }
        .ci-info-item .ci-val { font-size: .875rem; color: var(--text); word-break: break-word }
        .ci-info-item .ci-val:empty::after { content: "—"; color: var(--text-muted) }
        .ci-info-item.full { grid-column: 1 / -1 }
        .ci-pic-list { display: flex; flex-direction: column; gap: .65rem }
        .ci-pic-card {
            border: 1px solid var(--border); border-radius: .5rem; padding: .75rem 1rem;
            background: var(--surface-2, #f8fafc)
        }
        .ci-pic-card .ci-pic-name { font-weight: 700; font-size: .88rem; color: var(--text) }
        .ci-pic-card .ci-pic-badge {
            font-size: .65rem; background: #0d6efd; color: #fff;
            border-radius: 1rem; padding: .1rem .5rem; margin-left: .4rem; vertical-align: middle
        }
        .ci-pic-card .ci-pic-sub { font-size: .78rem; color: var(--text-muted); margin-top: .25rem }
        .ci-modal-footer { padding: .75rem 1.25rem; border-top: 1px solid var(--border); text-align: right }
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
            <div class="col-12 col-md-12">
                <label class="form-label" data-en="PO Number">Nomor PO</label>
                <input type="text" class="form-control" value="{{ $order->po_number }}" disabled>
            </div>
            <div class="col-12 col-md-12">
                <label class="form-label" data-en="PO Date">Tanggal PO</label>
                <input type="date" class="form-control" value="{{ optional($order->po_date)->toDateString() }}" disabled>
            </div>
            <div class="col-12 col-md-12">
                <label class="form-label" data-en="Customer">Pelanggan</label>
                <div class="d-flex gap-2 align-items-center">
                    <select class="form-select" disabled>
                        <option selected>{{ $order->customer?->name ?? '—' }}</option>
                    </select>
                    @if($order->customer)
                    <button type="button" class="cust-info-btn" id="btnCustInfo"
                        title="Informasi Pelanggan" data-en-title="Customer Information">
                        <i class="fas fa-info"></i>
                    </button>
                    @else
                    <button type="button" class="cust-info-btn" disabled>
                        <i class="fas fa-info"></i>
                    </button>
                    @endif
                </div>
            </div>

            @if($order->pic_name)
            <div class="col-12">
                <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:.5rem;padding:.65rem 1rem;display:flex;gap:1.5rem;flex-wrap:wrap">
                    <div>
                        <div style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:.15rem" data-en="PIC">PIC</div>
                        <div style="font-size:.88rem;font-weight:600;color:var(--text)">{{ $order->pic_name }}</div>
                    </div>
                    @if($order->pic_phone)
                    <div>
                        <div style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:.15rem" data-en="PIC Phone">Telp PIC</div>
                        <div style="font-size:.88rem;color:var(--text)">{{ $order->pic_phone }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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

    {{-- ═══ CUSTOMER DATA (show) ═══ --}}
    @if($order->customer)
    @php
        $c = $order->customer;
        $custDataJson = [
            'id'            => $c->id,
            'code'          => $c->customer_code,
            'name'          => $c->name,
            'type'          => $c->customerType?->name,
            'business_type' => $c->businessType?->name,
            'phone'         => $c->phone,
            'email'         => $c->email,
            'npwp'          => $c->npwp_number,
            'nib'           => $c->nib_number,
            'address'       => $c->address,
            'rt_rw'         => $c->rt_rw,
            'kelurahan'     => $c->kelurahan,
            'kecamatan'     => $c->kecamatan,
            'city'          => $c->city,
            'province'      => $c->province,
            'postal_code'   => $c->postal_code,
            'is_active'     => $c->is_active,
            'reg_date'      => $c->registration_date?->format('d/m/Y'),
            'pics'          => $c->pics->map(fn($p) => [
                'name'       => $p->name,
                'position'   => $p->position,
                'department' => $p->department,
                'phone'      => $p->phone,
                'email'      => $p->email,
                'is_primary' => $p->is_primary,
            ])->toArray(),
        ];
    @endphp
    <script>const CUSTOMER_SHOW = @json($custDataJson);</script>

    {{-- Modal --}}
    <div class="ci-modal-backdrop" id="custInfoModal" role="dialog" aria-modal="true">
        <div class="ci-modal">
            <div class="ci-modal-header">
                <div class="ci-modal-title">
                    <i class="fas fa-building" style="color:#0d6efd"></i>
                    <span>{{ $order->customer->name }}</span>
                    <span class="badge-code">{{ $order->customer->customer_code }}</span>
                </div>
                <button type="button" class="ci-close-btn" id="btnCloseCustModal"><i class="fas fa-times"></i></button>
            </div>
            <div class="ci-modal-body">
                <div class="ci-tab-nav">
                    <button type="button" class="ci-tab-btn active" data-ci-tab="ci-general">
                        <i class="fas fa-info-circle me-1"></i><span data-en="General">Umum</span>
                    </button>
                    <button type="button" class="ci-tab-btn" data-ci-tab="ci-address">
                        <i class="fas fa-map-marker-alt me-1"></i><span data-en="Address">Alamat</span>
                    </button>
                    <button type="button" class="ci-tab-btn" data-ci-tab="ci-pics">
                        <i class="fas fa-users me-1"></i><span data-en="PIC / Contacts">PIC / Kontak</span>
                    </button>
                </div>
                <div class="ci-tab-pane active" id="ci-general">
                    <div class="ci-info-grid">
                        <div class="ci-info-item"><label data-en="Customer Code">Kode Pelanggan</label><div class="ci-val">{{ $c->customer_code }}</div></div>
                        <div class="ci-info-item"><label data-en="Registration Date">Tgl Registrasi</label><div class="ci-val">{{ $c->registration_date?->format('d/m/Y') }}</div></div>
                        <div class="ci-info-item"><label data-en="Customer Type">Tipe Pelanggan</label><div class="ci-val">{{ $c->customerType?->name }}</div></div>
                        <div class="ci-info-item"><label data-en="Business Type">Bidang Usaha</label><div class="ci-val">{{ $c->businessType?->name }}</div></div>
                        <div class="ci-info-item"><label data-en="Phone">Telepon</label><div class="ci-val">{{ $c->phone }}</div></div>
                        <div class="ci-info-item"><label>Email</label><div class="ci-val">{{ $c->email }}</div></div>
                        <div class="ci-info-item"><label>NPWP</label><div class="ci-val">{{ $c->npwp_number }}</div></div>
                        <div class="ci-info-item"><label>NIB</label><div class="ci-val">{{ $c->nib_number }}</div></div>
                        <div class="ci-info-item"><label data-en="Status">Status</label>
                            <div class="ci-val">
                                {{ $c->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ci-tab-pane" id="ci-address">
                    <div class="ci-info-grid">
                        <div class="ci-info-item full"><label data-en="Street Address">Alamat Jalan</label><div class="ci-val">{{ $c->address }}</div></div>
                        <div class="ci-info-item"><label>RT/RW</label><div class="ci-val">{{ $c->rt_rw }}</div></div>
                        <div class="ci-info-item"><label>Kelurahan</label><div class="ci-val">{{ $c->kelurahan }}</div></div>
                        <div class="ci-info-item"><label>Kecamatan</label><div class="ci-val">{{ $c->kecamatan }}</div></div>
                        <div class="ci-info-item"><label data-en="City">Kota</label><div class="ci-val">{{ $c->city }}</div></div>
                        <div class="ci-info-item"><label data-en="Province">Provinsi</label><div class="ci-val">{{ $c->province }}</div></div>
                        <div class="ci-info-item"><label data-en="Postal Code">Kode Pos</label><div class="ci-val">{{ $c->postal_code }}</div></div>
                    </div>
                </div>
                <div class="ci-tab-pane" id="ci-pics">
                    <div class="ci-pic-list">
                        @forelse($c->pics as $pic)
                        <div class="ci-pic-card">
                            <div class="ci-pic-name">
                                {{ $pic->name }}
                                @if($pic->is_primary)
                                    <span class="ci-pic-badge"><span data-en="PRIMARY PIC">PIC UTAMA</span></span>
                                @endif
                            </div>
                            @if($pic->position || $pic->department)
                            <div class="ci-pic-sub">{{ collect([$pic->position, $pic->department])->filter()->implode(' · ') }}</div>
                            @endif
                            @if($pic->phone)<div class="ci-pic-sub"><i class="fas fa-phone fa-xs me-1"></i>{{ $pic->phone }}</div>@endif
                            @if($pic->email)<div class="ci-pic-sub"><i class="fas fa-envelope fa-xs me-1"></i>{{ $pic->email }}</div>@endif
                        </div>
                        @empty
                        <p class="text-muted" style="font-size:.85rem" data-en="No PIC registered">Belum ada PIC terdaftar</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="ci-modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" id="btnCloseCustModal2">
                    <i class="fas fa-times me-1"></i><span data-en="Close">Tutup</span>
                </button>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    // Tab switching (order tabs)
    document.querySelectorAll('.ord-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.ord-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ord-tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // ── Customer Info Modal (show page) ───────────────────────────────────────
    const ciModal = document.getElementById('custInfoModal');
    function closeCustModal() { ciModal?.classList.remove('show'); document.body.style.overflow = ''; }
    document.getElementById('btnCustInfo')?.addEventListener('click', () => {
        if (ciModal) { ciModal.classList.add('show'); document.body.style.overflow = 'hidden'; }
    });
    document.getElementById('btnCloseCustModal')?.addEventListener('click', closeCustModal);
    document.getElementById('btnCloseCustModal2')?.addEventListener('click', closeCustModal);
    ciModal?.addEventListener('click', e => { if (e.target === ciModal) closeCustModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && ciModal?.classList.contains('show')) closeCustModal(); });
    // Tab switching inside customer modal
    document.querySelectorAll('.ci-tab-btn').forEach(btn => btn.addEventListener('click', () => {
        document.querySelectorAll('.ci-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.ci-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.ciTab).classList.add('active');
    }));

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
