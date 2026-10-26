@extends('layouts.admin')
@section('title', 'Buat Pesanan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── Tabs ─────────────────────────────────────────────── */
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
            background: var(--surface-alt)
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

        /* ── Repeatable rows ──────────────────────────────────── */
        .ord-rows-wrap {
            display: flex;
            flex-direction: column;
            gap: .75rem
        }

        .ord-row-item {
            background: var(--surface-alt, #f8f9fa);
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

        /* ── Cost summary ─────────────────────────────────────── */
        .ord-cost-summary {
            background: var(--surface-card, #fff);
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
    </style>
@endpush

@section('content')
    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title" data-en="Create Order">Buat Pesanan Baru</div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data-management.orders.index') }}"
                            data-en="Orders">Pesanan</a></li>
                    <li class="breadcrumb-item active" data-en="Create">Buat</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('data-management.orders.store') }}" id="orderForm">
        @csrf

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

            {{-- ═══ TAB A: PELANGGAN ═══ --}}
            <div class="lkp-form-body ord-tab-pane active" id="t-cust">
                <div class="ord-section-title" data-en="A. Customer Information">A. Informasi Pelanggan</div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="PO Number">Nomor PO <span class="text-danger">*</span></label>
                        <input type="text" name="po_number" class="form-control @error('po_number') is-invalid @enderror"
                            value="{{ old('po_number', $nextPoNumber) }}" required>
                        @error('po_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="PO Date">Tanggal PO <span class="text-danger">*</span></label>
                        <input type="date" name="po_date" class="form-control"
                            value="{{ old('po_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Customer">Pelanggan</label>
                        <select name="customer_id" class="form-select" id="customerSelect">
                            <option value="">— Pilih Pelanggan —</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}" data-address="{{ $c->address }}" data-pic-name="{{ $c->primaryPic?->name }}" data-pic-phone="{{ $c->primaryPic?->phone }}"
                                    {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Address">Alamat</label>
                        <textarea name="customer_address" class="form-control" rows="2" id="custAddr">{{ old('customer_address') }}</textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">PIC</label>
                        <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="PIC Phone">Telp PIC</label>
                        <input type="text" name="pic_phone" class="form-control" value="{{ old('pic_phone') }}">
                    </div>
                </div>
            </div>

            {{-- ═══ TAB B: PEMESANAN ANGKUTAN (repeatable rows) ═══ --}}
            <div class="lkp-form-body ord-tab-pane" id="t-ship">
                <div class="ord-section-title" data-en="B. Shipment Order">B. Pemesanan Angkutan</div>
                <div class="ord-rows-wrap" id="shipmentRows">
                    {{-- rows injected by JS --}}
                </div>
                <button type="button" class="btn-add-row" id="addShipmentRow">
                    <i class="fas fa-plus me-1"></i><span data-en="Add Shipment Line">+ Tambah Baris Angkutan</span>
                </button>
            </div>

            {{-- ═══ TAB C: BIAYA (repeatable rows) ═══ --}}
            <div class="lkp-form-body ord-tab-pane" id="t-cost">
                <div class="ord-section-title" data-en="C. Transport Cost">C. Rincian Biaya Angkutan</div>
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Payment Method">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach (['Transfer Bank', 'Tunai', 'Cek/Giro'] as $pm)
                                <option value="{{ $pm }}" {{ old('payment_method') == $pm ? 'selected' : '' }}>
                                    {{ $pm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" data-en="Due Date">Jatuh Tempo</label>
                        <input type="date" name="payment_due_date" class="form-control"
                            value="{{ old('payment_due_date') }}">
                    </div>
                </div>
                <div class="ord-rows-wrap" id="costRows"></div>
                <button type="button" class="btn-add-row" id="addCostRow">
                    <i class="fas fa-plus me-1"></i><span data-en="Add Cost Line">+ Tambah Baris Biaya</span>
                </button>
                <div class="ord-cost-summary" id="costSummary">
                    <div class="ord-cost-total-row"><span data-en="Subtotal">Subtotal</span><span id="sumSubtotal">Rp
                            0</span></div>
                    <div class="ord-cost-total-row"><span data-en="PPN">PPN</span><span id="sumPpn">Rp 0</span></div>
                    <div class="ord-cost-total-row grand"><span data-en="Grand Total">Total</span><span id="sumTotal">Rp
                            0</span></div>
                </div>
            </div>

            {{-- ═══ TAB D: CATATAN ═══ --}}
            <div class="lkp-form-body ord-tab-pane" id="t-note">
                <div class="ord-section-title" data-en="D. Notes">D. Catatan Khusus</div>
                <textarea name="special_notes" class="form-control" rows="6">{{ old('special_notes') }}</textarea>
            </div>

            {{-- ═══ TAB E: PERSETUJUAN ═══ --}}
            <div class="lkp-form-body ord-tab-pane" id="t-appr">
                <div class="ord-section-title" data-en="E. Approval">E. Persetujuan</div>
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Submission Date">Tanggal Pengajuan</label>
                        <input type="date" name="submission_date" class="form-control"
                            value="{{ old('submission_date', now()->toDateString()) }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Status">Status</label>
                        <select name="approval_status" class="form-select">
                            <option value="pending" selected data-en="Pending">Menunggu</option>
                            <option value="approved" data-en="Approved">Disetujui</option>
                            <option value="rejected" data-en="Rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" data-en="Approval Date">Tanggal Persetujuan</label>
                        <input type="date" name="approval_date" class="form-control"
                            value="{{ old('approval_date') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Approval Notes">Catatan</label>
                        <textarea name="approval_notes" class="form-control" rows="3">{{ old('approval_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="lkp-form-actions">
                <a href="{{ route('data-management.orders.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i><span data-en="Cancel">Batal</span>
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i><span data-en="Save">Simpan Pesanan</span>
                </button>
            </div>
        </div>{{-- end lkp-form-card --}}
    </form>

    {{-- ═══ TEMPLATE HIDDEN (digunakan oleh JS) ═══ --}}
    <template id="tmplShipment">
        <div class="ord-row-item" data-row="shipment">
            <div class="ord-row-header">
                <span class="ord-row-num" data-en="Shipment #">Angkutan #<span class="row-num"></span></span>
                <button type="button" class="btn-remove-row" title="Hapus baris"><i class="fas fa-trash"></i></button>
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
                    <label class="form-label" data-en="Packaging Type">Jenis Kemasan</label>
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
                <div class="col-4 col-md-1">
                    <label class="form-label" data-en="Qty">Unit</label>
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
                <div class="col-4 col-md-6">
                    <label class="form-label" data-en="Capacity">Kapasitas Kendaraan</label>
                    <input type="number" name="shipments[__i__][vehicle_capacity]" class="form-control form-control-sm"
                        step="0.01" placeholder="ton">
                </div>
                <div class="col-4 col-md-5">
                    <label class="form-label" data-en="Volume">Total Volume Angkut</label>
                    <input type="number" name="shipments[__i__][total_volume]" class="form-control form-control-sm"
                        step="0.01">
                </div>
                <div class="col-4 col-md-1">
                    <label class="form-label" data-en="Unit">Satuan Angkutan</label>
                    <input type="text" name="shipments[__i__][measurement_unit]" class="form-control form-control-sm"
                        value="Ton">
                </div>
            </div>
        </div>
    </template>

    <template id="tmplCost">
        <div class="ord-row-item" data-row="cost">
            <div class="ord-row-header">
                <span class="ord-row-num" data-en="Cost Line #">Biaya #<span class="row-num"></span></span>
                <button type="button" class="btn-remove-row"><i class="fas fa-trash"></i></button>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" data-en="Description">Uraian</label>
                    <input type="text" name="costs[__i__][cost_description]" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number" name="costs[__i__][cost_qty]" class="form-control form-control-sm cost-qty"
                        step="0.01" min="0" value="0">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label" data-en="Unit Price">Harga Satuan</label>
                    <input type="number" name="costs[__i__][cost_unit_price]"
                        class="form-control form-control-sm cost-price" step="0.01" min="0" value="0">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">PPN %</label>
                    <input type="number" name="costs[__i__][ppn_percent]" class="form-control form-control-sm cost-ppn"
                        step="0.01" min="0" value="11">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label" data-en="Total">Total</label>
                    <input type="text" class="form-control form-control-sm cost-total-display" readonly
                        placeholder="Rp 0" tabindex="-1">
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        // ── Tab switching ──────────────────────────────────────────────────────────
        document.querySelectorAll('.ord-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.ord-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.ord-tab-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });

        // ── Customer address autofill ──────────────────────────────────────────────
        document.getElementById('customerSelect')?.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            document.getElementById('custAddr').value = opt.dataset.address || '';
            var picNameInput = document.querySelector('input[name="pic_name"]');
            var picPhoneInput = document.querySelector('input[name="pic_phone"]');
            if (picNameInput) picNameInput.value = opt.dataset.picName || '';
            if (picPhoneInput) picPhoneInput.value = opt.dataset.picPhone || '';
        });

        // ── Repeatable rows ────────────────────────────────────────────────────────
        function makeRow(tmplId, containerId, index) {
            const tmpl = document.getElementById(tmplId);
            const html = tmpl.innerHTML.replace(/__i__/g, index);
            const div = document.createElement('div');
            div.innerHTML = html;
            const el = div.firstElementChild;
            el.querySelector('.row-num').textContent = index + 1;
            el.querySelector('.btn-remove-row').addEventListener('click', () => {
                el.remove();
                renumberRows(containerId);
                if (tmplId === 'tmplCost') recalcAll();
            });
            if (tmplId === 'tmplCost') {
                el.querySelectorAll('.cost-qty,.cost-price,.cost-ppn').forEach(inp => inp.addEventListener('input', () => {
                    recalcRow(el);
                    recalcAll();
                }));
            }
            document.getElementById(containerId).appendChild(el);
            renumberRows(containerId);
        }

        function renumberRows(containerId) {
            document.querySelectorAll(`#${containerId} .row-num`).forEach((sp, i) => sp.textContent = i + 1);
        }

        // Start with 1 row each
        makeRow('tmplShipment', 'shipmentRows', 0);
        makeRow('tmplCost', 'costRows', 0);

        let shipIdx = 1,
            costIdx = 1;
        document.getElementById('addShipmentRow').addEventListener('click', () => makeRow('tmplShipment', 'shipmentRows',
            shipIdx++));
        document.getElementById('addCostRow').addEventListener('click', () => {
            makeRow('tmplCost', 'costRows', costIdx++);
            recalcAll();
        });

        // ── Cost calculation ───────────────────────────────────────────────────────
        function fmt(v) {
            return 'Rp ' + Math.round(v).toLocaleString('id-ID');
        }

        function recalcRow(rowEl) {
            const qty = parseFloat(rowEl.querySelector('.cost-qty')?.value) || 0;
            const price = parseFloat(rowEl.querySelector('.cost-price')?.value) || 0;
            const ppn = parseFloat(rowEl.querySelector('.cost-ppn')?.value) || 0;
            const sub = qty * price;
            const total = sub + sub * ppn / 100;
            rowEl.querySelector('.cost-total-display').value = fmt(total);
            rowEl.dataset.subtotal = sub;
            rowEl.dataset.ppn = sub * ppn / 100;
            rowEl.dataset.total = total;
        }

        function recalcAll() {
            let subSum = 0,
                ppnSum = 0,
                totSum = 0;
            document.querySelectorAll('#costRows .ord-row-item').forEach(row => {
                recalcRow(row);
                subSum += parseFloat(row.dataset.subtotal || 0);
                ppnSum += parseFloat(row.dataset.ppn || 0);
                totSum += parseFloat(row.dataset.total || 0);
            });
            document.getElementById('sumSubtotal').textContent = fmt(subSum);
            document.getElementById('sumPpn').textContent = fmt(ppnSum);
            document.getElementById('sumTotal').textContent = fmt(totSum);
        }
    </script>
@endpush
