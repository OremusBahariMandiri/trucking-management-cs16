@php
    $isEdit = !is_null($role ?? null);
    $old = fn(string $f, $d = null) => old($f, $isEdit ? $role?->$f ?? $d : $d);
    $err = fn(string $f) => $errors->first($f);
    $hasErr = fn(string $f) => $errors->has($f) ? 'is-invalid' : '';
@endphp

<div class="um-form-card">
    <div class="form-section-title"><i class="fas fa-user-tag"></i> Data Role</div>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label form-label-sm">Nama Role <span class="text-danger">*</span></label>
            <input type="text" name="name" id="roleName" class="form-control form-control-sm {{ $hasErr('name') }}"
                value="{{ $old('name') }}" placeholder="Contoh: Manajer Operasional" required>
            @if ($err('name'))
                <div class="invalid-feedback d-block">{{ $err('name') }}</div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label form-label-sm">
                Slug
                <span style="font-size:.7rem;color:var(--text-faint)">(auto dari nama)</span>
            </label>
            <input type="text" name="slug" id="roleSlug"
                class="form-control form-control-sm {{ $hasErr('slug') }}" value="{{ $old('slug') }}"
                placeholder="manajer-operasional">
            @if ($err('slug'))
                <div class="invalid-feedback d-block">{{ $err('slug') }}</div>
            @endif
        </div>

        <div class="col-12">
            <label class="form-label form-label-sm">Deskripsi</label>
            <textarea name="description" class="form-control form-control-sm {{ $hasErr('description') }}" rows="2"
                placeholder="Deskripsi singkat tentang role ini">{{ $old('description') }}</textarea>
        </div>
    </div>
</div>

<div class="um-action-bar">
    <div style="font-size:.8rem;color:var(--text-muted)">
        <i class="fas fa-info-circle me-1"></i>
        <span data-en="After saving, set permissions on the role detail page.">Setelah disimpan, atur permission di
            halaman detail role.</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $isEdit ? route('user-management.roles.show', $role) : route('user-management.roles.index') }}"
            class="btn btn-secondary">
            <span data-en="Cancel">Batal</span>
        </a>
        <button type="submit" class="btn-save-um">
            <i class="fas fa-check"></i>
            <span data-en="{{ $isEdit ? 'Save Changes' : 'Add Role' }}">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Role' }}
            </span>
        </button>
    </div>
</div>

@push('scripts')
    <script>
        // Auto-generate slug dari nama
        const nameInput = document.getElementById('roleName');
        const slugInput = document.getElementById('roleSlug');
        @if (!$isEdit)
            nameInput?.addEventListener('input', function() {
                slugInput.value = this.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            });
        @endif
    </script>
@endpush
