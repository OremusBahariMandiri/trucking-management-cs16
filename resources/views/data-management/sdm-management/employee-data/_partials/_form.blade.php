{{--
    _form.blade.php — shared form (create & edit)
    $employee = null  → create mode
    $employee = model → edit mode
--}}

@php
    $isEdit = !is_null($employee ?? null);
    $old    = fn(string $field, $default = null) => old($field, $isEdit ? ($employee?->$field ?? $default) : $default);
    $err    = fn(string $field) => $errors->first($field);
    $hasErr = fn(string $field) => $errors->has($field) ? 'is-invalid' : '';
@endphp

<div class="emp-form-card">

    {{-- ── Section: Data Utama ──────────────────────────────── --}}
    <div class="form-section-title">
        <i class="fas fa-id-card"></i>
        <span data-en="Employee Data">Data Karyawan</span>
    </div>

    <div class="row g-3">

        {{-- NRK --}}
        <div class="col-md-4">
            <label class="form-label form-label-sm" for="nrk">
                NRK <span class="text-danger">*</span>
            </label>
            <div class="input-group input-group-sm">
                <input type="text"
                       id="nrk"
                       name="nrk"
                       class="form-control {{ $hasErr('nrk') }}"
                       value="{{ $old('nrk') }}"
                       placeholder="EMP-2026-0001"
                       required>
                @if(!$isEdit)
                <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        id="btnGenerateNrk"
                        data-url="{{ route('data-management.sdm-management.employee-data.api.generate-nrk') }}"
                        title="Generate NRK otomatis">
                    <i class="fas fa-magic"></i>
                </button>
                @endif
            </div>
            @if($err('nrk'))
                <div class="invalid-feedback d-block">{{ $err('nrk') }}</div>
            @endif
        </div>

        {{-- Nama --}}
        <div class="col-md-8">
            <label class="form-label form-label-sm" for="full_name">
                <span data-en="Full Name">Nama Lengkap</span>
                <span class="text-danger">*</span>
            </label>
            <input type="text"
                   id="full_name"
                   name="full_name"
                   class="form-control form-control-sm {{ $hasErr('full_name') }}"
                   value="{{ $old('full_name') }}"
                   placeholder="Nama lengkap karyawan"
                   required>
            @if($err('full_name'))
                <div class="invalid-feedback d-block">{{ $err('full_name') }}</div>
            @endif
        </div>

        {{-- Departemen --}}
        <div class="col-md-4">
            <label class="form-label form-label-sm" for="department">
                <span data-en="Department">Departemen</span>
            </label>
            <input type="text"
                   id="department"
                   name="department"
                   class="form-control form-control-sm {{ $hasErr('department') }}"
                   value="{{ $old('department') }}"
                   placeholder="Contoh: Operasional">
            @if($err('department'))
                <div class="invalid-feedback d-block">{{ $err('department') }}</div>
            @endif
        </div>

        {{-- Jabatan --}}
        <div class="col-md-4">
            <label class="form-label form-label-sm" for="position">
                <span data-en="Position">Jabatan</span>
            </label>
            <input type="text"
                   id="position"
                   name="position"
                   class="form-control form-control-sm {{ $hasErr('position') }}"
                   value="{{ $old('position') }}"
                   placeholder="Contoh: Supervisor">
            @if($err('position'))
                <div class="invalid-feedback d-block">{{ $err('position') }}</div>
            @endif
        </div>

        {{-- Wilker --}}
        <div class="col-md-4">
            <label class="form-label form-label-sm" for="wilker">
                <span data-en="Work Area">Wilker</span>
            </label>
            <input type="text"
                   id="wilker"
                   name="wilker"
                   class="form-control form-control-sm {{ $hasErr('wilker') }}"
                   value="{{ $old('wilker') }}"
                   placeholder="Contoh: Surabaya">
            @if($err('wilker'))
                <div class="invalid-feedback d-block">{{ $err('wilker') }}</div>
            @endif
        </div>

    </div>

    {{-- ── Section: Catatan ─────────────────────────────────── --}}
    <div class="form-section-title mt-4">
        <i class="fas fa-sticky-note"></i>
        <span data-en="Notes">Catatan</span>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <textarea id="notes"
                      name="notes"
                      class="form-control form-control-sm {{ $hasErr('notes') }}"
                      rows="3"
                      placeholder="Catatan tambahan (opsional)">{{ $old('notes') }}</textarea>
            @if($err('notes'))
                <div class="invalid-feedback d-block">{{ $err('notes') }}</div>
            @endif
        </div>
    </div>

</div>

{{-- ── Action Bar ───────────────────────────────────────────── --}}
<div class="emp-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="Fields marked * are required.">Field bertanda * wajib diisi.</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $isEdit
               ? route('data-management.sdm-management.employee-data.show', $employee)
               : route('data-management.sdm-management.employee-data.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <span data-en="Cancel">Batal</span>
        </a>
        <button type="submit" class="btn-save-emp" id="btnSubmit">
            <i class="fas fa-check"></i>
            <span data-en="{{ $isEdit ? 'Save Changes' : 'Add Employee' }}">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
            </span>
        </button>
    </div>
</div>

{{-- Toast --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="empToast" class="toast align-items-center border-0" role="alert">
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