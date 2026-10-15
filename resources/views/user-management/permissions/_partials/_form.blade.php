@php
    $isEdit = !is_null($permission ?? null);
    $old    = fn(string $f, $d = null) => old($f, $isEdit ? ($permission?->$f ?? $d) : $d);
    $err    = fn(string $f) => $errors->first($f);
    $hasErr = fn(string $f) => $errors->has($f) ? 'is-invalid' : '';
@endphp

<div class="um-form-card">
    <div class="form-section-title"><i class="fas fa-key"></i> Data Permission</div>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label form-label-sm">Nama <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control form-control-sm {{ $hasErr('name') }}"
                   value="{{ $old('name') }}" placeholder="Contoh: Lihat Daftar Armada" required>
            @if($err('name'))
                <div class="invalid-feedback d-block">{{ $err('name') }}</div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label form-label-sm">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug"
                   class="form-control form-control-sm {{ $hasErr('slug') }}"
                   value="{{ $old('slug') }}"
                   placeholder="data-management.fleet-data.monitor" required>
            <div style="font-size:.72rem;color:var(--text-faint);margin-top:.25rem">
                Format: <code>group.sub-group.action</code>
            </div>
            @if($err('slug'))
                <div class="invalid-feedback d-block">{{ $err('slug') }}</div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label form-label-sm">Group <span class="text-danger">*</span></label>
            <input type="text" name="group" list="groupList"
                   class="form-control form-control-sm {{ $hasErr('group') }}"
                   value="{{ $old('group') }}"
                   placeholder="data-management.fleet-data" required>
            <datalist id="groupList">
                @foreach($groups as $g)
                    <option value="{{ $g }}">
                @endforeach
            </datalist>
            @if($err('group'))
                <div class="invalid-feedback d-block">{{ $err('group') }}</div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label form-label-sm">Deskripsi</label>
            <input type="text" name="description"
                   class="form-control form-control-sm"
                   value="{{ $old('description') }}" placeholder="Opsional">
        </div>
    </div>
</div>

<div class="um-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i> Field bertanda * wajib diisi.
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('user-management.permissions.index') }}"
           class="btn btn-outline-secondary btn-sm">Batal</a>
        <button type="submit" class="btn-save-um">
            <i class="fas fa-check"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Permission' }}
        </button>
    </div>
</div>