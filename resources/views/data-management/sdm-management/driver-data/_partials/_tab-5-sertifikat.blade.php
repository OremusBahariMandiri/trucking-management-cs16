{{-- Tab 5: Sertifikat --}}
<div class="drv-form-card">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="form-section-title mb-0" style="border:none;padding:0">
            <i class="fas fa-certificate"></i>
            <span data-en="Certificates">Sertifikat</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddCertificate">
            <i class="fas fa-plus me-1"></i>
            <span data-en="Add Certificate">Tambah Sertifikat</span>
        </button>
    </div>

    {{-- Existing certificates (edit mode) — tabel --}}
    @if($isEdit && $driver->certificates->isNotEmpty())
    <div class="mb-3">
        <div class="form-section-title" style="font-size:.7rem">
            <span data-en="Saved Certificates">Sertifikat Tersimpan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem">
                <thead>
                    <tr>
                        <th data-en="Certificate Name">Nama Sertifikat</th>
                        <th data-en="Issuer">Penerbit</th>
                        <th data-en="Expiry">Kadaluarsa</th>
                        <th data-en="Status">Status</th>
                        <th data-en="File">File</th>
                        <th class="text-end" data-en="Action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($driver->certificates as $cert)
                    <tr class="drv-doc-existing" data-id="{{ $cert->id }}">
                        <td class="fw-semibold">{{ $cert->certificate_name }}</td>
                        <td>{{ $cert->issuer ?? '—' }}</td>
                        <td>{{ $cert->expiry_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if($cert->expiry_date)
                                @if($cert->is_expired)
                                    <span class="expiry-expired"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>
                                @elseif($cert->is_expiring_soon)
                                    <span class="expiry-soon"><i class="fas fa-history"></i> Segera habis</span>
                                @else
                                    <span class="expiry-ok"><i class="fas fa-check-circle"></i> Aktif</span>
                                @endif
                            @else
                                <span style="color:var(--text-faint)">—</span>
                            @endif
                        </td>
                        <td>
                            {{-- Upload file baru untuk sertifikat ini --}}
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @if($cert->file_path)
                                    <a href="{{ Storage::url($cert->file_path) }}" target="_blank"
                                       class="btn btn-xs btn-outline-secondary"
                                       style="font-size:.75rem;padding:2px 8px"
                                       title="Lihat file">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                @endif
                                <label class="btn btn-xs btn-outline-primary mb-0"
                                       style="font-size:.75rem;padding:2px 8px;cursor:pointer"
                                       title="{{ $cert->file_path ? 'Ganti file' : 'Upload file' }}">
                                    <i class="fas fa-upload me-1"></i>{{ $cert->file_path ? 'Ganti' : 'Upload' }}
                                    <input type="file"
                                           name="existing_certificates[{{ $cert->id }}][file]"
                                           accept="image/*,.pdf"
                                           hidden>
                                </label>
                            </div>
                        </td>
                        <td class="text-end">
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-cert"
                                    data-url="{{ route('data-management.sdm-management.driver-data.certificates.destroy', [$driver, $cert]) }}"
                                    data-id="{{ $cert->id }}"
                                    title="Hapus sertifikat">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Container: new certificate rows --}}
    <div id="certificateRows">
        {{-- JS will inject rows here --}}
    </div>

    {{-- Empty state --}}
    <div id="certificateEmpty" class="drv-empty" style="{{ ($isEdit && $driver->certificates->isNotEmpty()) ? 'display:none' : '' }}">
        <div class="drv-empty-icon"><i class="fas fa-certificate"></i></div>
        <h6 data-en="No certificates added">Belum ada sertifikat</h6>
        <p data-en="Click "Add Certificate" to add.">Klik "Tambah Sertifikat" untuk menambah.</p>
    </div>

</div>

{{-- Template: certificate row --}}
<template id="certificateRowTemplate">
    <div class="drv-doc-row drv-doc-new">
        <div class="drv-doc-row-header">
            <span class="drv-doc-row-num" data-en="Certificate">Sertifikat</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-12">
                <label class="form-label form-label-sm">
                    <span data-en="Certificate Name">Nama Sertifikat</span> <span class="text-danger">*</span>
                </label>
                <input type="text" name="certificates[__IDX__][certificate_name]"
                       class="form-control form-control-sm" required
                       placeholder="SIM B2, Sertifikat Keselamatan, dll.">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Certificate Number">Nomor</label>
                <input type="text" name="certificates[__IDX__][certificate_number]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Issuer">Penerbit</label>
                <input type="text" name="certificates[__IDX__][issuer]"
                       class="form-control form-control-sm"
                       placeholder="Instansi penerbit">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Issued">Tanggal Terbit</label>
                <input type="date" name="certificates[__IDX__][issued_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm" data-en="Expiry">Kadaluarsa</label>
                <input type="date" name="certificates[__IDX__][expiry_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-12">
                <label class="form-label form-label-sm" data-en="Description">Keterangan</label>
                <input type="text" name="certificates[__IDX__][description]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="File (optional)">File (opsional)</label>
                <input type="file" name="certificates[__IDX__][file]"
                       class="form-control form-control-sm"
                       accept="image/*,.pdf">
            </div>
        </div>
    </div>
</template>