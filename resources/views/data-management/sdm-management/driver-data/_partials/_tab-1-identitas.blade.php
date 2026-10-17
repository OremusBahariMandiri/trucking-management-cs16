{{-- Tab 1: Identitas Supir --}}
<div class="drv-form-card">

    <div class="row g-3">

        {{-- Kiri: Foto --}}
        <div class="col-md-3">
            <div class="form-section-title">
                <i class="fas fa-camera"></i>
                <span data-en="Photo">Foto</span>
            </div>

            <div class="drv-photo-upload" id="photoUploadArea">
                {{--
                    Saat edit + ada foto: div preview jadi target lightbox (id=driverPhotoThumb).
                    JS initPhotoModal() membaca data-src & data-name, lalu membuka lightbox.
                    Saat create / belum ada foto: id biasa, tidak ada lightbox.
                --}}
                <div class="drv-photo-preview"
                    @if ($isEdit && $driver->photo_path) id="driverPhotoThumb"
                         data-src="{{ Storage::url($driver->photo_path) }}"
                         data-name="{{ $driver->full_name }}"
                         style="cursor:zoom-in"
                         title="Klik untuk perbesar"
                     @else
                         id="photoPreview" @endif>
                    @if ($isEdit && $driver->photo_path)
                        <img src="{{ Storage::url($driver->photo_path) }}" alt="Foto Supir" id="photoPreviewImg">
                    @else
                        <div class="drv-photo-default" id="photoDefault">
                            <i class="fas fa-user"></i>
                            <span data-en="Click to upload">Klik untuk upload</span>
                        </div>
                        <img src="" alt="" id="photoPreviewImg" hidden>
                    @endif
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*" class="{{ $hasErr('photo') }}"
                    hidden>
                <div class="drv-photo-actions mt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                        onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-upload me-1"></i>
                        <span data-en="Choose Photo">Pilih Foto</span>
                    </button>
                    @if ($isEdit && $driver->photo_path)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_photo" id="removePhoto"
                                value="1">
                            <label class="form-check-label" for="removePhoto" style="font-size:.8rem"
                                data-en="Remove photo">
                                Hapus foto
                            </label>
                        </div>
                    @endif
                </div>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div style="font-size:.75rem;color:var(--text-faint);margin-top:.4rem">
                    <span data-en="Max 2MB, JPG/PNG">Maks 2MB, JPG/PNG</span>
                </div>
            </div>
        </div>

        {{-- Kanan: Data Diri --}}
        <div class="col-md-9">
            <div class="form-section-title">
                <i class="fas fa-user-circle"></i>
                <span data-en="Personal Data">Data Diri</span>
            </div>

            <div class="row g-3">

                {{-- Nama Lengkap --}}
                <div class="col-md-12">
                    <label for="full_name" class="form-label form-label-sm">
                        <span data-en="Full Name">Nama Lengkap</span> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="full_name" id="full_name"
                        class="form-control form-control-sm {{ $hasErr('full_name') }}" value="{{ $old('full_name') }}"
                        placeholder="Nama lengkap sesuai KTP">
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Kode Supir --}}
                <div class="col-md-6">
                    <label for="driver_code" class="form-label form-label-sm">
                        <span data-en="Driver Code">Kode Supir</span> <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="driver_code" id="driver_code"
                            class="form-control {{ $hasErr('driver_code') }}" value="{{ $old('driver_code') }}"
                            placeholder="SP-001">
                        <button type="button" class="btn btn-outline-secondary" id="btnGenerateCode"
                            data-url="{{ route('data-management.sdm-management.driver-data.api.generate-code') }}"
                            title="Generate otomatis">
                            <i class="fas fa-magic"></i>
                        </button>
                    </div>
                    @error('driver_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nik" class="form-label form-label-sm" data-en="NIK (ID Number)">NIK</label>
                    <input type="text" name="nik" id="nik"
                        class="form-control form-control-sm {{ $hasErr('nik') }}" value="{{ $old('nik') }}"
                        maxlength="16" placeholder="16 digit NIK">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-md-4">
                    <label for="gender" class="form-label form-label-sm" data-en="Gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-select form-select-sm {{ $hasErr('gender') }}">
                        <option value="male" {{ $old('gender', 'male') === 'male' ? 'selected' : '' }}
                            data-en="Male">Laki-laki</option>
                        <option value="female" {{ $old('gender') === 'female' ? 'selected' : '' }} data-en="Female">
                            Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tempat Lahir --}}
                <div class="col-md-4">
                    <label for="birth_place" class="form-label form-label-sm" data-en="Birth Place">Tempat
                        Lahir</label>
                    <input type="text" name="birth_place" id="birth_place"
                        class="form-control form-control-sm {{ $hasErr('birth_place') }}"
                        value="{{ $old('birth_place') }}">
                    @error('birth_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tgl Lahir --}}
                <div class="col-md-4">
                    <label for="birth_date" class="form-label form-label-sm" data-en="Birth Date">Tanggal
                        Lahir</label>
                    <input type="date" name="birth_date" id="birth_date"
                        class="form-control form-control-sm {{ $hasErr('birth_date') }}"
                        value="{{ $old('birth_date', $driver?->birth_date?->format('Y-m-d')) }}">
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Pernikahan --}}
                <div class="col-md-4">
                    <label for="marital_status" class="form-label form-label-sm" data-en="Marital Status">Status
                        Pernikahan</label>
                    <select name="marital_status" id="marital_status"
                        class="form-select form-select-sm {{ $hasErr('marital_status') }}">
                        <option value="" data-en="-- Select --">-- Pilih --</option>
                        <option value="single" {{ $old('marital_status') === 'single' ? 'selected' : '' }}
                            data-en="Single">Belum Menikah</option>
                        <option value="married" {{ $old('marital_status') === 'married' ? 'selected' : '' }}
                            data-en="Married">Menikah</option>
                        <option value="divorced" {{ $old('marital_status') === 'divorced' ? 'selected' : '' }}
                            data-en="Divorced">Cerai Hidup</option>
                        <option value="widowed" {{ $old('marital_status') === 'widowed' ? 'selected' : '' }}
                            data-en="Widowed">Cerai Mati</option>
                    </select>
                    @error('marital_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jumlah Anak --}}
                <div class="col-md-4">
                    <label for="children_count" class="form-label form-label-sm" data-en="Children">Jumlah
                        Anak</label>
                    <input type="number" name="children_count" id="children_count"
                        class="form-control form-control-sm {{ $hasErr('children_count') }}"
                        value="{{ $old('children_count', 0) }}" min="0" max="20">
                    @error('children_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- No. Telepon --}}
                <div class="col-md-4">
                    <label for="phone" class="form-label form-label-sm" data-en="Phone">No. Telepon</label>
                    <input type="text" name="phone" id="phone"
                        class="form-control form-control-sm {{ $hasErr('phone') }}" value="{{ $old('phone') }}"
                        placeholder="08xx-xxxx-xxxx">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-4">
                    <label for="email" class="form-label form-label-sm" data-en="Email">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control form-control-sm {{ $hasErr('email') }}" value="{{ $old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tgl Bergabung --}}
                <div class="col-md-4">
                    <label for="join_date" class="form-label form-label-sm" data-en="Join Date">Tgl Bergabung</label>
                    <input type="date" name="join_date" id="join_date"
                        class="form-control form-control-sm {{ $hasErr('join_date') }}"
                        value="{{ $old('join_date', $driver?->join_date?->format('Y-m-d')) }}">
                    @error('join_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-4">
                    <label for="status" class="form-label form-label-sm" data-en="Status">Status</label>
                    <select name="status" id="status"
                        class="form-select form-select-sm {{ $hasErr('status') }}">
                        <option value="active" {{ $old('status', 'active') === 'active' ? 'selected' : '' }}
                            data-en="Active">Aktif</option>
                        <option value="inactive" {{ $old('status') === 'inactive' ? 'selected' : '' }}
                            data-en="Inactive">Tidak Aktif</option>
                        <option value="resigned" {{ $old('status') === 'resigned' ? 'selected' : '' }}
                            data-en="Resigned">Resign</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

</div>
