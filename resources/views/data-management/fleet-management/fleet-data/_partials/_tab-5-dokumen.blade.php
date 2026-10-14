<div class="veh-form-card">

    {{-- STNK ──────────────────────────────────────────────── --}}
    <div class="form-section-title"><i class="fas fa-file-invoice"></i> STNK</div>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label" data-en="STNK Number">Nomor STNK</label>
            <input type="text" name="stnk_number"
                   class="form-control {{ $hasErr('stnk_number') }}"
                   value="{{ $old('stnk_number') }}"
                   placeholder="Nomor STNK">
        </div>
        <div class="col-md-6">
            <label class="form-label" data-en="Tax Expiry Date">Berlaku Hingga (Pajak)</label>
            <input type="date" name="tax_expiry_date"
                   class="form-control {{ $hasErr('tax_expiry_date') }}"
                   value="{{ $old('tax_expiry_date') }}">
            @php
                $taxExp = $old('tax_expiry_date') ? \Carbon\Carbon::parse($old('tax_expiry_date')) : null;
            @endphp
            @if($taxExp && $taxExp->isPast())
                <div class="form-text expiry-expired">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span data-en="Already expired">Sudah kedaluwarsa</span>
                </div>
            @elseif($taxExp && $taxExp->diffInDays(now()) <= 30)
                <div class="form-text expiry-soon">
                    <i class="fas fa-history"></i>
                    <span data-en="Expiring soon">Segera kedaluwarsa</span>
                </div>
            @endif
        </div>
    </div>

    {{-- KIR ──────────────────────────────────────────────── --}}
    <div class="form-section-title"><i class="fas fa-clipboard-check"></i>
        <span data-en="KIR / Periodic Inspection">KIR / Uji Berkala</span>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label" data-en="KIR Number">Nomor KIR</label>
            <input type="text" name="kir_number"
                   class="form-control {{ $hasErr('kir_number') }}"
                   value="{{ $old('kir_number') }}"
                   placeholder="Nomor buku KIR">
        </div>
        <div class="col-md-6">
            <label class="form-label" data-en="KIR Expiry Date">Berlaku Hingga (KIR)</label>
            <input type="date" name="kir_expiry_date"
                   class="form-control {{ $hasErr('kir_expiry_date') }}"
                   value="{{ $old('kir_expiry_date') }}">
        </div>
    </div>

    {{-- BPKB ──────────────────────────────────────────────── --}}
    <div class="form-section-title"><i class="fas fa-bookmark"></i> BPKB</div>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label" data-en="BPKB Number">Nomor BPKB</label>
            <input type="text" name="bpkb_number"
                   class="form-control {{ $hasErr('bpkb_number') }}"
                   value="{{ $old('bpkb_number') }}"
                   placeholder="Nomor BPKB">
        </div>
    </div>

    {{-- Asuransi ──────────────────────────────────────────── --}}
    <div class="form-section-title"><i class="fas fa-shield-alt"></i>
        <span data-en="Insurance">Asuransi</span>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label" data-en="Policy Number">Nomor Polis</label>
            <input type="text" name="insurance_policy_number"
                   class="form-control {{ $hasErr('insurance_policy_number') }}"
                   value="{{ $old('insurance_policy_number') }}"
                   placeholder="Nomor polis asuransi">
        </div>
        <div class="col-md-4">
            <label class="form-label" data-en="Insurance Company">Perusahaan Asuransi</label>
            <input type="text" name="insurance_company"
                   class="form-control {{ $hasErr('insurance_company') }}"
                   value="{{ $old('insurance_company') }}"
                   placeholder="Nama perusahaan asuransi">
        </div>
        <div class="col-md-4">
            <label class="form-label" data-en="Insurance Expiry">Berlaku Hingga</label>
            <input type="date" name="insurance_expiry_date"
                   class="form-control {{ $hasErr('insurance_expiry_date') }}"
                   value="{{ $old('insurance_expiry_date') }}">
        </div>
    </div>

</div>