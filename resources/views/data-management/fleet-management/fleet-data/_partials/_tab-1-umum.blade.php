<div class="veh-form-card">

    <div class="form-section-title"><i class="fas fa-list-alt"></i>
        <span data-en="Vehicle Identity">Identitas Kendaraan</span>
    </div>

    <div class="row g-3">

        {{-- Kode Kendaraan --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Vehicle Code">Kode Kendaraan</label>
            <div class="input-group">
                <input type="text" name="vehicle_code" id="vehicle_code"
                       class="form-control {{ $hasErr('vehicle_code') }}"
                       value="{{ $old('vehicle_code') }}"
                       placeholder="TRK-2024-001">
                @if(!$isEdit)
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        id="btnGenerateCode"
                        data-url="{{ route('data-management.fleet-data.api.search') }}?generate=1"
                        title="Generate otomatis">
                    <i class="fas fa-magic"></i>
                </button>
                @endif
            </div>
            @if($err('vehicle_code'))
                <div class="invalid-feedback d-block">{{ $err('vehicle_code') }}</div>
            @endif
        </div>

        {{-- Nomor Polisi --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Police Number">Nomor Polisi</label>
            <input type="text" name="police_number"
                   class="form-control {{ $hasErr('police_number') }}"
                   value="{{ $old('police_number') }}"
                   placeholder="B 1234 XYZ"
                   style="text-transform:uppercase">
            <div class="invalid-feedback">{{ $err('police_number') }}</div>
        </div>

        {{-- Nomor Lambung --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Hull Number">Nomor Lambung</label>
            <input type="text" name="hull_number"
                   class="form-control {{ $hasErr('hull_number') }}"
                   value="{{ $old('hull_number') }}"
                   placeholder="Nomor lambung / unit">
            <div class="invalid-feedback">{{ $err('hull_number') }}</div>
        </div>

        {{-- Jenis Kendaraan --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Vehicle Type">Jenis Kendaraan</label>
            <select name="vehicle_type_id" class="form-select {{ $hasErr('vehicle_type_id') }}">
                <option value="" data-en="-- Select Type --">-- Pilih Jenis --</option>
                @foreach($options['vehicle_types'] as $t)
                    <option value="{{ $t->id }}" {{ $old('vehicle_type_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">{{ $err('vehicle_type_id') }}</div>
        </div>

        {{-- Merek --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Brand">Merek</label>
            <select name="vehicle_brand_id" id="vehicle_brand_id"
                    class="form-select {{ $hasErr('vehicle_brand_id') }}">
                <option value="" data-en="-- Select Brand --">-- Pilih Merek --</option>
                @foreach($options['vehicle_brands'] as $b)
                    <option value="{{ $b->id }}" {{ $old('vehicle_brand_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">{{ $err('vehicle_brand_id') }}</div>
        </div>

        {{-- Model --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Model">Model</label>
            <select name="vehicle_model_id" id="vehicle_model_id"
                    class="form-select {{ $hasErr('vehicle_model_id') }}"
                    data-models="{{ $options['vehicle_models']->map(fn($m) => ['id'=>$m->id,'name'=>$m->name,'brand_id'=>$m->vehicle_brand_id])->toJson() }}">
                <option value="" data-en="-- Select Model --">-- Pilih Model --</option>
                @foreach($options['vehicle_models'] as $m)
                    <option value="{{ $m->id }}" data-brand="{{ $m->vehicle_brand_id }}"
                            {{ $old('vehicle_model_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">{{ $err('vehicle_model_id') }}</div>
        </div>

        {{-- Tahun Pembuatan --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Manufacture Year">Tahun Pembuatan</label>
            <input type="number" name="manufacture_year"
                   class="form-control {{ $hasErr('manufacture_year') }}"
                   value="{{ $old('manufacture_year') }}"
                   min="1980" max="{{ date('Y') }}"
                   placeholder="{{ date('Y') }}">
            <div class="invalid-feedback">{{ $err('manufacture_year') }}</div>
        </div>

        {{-- Warna --}}
        <div class="col-md-6">
            <label class="form-label" data-en="Color">Warna</label>
            <input type="text" name="color"
                   class="form-control {{ $hasErr('color') }}"
                   value="{{ $old('color') }}"
                   placeholder="Putih, Merah, dll">
        </div>

        {{-- Status --}}
        <div class="col-md-12">
            <label class="form-label" data-en="Vehicle Status">Status Kendaraan</label>
            <select name="vehicle_status" class="form-select {{ $hasErr('vehicle_status') }}">
                <option value="" data-en="-- Select Status --">-- Pilih Status --</option>
                @foreach($options['statuses'] as $val => $label)
                    <option value="{{ $val }}" {{ $old('vehicle_status', 'active') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">{{ $err('vehicle_status') }}</div>
        </div>

    </div>
</div>