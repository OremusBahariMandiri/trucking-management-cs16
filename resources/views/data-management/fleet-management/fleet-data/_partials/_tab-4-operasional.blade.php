<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-tachometer-alt"></i>
        <span data-en="Odometer & Service Data">Data Odometer &amp; Servis</span>
    </div>

    <div class="row g-3 mb-4">

        {{-- Odometer Terakhir --}}
        {{-- Odometer Awal --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Initial Odometer (km)">Odometer Awal (km)</label>
            <div class="input-group">
                <input type="number" name="initial_odometer_km"
                    class="form-control {{ $hasErr('initial_odometer_km') }}" value="{{ $old('initial_odometer_km') }}"
                    min="0" placeholder="0">
                <span class="input-group-text">km</span>
            </div>
            <div class="invalid-feedback">{{ $err('initial_odometer_km') }}</div>
        </div>

        {{-- Tanggal Odometer Awal --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Initial Odometer Date">Tanggal Odometer Awal</label>
            <input type="date" name="initial_odometer_date"
                class="form-control {{ $hasErr('initial_odometer_date') }}" value="{{ $old('initial_odometer_date') }}">
            <div class="invalid-feedback">{{ $err('initial_odometer_date') }}</div>
        </div>

        {{-- Odometer Akhir (sudah ada) --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Last Odometer (km)">Odometer Akhir (km)</label>
            <div class="input-group">
                <input type="number" name="last_odometer_km" class="form-control {{ $hasErr('last_odometer_km') }}"
                    value="{{ $old('last_odometer_km') }}" min="0" placeholder="0">
                <span class="input-group-text">km</span>
            </div>
            <div class="invalid-feedback">{{ $err('last_odometer_km') }}</div>
        </div>

        {{-- Tanggal Odometer Akhir --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Last Odometer Date">Tanggal Odometer Akhir</label>
            <input type="date" name="last_odometer_date" class="form-control {{ $hasErr('last_odometer_date') }}"
                value="{{ $old('last_odometer_date') }}">
            <div class="invalid-feedback">{{ $err('last_odometer_date') }}</div>
        </div>

    </div>

    <div class="form-section-title"><i class="fas fa-broadcast-tower"></i>
        <span data-en="GPS Tracker">GPS Tracker</span>
    </div>

    <div class="row g-3">

        {{-- ID GPS Tracker --}}
        <div class="col-md-4">
            <label class="form-label" data-en="GPS Tracker ID">ID GPS Tracker</label>
            <input type="text" name="gps_tracker_id" class="form-control {{ $hasErr('gps_tracker_id') }}"
                value="{{ $old('gps_tracker_id') }}" placeholder="Nomor IMEI / Serial GPS">
        </div>

        {{-- Provider GPS --}}
        <div class="col-md-4">
            <label class="form-label" data-en="GPS Provider">Provider GPS</label>
            <input type="text" name="gps_provider" class="form-control {{ $hasErr('gps_provider') }}"
                value="{{ $old('gps_provider') }}" placeholder="Nama penyedia layanan GPS">
        </div>

        {{-- Status GPS --}}
        <div class="col-md-4">
            <label class="form-label" data-en="GPS Status">Status GPS</label>
            <select name="gps_status" class="form-select {{ $hasErr('gps_status') }}">
                <option value="" data-en="-- Select Status --">-- Pilih Status --</option>
                @foreach ($options['gps_statuses'] as $val => $label)
                    <option value="{{ $val }}" {{ $old('gps_status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>
