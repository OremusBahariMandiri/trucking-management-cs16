<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-id-card"></i>
        <span data-en="Ownership Status">Status Kepemilikan</span>
    </div>

    <div class="row g-3 mb-4">

        {{-- Status Kepemilikan --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Ownership Status">Status Kepemilikan</label>
            <select name="ownership_status" class="form-select {{ $hasErr('ownership_status') }}">
                <option value="" data-en="-- Select --">-- Pilih --</option>
                @foreach ($options['ownership_statuses'] as $val => $label)
                    <option value="{{ $val }}"
                        {{ (int) $old('ownership_status') === (int) $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tahun Perolehan --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Acquisition Year">Tahun Perolehan</label>
            <input type="number" name="acquisition_year" class="form-control {{ $hasErr('acquisition_year') }}"
                value="{{ $old('acquisition_year') }}" min="1980" max="{{ date('Y') }}"
                placeholder="{{ date('Y') }}">
        </div>

        {{-- Nilai Perolehan --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Acquisition Value (IDR)">Nilai Perolehan (Rp)</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="acquisition_value" class="form-control {{ $hasErr('acquisition_value') }}"
                    value="{{ $old('acquisition_value') }}" min="0" step="1000000" placeholder="500000000">
            </div>
        </div>

        {{-- Perusahaan Leasing --}}
        {{-- <div class="col-md-6">
            <label class="form-label" data-en="Leasing Company">Perusahaan Leasing</label>
            <input type="text" name="leasing_company" class="form-control {{ $hasErr('leasing_company') }}"
                value="{{ $old('leasing_company') }}" placeholder="Nama perusahaan leasing (jika ada)">
        </div> --}}

        {{-- Akhir Leasing --}}
        {{-- <div class="col-md-6">
            <label class="form-label" data-en="Leasing End Date">Tanggal Akhir Leasing</label>
            <input type="date" name="leasing_end_date" class="form-control {{ $hasErr('leasing_end_date') }}"
                value="{{ $old('leasing_end_date') }}">
        </div> --}}

    </div>
</div>
