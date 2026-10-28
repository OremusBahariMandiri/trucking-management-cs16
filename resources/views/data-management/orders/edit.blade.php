@extends('layouts.admin')
@section('title', 'Edit Pesanan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .ord-tab-nav {
            display: flex;
            gap: .25rem;
            flex-wrap: wrap;
            border-bottom: 2px solid var(--border, rgba(0, 0, 0, .1));
            margin-bottom: 1.5rem
        }

        .ord-tab-btn {
            background: none;
            border: none;
            padding: .55rem 1rem;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            border-radius: .5rem .5rem 0 0;
            transition: all .15s
        }

        .ord-tab-btn.active {
            color: var(--primary, #0d6efd);
            border-bottom-color: var(--primary, #0d6efd);
            background: rgba(13, 110, 253, .07)
        }

        .ord-tab-btn:hover:not(.active) {
            color: var(--text);
            background: var(--surface-2)
        }

        .ord-tab-pane {
            display: none
        }

        .ord-tab-pane.active {
            display: block
        }

        .ord-section-title {
            font-size: .77rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding-bottom: .5rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1rem
        }

        .ord-rows-wrap {
            display: flex;
            flex-direction: column;
            gap: .75rem
        }

        .ord-row-item {
            background: var(--surface-2, #f8fafc);
            border: 1px solid var(--border);
            border-radius: .6rem;
            padding: 1rem
        }

        .ord-row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: .75rem
        }

        .ord-row-num {
            font-size: .72rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: .05em;
            text-transform: uppercase
        }

        .btn-remove-row {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: .2rem .4rem;
            border-radius: .3rem;
            font-size: .8rem;
            opacity: .7;
            transition: opacity .15s
        }

        .btn-remove-row:hover {
            opacity: 1;
            background: rgba(220, 53, 69, .08)
        }

        .btn-add-row {
            border: 1px dashed var(--border);
            background: transparent;
            color: var(--text-muted);
            border-radius: .5rem;
            padding: .45rem 1rem;
            font-size: .8rem;
            cursor: pointer;
            transition: all .15s;
            width: 100%;
            margin-top: .5rem
        }

        .btn-add-row:hover {
            border-color: var(--primary, #0d6efd);
            color: var(--primary, #0d6efd);
            background: rgba(13, 110, 253, .04)
        }

        .ord-cost-summary {
            background: var(--surface, #fff);
            border: 1px solid var(--border);
            border-radius: .6rem;
            padding: 1rem 1.25rem;
            margin-top: .75rem
        }

        .ord-cost-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .25rem 0;
            font-size: .85rem
        }

        .ord-cost-total-row.grand {
            border-top: 1px solid var(--border);
            margin-top: .4rem;
            padding-top: .6rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary, #0d6efd)
        }

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

        .cust-ro-strip {
            background: var(--surface-2, #f8fafc); border: 1px solid var(--border);
            border-radius: .5rem; padding: .75rem 1rem; display: none
        }
        .cust-ro-strip.visible { display: block }
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
            <div class="veh-title" data-en="Edit Order">Edit Pesanan — {{ $order->po_number }}</div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data-management.orders.index') }}"
                            data-en="Orders">Pesanan</a></li>
                    <li class="breadcrumb-item active">{{ $order->po_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('data-management.orders.update', $order) }}" id="orderForm">
        @csrf @method('PUT')

        <div class="lkp-form-card">
            <div class="lkp-form-body" style="padding-bottom:0">
                <nav class="ord-tab-nav">
                    <button type="button" class="ord-tab-btn active" data-tab="t-cust"><i
                            class="fas fa-user me-1"></i><span data-en="A. Customer">A. Pelanggan</span></button>
                    <button type="button" class="ord-tab-btn" data-tab="t-ship"><i class="fas fa-truck me-1"></i><span
                            data-en="B. Shipment">B. Pemesanan</span></button>
                    <button type="button" class="ord-tab-btn" data-tab="t-cost"><i class="fas fa-receipt me-1"></i><span
                            data-en="C. Cost">C. Biaya</span></button>
                    <button type="button" class="ord-tab-btn" data-tab="t-note"><i
                            class="fas fa-sticky-note me-1"></i><span data-en="D. Notes">D. Catatan</span></button>
                    <button type="button" class="ord-tab-btn" data-tab="t-appr"><i
                            class="fas fa-check-circle me-1"></i><span data-en="E. Approval">E. Persetujuan</span></button>
                </nav>
            </div>

            {{-- TAB A --}}
            <div class="lkp-form-body ord-tab-pane active" id="t-cust">
                <div class="ord-section-title" data-en="A. Customer Information">A. Informasi Pelanggan</div>
                <div class="row g-3">
                    <div class="col-12 col-md-12">
                        <label class="form-label">Nomor PO <span class="text-danger">*</span></label>
                        <input type="text" name="po_number" class="form-control"
                            value="{{ old('po_number', $order->po_number) }}" required>
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="PO Date">Tanggal PO <span class="text-danger">*</span></label>
                        <input type="date" name="po_date" class="form-control"
                            value="{{ old('po_date', optional($order->po_date)->toDateString()) }}" required>
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Customer">Pelanggan</label>
                        <div class="d-flex gap-2 align-items-center">
                            <select name="customer_id" class="form-select" id="customerSelect">
                                <option value="">— Pilih —</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" data-address="{{ $c->address }}"
                                        data-pic-name="{{ $c->primaryPic?->name }}"
                                        data-pic-phone="{{ $c->primaryPic?->phone }}"
                                        {{ old('customer_id', $order->customer_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="cust-info-btn" id="btnCustInfo"
                                title="Informasi Pelanggan" data-en-title="Customer Information"
                                {{ old('customer_id', $order->customer_id) ? '' : 'disabled' }}>
                                <i class="fas fa-info"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Read-only customer info strip --}}
                    {{-- <div class="col-12">
                        <div class="cust-ro-strip {{ old('customer_id', $order->customer_id) ? 'visible' : '' }}" id="custRoStrip">
                            <div class="cust-ro-grid">
                                <div class="cust-ro-field">
                                    <label data-en="Customer Code">Kode Pelanggan</label>
                                    <div class="cust-ro-val" id="ro_code">{{ $order->customer?->customer_code }}</div>
                                </div>
                                <div class="cust-ro-field">
                                    <label data-en="Type">Tipe</label>
                                    <div class="cust-ro-val" id="ro_type">{{ $order->customer?->customerType?->name }}</div>
                                </div>
                                <div class="cust-ro-field">
                                    <label data-en="Business">Bidang Usaha</label>
                                    <div class="cust-ro-val" id="ro_biz">{{ $order->customer?->businessType?->name }}</div>
                                </div>
                                <div class="cust-ro-field">
                                    <label data-en="Phone">Telepon</label>
                                    <div class="cust-ro-val" id="ro_phone">{{ $order->customer?->phone }}</div>
                                </div>
                                <div class="cust-ro-field">
                                    <label data-en="Email">Email</label>
                                    <div class="cust-ro-val" id="ro_email">{{ $order->customer?->email }}</div>
                                </div>
                                <div class="cust-ro-field">
                                    <label>NPWP</label>
                                    <div class="cust-ro-val" id="ro_npwp">{{ $order->customer?->npwp_number }}</div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-12">
                        <label class="form-label" data-en="Address">Alamat</label>
                        <textarea name="customer_address" class="form-control" rows="2" id="custAddr">{{ old('customer_address', $order->customer_address) }}</textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">PIC</label>
                        <input type="text" name="pic_name" class="form-control"
                            value="{{ old('pic_name', $order->pic_name) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="PIC Phone">Telp PIC</label>
                        <input type="text" name="pic_phone" class="form-control"
                            value="{{ old('pic_phone', $order->pic_phone) }}">
                    </div> --}}
                </div>
            </div>

            {{-- TAB B --}}
            <div class="lkp-form-body ord-tab-pane" id="t-ship">
                <div class="ord-section-title" data-en="B. Shipment">B. Pemesanan Angkutan</div>
                <div class="ord-rows-wrap" id="shipmentRows">
                    @foreach ($order->shipments as $si => $s)
                        <div class="ord-row-item" data-row="shipment">
                            <div class="ord-row-header">
                                <span class="ord-row-num" data-en="Shipment #">Angkutan #<span
                                        class="row-num">{{ $si + 1 }}</span></span>
                                <button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" data-en="Goods Type">Jenis Barang</label>
                                    <select name="shipments[{{ $si }}][goods_type_id]"
                                        class="form-select form-select-sm">
                                        <option value="">— Pilih —</option>
                                        @foreach ($goodsTypes as $g)
                                            <option value="{{ $g->id }}"
                                                {{ $s->goods_type_id == $g->id ? 'selected' : '' }}>{{ $g->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" data-en="Goods Category">Kategori Barang</label>
                                    <select name="shipments[{{ $si }}][goods_category_id]"
                                        class="form-select form-select-sm">
                                        <option value="">— Pilih —</option>
                                        @foreach ($goodsCategories as $g)
                                            <option value="{{ $g->id }}"
                                                {{ $s->goods_category_id == $g->id ? 'selected' : '' }}>{{ $g->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" data-en="Packaging">Jenis Kemasan</label>
                                    <select name="shipments[{{ $si }}][packaging_type_id]"
                                        class="form-select form-select-sm">
                                        <option value="">— Pilih —</option>
                                        @foreach ($packagingTypes as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $s->packaging_type_id == $p->id ? 'selected' : '' }}>{{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label" data-en="Vehicle Type">Jenis Kendaraan</label>
                                    <select name="shipments[{{ $si }}][vehicle_type_id]"
                                        class="form-select form-select-sm">
                                        <option value="">— Pilih —</option>
                                        @foreach ($vehicleTypes as $v)
                                            <option value="{{ $v->id }}"
                                                {{ $s->vehicle_type_id == $v->id ? 'selected' : '' }}>{{ $v->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 col-md-1">
                                    <label class="form-label">Unit</label>
                                    <input type="number" name="shipments[{{ $si }}][vehicle_quantity]"
                                        class="form-control form-control-sm" value="{{ $s->vehicle_quantity }}"
                                        min="1">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" data-en="Loading Address">Alamat Muat</label>
                                    <textarea name="shipments[{{ $si }}][loading_address]" class="form-control form-control-sm"
                                        rows="2">{{ $s->loading_address }}</textarea>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" data-en="Unloading Address">Alamat Bongkar</label>
                                    <textarea name="shipments[{{ $si }}][unloading_address]" class="form-control form-control-sm"
                                        rows="2">{{ $s->unloading_address }}</textarea>
                                </div>
                                <div class="col-6 col-md-6">
                                    <label class="form-label" data-en="Loading Date">Tgl Muat</label>
                                    <input type="date" name="shipments[{{ $si }}][loading_date]"
                                        class="form-control form-control-sm"
                                        value="{{ optional($s->loading_date)->toDateString() }}">
                                </div>
                                <div class="col-6 col-md-6">
                                    <label class="form-label" data-en="Unloading Date">Tgl Bongkar</label>
                                    <input type="date" name="shipments[{{ $si }}][unloading_date]"
                                        class="form-control form-control-sm"
                                        value="{{ optional($s->unloading_date)->toDateString() }}">
                                </div>
                                <div class="col-4 col-md-6">
                                    <label class="form-label" data-en="Capacity">Kapasitas Kendaraan</label>
                                    <input type="number" name="shipments[{{ $si }}][vehicle_capacity]"
                                        class="form-control form-control-sm" value="{{ $s->vehicle_capacity }}"
                                        step="0.01">
                                </div>
                                <div class="col-4 col-md-4">
                                    <label class="form-label" data-en="Volume">Total Volume Angkut</label>
                                    <input type="number" name="shipments[{{ $si }}][total_volume]"
                                        class="form-control form-control-sm" value="{{ $s->total_volume }}"
                                        step="0.01">
                                </div>
                                <div class="col-4 col-md-2">
                                    <label class="form-label" data-en="Unit">Satuan Angkutan</label>
                                    <input type="text" name="shipments[{{ $si }}][measurement_unit]"
                                        class="form-control form-control-sm" value="{{ $s->measurement_unit }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-row" id="addShipmentRow">
                    <i class="fas fa-plus me-1"></i><span data-en="Add Line">+ Tambah Baris Angkutan</span>
                </button>
            </div>

            {{-- TAB C --}}
            <div class="lkp-form-body ord-tab-pane" id="t-cost">
                <div class="ord-section-title" data-en="C. Cost">C. Rincian Biaya Angkutan</div>
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Payment Method">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach (['Transfer Bank', 'Tunai', 'Cek/Giro'] as $pm)
                                <option value="{{ $pm }}"
                                    {{ old('payment_method', $order->payment_method) == $pm ? 'selected' : '' }}>
                                    {{ $pm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Due Date">Jatuh Tempo</label>
                        <input type="date" name="payment_due_date" class="form-control"
                            value="{{ old('payment_due_date', optional($order->payment_due_date)->toDateString()) }}">
                    </div>
                </div>
                <div class="ord-rows-wrap" id="costRows">
                    @foreach ($order->costs as $ci => $c)
                        <div class="ord-row-item" data-row="cost" data-subtotal="{{ $c->cost_subtotal }}"
                            data-ppn="{{ $c->ppn_amount }}" data-total="{{ $c->total_amount }}">
                            <div class="ord-row-header">
                                <span class="ord-row-num">Biaya #<span class="row-num">{{ $ci + 1 }}</span></span>
                                <button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" data-en="Description">Uraian</label>
                                    <input type="text" name="costs[{{ $ci }}][cost_description]"
                                        class="form-control form-control-sm" value="{{ $c->cost_description }}">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label">Qty</label>
                                    <input type="number" name="costs[{{ $ci }}][cost_qty]"
                                        class="form-control form-control-sm cost-qty" value="{{ $c->cost_qty }}"
                                        step="0.01" min="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label" data-en="Unit Price">Harga Satuan</label>
                                    <input type="number" name="costs[{{ $ci }}][cost_unit_price]"
                                        class="form-control form-control-sm cost-price" value="{{ $c->cost_unit_price }}"
                                        step="0.01" min="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label">PPN %</label>
                                    <input type="number" name="costs[{{ $ci }}][ppn_percent]"
                                        class="form-control form-control-sm cost-ppn" value="{{ $c->ppn_percent }}"
                                        step="0.01" min="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label" data-en="Total">Total</label>
                                    <input type="text" class="form-control form-control-sm cost-total-display" readonly
                                        value="Rp {{ number_format($c->total_amount, 0, ',', '.') }}" tabindex="-1">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-add-row" id="addCostRow">
                    <i class="fas fa-plus me-1"></i><span data-en="Add Cost Line">+ Tambah Baris Biaya</span>
                </button>
                <div class="ord-cost-summary">
                    <div class="ord-cost-total-row"><span data-en="Subtotal">Subtotal</span><span id="sumSubtotal">Rp
                            0</span></div>
                    <div class="ord-cost-total-row"><span>PPN</span><span id="sumPpn">Rp 0</span></div>
                    <div class="ord-cost-total-row grand"><span data-en="Grand Total">Total</span><span id="sumTotal">Rp
                            0</span></div>
                </div>
            </div>

            {{-- TAB D --}}
            <div class="lkp-form-body ord-tab-pane" id="t-note">
                <div class="ord-section-title" data-en="D. Notes">D. Catatan Khusus</div>
                <textarea name="special_notes" class="form-control" rows="6">{{ old('special_notes', $order->special_notes) }}</textarea>
            </div>

            {{-- TAB E --}}
            <div class="lkp-form-body ord-tab-pane" id="t-appr">
                <div class="ord-section-title" data-en="E. Approval">E. Persetujuan</div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Submission Date">Tanggal Pengajuan</label>
                        <input type="date" name="submission_date" class="form-control"
                            value="{{ old('submission_date', optional($order->submission_date)->toDateString()) }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Status">Status</label>
                        <select name="approval_status" class="form-select">
                            <option value="pending"
                                {{ old('approval_status', $order->approval_status) == 'pending' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="approved"
                                {{ old('approval_status', $order->approval_status) == 'approved' ? 'selected' : '' }}>
                                Disetujui</option>
                            <option value="rejected"
                                {{ old('approval_status', $order->approval_status) == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Approval Date">Tanggal Persetujuan</label>
                        <input type="date" name="approval_date" class="form-control"
                            value="{{ old('approval_date', optional($order->approval_date)->toDateString()) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Notes">Catatan</label>
                        <textarea name="approval_notes" class="form-control" rows="3">{{ old('approval_notes', $order->approval_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="lkp-form-actions">
                <a href="{{ route('data-management.orders.index', $order) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i><span data-en="Cancel">Batal</span>
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i><span data-en="Update">Perbarui Pesanan</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Templates for new rows --}}
    <template id="tmplShipment">
        <div class="ord-row-item" data-row="shipment">
            <div class="ord-row-header">
                <span class="ord-row-num">Angkutan #<span class="row-num"></span></span>
                <button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label class="form-label" data-en="Goods Type">Jenis Barang</label>
                    <select name="shipments[__i__][goods_type_id]" class="form-select form-select-sm">
                        <option value="">— Pilih —</option>
                        @foreach ($goodsTypes as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" data-en="Goods Category">Kategori Barang</label>
                    <select name="shipments[__i__][goods_category_id]" class="form-select form-select-sm">
                        <option value="">— Pilih —</option>
                        @foreach ($goodsCategories as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" data-en="Packaging">Jenis Kemasan</label>
                    <select name="shipments[__i__][packaging_type_id]" class="form-select form-select-sm">
                        <option value="">— Pilih —</option>
                        @foreach ($packagingTypes as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label" data-en="Vehicle Type">Jenis Kendaraan</label>
                    <select name="shipments[__i__][vehicle_type_id]" class="form-select form-select-sm">
                        <option value="">— Pilih —</option>
                        @foreach ($vehicleTypes as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-md-1"><label class="form-label">Unit</label>
                    <input type="number" name="shipments[__i__][vehicle_quantity]" class="form-control form-control-sm"
                        value="1" min="1">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" data-en="Loading Address">Alamat Muat</label>
                    <textarea name="shipments[__i__][loading_address]" class="form-control form-control-sm" rows="2"></textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" data-en="Unloading Address">Alamat Bongkar</label>
                    <textarea name="shipments[__i__][unloading_address]" class="form-control form-control-sm" rows="2"></textarea>
                </div>
                <div class="col-6 col-md-6">
                    <label class="form-label" data-en="Loading Date">Tgl Muat</label>
                    <input type="date" name="shipments[__i__][loading_date]" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-6">
                    <label class="form-label" data-en="Unloading Date">Tgl Bongkar</label>
                    <input type="date" name="shipments[__i__][unloading_date]" class="form-control form-control-sm">
                </div>
                <div class="col-4 col-md-6"><label class="form-label" data-en="Capacity">Kapasitas Kendaraan</label>
                    <input type="number" name="shipments[__i__][vehicle_capacity]" class="form-control form-control-sm"
                        step="0.01" placeholder="ton">
                </div>
                <div class="col-4 col-md-4"><label class="form-label" data-en="Volume">Total Volume Angkut</label>
                    <input type="number" name="shipments[__i__][total_volume]" class="form-control form-control-sm"
                        step="0.01">
                </div>
                <div class="col-4 col-md-2"><label class="form-label" data-en="Unit">Satuan Angkutan</label>
                    <input type="text" name="shipments[__i__][measurement_unit]" class="form-control form-control-sm"
                        value="Ton">
                </div>
            </div>
        </div>
    </template>

    <template id="tmplCost">
        <div class="ord-row-item" data-row="cost">
            <div class="ord-row-header">
                <span class="ord-row-num">Biaya #<span class="row-num"></span></span>
                <button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" data-en="Description">Uraian</label>
                    <input type="text" name="costs[__i__][cost_description]" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2"><label class="form-label">Qty</label>
                    <input type="number" name="costs[__i__][cost_qty]" class="form-control form-control-sm cost-qty"
                        step="0.01" min="0" value="0">
                </div>
                <div class="col-6 col-md-2"><label class="form-label" data-en="Unit Price">Harga Satuan</label>
                    <input type="number" name="costs[__i__][cost_unit_price]"
                        class="form-control form-control-sm cost-price" step="0.01" min="0" value="0">
                </div>
                <div class="col-6 col-md-2"><label class="form-label">PPN %</label>
                    <input type="number" name="costs[__i__][ppn_percent]" class="form-control form-control-sm cost-ppn"
                        step="0.01" min="0" value="11">
                </div>
                <div class="col-6 col-md-2"><label class="form-label" data-en="Total">Total</label>
                    <input type="text" class="form-control form-control-sm cost-total-display" readonly
                        placeholder="Rp 0" tabindex="-1">
                </div>
            </div>
        </div>
    </template>

    {{-- ═══ CUSTOMER DATA JSON ═══ --}}
    @php
        $customersJson = $customers->map(fn($c) => [
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
        ])->keyBy('id');
    @endphp
    <script>const CUSTOMERS_DATA = @json($customersJson);</script>

    {{-- ═══ CUSTOMER INFO MODAL ═══ --}}
    <div class="ci-modal-backdrop" id="custInfoModal" role="dialog" aria-modal="true">
        <div class="ci-modal">
            <div class="ci-modal-header">
                <div class="ci-modal-title">
                    <i class="fas fa-building" style="color:#0d6efd"></i>
                    <span id="ciModalName"></span>
                    <span class="badge-code" id="ciModalCode"></span>
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
                        <div class="ci-info-item"><label data-en="Customer Code">Kode Pelanggan</label><div class="ci-val" id="ci_code"></div></div>
                        <div class="ci-info-item"><label data-en="Registration Date">Tgl Registrasi</label><div class="ci-val" id="ci_reg_date"></div></div>
                        <div class="ci-info-item"><label data-en="Customer Type">Tipe Pelanggan</label><div class="ci-val" id="ci_type"></div></div>
                        <div class="ci-info-item"><label data-en="Business Type">Bidang Usaha</label><div class="ci-val" id="ci_biz"></div></div>
                        <div class="ci-info-item"><label data-en="Phone">Telepon</label><div class="ci-val" id="ci_phone"></div></div>
                        <div class="ci-info-item"><label data-en="Email">Email</label><div class="ci-val" id="ci_email"></div></div>
                        <div class="ci-info-item"><label>NPWP</label><div class="ci-val" id="ci_npwp"></div></div>
                        <div class="ci-info-item"><label>NIB</label><div class="ci-val" id="ci_nib"></div></div>
                        <div class="ci-info-item"><label data-en="Status">Status</label><div class="ci-val" id="ci_status"></div></div>
                    </div>
                </div>
                <div class="ci-tab-pane" id="ci-address">
                    <div class="ci-info-grid">
                        <div class="ci-info-item full"><label data-en="Street Address">Alamat Jalan</label><div class="ci-val" id="ci_address"></div></div>
                        <div class="ci-info-item"><label>RT/RW</label><div class="ci-val" id="ci_rtrw"></div></div>
                        <div class="ci-info-item"><label>Kelurahan</label><div class="ci-val" id="ci_kelurahan"></div></div>
                        <div class="ci-info-item"><label>Kecamatan</label><div class="ci-val" id="ci_kecamatan"></div></div>
                        <div class="ci-info-item"><label data-en="City">Kota</label><div class="ci-val" id="ci_city"></div></div>
                        <div class="ci-info-item"><label data-en="Province">Provinsi</label><div class="ci-val" id="ci_province"></div></div>
                        <div class="ci-info-item"><label data-en="Postal Code">Kode Pos</label><div class="ci-val" id="ci_postal"></div></div>
                    </div>
                </div>
                <div class="ci-tab-pane" id="ci-pics">
                    <div class="ci-pic-list" id="ciPicList">
                        <p class="text-muted" style="font-size:.85rem" data-en="No PIC registered">Belum ada PIC terdaftar</p>
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

@endsection

@push('scripts')
    <script>
        // Tabs
        document.querySelectorAll('.ord-tab-btn').forEach(btn => btn.addEventListener('click', () => {
            document.querySelectorAll('.ord-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ord-tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        }));

        // ── Customer select: autofill + read-only strip + info button ────────────
        const custSelect  = document.getElementById('customerSelect');
        const btnCustInfo = document.getElementById('btnCustInfo');
        const custRoStrip = document.getElementById('custRoStrip');

        function fillCustomerData(id) {
            const c = CUSTOMERS_DATA[id];
            if (!c) {
                custRoStrip?.classList.remove('visible');
                if (btnCustInfo) btnCustInfo.disabled = true;
                return;
            }
            document.getElementById('custAddr').value = c.address || '';
            const pri = c.pics.find(p => p.is_primary) || c.pics[0] || {};
            const pn = document.querySelector('input[name="pic_name"]');
            const pp = document.querySelector('input[name="pic_phone"]');
            if (pn) pn.value = pri.name  || '';
            if (pp) pp.value = pri.phone || '';

            document.getElementById('ro_code').textContent  = c.code  || '';
            document.getElementById('ro_type').textContent  = c.type  || '';
            document.getElementById('ro_biz').textContent   = c.business_type || '';
            document.getElementById('ro_phone').textContent = c.phone || '';
            document.getElementById('ro_email').textContent = c.email || '';
            document.getElementById('ro_npwp').textContent  = c.npwp  || '';
            custRoStrip?.classList.add('visible');
            if (btnCustInfo) btnCustInfo.disabled = false;
        }

        custSelect?.addEventListener('change', function() { fillCustomerData(this.value); });

        // ── Customer Info Modal ────────────────────────────────────────────────────
        const ciModal = document.getElementById('custInfoModal');

        function openCustModal(id) {
            const c = CUSTOMERS_DATA[id];
            if (!c) return;
            const isEn = (typeof lang !== 'undefined') ? lang === 'en' : false;
            document.getElementById('ciModalName').textContent = c.name || '';
            document.getElementById('ciModalCode').textContent = c.code || '';
            document.getElementById('ci_code').textContent     = c.code     || '';
            document.getElementById('ci_reg_date').textContent = c.reg_date || '';
            document.getElementById('ci_type').textContent     = c.type     || '';
            document.getElementById('ci_biz').textContent      = c.business_type || '';
            document.getElementById('ci_phone').textContent    = c.phone    || '';
            document.getElementById('ci_email').textContent    = c.email    || '';
            document.getElementById('ci_npwp').textContent     = c.npwp     || '';
            document.getElementById('ci_nib').textContent      = c.nib      || '';
            document.getElementById('ci_status').textContent   = c.is_active
                ? (isEn ? 'Active' : 'Aktif') : (isEn ? 'Inactive' : 'Tidak Aktif');
            document.getElementById('ci_address').textContent   = c.address   || '';
            document.getElementById('ci_rtrw').textContent      = c.rt_rw     || '';
            document.getElementById('ci_kelurahan').textContent = c.kelurahan || '';
            document.getElementById('ci_kecamatan').textContent = c.kecamatan || '';
            document.getElementById('ci_city').textContent      = c.city      || '';
            document.getElementById('ci_province').textContent  = c.province  || '';
            document.getElementById('ci_postal').textContent    = c.postal_code || '';
            const picList = document.getElementById('ciPicList');
            picList.innerHTML = !c.pics?.length
                ? `<p class="text-muted" style="font-size:.85rem">${isEn ? 'No PIC registered' : 'Belum ada PIC terdaftar'}</p>`
                : c.pics.map(p => `<div class="ci-pic-card">
                    <div class="ci-pic-name">${p.name || '—'}${p.is_primary ? `<span class="ci-pic-badge">${isEn ? 'Primary' : 'Utama'}</span>` : ''}</div>
                    <div class="ci-pic-sub">${[p.position, p.department].filter(Boolean).join(' · ') || ''}</div>
                    ${p.phone ? `<div class="ci-pic-sub"><i class="fas fa-phone fa-xs me-1"></i>${p.phone}</div>` : ''}
                    ${p.email ? `<div class="ci-pic-sub"><i class="fas fa-envelope fa-xs me-1"></i>${p.email}</div>` : ''}
                </div>`).join('');
            document.querySelectorAll('.ci-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ci-tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelector('[data-ci-tab="ci-general"]').classList.add('active');
            document.getElementById('ci-general').classList.add('active');
            ciModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCustModal() { ciModal.classList.remove('show'); document.body.style.overflow = ''; }

        btnCustInfo?.addEventListener('click', () => { if (custSelect?.value) openCustModal(custSelect.value); });
        document.getElementById('btnCloseCustModal')?.addEventListener('click', closeCustModal);
        document.getElementById('btnCloseCustModal2')?.addEventListener('click', closeCustModal);
        ciModal?.addEventListener('click', e => { if (e.target === ciModal) closeCustModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && ciModal?.classList.contains('show')) closeCustModal(); });

        document.querySelectorAll('.ci-tab-btn').forEach(btn => btn.addEventListener('click', () => {
            document.querySelectorAll('.ci-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ci-tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.ciTab).classList.add('active');
        }));

        // Row helpers
        function bindRemove(el, containerId) {
            el.querySelector('.btn-remove-row').addEventListener('click', () => {
                el.remove();
                renumber(containerId);
                if (containerId === 'costRows') recalcAll();
            });
        }

        function renumber(cid) {
            document.querySelectorAll(`#${cid} .row-num`).forEach((sp, i) => sp.textContent = i + 1);
        }

        function makeRow(tmplId, containerId, index) {
            const div = document.createElement('div');
            div.innerHTML = document.getElementById(tmplId).innerHTML.replace(/__i__/g, index);
            const el = div.firstElementChild;
            el.querySelector('.row-num').textContent = document.querySelectorAll(`#${containerId} .ord-row-item`).length +
            1;
            bindRemove(el, containerId);
            if (tmplId === 'tmplCost') el.querySelectorAll('.cost-qty,.cost-price,.cost-ppn').forEach(inp => inp
                .addEventListener('input', () => {
                    recalcRow(el);
                    recalcAll();
                }));
            document.getElementById(containerId).appendChild(el);
        }

        // Bind existing rows
        document.querySelectorAll('#shipmentRows .ord-row-item').forEach(el => bindRemove(el, 'shipmentRows'));
        document.querySelectorAll('#costRows .ord-row-item').forEach(el => {
            bindRemove(el, 'costRows');
            el.querySelectorAll('.cost-qty,.cost-price,.cost-ppn').forEach(inp => inp.addEventListener('input',
            () => {
                    recalcRow(el);
                    recalcAll();
                }));
        });

        let sIdx = {{ $order->shipments->count() }},
            cIdx = {{ $order->costs->count() }};
        document.getElementById('addShipmentRow').addEventListener('click', () => makeRow('tmplShipment', 'shipmentRows',
            sIdx++));
        document.getElementById('addCostRow').addEventListener('click', () => {
            makeRow('tmplCost', 'costRows', cIdx++);
            recalcAll();
        });

        // Calc
        const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');

        function recalcRow(el) {
            const qty = parseFloat(el.querySelector('.cost-qty')?.value) || 0;
            const price = parseFloat(el.querySelector('.cost-price')?.value) || 0;
            const ppn = parseFloat(el.querySelector('.cost-ppn')?.value) || 0;
            const sub = qty * price;
            const ppnAmt = sub * ppn / 100;
            el.querySelector('.cost-total-display').value = fmt(sub + ppnAmt);
            el.dataset.subtotal = sub;
            el.dataset.ppn = ppnAmt;
            el.dataset.total = sub + ppnAmt;
        }

        function recalcAll() {
            let s = 0,
                p = 0,
                t = 0;
            document.querySelectorAll('#costRows .ord-row-item').forEach(el => {
                recalcRow(el);
                s += parseFloat(el.dataset.subtotal || 0);
                p += parseFloat(el.dataset.ppn || 0);
                t += parseFloat(el.dataset.total || 0);
            });
            document.getElementById('sumSubtotal').textContent = fmt(s);
            document.getElementById('sumPpn').textContent = fmt(p);
            document.getElementById('sumTotal').textContent = fmt(t);
        }
        recalcAll();
    </script>
@endpush
