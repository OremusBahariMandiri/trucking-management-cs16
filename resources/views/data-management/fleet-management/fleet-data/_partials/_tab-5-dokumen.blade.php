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

        {{-- File STNK --}}
        <div class="col-12">
            <label class="form-label" data-en="STNK File (PDF/Image)">
                <i class="fas fa-paperclip me-1"></i>
                <span data-en="STNK File">Lampiran File STNK</span>
            </label>

            @php $stnkDoc = $isEdit ? $fleet?->documents->firstWhere('document_type', 'stnk') : null; @endphp

            @if($stnkDoc?->file_path)
                <div class="doc-file-existing mb-2 d-flex align-items-center gap-2 p-2 rounded border">
                    <i class="fas {{ str_ends_with($stnkDoc->file_path, '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg"></i>
                    <a href="{{ $stnkDoc->file_url }}" target="_blank" class="text-decoration-none flex-grow-1 text-truncate" title="{{ basename($stnkDoc->file_path) }}">
                        {{ basename($stnkDoc->file_path) }}
                    </a>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="fas fa-check-circle me-1"></i>
                        <span data-en="Uploaded">Terunggah</span>
                    </span>
                
                </div>
            @endif

            <input type="file" name="document_files[stnk]"
                   class="form-control {{ $hasErr('document_files.stnk') }}"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">
            @if($err('document_files.stnk'))
                <div class="invalid-feedback d-block">{{ $err('document_files.stnk') }}</div>
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

        {{-- File KIR --}}
        <div class="col-12">
            <label class="form-label" data-en="KIR File (PDF/Image)">
                <i class="fas fa-paperclip me-1"></i>
                <span data-en="KIR File">Lampiran File KIR</span>
            </label>

            @php $kirDoc = $isEdit ? $fleet?->documents->firstWhere('document_type', 'kir') : null; @endphp

            @if($kirDoc?->file_path)
                <div class="doc-file-existing mb-2 d-flex align-items-center gap-2 p-2 rounded border">
                    <i class="fas {{ str_ends_with($kirDoc->file_path, '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg"></i>
                    <a href="{{ $kirDoc->file_url }}" target="_blank" class="text-decoration-none flex-grow-1 text-truncate" title="{{ basename($kirDoc->file_path) }}">
                        {{ basename($kirDoc->file_path) }}
                    </a>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="fas fa-check-circle me-1"></i>
                        <span data-en="Uploaded">Terunggah</span>
                    </span>
                </div>
            @endif

            <input type="file" name="document_files[kir]"
                   class="form-control {{ $hasErr('document_files.kir') }}"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">
            @if($err('document_files.kir'))
                <div class="invalid-feedback d-block">{{ $err('document_files.kir') }}</div>
            @endif
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

        {{-- File BPKB --}}
        <div class="col-12">
            <label class="form-label" data-en="BPKB File (PDF/Image)">
                <i class="fas fa-paperclip me-1"></i>
                <span data-en="BPKB File">Lampiran File BPKB</span>
            </label>

            @php $bpkbDoc = $isEdit ? $fleet?->documents->firstWhere('document_type', 'bpkb') : null; @endphp

            @if($bpkbDoc?->file_path)
                <div class="doc-file-existing mb-2 d-flex align-items-center gap-2 p-2 rounded border">
                    <i class="fas {{ str_ends_with($bpkbDoc->file_path, '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg"></i>
                    <a href="{{ $bpkbDoc->file_url }}" target="_blank" class="text-decoration-none flex-grow-1 text-truncate" title="{{ basename($bpkbDoc->file_path) }}">
                        {{ basename($bpkbDoc->file_path) }}
                    </a>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="fas fa-check-circle me-1"></i>
                        <span data-en="Uploaded">Terunggah</span>
                    </span>
                </div>
            @endif

            <input type="file" name="document_files[bpkb]"
                   class="form-control {{ $hasErr('document_files.bpkb') }}"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">
            @if($err('document_files.bpkb'))
                <div class="invalid-feedback d-block">{{ $err('document_files.bpkb') }}</div>
            @endif
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

        {{-- File Asuransi --}}
        <div class="col-12">
            <label class="form-label" data-en="Insurance Policy File (PDF/Image)">
                <i class="fas fa-paperclip me-1"></i>
                <span data-en="Insurance File">Lampiran File Polis Asuransi</span>
            </label>

            @php $insuranceDoc = $isEdit ? $fleet?->documents->firstWhere('document_type', 'asuransi') : null; @endphp

            @if($insuranceDoc?->file_path)
                <div class="doc-file-existing mb-2 d-flex align-items-center gap-2 p-2 rounded border">
                    <i class="fas {{ str_ends_with($insuranceDoc->file_path, '.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} fa-lg"></i>
                    <a href="{{ $insuranceDoc->file_url }}" target="_blank" class="text-decoration-none flex-grow-1 text-truncate" title="{{ basename($insuranceDoc->file_path) }}">
                        {{ basename($insuranceDoc->file_path) }}
                    </a>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="fas fa-check-circle me-1"></i>
                        <span data-en="Uploaded">Terunggah</span>
                    </span>
                </div>
            @endif

            <input type="file" name="document_files[asuransi]"
                   class="form-control {{ $hasErr('document_files.asuransi') }}"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">
            @if($err('document_files.asuransi'))
                <div class="invalid-feedback d-block">{{ $err('document_files.asuransi') }}</div>
            @endif
        </div>
    </div>

</div>