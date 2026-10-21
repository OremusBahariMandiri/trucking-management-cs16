{{--
    _form.blade.php — shared form (create & edit)
    $driver = null  → create mode
    $driver = model → edit mode
--}}

@php
    $isEdit = !is_null($driver ?? null);
    $old    = fn(string $field, $default = null) => old($field, $isEdit ? ($driver?->$field ?? $default) : $default);
    $err    = fn(string $field) => $errors->first($field);
    $hasErr = fn(string $field) => $errors->has($field) ? 'is-invalid' : '';
@endphp

{{-- ── Tab Navigation ───────────────────────────────────────── --}}
<div class="drv-tab-nav mb-0">
    <ul class="nav nav-tabs" id="driverTabs" role="tablist">
        @php
            $tabs = [
                ['id' => 'tab-identitas',  'icon' => 'fas fa-user',          'label' => 'Identitas',   'label_en' => 'Identity'],
                ['id' => 'tab-ktp',        'icon' => 'fas fa-id-card',       'label' => 'Alamat KTP',    'label_en' => 'ID Card Adress'],
                ['id' => 'tab-alamat',     'icon' => 'fas fa-map-marker-alt','label' => 'Alamat Domisili',      'label_en' => 'Address'],
                ['id' => 'tab-dokumen',    'icon' => 'fas fa-file-alt',      'label' => 'Dokumen',     'label_en' => 'Documents'],
                ['id' => 'tab-sertifikat', 'icon' => 'fas fa-certificate',   'label' => 'Sertifikat',  'label_en' => 'Certificates'],
                ['id' => 'tab-catatan',    'icon' => 'fas fa-book',          'label' => 'Catatan',     'label_en' => 'Notes'],
            ];
        @endphp
        @foreach($tabs as $i => $tab)
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

{{-- ── Tab Content ──────────────────────────────────────────── --}}
<div class="tab-content mb-3">

    <div class="tab-pane fade show active" id="tab-identitas" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-1-identitas')
    </div>

    <div class="tab-pane fade" id="tab-ktp" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-2-ktp')
    </div>

    <div class="tab-pane fade" id="tab-alamat" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-3-alamat')
    </div>

    <div class="tab-pane fade" id="tab-dokumen" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-4-dokumen')
    </div>

    <div class="tab-pane fade" id="tab-sertifikat" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-5-sertifikat')
    </div>

    <div class="tab-pane fade" id="tab-catatan" role="tabpanel">
        @include('data-management.sdm-management.driver-data._partials._tab-6-catatan')
    </div>

</div>

{{-- ── Action Bar ───────────────────────────────────────────── --}}
<div class="drv-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="All fields are optional unless marked required.">Field bertanda * wajib diisi.</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $isEdit ? route('data-management.sdm-management.driver-data.index', $driver) : route('data-management.sdm-management.driver-data.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <span data-en="Cancel">Batal</span>
        </a>
        <button type="submit" class="btn-save" id="btnSubmit">
            <i class="fas fa-check"></i>
            <span data-en="{{ $isEdit ? 'Save Changes' : 'Add Driver' }}">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Supir' }}
            </span>
        </button>
    </div>
</div>

{{-- Toast --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="drvToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif