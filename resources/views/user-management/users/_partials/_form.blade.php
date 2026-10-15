@php
    $isEdit      = !is_null($user ?? null);
    $old         = fn(string $f, $d = null) => old($f, $isEdit ? ($user?->$f ?? $d) : $d);
    $err         = fn(string $f) => $errors->first($f);
    $hasErr      = fn(string $f) => $errors->has($f) ? 'is-invalid' : '';
    $assignedIds = $isEdit ? $user->roles->pluck('id')->toArray() : [];
@endphp

<div class="um-form-card">

    {{-- Data Akun --}}
    <div class="form-section-title"><i class="fas fa-id-card"></i> <span data-en="Account Data">Data Akun</span></div>
    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="Employee Code">Kode Karyawan</label>
            <input type="text" name="employee_code" class="form-control form-control-sm {{ $hasErr('employee_code') }}"
                   value="{{ $old('employee_code') }}"
                   placeholder="EMP-XXXX (auto jika kosong)"
                   data-placeholder-en="EMP-XXXX (auto if empty)">
            @if($err('employee_code'))
                <div class="invalid-feedback d-block">{{ $err('employee_code') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="NIK">NIK</label>
            <input type="text" name="employee_id_number" class="form-control form-control-sm {{ $hasErr('employee_id_number') }}"
                   value="{{ $old('employee_id_number') }}"
                   placeholder="Nomor Induk Karyawan"
                   data-placeholder-en="Employee ID Number">
            @if($err('employee_id_number'))
                <div class="invalid-feedback d-block">{{ $err('employee_id_number') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="Full Name">
                Nama Lengkap <span class="text-danger">*</span>
            </label>
            <input type="text" name="full_name" class="form-control form-control-sm {{ $hasErr('full_name') }}"
                   value="{{ $old('full_name') }}"
                   placeholder="Nama lengkap"
                   data-placeholder-en="Full name"
                   required>
            @if($err('full_name'))
                <div class="invalid-feedback d-block">{{ $err('full_name') }}</div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="Department">Departemen</label>
            <input type="text" name="department" class="form-control form-control-sm {{ $hasErr('department') }}"
                   value="{{ $old('department') }}"
                   placeholder="Contoh: Operasional"
                   data-placeholder-en="e.g. Operations">
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="Position">Jabatan</label>
            <input type="text" name="position" class="form-control form-control-sm {{ $hasErr('position') }}"
                   value="{{ $old('position') }}"
                   placeholder="Contoh: Supervisor"
                   data-placeholder-en="e.g. Supervisor">
        </div>

        <div class="col-md-4">
            <label class="form-label form-label-sm" data-en="Work Location">Lokasi Kerja</label>
            <input type="text" name="work_location" class="form-control form-control-sm {{ $hasErr('work_location') }}"
                   value="{{ $old('work_location') }}"
                   placeholder="Contoh: Surabaya"
                   data-placeholder-en="e.g. Surabaya">
        </div>
    </div>

    {{-- Password --}}
    <div class="form-section-title mt-2"><i class="fas fa-lock"></i> <span data-en="Password">Kata Sandi</span></div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label form-label-sm">
                @if(!$isEdit)
                    <span data-en="Password">Kata Sandi</span> <span class="text-danger">*</span>
                @else
                    <span data-en="Password">Kata Sandi</span>
                    <span style="font-size:.72rem;color:var(--text-faint)"
                          data-en="(leave blank to keep current)">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input type="password" name="password"
                   class="form-control form-control-sm {{ $hasErr('password') }}"
                   placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}"
                   data-placeholder-en="{{ $isEdit ? 'Leave blank to keep current' : 'Min. 8 characters' }}"
                   {{ $isEdit ? '' : 'required' }}>
            @if($err('password'))
                <div class="invalid-feedback d-block">{{ $err('password') }}</div>
            @endif
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-sm" data-en="Confirm Password">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation"
                   class="form-control form-control-sm"
                   placeholder="Ulangi kata sandi"
                   data-placeholder-en="Repeat password">
        </div>
    </div>

    {{-- Roles --}}
    <div class="form-section-title mt-2"><i class="fas fa-user-tag"></i> <span data-en="Role">Role</span></div>
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
                <span data-en="No roles yet.">Belum ada role.</span>
                <a href="{{ route('user-management.roles.create') }}" data-en="Create a role first.">Buat role dulu</a>.
            </p>
        </div>
        @endforelse
    </div>
</div>

<div class="um-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="Fields marked * are required.">Field bertanda * wajib diisi.</span>
    </div>
   <div class="d-flex gap-2">
    <a href="{{ $isEdit
           ? route('user-management.users.show', $user)
           : route('user-management.users.index') }}"
       class="btn btn-secondary">
        <span data-en="Cancel">Batal</span>
    </a>
    <button type="submit" class="btn-save-um" id="btnSubmit">
        <i class="fas fa-check"></i>
        <span data-en="{{ $isEdit ? 'Save Changes' : 'Add User' }}">
            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah User' }}
        </span>
    </button>
</div>
</div>