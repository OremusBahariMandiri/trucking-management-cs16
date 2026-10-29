<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-images"></i>
        <span data-en="Vehicle Photos">Foto Kendaraan</span>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         FOTO TERSIMPAN (mode edit saja)
         Pakai .photo-preview-grid + .photo-item dari SCSS
    ════════════════════════════════════════════════════════ --}}
    @if($isEdit && $fleet->photos->count())
    <p class="mb-2" style="font-size:.82rem;color:var(--text-muted)">
        <i class="fas fa-check-circle text-success me-1"></i>
        Foto tersimpan
        <span class="badge bg-secondary ms-1">{{ $fleet->photos->count() }}</span>
    </p>

    {{-- ═══ FOTO TERSIMPAN — dengan inline edit kategori & keterangan ═══ --}}
    <div class="mb-4" id="existingPhotoList">
        @foreach($fleet->photos->sortByDesc('is_primary')->sortBy('sort_order') as $photo)
        @php $catLabels = \App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES; @endphp
        <div class="border rounded mb-2 overflow-hidden existing-photo-row"
             id="photo-item-{{ $photo->id }}"
             style="background:var(--surface);border-color:var(--border)!important">
            <div class="d-flex gap-3 align-items-start p-2">

                {{-- Thumbnail --}}
                <div class="flex-shrink-0 position-relative rounded overflow-hidden"
                     style="width:90px;height:90px;background:var(--surface-2)">
                    <img src="{{ Storage::url($photo->file_path) }}"
                         alt="{{ $photo->caption }}"
                         style="width:100%;height:100%;object-fit:cover;display:block">
                    @if($photo->is_primary)
                    <span class="position-absolute top-0 start-0 m-1 badge text-white"
                          style="font-size:.55rem;background:rgba(220,170,0,.9)">
                        <i class="fas fa-star me-1"></i>UTAMA
                    </span>
                    @endif
                </div>

                {{-- Edit fields --}}
                <div class="flex-grow-1 row g-2 align-items-end">

                    {{-- Kategori --}}
                    <div class="col-md-5 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Kategori</label>
                        <select class="form-select form-select-sm photo-meta-category"
                                data-photo-id="{{ $photo->id }}">
                            @foreach($catLabels as $val => $lbl)
                            <option value="{{ $val }}" {{ $photo->category === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-5 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Keterangan</label>
                        <input type="text"
                               class="form-control form-control-sm photo-meta-caption"
                               data-photo-id="{{ $photo->id }}"
                               value="{{ $photo->caption ?? '' }}"
                               placeholder="Opsional">
                    </div>

                    {{-- Simpan --}}
                    <div class="col-md-2 col-12">
                        <button type="button"
                                class="btn btn-sm btn-primary w-100 btn-save-meta"
                                data-photo-id="{{ $photo->id }}"
                                data-url="{{ route('data-management.fleet-data.foto.update-meta', [$fleet, $photo]) }}"
                                title="Simpan perubahan">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                        <div class="meta-save-feedback mt-1 d-none text-center"
                             id="meta-feedback-{{ $photo->id }}"
                             style="font-size:.7rem"></div>
                    </div>

                </div>

                {{-- Aksi kanan --}}
                <div class="flex-shrink-0 d-flex flex-column gap-1 align-self-start">
                    @if(!$photo->is_primary)
                    <button type="button" class="btn btn-sm btn-outline-warning"
                            data-photo-primary="{{ route('data-management.fleet-data.foto.set-primary', [$fleet, $photo]) }}"
                            title="Jadikan foto utama" style="font-size:.7rem;padding:2px 7px">
                        <i class="fas fa-star"></i>
                    </button>
                    @endif
                    <button type="button" class="btn btn-sm btn-outline-danger"
                            data-photo-delete="{{ route('data-management.fleet-data.foto.destroy', [$fleet, $photo]) }}"
                            title="Hapus foto" style="font-size:.7rem;padding:2px 7px">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- JS: handle save meta via fetch --}}
    <script>
    document.querySelectorAll('.btn-save-meta').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var photoId  = this.dataset.photoId;
            var url      = this.dataset.url;
            var row      = document.getElementById('photo-item-' + photoId);
            var category = row.querySelector('.photo-meta-category').value;
            var caption  = row.querySelector('.photo-meta-caption').value;
            var feedback = document.getElementById('meta-feedback-' + photoId);
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf     = csrfMeta ? csrfMeta.content : '';

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ category: category, caption: caption })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan';
                if (data.success) {
                    feedback.className = 'meta-save-feedback mt-1 text-center text-success';
                    feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i>Tersimpan';
                } else {
                    feedback.className = 'meta-save-feedback mt-1 text-center text-danger';
                    feedback.innerHTML = '<i class="fas fa-times-circle me-1"></i>Gagal';
                }
                setTimeout(function() {
                    feedback.className = 'meta-save-feedback mt-1 d-none text-center';
                }, 2500);
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan';
                feedback.className = 'meta-save-feedback mt-1 text-center text-danger';
                feedback.innerHTML = '<i class="fas fa-times-circle me-1"></i>Error';
                setTimeout(function() {
                    feedback.className = 'meta-save-feedback mt-1 d-none text-center';
                }, 2500);
            });
        });
    });
    </script>

    @endif

    {{-- ═══════════════════════════════════════════════════════
         UPLOAD FOTO BARU
         ─────────────────────────────────────────────────────
         Prinsip SAMA PERSIS seperti tab dokumen:
           • Slot 0 di-hardcode langsung di HTML (name="photos[0][file]")
             → selalu ada di DOM → browser submit tanpa perlu JS
           • Slot tambahan dibuat JS (clone template), pakai real
             <input type="file"> juga, bukan DataTransfer
         ─────────────────────────────────────────────────────
         Tidak ada DataTransfer, tidak ada hidden input trick.
    ════════════════════════════════════════════════════════ --}}

    <div class="d-flex align-items-center justify-content-between mb-3">
        <p class="mb-0" style="font-size:.82rem;color:var(--text-muted)">
            <i class="fas fa-camera me-1" style="color:var(--veh-accent)"></i>
            <span style="color:var(--text);font-weight:600">
                {{ $isEdit ? 'Tambah foto baru' : 'Upload foto kendaraan' }}
            </span>
            <span class="ms-1">— JPG, PNG, WebP · maks. 5 MB/foto</span>
        </p>
        <button type="button" id="btnAddPhotoSlot"
                class="btn btn-sm btn-outline-secondary"
                style="border-color:var(--border);color:var(--text)">
            <i class="fas fa-plus me-1"></i>Tambah Foto Lagi
        </button>
    </div>

    <div id="photoPreviewGrid">

        {{-- ── SLOT 0 ── hardcoded di HTML, persis seperti document_files[stnk]
             Browser SELALU submit input ini. Jika tidak diisi, preparedPhotos()
             mengabaikannya karena file tidak ada.
        ──────────────────────────────────────────────────────────────────── --}}
        <div class="photo-upload-card mb-2 border rounded"
             style="background:var(--surface);border-color:var(--border)!important"
             data-slot-index="0">
            <div class="d-flex gap-3 align-items-start p-2">

                {{-- Thumbnail preview --}}
                <div class="flex-shrink-0 position-relative rounded overflow-hidden"
                     style="width:100px;height:100px;background:var(--surface-2)">
                    <div class="photo-thumb-placeholder d-flex flex-column align-items-center
                                justify-content-center h-100"
                         style="color:var(--text-faint)">
                        <i class="fas fa-image fa-lg mb-1"></i>
                        <span style="font-size:.6rem">Pilih file</span>
                    </div>
                    <img class="photo-thumb-img" src="" alt="preview"
                         style="display:none;position:absolute;inset:0;
                                width:100%;height:100%;object-fit:cover">
                    <span class="photo-index-badge position-absolute top-0 start-0 m-1
                                 badge text-white"
                          style="font-size:.6rem;background:rgba(0,0,0,.55)">#1</span>
                </div>

                {{-- Fields --}}
                <div class="flex-grow-1 row g-2 align-items-start">

                    {{-- File foto — SELALU ADA DI DOM, submit natural --}}
                    <div class="col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">
                            File Foto
                            <small class="ms-1">(JPG, PNG, WebP · maks. 5 MB)</small>
                        </label>
                        <input type="file"
                               name="photos[0][file]"
                               class="photo-file-input form-control form-control-sm {{ $hasErr('photos.0.file') }}"
                               accept="image/jpeg,image/png,image/webp">
                        @if($err('photos.0.file'))
                            <div class="invalid-feedback d-block">{{ $err('photos.0.file') }}</div>
                        @endif
                        <div class="photo-file-info mt-1 d-none"
                             style="font-size:.72rem;color:var(--text-muted)">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <span class="photo-file-name"></span>
                            <span class="photo-file-size ms-1 badge"
                                  style="background:var(--surface-2);color:var(--text-muted);
                                         border:1px solid var(--border)"></span>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-5 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Kategori</label>
                        <select name="photos[0][category]"
                                class="category-select form-select form-select-sm">
                            <option value="">-- Pilih kategori --</option>
                            @foreach(\App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-7 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Keterangan</label>
                        <input type="text"
                               name="photos[0][caption]"
                               class="caption-input form-control form-control-sm"
                               placeholder="Opsional — mis: Tampak depan kiri">
                    </div>

                    {{-- Radio: foto utama --}}
                    <div class="col-12">
                        <div class="form-check mb-0">
                            <input class="form-check-input primary-check"
                                   type="radio" name="photos_primary" value="0" checked>
                            <label class="form-check-label"
                                   style="font-size:.78rem;color:var(--text-muted)">
                                <i class="fas fa-star text-warning me-1"></i>
                                Jadikan foto utama
                            </label>
                        </div>
                    </div>

                </div>

                {{-- Hapus (slot 0 tidak bisa dihapus, hanya dikosongkan) --}}
                <button type="button"
                        class="btn btn-sm btn-outline-secondary btn-clear-photo flex-shrink-0 align-self-start"
                        title="Kosongkan slot ini">
                    <i class="fas fa-times"></i>
                </button>

            </div>
        </div>

        {{-- Slot tambahan diisi JS di sini --}}
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TEMPLATE untuk slot foto TAMBAHAN (slot 1, 2, 3, ...)
         Di-clone JS saat klik "Tambah Foto Lagi".
         name="" dikosongkan — JS set sesuai nomor slot.
    ════════════════════════════════════════════════════════ --}}
    <template id="photoItemTemplate">
        <div class="photo-upload-card mb-2 border rounded"
             style="background:var(--surface);border-color:var(--border)!important">
            <div class="d-flex gap-3 align-items-start p-2">

                <div class="flex-shrink-0 position-relative rounded overflow-hidden"
                     style="width:100px;height:100px;background:var(--surface-2)">
                    <div class="photo-thumb-placeholder d-flex flex-column align-items-center
                                justify-content-center h-100"
                         style="color:var(--text-faint)">
                        <i class="fas fa-image fa-lg mb-1"></i>
                        <span style="font-size:.6rem">Pilih file</span>
                    </div>
                    <img class="photo-thumb-img" src="" alt="preview"
                         style="display:none;position:absolute;inset:0;
                                width:100%;height:100%;object-fit:cover">
                    <span class="photo-index-badge position-absolute top-0 start-0 m-1
                                 badge text-white"
                          style="font-size:.6rem;background:rgba(0,0,0,.55)"></span>
                </div>

                <div class="flex-grow-1 row g-2 align-items-start">

                    <div class="col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">
                            File Foto
                            <small class="ms-1">(JPG, PNG, WebP · maks. 5 MB)</small>
                        </label>
                        {{-- name diset JS --}}
                        <input type="file"
                               class="photo-file-input form-control form-control-sm"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="photo-file-info mt-1 d-none"
                             style="font-size:.72rem;color:var(--text-muted)">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <span class="photo-file-name"></span>
                            <span class="photo-file-size ms-1 badge"
                                  style="background:var(--surface-2);color:var(--text-muted);
                                         border:1px solid var(--border)"></span>
                        </div>
                    </div>

                    <div class="col-md-5 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Kategori</label>
                        <select class="category-select form-select form-select-sm">
                            <option value="">-- Pilih kategori --</option>
                            @foreach(\App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-7 col-12">
                        <label class="form-label mb-1"
                               style="font-size:.75rem;color:var(--text-muted)">Keterangan</label>
                        <input type="text"
                               class="caption-input form-control form-control-sm"
                               placeholder="Opsional — mis: Tampak depan kiri">
                    </div>

                    <div class="col-12">
                        <div class="form-check mb-0">
                            <input class="form-check-input primary-check"
                                   type="radio" name="photos_primary" value="">
                            <label class="form-check-label"
                                   style="font-size:.78rem;color:var(--text-muted)">
                                <i class="fas fa-star text-warning me-1"></i>
                                Jadikan foto utama
                            </label>
                        </div>
                    </div>

                </div>

                <button type="button"
                        class="btn btn-sm btn-outline-danger btn-remove-photo flex-shrink-0 align-self-start"
                        title="Hapus slot foto ini">
                    <i class="fas fa-times"></i>
                </button>

            </div>
        </div>
    </template>

</div>