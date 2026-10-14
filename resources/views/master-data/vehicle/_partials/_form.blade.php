{{--
    _form.blade.php — shared form (create & edit)
    $vehicle = null  → create mode
    $vehicle = model → edit mode
--}}

@php
    $isEdit  = !is_null($vehicle ?? null);
    $old     = fn(string $field, $default = null) => old($field, $isEdit ? ($vehicle?->$field ?? $default) : $default);
    $err     = fn(string $field) => $errors->first($field);
    $hasErr  = fn(string $field) => $errors->has($field) ? 'is-invalid' : '';
@endphp

{{-- ── Tab Navigation ────────────────────────────────────── --}}
<div class="veh-tab-nav mb-0">
    <ul class="nav nav-tabs" id="vehicleTabs" role="tablist">
        @php
            $tabs = [
                ['id'=>'tab-umum',        'icon'=>'fas fa-info-circle',     'label'=>'Informasi Umum', 'label_en'=>'General Info'],
                ['id'=>'tab-spesifikasi', 'icon'=>'fas fa-cogs',            'label'=>'Spesifikasi',    'label_en'=>'Specifications'],
                ['id'=>'tab-kepemilikan', 'icon'=>'fas fa-building',        'label'=>'Kepemilikan',    'label_en'=>'Ownership'],
                ['id'=>'tab-operasional', 'icon'=>'fas fa-tachometer-alt',  'label'=>'Operasional',    'label_en'=>'Operational'],
                ['id'=>'tab-dokumen',     'icon'=>'fas fa-file-alt',        'label'=>'Dokumen',        'label_en'=>'Documents'],
                ['id'=>'tab-foto',        'icon'=>'fas fa-images',          'label'=>'Foto',           'label_en'=>'Photos'],
                ['id'=>'tab-catatan',     'icon'=>'fas fa-book',            'label'=>'Catatan',        'label_en'=>'Notes'],
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

{{-- ── Tab Content ───────────────────────────────────────── --}}
<div class="tab-content mb-3">

    {{-- Tab 1: Informasi Umum --}}
    <div class="tab-pane fade show active" id="tab-umum" role="tabpanel">
        @include('master-data.vehicle._partials._tab-1-umum')
    </div>

    {{-- Tab 2: Spesifikasi Teknis --}}
    <div class="tab-pane fade" id="tab-spesifikasi" role="tabpanel">
        @include('master-data.vehicle._partials._tab-2-spesifikasi')
    </div>

    {{-- Tab 3: Kepemilikan & Penugasan --}}
    <div class="tab-pane fade" id="tab-kepemilikan" role="tabpanel">
        @include('master-data.vehicle._partials._tab-3-kepemilikan')
    </div>

    {{-- Tab 4: Operasional --}}
    <div class="tab-pane fade" id="tab-operasional" role="tabpanel">
        @include('master-data.vehicle._partials._tab-4-operasional')
    </div>

    {{-- Tab 5: Dokumen & Legalitas --}}
    <div class="tab-pane fade" id="tab-dokumen" role="tabpanel">
        @include('master-data.vehicle._partials._tab-5-dokumen')
    </div>

    {{-- Tab 6: Foto --}}
    <div class="tab-pane fade" id="tab-foto" role="tabpanel">
        @include('master-data.vehicle._partials._tab-6-foto')
    </div>

    {{-- Tab 7: Catatan --}}
    <div class="tab-pane fade" id="tab-catatan" role="tabpanel">
        @include('master-data.vehicle._partials._tab-7-catatan')
    </div>

</div>

{{-- ── Action Bar ────────────────────────────────────────── --}}
<div class="veh-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="All fields are optional. Fill in available data.">Semua field bersifat opsional. Isi sesuai data yang tersedia.</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $isEdit ? route('master-data.kendaraan.show', $vehicle) : route('master-data.kendaraan.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <span data-en="Cancel">Batal</span>
        </a>
        <button type="submit" class="btn-save" id="btnSubmit">
            <i class="fas fa-check"></i>
            <span data-en="{{ $isEdit ? 'Save Changes' : 'Add Vehicle' }}">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kendaraan' }}</span>
        </button>
    </div>
</div>

{{-- Toast notification --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="vehToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- Session alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif