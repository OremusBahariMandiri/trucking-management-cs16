{{-- Tab 3: Alamat Domisili --}}
<div class="drv-form-card">

    <div class="form-section-title">
        <i class="fas fa-home"></i>
        <span data-en="Domicile Address">Alamat Domisili</span>
    </div>

    {{-- Toggle: sama dengan KTP --}}
    <div class="drv-toggle-wrap mb-3" id="domicileToggleWrap">
        <input class="form-check-input" type="checkbox"
               name="domicile_same_as_ktp" id="domicileSameAsKtp"
               value="1" {{ $old('domicile_same_as_ktp', $driver?->domicile_same_as_ktp) ? 'checked' : '' }}>
        <label class="drv-toggle-label" for="domicileSameAsKtp">
            <span data-en="Same as KTP address">Sama dengan alamat KTP</span>
            <small data-en="Fields below will be auto-filled">Field di bawah akan terisi otomatis</small>
        </label>
    </div>

    <div id="domicileFields">
        <div class="row g-3">

            {{-- Alamat Domisili --}}
            <div class="col-12">
                <label for="domicile_address" class="form-label form-label-sm" data-en="Street Address">Alamat Jalan</label>
                <textarea name="domicile_address" id="domicile_address" rows="2"
                          class="form-control form-control-sm {{ $hasErr('domicile_address') }}"
                          placeholder="Jl. ...">{{ $old('domicile_address') }}</textarea>
                @error('domicile_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kelurahan --}}
            <div class="col-md-4">
                <label for="domicile_village" class="form-label form-label-sm" data-en="Village/Kelurahan">Kelurahan</label>
                <input type="text" name="domicile_village" id="domicile_village"
                       class="form-control form-control-sm {{ $hasErr('domicile_village') }}"
                       value="{{ $old('domicile_village') }}">
                @error('domicile_village')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kecamatan --}}
            <div class="col-md-4">
                <label for="domicile_district" class="form-label form-label-sm" data-en="District/Kecamatan">Kecamatan</label>
                <input type="text" name="domicile_district" id="domicile_district"
                       class="form-control form-control-sm {{ $hasErr('domicile_district') }}"
                       value="{{ $old('domicile_district') }}">
                @error('domicile_district')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kota --}}
            <div class="col-md-4">
                <label for="domicile_city" class="form-label form-label-sm" data-en="City/Kab.">Kota / Kabupaten</label>
                <input type="text" name="domicile_city" id="domicile_city"
                       class="form-control form-control-sm {{ $hasErr('domicile_city') }}"
                       value="{{ $old('domicile_city') }}">
                @error('domicile_city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Provinsi --}}
            <div class="col-md-4">
                <label for="domicile_province" class="form-label form-label-sm" data-en="Province">Provinsi</label>
                <input type="text" name="domicile_province" id="domicile_province"
                       class="form-control form-control-sm {{ $hasErr('domicile_province') }}"
                       value="{{ $old('domicile_province') }}">
                @error('domicile_province')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kode Pos --}}
            <div class="col-md-4">
                <label for="domicile_postal_code" class="form-label form-label-sm" data-en="Postal Code">Kode Pos</label>
                <input type="text" name="domicile_postal_code" id="domicile_postal_code"
                       class="form-control form-control-sm {{ $hasErr('domicile_postal_code') }}"
                       value="{{ $old('domicile_postal_code') }}"
                       maxlength="10">
                @error('domicile_postal_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>