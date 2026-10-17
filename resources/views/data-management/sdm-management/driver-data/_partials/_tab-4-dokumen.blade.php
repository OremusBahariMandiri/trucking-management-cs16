{{-- Tab 4: Dokumen --}}
<div class="drv-form-card">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="form-section-title mb-0" style="border:none;padding:0">
            <i class="fas fa-file-alt"></i>
            <span data-en="Documents">Dokumen</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddDocument">
            <i class="fas fa-plus me-1"></i>
            <span data-en="Add Document">Tambah Dokumen</span>
        </button>
    </div>

    {{-- Existing documents (edit mode) — tabel --}}
    @if($isEdit && $driver->documents->isNotEmpty())
    <div class="mb-3">
        <div class="form-section-title" style="font-size:.7rem">
            <span data-en="Saved Documents">Dokumen Tersimpan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem">
                <thead>
                    <tr>
                        <th data-en="Document Name">Nama Dokumen</th>
                        <th data-en="Number">Nomor</th>
                        <th data-en="Expiry">Kadaluarsa</th>
                        <th data-en="Status">Status</th>
                        <th data-en="File">File</th>
                        <th class="text-end" data-en="Action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($driver->documents as $doc)
                    <tr class="drv-doc-existing" data-id="{{ $doc->id }}">
                        <td class="fw-semibold">{{ $doc->document_name }}</td>
                        <td style="font-family:monospace">{{ $doc->document_number ?? '—' }}</td>
                        <td>{{ $doc->expiry_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if($doc->expiry_date)
                                @if($doc->is_expired)
                                    <span class="expiry-expired"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>
                                @elseif($doc->is_expiring_soon)
                                    <span class="expiry-soon"><i class="fas fa-history"></i> Segera habis</span>
                                @else
                                    <span class="expiry-ok"><i class="fas fa-check-circle"></i> Aktif</span>
                                @endif
                            @else
                                <span style="color:var(--text-faint)">—</span>
                            @endif
                        </td>
                        <td>
                            {{-- Upload file baru untuk dokumen ini --}}
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @if($doc->file_path)
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                       class="btn btn-xs btn-outline-secondary"
                                       style="font-size:.75rem;padding:2px 8px"
                                       title="Lihat file">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                @endif
                                <label class="btn btn-xs btn-outline-primary mb-0"
                                       style="font-size:.75rem;padding:2px 8px;cursor:pointer"
                                       title="{{ $doc->file_path ? 'Ganti file' : 'Upload file' }}">
                                    <i class="fas fa-upload me-1"></i>{{ $doc->file_path ? 'Ganti' : 'Upload' }}
                                    <input type="file"
                                           name="existing_documents[{{ $doc->id }}][file]"
                                           accept="image/*,.pdf"
                                           hidden>
                                </label>
                            </div>
                        </td>
                        <td class="text-end">
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-doc"
                                    data-url="{{ route('data-management.sdm-management.driver-data.documents.destroy', [$driver, $doc]) }}"
                                    data-id="{{ $doc->id }}"
                                    title="Hapus dokumen">
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

    {{-- Container: new document rows --}}
    <div id="documentRows">
        {{-- JS will inject rows here --}}
    </div>

    {{-- Empty state (shown if no rows and not edit) --}}
    <div id="documentEmpty" class="drv-empty" style="{{ ($isEdit && $driver->documents->isNotEmpty()) ? 'display:none' : '' }}">
        <div class="drv-empty-icon"><i class="fas fa-file-alt"></i></div>
        <h6 data-en="No documents added">Belum ada dokumen</h6>
        <p data-en="Click "Add Document" to add.">Klik "Tambah Dokumen" untuk menambah.</p>
    </div>

</div>

{{-- Template: document row --}}
<template id="documentRowTemplate">
    <div class="drv-doc-row drv-doc-new">
        <div class="drv-doc-row-header">
            <span class="drv-doc-row-num" data-en="Document">Dokumen</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label form-label-sm">
                    <span data-en="Document Name">Nama Dokumen</span> <span class="text-danger">*</span>
                </label>
                <input type="text" name="documents[__IDX__][document_name]"
                       class="form-control form-control-sm" required
                       placeholder="SIM, SKCK, dll.">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm" data-en="Number">Nomor</label>
                <input type="text" name="documents[__IDX__][document_number]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm" data-en="Issued">Terbit</label>
                <input type="date" name="documents[__IDX__][issued_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm" data-en="Expiry">Kadaluarsa</label>
                <input type="date" name="documents[__IDX__][expiry_date]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="Description">Keterangan</label>
                <input type="text" name="documents[__IDX__][description]"
                       class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label form-label-sm" data-en="File (optional)">File (opsional)</label>
                <input type="file" name="documents[__IDX__][file]"
                       class="form-control form-control-sm"
                       accept="image/*,.pdf">
            </div>
        </div>
    </div>
</template>