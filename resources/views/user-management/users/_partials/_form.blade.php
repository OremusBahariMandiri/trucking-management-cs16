@php
    $isEdit      = !is_null($user ?? null);
    $old         = fn(string $f, $d = null) => old($f, $isEdit ? ($user?->$f ?? $d) : $d);
    $err         = fn(string $f) => $errors->first($f);
    $hasErr      = fn(string $f) => $errors->has($f) ? 'is-invalid' : '';
    $assignedIds = $isEdit ? $user->roles->pluck('id')->toArray() : [];
@endphp

<div class="um-form-card">

    {{-- Data Akun --}}
    <div class="form-section-title"><i class="fas fa-id-card"></i> Data Akun</div>
    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label form-label-sm">Kode Karyawan</label>
            <input type="text" name="employee_code" class="form-control form-control-sm {{ $hasErr('employee_code') }}"
                   value="{{ $old('employee_code') }}" placeholder="EMP-XXXX (auto jika kosong)">
            @if($err('employee_code'))
                <div class="invalid-feedback d-block">{{ $err('employee_code') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm">NIK</label>
            <input type="text" name="employee_id_number" class="form-control form-control-sm {{ $hasErr('employee_id_number') }}"
                   value="{{ $old('employee_id_number') }}" placeholder="Nomor Induk Karyawan">
            @if($err('employee_id_number'))
                <div class="invalid-feedback d-block">{{ $err('employee_id_number') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm">
                Nama Lengkap <span class="text-danger">*</span>
            </label>
            <input type="text" name="full_name" class="form-control form-control-sm {{ $hasErr('full_name') }}"
                   value="{{ $old('full_name') }}" placeholder="Nama lengkap" required>
            @if($err('full_name'))
                <div class="invalid-feedback d-block">{{ $err('full_name') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm">Departemen</label>
            <input type="text" name="department" class="form-control form-control-sm {{ $hasErr('department') }}"
                   value="{{ $old('department') }}" placeholder="Contoh: Operasional">
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm">Jabatan</label>
            <input type="text" name="position" class="form-control form-control-sm {{ $hasErr('position') }}"
                   value="{{ $old('position') }}" placeholder="Contoh: Supervisor">
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm">Lokasi Kerja</label>
            <input type="text" name="work_location" class="form-control form-control-sm {{ $hasErr('work_location') }}"
                   value="{{ $old('work_location') }}" placeholder="Contoh: Surabaya">
        </div>
    </div>

    {{-- Password --}}
    <div class="form-section-title mt-2"><i class="fas fa-lock"></i> Kata Sandi</div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label form-label-sm">
                Kata Sandi @if(!$isEdit) <span class="text-danger">*</span> @else <span style="font-size:.72rem;color:var(--text-faint)">(kosongkan jika tidak diubah)</span> @endif
            </label>
            <input type="password" name="password"
                   class="form-control form-control-sm {{ $hasErr('password') }}"
                   placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}"
                   {{ $isEdit ? '' : 'required' }}>
            @if($err('password'))
                <div class="invalid-feedback d-block">{{ $err('password') }}</div>
            @endif
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-sm">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation"
                   class="form-control form-control-sm"
                   placeholder="Ulangi kata sandi">
        </div>
    </div>

    {{-- Roles --}}
    <div class="form-section-title mt-2"><i class="fas fa-user-tag"></i> Role</div>
    <div class="row g-2">
        @forelse($roles as $role)
        <div class="col-sm-6 col-md-4">
            <label class="d-flex align-items-start gap-2 perm-check-item" style="cursor:pointer">
                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}"
                       class="form-check-input mt-1"
                       {{ in_array($role->id, old('role_ids', $assignedIds)) ? 'checked' : '' }}>
                <div>
                    <div class="perm-check-label">{{ $role->name }}</div>
                    @if($role->description)
                        <div class="perm-slug">{{ $role->description }}</div>
                    @endif
                </div>
            </label>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted mb-0" style="font-size:.875rem">
                Belum ada role. <a href="{{ route('user-management.roles.create') }}">Buat role dulu</a>.
            </p>
        </div>
        @endforelse
    </div>
</div>

<div class="um-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i> Field bertanda * wajib diisi.
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $isEdit
               ? route('user-management.users.index', $user)
               : route('user-management.users.index') }}"
           class="btn btn-outline-secondary btn-sm">Batal</a>
        <button type="submit" class="btn-save-um" id="btnSubmit">
            <i class="fas fa-check"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah User' }}
        </button>
    </div>
</div>