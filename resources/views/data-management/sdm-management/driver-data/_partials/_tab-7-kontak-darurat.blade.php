{{-- Tab 7: Kontak Darurat --}}

@php
    $ecOpts = $ecOptions ?? [];
    $contactTypes    = $ecOpts['contact_types']    ?? ['orang_tua'=>'Orang Tua','suami'=>'Suami','istri'=>'Istri','saudara_kandung'=>'Saudara Kandung'];
    $contactStatuses = $ecOpts['contact_statuses'] ?? ['aktif'=>'Aktif','tidak_aktif'=>'Tidak Aktif'];
    $religions       = $ecOpts['religions']        ?? ['islam'=>'Islam','kristen'=>'Kristen','katolik'=>'Katolik','hindu'=>'Hindu','budha'=>'Budha','konghucu'=>'Konghucu'];
    $genders         = ['male'=>'Laki-laki','female'=>'Perempuan'];
    $maritalStats    = ['single'=>'Belum Menikah','married'=>'Menikah','divorced'=>'Cerai','widowed'=>'Duda/Janda'];
@endphp

<div class="drv-form-card">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-section-title mb-0" style="border:none;padding:0">
            <i class="fas fa-phone-alt"></i>
            <span data-en="Emergency Contacts">Kontak Darurat</span>
        </div>
        <button type="button" class="btn btn-sm drv-btn-accent" id="btnAddEmergencyContact">
            <i class="fas fa-plus me-1"></i>
            <span data-en="Add Contact">Tambah Kontak</span>
        </button>
    </div>

    {{-- ──────────────────────────────────────────────────────────
         EDIT MODE: existing contacts — tampil sebagai form lengkap
         ────────────────────────────────────────────────────────── --}}
    @if($isEdit && $driver->emergencyContacts->isNotEmpty())
        <div id="existingEcForms">
            @foreach($driver->emergencyContacts as $ecIdx => $ec)
            <div class="ec-card mb-4" data-ec-id="{{ $ec->id }}">

                {{-- Card header --}}
                <div class="ec-card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ec-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="ec-name">{{ $ec->full_name }}</div>
                            <div class="ec-meta">
                                <span class="ec-type-badge">{{ $ec->contact_type_label }}</span>
                                &bull;
                                <span>{{ $ec->phone_primary }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="ec-status-badge {{ $ec->contact_status === 'aktif' ? 'ec-status-aktif' : 'ec-status-nonaktif' }}">
                            {{ $ec->contact_status_label }}
                        </span>
                        <button type="button"
                                class="btn btn-sm ec-btn-hapus btn-delete-ec"
                                data-url="{{ route('data-management.sdm-management.driver-data.emergency-contacts.destroy', [$driver, $ec]) }}"
                                data-id="{{ $ec->id }}"
                                title="Hapus kontak darurat">
                            <i class="fas fa-trash-alt me-1"></i>
                            <span data-en="Delete">Hapus</span>
                        </button>
                    </div>
                </div>

                {{-- Card body: full editable form --}}
                <div class="ec-card-body">

                    {{-- Section: Identitas --}}
                    <div class="ec-section">
                        <div class="ec-section-title" data-en="Identity">Identitas</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ec-label">
                                    <span data-en="Contact Type">Jenis Kontak</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="existing_emergency_contacts[{{ $ec->id }}][contact_type]"
                                        class="form-select form-select-sm" required>
                                    @foreach($contactTypes as $val => $label)
                                    <option value="{{ $val }}" {{ $ec->contact_type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Contact Status">Status Kontak</label>
                                <select name="existing_emergency_contacts[{{ $ec->id }}][contact_status]"
                                        class="form-select form-select-sm">
                                    @foreach($contactStatuses as $val => $label)
                                    <option value="{{ $val }}" {{ $ec->contact_status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label">
                                    <span data-en="Full Name">Nama Lengkap</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][full_name]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->full_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label">NIK</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][nik]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->nik }}" maxlength="16"
                                       placeholder="16 digit (opsional)">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Place of Birth">Tempat Lahir</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][birth_place]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->birth_place }}">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Date of Birth">Tanggal Lahir</label>
                                <input type="date"
                                       name="existing_emergency_contacts[{{ $ec->id }}][birth_date]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->birth_date?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="ec-label" data-en="Gender">Jenis Kelamin</label>
                                <select name="existing_emergency_contacts[{{ $ec->id }}][gender]"
                                        class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($genders as $val => $label)
                                    <option value="{{ $val }}" {{ $ec->gender === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ec-label" data-en="Religion">Agama</label>
                                <select name="existing_emergency_contacts[{{ $ec->id }}][religion]"
                                        class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($religions as $val => $label)
                                    <option value="{{ $val }}" {{ $ec->religion === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ec-label" data-en="Marital Status">Status Pernikahan</label>
                                <select name="existing_emergency_contacts[{{ $ec->id }}][marital_status]"
                                        class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($maritalStats as $val => $label)
                                    <option value="{{ $val }}" {{ $ec->marital_status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Kontak --}}
                    <div class="ec-section">
                        <div class="ec-section-title" data-en="Contact">Kontak</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ec-label">
                                    <span data-en="Primary Phone">No. Telepon Utama</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][phone_primary]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->phone_primary }}" required
                                       placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Alternative Phone">No. Telepon Alternatif</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][phone_alternative]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->phone_alternative }}"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    {{-- Section: Alamat --}}
                    <div class="ec-section ec-section-last">
                        <div class="ec-section-title" data-en="Address">Alamat</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Province">Provinsi</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][province]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->province }}" placeholder="Provinsi">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="City">Kota/Kabupaten</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][city]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->city }}" placeholder="Kota/Kabupaten">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="District">Kecamatan</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][district]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->district }}" placeholder="Kecamatan">
                            </div>
                            <div class="col-md-6">
                                <label class="ec-label" data-en="Village">Kelurahan/Desa</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][village]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->village }}" placeholder="Kelurahan/Desa">
                            </div>
                            <div class="col-md-4">
                                <label class="ec-label">RT / RW</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][rt_rw]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->rt_rw }}" placeholder="001/002">
                            </div>
                            <div class="col-md-4">
                                <label class="ec-label" data-en="Postal Code">Kode Pos</label>
                                <input type="text"
                                       name="existing_emergency_contacts[{{ $ec->id }}][postal_code]"
                                       class="form-control form-control-sm"
                                       value="{{ $ec->postal_code }}" placeholder="12345">
                            </div>
                            <div class="col-12">
                                <label class="ec-label" data-en="Full Address">Alamat Lengkap</label>
                                <textarea name="existing_emergency_contacts[{{ $ec->id }}][full_address]"
                                          class="form-control form-control-sm" rows="2"
                                          placeholder="Nama jalan, nomor rumah, dll.">{{ $ec->full_address }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>{{-- /ec-card-body --}}
            </div>{{-- /ec-card --}}
            @endforeach
        </div>
    @endif

    {{-- ── Container: NEW contact rows ─────────────────────────── --}}
    <div id="emergencyContactRows"></div>

    {{-- Empty state --}}
    <div id="emergencyContactEmpty" class="drv-empty"
         style="{{ ($isEdit && $driver->emergencyContacts->isNotEmpty()) ? 'display:none' : '' }}">
        <div class="drv-empty-icon"><i class="fas fa-phone-alt"></i></div>
        <h6 data-en="No emergency contacts added">Belum ada kontak darurat</h6>
        <p data-en="Click &quot;Add Contact&quot; to add.">Klik "Tambah Kontak" untuk menambah.</p>
    </div>

</div>{{-- /drv-form-card --}}

{{-- ── Template: new contact row ───────────────────────────────── --}}
<template id="emergencyContactRowTemplate">
    <div class="ec-card ec-card-new mb-4">

        <div class="ec-card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="ec-avatar ec-avatar-new">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="ec-name ec-new-label" data-en="New Emergency Contact">Kontak Darurat Baru</div>
                    <div class="ec-meta" data-en="Fill in the form below">Isi form di bawah ini</div>
                </div>
            </div>
            <button type="button" class="btn btn-sm ec-btn-hapus btn-remove-ec-row">
                <i class="fas fa-times me-1"></i>
                <span data-en="Remove">Hapus</span>
            </button>
        </div>

        <div class="ec-card-body">

            {{-- Section: Identitas --}}
            <div class="ec-section">
                <div class="ec-section-title" data-en="Identity">Identitas</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="ec-label">
                            <span data-en="Contact Type">Jenis Kontak</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="emergency_contacts[__IDX__][contact_type]" class="form-select form-select-sm" required>
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            @foreach($contactTypes as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Contact Status">Status Kontak</label>
                        <select name="emergency_contacts[__IDX__][contact_status]" class="form-select form-select-sm">
                            @foreach($contactStatuses as $val => $label)
                            <option value="{{ $val }}" {{ $val==='aktif'?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label">
                            <span data-en="Full Name">Nama Lengkap</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="emergency_contacts[__IDX__][full_name]"
                               class="form-control form-control-sm" required
                               placeholder="Nama lengkap kontak darurat">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label">NIK</label>
                        <input type="text" name="emergency_contacts[__IDX__][nik]"
                               class="form-control form-control-sm" maxlength="16"
                               placeholder="16 digit (opsional)">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Place of Birth">Tempat Lahir</label>
                        <input type="text" name="emergency_contacts[__IDX__][birth_place]"
                               class="form-control form-control-sm" placeholder="Kota tempat lahir">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Date of Birth">Tanggal Lahir</label>
                        <input type="date" name="emergency_contacts[__IDX__][birth_date]"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="ec-label" data-en="Gender">Jenis Kelamin</label>
                        <select name="emergency_contacts[__IDX__][gender]" class="form-select form-select-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($genders as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="ec-label" data-en="Religion">Agama</label>
                        <select name="emergency_contacts[__IDX__][religion]" class="form-select form-select-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($religions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="ec-label" data-en="Marital Status">Status Pernikahan</label>
                        <select name="emergency_contacts[__IDX__][marital_status]" class="form-select form-select-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($maritalStats as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section: Kontak --}}
            <div class="ec-section">
                <div class="ec-section-title" data-en="Contact">Kontak</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="ec-label">
                            <span data-en="Primary Phone">No. Telepon Utama</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="emergency_contacts[__IDX__][phone_primary]"
                               class="form-control form-control-sm" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Alternative Phone">No. Telepon Alternatif</label>
                        <input type="text" name="emergency_contacts[__IDX__][phone_alternative]"
                               class="form-control form-control-sm" placeholder="08xxxxxxxxxx (opsional)">
                    </div>
                </div>
            </div>

            {{-- Section: Alamat --}}
            <div class="ec-section ec-section-last">
                <div class="ec-section-title" data-en="Address">Alamat</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Province">Provinsi</label>
                        <input type="text" name="emergency_contacts[__IDX__][province]"
                               class="form-control form-control-sm" placeholder="Provinsi">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="City">Kota/Kabupaten</label>
                        <input type="text" name="emergency_contacts[__IDX__][city]"
                               class="form-control form-control-sm" placeholder="Kota/Kabupaten">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="District">Kecamatan</label>
                        <input type="text" name="emergency_contacts[__IDX__][district]"
                               class="form-control form-control-sm" placeholder="Kecamatan">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Village">Kelurahan/Desa</label>
                        <input type="text" name="emergency_contacts[__IDX__][village]"
                               class="form-control form-control-sm" placeholder="Kelurahan/Desa">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label">RT / RW</label>
                        <input type="text" name="emergency_contacts[__IDX__][rt_rw]"
                               class="form-control form-control-sm" placeholder="001/002">
                    </div>
                    <div class="col-md-6">
                        <label class="ec-label" data-en="Postal Code">Kode Pos</label>
                        <input type="text" name="emergency_contacts[__IDX__][postal_code]"
                               class="form-control form-control-sm" placeholder="12345">
                    </div>
                    <div class="col-12">
                        <label class="ec-label" data-en="Full Address">Alamat Lengkap</label>
                        <textarea name="emergency_contacts[__IDX__][full_address]"
                                  class="form-control form-control-sm" rows="3"
                                  placeholder="Nama jalan, nomor rumah, RT/RW, dll."></textarea>
                    </div>
                </div>
            </div>

        </div>{{-- /ec-card-body --}}
    </div>{{-- /ec-card --}}
</template>

{{-- ── Styles ────────────────────────────────────────────────── --}}
<style>
/* ═══════════════════════════════════════════════════════════════
   Emergency Contact Card — tema teal driver
   ═══════════════════════════════════════════════════════════════ */

/* Wrapper card */
.ec-card {
    border: 1px solid var(--border);
    border-radius: var(--drv-radius);
    overflow: hidden;
    background: var(--surface);
    box-shadow: var(--drv-shadow);
    transition: box-shadow var(--drv-transition);
}
.ec-card:hover { box-shadow: var(--drv-shadow-hover); }

/* Header: strip teal tipis di sisi kiri */
.ec-card-header {
    padding: .875rem 1.25rem;
    background: var(--surface-2, rgba(255,255,255,.04));
    border-bottom: 1px solid var(--border);
    border-left: 3px solid var(--drv-accent);
}
/* Kartu "baru" pakai strip hijau */
.ec-card-new .ec-card-header { border-left-color: #16a34a; }

/* Avatar */
.ec-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: var(--drv-accent-soft);
    border: 1.5px solid var(--drv-accent);
    display: flex; align-items: center; justify-content: center;
    color: var(--drv-accent);
    font-size: .95rem;
    flex-shrink: 0;
}
.ec-avatar-new {
    background: rgba(22,163,74,.12);
    border-color: #16a34a;
    color: #16a34a;
}

/* Teks header */
.ec-name {
    font-weight: 600;
    font-size: .9rem;
    color: var(--text);
    line-height: 1.3;
}
.ec-meta {
    font-size: .78rem;
    color: var(--text-muted);
    margin-top: 1px;
    display: flex;
    align-items: center;
    gap: .35rem;
    flex-wrap: wrap;
}

/* Badge jenis kontak */
.ec-type-badge {
    display: inline-block;
    padding: 1px 7px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    background: var(--drv-accent-soft);
    color: var(--drv-accent);
    border: 1px solid var(--drv-accent);
}

/* Badge status */
.ec-status-badge {
    padding: 2px 10px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}
.ec-status-aktif    { background: rgba(22,163,74,.15); color: #16a34a; border: 1px solid rgba(22,163,74,.35); }
.ec-status-nonaktif { background: rgba(100,116,139,.12); color: var(--text-muted); border: 1px solid rgba(100,116,139,.25); }

/* Tombol hapus */
.ec-btn-hapus {
    font-size: .78rem;
    padding: .25rem .65rem;
    border: 1px solid rgba(220,38,38,.4);
    color: #ef4444;
    background: rgba(220,38,38,.08);
    border-radius: var(--drv-radius-sm);
    transition: background var(--drv-transition), border-color var(--drv-transition);
}
.ec-btn-hapus:hover {
    background: rgba(220,38,38,.18);
    border-color: #ef4444;
    color: #ef4444;
}

/* Body */
.ec-card-body { padding: 1rem 1.25rem 0; }

/* ── Section dalam card ──────────────────────────────────────── */
.ec-section {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: calc(var(--drv-radius) - 2px);
    background: var(--surface-2, rgba(14,116,144,.035));
    border: 1px solid rgba(14,116,144,.12);
}
.ec-section-last { margin-bottom: 1.25rem; }

/* Section title: teks kecil uppercase, warna teal */
.ec-section-title {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--drv-accent);
    margin-bottom: .75rem;
    padding-bottom: .4rem;
    border-bottom: 1px solid rgba(14,116,144,.18);
}

/* Label elegan: ringan, sedikit muted, tanpa ikon */
.ec-label {
    display: block;
    font-size: .78rem;
    font-weight: 500;
    color: var(--text-muted, #64748b);
    margin-bottom: .3rem;
    line-height: 1.4;
}

/* Tombol tambah */
.drv-btn-accent {
    background: var(--drv-accent);
    color: #fff;
    border: none;
    border-radius: var(--drv-radius-sm);
    padding: .35rem .85rem;
    font-size: .82rem;
    font-weight: 600;
    transition: background var(--drv-transition);
}
.drv-btn-accent:hover { background: var(--drv-accent-hover); color: #fff; }

/* Dark-mode header */
@media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"]) .ec-card-header {
        background: rgba(255,255,255,.03);
    }
    :root:not([data-theme="light"]) .ec-section {
        background: rgba(34,211,238,.04);
        border-color: rgba(34,211,238,.12);
    }
}
:root[data-theme="dark"] .ec-card-header { background: rgba(255,255,255,.03); }
:root[data-theme="dark"] .ec-section {
    background: rgba(34,211,238,.04);
    border-color: rgba(34,211,238,.12);
}
</style>

{{-- ── JS ─────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
(function () {
    let ecIndex = 0;

    const btnAdd    = document.getElementById('btnAddEmergencyContact');
    const container = document.getElementById('emergencyContactRows');
    const empty     = document.getElementById('emergencyContactEmpty');
    const template  = document.getElementById('emergencyContactRowTemplate');

    function updateEmpty() {
        const newRows      = container ? container.querySelectorAll('.ec-card').length : 0;
        const existingRows = document.querySelectorAll('#existingEcForms .ec-card').length;
        if (empty) empty.style.display = (newRows + existingRows) > 0 ? 'none' : '';
    }

    if (btnAdd && template && container) {
        btnAdd.addEventListener('click', function () {
            const clone = template.content.cloneNode(true);
            const card  = clone.querySelector('.ec-card');

            // Replace __IDX__
            card.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/__IDX__/g, ecIndex);
            });

            // Label nomor
            const newRows = container.querySelectorAll('.ec-card').length;
            const titleEl = card.querySelector('.ec-new-label');
            if (titleEl) titleEl.textContent = 'Kontak Darurat Baru #' + (newRows + 1);

            // Tombol hapus
            card.querySelector('.btn-remove-ec-row').addEventListener('click', function () {
                card.style.transition = 'opacity .25s, transform .25s';
                card.style.opacity    = '0';
                card.style.transform  = 'translateY(-6px)';
                setTimeout(() => { card.remove(); updateEmpty(); }, 250);
            });

            container.appendChild(card);
            ecIndex++;
            updateEmpty();
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    }

    // ── AJAX delete existing ──────────────────────────────────────
    document.querySelectorAll('.btn-delete-ec').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Hapus kontak darurat ini? Data tidak dapat dikembalikan.')) return;

            const url  = btn.dataset.url;
            const card = btn.closest('.ec-card');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                card.style.transition = 'opacity .3s, transform .3s';
                card.style.opacity    = '0';
                card.style.transform  = 'scale(.97)';
                setTimeout(() => { card.remove(); updateEmpty(); }, 300);

                const toastEl = document.getElementById('drvToast');
                if (toastEl) {
                    toastEl.querySelector('.toast-body').textContent = data.message ?? 'Kontak darurat dihapus.';
                    toastEl.className = 'toast align-items-center border-0 bg-success text-white';
                    bootstrap.Toast.getOrCreateInstance(toastEl).show();
                }
            })
            .catch(() => alert('Gagal menghapus kontak darurat.'));
        });
    });

    updateEmpty();
})();
</script>
@endpush
