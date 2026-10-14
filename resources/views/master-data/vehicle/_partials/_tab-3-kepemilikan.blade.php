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
                @foreach($options['ownership_statuses'] as $val => $label)
                    <option value="{{ $val }}" {{ $old('ownership_status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tahun Perolehan --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Acquisition Year">Tahun Perolehan</label>
            <input type="number" name="acquisition_year"
                   class="form-control {{ $hasErr('acquisition_year') }}"
                   value="{{ $old('acquisition_year') }}"
                   min="1980" max="{{ date('Y') }}"
                   placeholder="{{ date('Y') }}">
        </div>

        {{-- Nilai Perolehan --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Acquisition Value (IDR)">Nilai Perolehan (Rp)</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" name="acquisition_value"
                       class="form-control {{ $hasErr('acquisition_value') }}"
                       value="{{ $old('acquisition_value') }}"
                       min="0" step="1000000"
                       placeholder="500000000">
            </div>
            <div class="form-text" data-en="Value in rupiah">Nilai dalam rupiah</div>
        </div>

        {{-- Perusahaan Leasing --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Leasing Company">Perusahaan Leasing</label>
            <input type="text" name="leasing_company"
                   class="form-control {{ $hasErr('leasing_company') }}"
                   value="{{ $old('leasing_company') }}"
                   placeholder="Nama perusahaan leasing (jika ada)">
        </div>

        {{-- Akhir Leasing --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Leasing End Date">Tanggal Akhir Leasing</label>
            <input type="date" name="leasing_end_date"
                   class="form-control {{ $hasErr('leasing_end_date') }}"
                   value="{{ $old('leasing_end_date') }}">
        </div>

    </div>

    <div class="form-section-title"><i class="fas fa-sitemap"></i>
        <span data-en="Assignment">Penugasan</span>
    </div>

    <div class="row g-3">

        {{-- Departemen --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Department">Departemen</label>
            <input type="number" name="department_id"
                   class="form-control {{ $hasErr('department_id') }}"
                   value="{{ $old('department_id') }}"
                   placeholder="ID Departemen">
            <div class="form-text" data-en="Department table will be created in AC01">Tabel departemen akan dibuat pada AC01</div>
        </div>

        {{-- Cabang --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Branch">Cabang</label>
            <input type="number" name="branch_id"
                   class="form-control {{ $hasErr('branch_id') }}"
                   value="{{ $old('branch_id') }}"
                   placeholder="ID Cabang">
            <div class="form-text" data-en="Branch table will be created in AC02">Tabel cabang akan dibuat pada AC02</div>
        </div>

        {{-- Pengemudi --}}
        <div class="col-md-4">
            <label class="form-label" data-en="Assigned Driver">Pengemudi Tetap</label>
            <select name="driver_id" class="form-select {{ $hasErr('driver_id') }}">
                <option value="" data-en="-- Select Driver --">-- Pilih Pengemudi --</option>
                {{--
                    Populate dari $options['drivers'] jika sudah tersedia.
                    Contoh: @foreach($options['drivers'] ?? [] as $d) ... @endforeach
                --}}
            </select>
            <div class="form-text" data-en="Driver who regularly operates this vehicle">Pengemudi yang biasa mengoperasikan kendaraan ini</div>
        </div>

    </div>
</div>