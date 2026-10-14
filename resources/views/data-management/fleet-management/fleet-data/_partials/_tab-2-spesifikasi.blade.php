<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-cogs"></i>
        <span data-en="Engine & Chassis Identity">Identitas Mesin &amp; Rangka</span>
    </div>

    <div class="row g-3 mb-4">

        {{-- Nomor Rangka --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Chassis Number">Nomor Rangka (Chassis)</label>
            <input type="text" name="chassis_number"
                   class="form-control {{ $hasErr('chassis_number') }}"
                   value="{{ $old('chassis_number') }}"
                   placeholder="Contoh: MHY3BC4J0P1234567"
                   style="font-family:monospace">
            <div class="invalid-feedback">{{ $err('chassis_number') }}</div>
        </div>

        {{-- Nomor Mesin --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Engine Number">Nomor Mesin</label>
            <input type="text" name="engine_number"
                   class="form-control {{ $hasErr('engine_number') }}"
                   value="{{ $old('engine_number') }}"
                   placeholder="Contoh: 14BT-1234567"
                   style="font-family:monospace">
            <div class="invalid-feedback">{{ $err('engine_number') }}</div>
        </div>

        {{-- Jenis BBM --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Fuel Type">Jenis Bahan Bakar</label>
            <select name="fuel_type_id" class="form-select {{ $hasErr('fuel_type_id') }}">
                <option value="" data-en="-- Select Fuel --">-- Pilih BBM --</option>
                @foreach($options['fuel_types'] as $f)
                    <option value="{{ $f->id }}" {{ $old('fuel_type_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Kapasitas Mesin --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Engine Capacity (CC)">Kapasitas Mesin (CC)</label>
            <div class="input-group">
                <input type="number" name="engine_capacity_cc"
                       class="form-control {{ $hasErr('engine_capacity_cc') }}"
                       value="{{ $old('engine_capacity_cc') }}"
                       min="0" placeholder="8000">
                <span class="input-group-text">cc</span>
            </div>
        </div>

        {{-- Konfigurasi Sumbu --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Axle Configuration">Konfigurasi Sumbu</label>
            <select name="axis_configuration" class="form-select {{ $hasErr('axis_configuration') }}">
                <option value="" data-en="-- Select --">-- Pilih --</option>
                @foreach($options['axis_configs'] as $a)
                    <option value="{{ $a }}" {{ $old('axis_configuration') === $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah Roda --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Wheel Count">Jumlah Roda</label>
            <div class="input-group">
                <input type="number" name="wheel_count"
                       class="form-control {{ $hasErr('wheel_count') }}"
                       value="{{ $old('wheel_count') }}"
                       min="2" max="24" placeholder="6">
                <span class="input-group-text" data-en="wheels">roda</span>
            </div>
        </div>

    </div>

    <div class="form-section-title"><i class="fas fa-box"></i>
        <span data-en="Dimensions & Load">Dimensi &amp; Muatan</span>
    </div>

    <div class="row g-3">

        {{-- Kapasitas Muatan --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Load Capacity">Kapasitas Muatan</label>
            <div class="input-group">
                <input type="number" name="load_capacity_ton"
                       class="form-control {{ $hasErr('load_capacity_ton') }}"
                       value="{{ $old('load_capacity_ton') }}"
                       min="0" step="0.5" placeholder="10">
                <span class="input-group-text">ton</span>
            </div>
        </div>

        {{-- Berat Kotor (GVW) --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Gross Weight (GVW)">Berat Kotor (GVW)</label>
            <div class="input-group">
                <input type="number" name="gross_vehicle_weight_ton"
                       class="form-control {{ $hasErr('gross_vehicle_weight_ton') }}"
                       value="{{ $old('gross_vehicle_weight_ton') }}"
                       min="0" step="0.5" placeholder="24">
                <span class="input-group-text">ton</span>
            </div>
        </div>

        {{-- Berat Kosong --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Tare Weight">Berat Kosong (Tare)</label>
            <div class="input-group">
                <input type="number" name="tare_weight_ton"
                       class="form-control {{ $hasErr('tare_weight_ton') }}"
                       value="{{ $old('tare_weight_ton') }}"
                       min="0" step="0.5" placeholder="8">
                <span class="input-group-text">ton</span>
            </div>
        </div>

        {{-- Jenis Body --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Body Type">Jenis Body</label>
            <select name="body_type_id" class="form-select {{ $hasErr('body_type_id') }}">
                <option value="" data-en="-- Select Body --">-- Pilih Body --</option>
                @foreach($options['body_types'] as $bt)
                    <option value="{{ $bt->id }}" {{ $old('body_type_id') == $bt->id ? 'selected' : '' }}>
                        {{ $bt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Dimensi Body --}}
        <div class="col-md-12">
            <label class="form-label" data-en="Body Dimensions">Dimensi Body</label>
            <input type="text" name="body_dimension"
                   class="form-control {{ $hasErr('body_dimension') }}"
                   value="{{ $old('body_dimension') }}"
                   placeholder="P×L×T dalam cm, contoh: 720×240×250">
            <div class="form-text" data-en="Free format, e.g: 720×240×250 cm">Format bebas, contoh: 720×240×250 cm</div>
        </div>

    </div>
</div>