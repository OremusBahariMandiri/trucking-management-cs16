{{-- Tab 2: Data KTP --}}
<div class="drv-form-card">

    <div class="form-section-title">
        <i class="fas fa-map-marker-alt"></i>
        <span data-en="KTP Address">Alamat Sesuai KTP</span>
    </div>

    <div class="row g-3">

        {{-- Alamat KTP --}}
        <div class="col-12">
            <label for="ktp_address" class="form-label form-label-sm" data-en="Street Address">Alamat Jalan</label>
            <textarea name="ktp_address" id="ktp_address" rows="2"
                class="form-control form-control-sm {{ $hasErr('ktp_address') }}" placeholder="Jl. ...">{{ $old('ktp_address') }}</textarea>
            @error('ktp_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelurahan --}}
        <div class="col-md-4">
            <label for="ktp_village" class="form-label form-label-sm" data-en="Village/Kelurahan">Kelurahan</label>
            <input type="text" name="ktp_village" id="ktp_village"
                class="form-control form-control-sm {{ $hasErr('ktp_village') }}" value="{{ $old('ktp_village') }}">
            @error('ktp_village')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kecamatan --}}
        <div class="col-md-4">
            <label for="ktp_district" class="form-label form-label-sm" data-en="District/Kecamatan">Kecamatan</label>
            <input type="text" name="ktp_district" id="ktp_district"
                class="form-control form-control-sm {{ $hasErr('ktp_district') }}" value="{{ $old('ktp_district') }}">
            @error('ktp_district')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kota --}}
        <div class="col-md-4">
            <label for="ktp_city" class="form-label form-label-sm" data-en="City/Kab.">Kota / Kabupaten</label>
            <input type="text" name="ktp_city" id="ktp_city"
                class="form-control form-control-sm {{ $hasErr('ktp_city') }}" value="{{ $old('ktp_city') }}">
            @error('ktp_city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Provinsi --}}
        <div class="col-md-4">
            <label for="ktp_province" class="form-label form-label-sm" data-en="Province">Provinsi</label>
            <input type="text" name="ktp_province" id="ktp_province"
                class="form-control form-control-sm {{ $hasErr('ktp_province') }}" value="{{ $old('ktp_province') }}">
            @error('ktp_province')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kode Pos --}}
        <div class="col-md-4">
            <label for="ktp_postal_code" class="form-label form-label-sm" data-en="Postal Code">Kode Pos</label>
            <input type="text" name="ktp_postal_code" id="ktp_postal_code"
                class="form-control form-control-sm {{ $hasErr('ktp_postal_code') }}"
                value="{{ $old('ktp_postal_code') }}" maxlength="10">
            @error('ktp_postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>
</div>
