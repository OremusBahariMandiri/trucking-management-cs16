<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-images"></i>
        <span data-en="Vehicle Photos">Foto Kendaraan</span>
    </div>

    {{-- Foto existing (mode edit) --}}
    @if($isEdit && $fleet->photos->count())
    <p style="font-size:.82rem;color:var(--text-muted)" class="mb-2">
        <span data-en="Saved photos — click image to manage">Foto tersimpan — klik gambar untuk kelola</span>
    </p>
    <div class="photo-preview-grid mb-4" id="existingPhotoGrid">
        @foreach($fleet->photos->sortByDesc('is_primary')->sortBy('sort_order') as $photo)
        <div class="photo-item" id="photo-item-{{ $photo->id }}">
            <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}">

            @if($photo->is_primary)
                <span class="photo-badge-primary" data-en="PRIMARY">UTAMA</span>
            @endif

            @if($photo->caption)
                <div class="photo-caption">{{ $photo->caption }}</div>
            @endif

            <div class="photo-overlay">
                @if(!$photo->is_primary)
                <button type="button" class="btn btn-sm btn-light"
                        data-photo-primary="{{ route('data-management.fleet-data.foto.set-primary', [$fleet, $photo]) }}"
                        title="Set sebagai foto utama">
                    <i class="fas fa-star"></i>
                </button>
                @endif
                <button type="button" class="btn btn-sm btn-danger"
                        data-photo-delete="{{ route('data-management.fleet-data.foto.destroy', [$fleet, $photo]) }}"
                        title="Hapus foto">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Drop zone upload foto baru --}}
    <p style="font-size:.82rem;color:var(--text-muted)" class="mb-2">
        <span data-en="{{ $isEdit ? 'Add new photos:' : 'Upload vehicle photos:' }}">{{ $isEdit ? 'Tambah foto baru:' : 'Upload foto kendaraan:' }}</span>
        <span class="ms-1" style="color:var(--text-faint)" data-en="Max 5MB per photo, JPG/PNG/WebP format">Max 5MB per foto, format JPG/PNG/WebP</span>
    </p>

    <div class="photo-drop-zone mb-3" id="photoDropZone">
        <i class="fas fa-cloud-upload-alt"></i>
        <p><span data-en="Drag & drop photos here, or">Tarik &amp; lepas foto di sini, atau</span>
           <strong data-en="click to select files">klik untuk pilih file</strong></p>
        <p class="mt-1" style="font-size:.75rem" data-en="First photo will automatically become the primary photo">Foto pertama akan otomatis menjadi foto utama</p>
        <input type="file" id="photoFileInput" accept="image/*" multiple style="display:none">
    </div>

    {{-- Preview grid (foto baru yang dipilih) --}}
    <div class="photo-preview-grid" id="photoPreviewGrid"></div>

    {{-- Template untuk preview item (disembunyikan, di-clone via JS) --}}
    <template id="photoItemTemplate">
        <div class="photo-item">
            <img src="" alt="preview">
            {{-- File input (diisi via JS DataTransfer) --}}
            <input type="file" hidden>

            <div class="photo-overlay">
                <button type="button" class="btn btn-sm btn-danger btn-remove-photo" title="Batal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Caption & kategori tampil di luar overlay --}}
            <div class="photo-caption" style="padding:.25rem .4rem;background:rgba(0,0,0,.55);position:absolute;bottom:0;left:0;right:0;display:flex;gap:.3rem;align-items:center">
                <select class="category-select form-select form-select-sm"
                        style="font-size:.65rem;padding:.1rem .25rem;flex:0 0 80px;background:transparent;border:none;color:#fff">
                    <option value="">Kategori</option>
                    @foreach($options['photo_categories'] as $val => $label)
                        <option value="{{ $val }}" style="color:#000">{{ $label }}</option>
                    @endforeach
                </select>
                <input type="text" class="caption-input form-control form-control-sm"
                       placeholder="Keterangan…"
                       style="font-size:.65rem;background:transparent;border:none;color:#fff;padding:.1rem .2rem">
            </div>
        </div>
    </template>

</div>