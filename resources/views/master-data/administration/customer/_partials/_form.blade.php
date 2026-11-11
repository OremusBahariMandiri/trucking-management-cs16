{{--
    _form.blade.php — shared form (create & edit)
    $customer = null  → create mode
    $customer = model → edit mode
--}}
@php
    $isEdit = !is_null($customer ?? null);
    $val = function (string $field, $default = null) use ($customer) {
        if (old($field) !== null && old($field) !== '') return old($field);
        if ($customer === null) return $default;
        $v = $customer->{$field};
        return $v instanceof \Carbon\Carbon ? $v->format('Y-m-d') : $v;
    };
    $hasErr = fn(string $f) => $errors->has($f) ? 'is-invalid' : '';
    $err    = fn(string $f) => $errors->first($f);
@endphp

{{-- ── Tab Navigation ──────────────────────────────────────────── --}}
<div class="veh-tab-nav mb-0">
    <ul class="nav nav-tabs" id="custTabs" role="tablist">
        @php
            $tabs = [
                ['id'=>'tab-info',      'icon'=>'fas fa-building',        'label'=>'Informasi',  'label_en'=>'Information'],
                ['id'=>'tab-legalitas', 'icon'=>'fas fa-file-invoice',    'label'=>'Legalitas',  'label_en'=>'Legality'],
                ['id'=>'tab-alamat',    'icon'=>'fas fa-map-marker-alt',  'label'=>'Alamat',     'label_en'=>'Address'],
                ['id'=>'tab-pic',       'icon'=>'fas fa-user-tie',        'label'=>'PIC',        'label_en'=>'PIC'],
            ];
        @endphp
        @foreach ($tabs as $i => $tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                    id="{{ $tab['id'] }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tab['id'] }}"
                    type="button" role="tab">
                    <i class="{{ $tab['icon'] }}"></i>
                    <span class="d-none d-sm-inline" data-en="{{ $tab['label_en'] }}">{{ $tab['label'] }}</span>
                </button>
            </li>
        @endforeach
    </ul>
</div>

{{-- ── Tab Content ─────────────────────────────────────────────── --}}
<div class="tab-content mb-3">

    {{-- Tab 1: Informasi --}}
    <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
        <div class="veh-form-card">
            <div class="form-section-title">
                <i class="fas fa-info-circle"></i>
                <span data-en="General Information">Informasi Umum</span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" data-en="Registration Date">Tanggal Registrasi</label>
                    <input type="date" name="registration_date"
                        class="form-control {{ $hasErr('registration_date') }}"
                        value="{{ $val('registration_date', date('Y-m-d')) }}">
                    <div class="invalid-feedback">{{ $err('registration_date') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Customer Code">Kode Pelanggan</label>
                    <input type="text" name="customer_code"
                        class="form-control {{ $hasErr('customer_code') }}"
                        value="{{ $val('customer_code') }}"
                        placeholder="CS-001">
                    <div class="invalid-feedback">{{ $err('customer_code') }}</div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">
                        <span data-en="Customer Name">Nama Pelanggan</span>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                        class="form-control {{ $hasErr('name') }}"
                        value="{{ $val('name') }}"
                        {{ !$isEdit ? 'autofocus' : '' }} required>
                    <div class="invalid-feedback">{{ $err('name') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Customer Type">Jenis Pelanggan</label>
                    <select name="customer_type_id" class="form-select {{ $hasErr('customer_type_id') }}">
                        <option value="" data-en="— Select —">— Pilih —</option>
                        @foreach ($customerTypes as $ct)
                            <option value="{{ $ct->id }}" {{ $val('customer_type_id') == $ct->id ? 'selected' : '' }}>
                                {{ $ct->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">{{ $err('customer_type_id') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Business Type">Jenis Usaha</label>
                    <select name="business_type_id" class="form-select {{ $hasErr('business_type_id') }}">
                        <option value="" data-en="— Select —">— Pilih —</option>
                        @foreach ($businessTypes as $bt)
                            <option value="{{ $bt->id }}" {{ $val('business_type_id') == $bt->id ? 'selected' : '' }}>
                                {{ $bt->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">{{ $err('business_type_id') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Phone">Telepon</label>
                    <input type="text" name="phone"
                        class="form-control {{ $hasErr('phone') }}"
                        value="{{ $val('phone') }}">
                    <div class="invalid-feedback">{{ $err('phone') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Email">Email</label>
                    <input type="email" name="email"
                        class="form-control {{ $hasErr('email') }}"
                        value="{{ $val('email') }}">
                    <div class="invalid-feedback">{{ $err('email') }}</div>
                </div>
                <div class="col-md-12">
                    <div class="lkp-toggle-wrap" style="margin-top:.5rem">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ $val('is_active', true) ? 'checked' : '' }}>
                        <label class="lkp-toggle-label" for="is_active">
                            <span data-en="Active">Aktif</span>
                            <small data-en="Customer is active">Pelanggan dapat digunakan dalam transaksi</small>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 2: Legalitas --}}
    <div class="tab-pane fade" id="tab-legalitas" role="tabpanel">
        <div class="veh-form-card">
            <div class="form-section-title">
                <i class="fas fa-file-invoice"></i>
                <span data-en="Legal Documents">Dokumen Legalitas</span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" data-en="NPWP Number">Nomor NPWP</label>
                    <input type="text" name="npwp_number"
                        class="form-control {{ $hasErr('npwp_number') }}"
                        value="{{ $val('npwp_number') }}">
                    <div class="invalid-feedback">{{ $err('npwp_number') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="NPWP File">File NPWP</label>
                    <input type="file" name="npwp_file"
                        class="form-control {{ $hasErr('npwp_file') }}"
                        accept=".pdf,.jpg,.jpeg,.png">
                    @if ($isEdit && $customer->npwp_file_name)
                        <div class="mt-1">
                            <a href="{{ $customer->npwp_file_url }}" target="_blank" rel="noopener"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-file me-1"></i>{{ $customer->npwp_file_name }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    @endif
                    <div class="invalid-feedback">{{ $err('npwp_file') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="NIB Number">Nomor NIB</label>
                    <input type="text" name="nib_number"
                        class="form-control {{ $hasErr('nib_number') }}"
                        value="{{ $val('nib_number') }}">
                    <div class="invalid-feedback">{{ $err('nib_number') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="NIB File">File NIB</label>
                    <input type="file" name="nib_file"
                        class="form-control {{ $hasErr('nib_file') }}"
                        accept=".pdf,.jpg,.jpeg,.png">
                    @if ($isEdit && $customer->nib_file_name)
                        <div class="mt-1">
                            <a href="{{ $customer->nib_file_url }}" target="_blank" rel="noopener"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-file me-1"></i>{{ $customer->nib_file_name }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    @endif
                    <div class="invalid-feedback">{{ $err('nib_file') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 3: Alamat --}}
    <div class="tab-pane fade" id="tab-alamat" role="tabpanel">
        <div class="veh-form-card">
            <div class="form-section-title">
                <i class="fas fa-map-marker-alt"></i>
                <span data-en="Address">Alamat</span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" data-en="Province">Provinsi</label>
                    <input type="text" name="province"
                        class="form-control {{ $hasErr('province') }}"
                        value="{{ $val('province') }}">
                    <div class="invalid-feedback">{{ $err('province') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="City">Kota</label>
                    <input type="text" name="city"
                        class="form-control {{ $hasErr('city') }}"
                        value="{{ $val('city') }}">
                    <div class="invalid-feedback">{{ $err('city') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Kecamatan">Kecamatan</label>
                    <input type="text" name="kecamatan"
                        class="form-control {{ $hasErr('kecamatan') }}"
                        value="{{ $val('kecamatan') }}">
                    <div class="invalid-feedback">{{ $err('kecamatan') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Kelurahan">Kelurahan</label>
                    <input type="text" name="kelurahan"
                        class="form-control {{ $hasErr('kelurahan') }}"
                        value="{{ $val('kelurahan') }}">
                    <div class="invalid-feedback">{{ $err('kelurahan') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="RT/RW">RT/RW</label>
                    <input type="text" name="rt_rw"
                        class="form-control {{ $hasErr('rt_rw') }}"
                        value="{{ $val('rt_rw') }}"
                        placeholder="001/002">
                    <div class="invalid-feedback">{{ $err('rt_rw') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" data-en="Postal Code">Kode Pos</label>
                    <input type="text" name="postal_code"
                        class="form-control {{ $hasErr('postal_code') }}"
                        value="{{ $val('postal_code') }}">
                    <div class="invalid-feedback">{{ $err('postal_code') }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label" data-en="Street Address">Alamat Jalan</label>
                    <textarea name="address"
                        class="form-control {{ $hasErr('address') }}"
                        rows="3">{{ $val('address') }}</textarea>
                    <div class="invalid-feedback">{{ $err('address') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 4: PIC --}}
    <div class="tab-pane fade" id="tab-pic" role="tabpanel">
        <div class="veh-form-card">
            <div class="form-section-title d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-user-tie"></i>
                    <span data-en="Person In Charge">Penanggung Jawab (PIC)</span>
                </span>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addPicBtn">
                    <i class="fas fa-plus me-1"></i>
                    <span data-en="Add PIC">Tambah PIC</span>
                </button>
            </div>
            <div id="picContainer">
                @if ($isEdit && $customer->pics->count())
                    @foreach ($customer->pics as $i => $pic)
                        <div class="pic-row" data-index="{{ $i }}">
                            <input type="hidden" name="pics[{{ $i }}][id]" value="{{ $pic->id }}">
                            <div class="pic-row-header">
                                <span class="pic-row-label">PIC #{{ $i + 1 }}</span>
                                @if ($i > 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-pic">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label form-label-sm" data-en="Name">Nama</label>
                                    <input type="text" name="pics[{{ $i }}][name]"
                                        class="form-control form-control-sm" value="{{ $pic->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm" data-en="Position">Jabatan</label>
                                    <input type="text" name="pics[{{ $i }}][position]"
                                        class="form-control form-control-sm" value="{{ $pic->position }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm" data-en="Department">Departemen</label>
                                    <input type="text" name="pics[{{ $i }}][department]"
                                        class="form-control form-control-sm" value="{{ $pic->department }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm" data-en="Phone">Telepon</label>
                                    <input type="text" name="pics[{{ $i }}][phone]"
                                        class="form-control form-control-sm" value="{{ $pic->phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm" data-en="Email">Email</label>
                                    <input type="email" name="pics[{{ $i }}][email]"
                                        class="form-control form-control-sm" value="{{ $pic->email }}">
                                </div>
                                <div class="col-md-12">
                                    <div class="lkp-toggle-wrap">
                                        <input class="form-check-input" type="checkbox"
                                            name="pics[{{ $i }}][is_primary]" value="1"
                                            id="pic_primary_{{ $i }}"
                                            {{ $pic->is_primary ? 'checked' : '' }}>
                                        <label class="lkp-toggle-label" for="pic_primary_{{ $i }}">
                                            <span data-en="Primary PIC">PIC Utama</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="pic-row" data-index="0">
                        <div class="pic-row-header">
                            <span class="pic-row-label">PIC #1</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label form-label-sm" data-en="Name">Nama</label>
                                <input type="text" name="pics[0][name]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Position">Jabatan</label>
                                <input type="text" name="pics[0][position]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Department">Departemen</label>
                                <input type="text" name="pics[0][department]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Phone">Telepon</label>
                                <input type="text" name="pics[0][phone]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Email">Email</label>
                                <input type="email" name="pics[0][email]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-12">
                                <div class="lkp-toggle-wrap">
                                    <input class="form-check-input" type="checkbox"
                                        name="pics[0][is_primary]" value="1"
                                        id="pic_primary_0" checked>
                                    <label class="lkp-toggle-label" for="pic_primary_0">
                                        <span data-en="Primary PIC">PIC Utama</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ── Action Bar ───────────────────────────────────────────────── --}}
<div class="veh-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="Fields marked * are required.">Field bertanda * wajib diisi.</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('master-data.administration.customers.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-times me-1"></i>
            <span data-en="Cancel">Batal</span>
        </a>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save me-1"></i>
            <span data-en="{{ $isEdit ? 'Save Changes' : 'Save' }}">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
        </button>
    </div>
</div>

{{-- PIC styles --}}
<style>
.pic-row {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: .75rem;
    transition: border-color .15s;
}
.pic-row:focus-within {
    border-color: var(--veh-accent);
}
.pic-row-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .75rem;
}
.pic-row-label {
    font-size: .8rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
}
</style>

{{-- PIC JS --}}
<script>
(function () {
    var picCount = {{ $isEdit ? ($customer->pics->count() ?: 1) : 1 }};

    document.getElementById('addPicBtn').addEventListener('click', function () {
        var idx = picCount++;
        var html = `
        <div class="pic-row" data-index="${idx}">
            <div class="pic-row-header">
                <span class="pic-row-label">PIC #${idx + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-pic">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label form-label-sm">Nama</label>
                    <input type="text" name="pics[${idx}][name]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Jabatan</label>
                    <input type="text" name="pics[${idx}][position]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Departemen</label>
                    <input type="text" name="pics[${idx}][department]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Telepon</label>
                    <input type="text" name="pics[${idx}][phone]" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Email</label>
                    <input type="email" name="pics[${idx}][email]" class="form-control form-control-sm">
                </div>
                <div class="col-md-12">
                    <div class="lkp-toggle-wrap">
                        <input class="form-check-input" type="checkbox"
                            name="pics[${idx}][is_primary]" value="1" id="pic_primary_${idx}">
                        <label class="lkp-toggle-label" for="pic_primary_${idx}">
                            <span>PIC Utama</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>`;
        document.getElementById('picContainer').insertAdjacentHTML('beforeend', html);
    });

    document.getElementById('picContainer').addEventListener('click', function (e) {
        if (e.target.closest('.remove-pic')) {
            e.target.closest('.pic-row').remove();
        }
    });

    document.getElementById('picContainer').addEventListener('change', function (e) {
        if (e.target.matches('input[type="checkbox"][name$="[is_primary]"]') && e.target.checked) {
            document.querySelectorAll('#picContainer input[type="checkbox"][name$="[is_primary]"]').forEach(function (cb) {
                if (cb !== e.target) cb.checked = false;
            });
        }
    });

    // Simpan tab aktif di sessionStorage
    var tabEls = document.querySelectorAll('#custTabs button[data-bs-toggle="tab"]');
    var storageKey = 'custFormTab';
    var saved = sessionStorage.getItem(storageKey);
    if (saved) {
        var btn = document.querySelector('#custTabs button[data-bs-target="' + saved + '"]');
        if (btn) { new bootstrap.Tab(btn).show(); }
    }
    tabEls.forEach(function (btn) {
        btn.addEventListener('shown.bs.tab', function (e) {
            sessionStorage.setItem(storageKey, e.target.getAttribute('data-bs-target'));
        });
    });
})();
</script>
