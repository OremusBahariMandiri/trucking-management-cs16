@extends('layouts.admin')
@section('title', 'Edit Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Edit Customer">Edit Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-data.administration.customers.index') }}"
                            data-en="Customers">Pelanggan</a></li>
                    <li class="breadcrumb-item active" data-en="Edit">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('master-data.administration.customers.update', $customer) }}"
        enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')

        {{-- Informasi Utama --}}
        <div class="lkp-form-card mb-4">
            <div class="lkp-form-header">
                <i class="fas fa-building"></i>
                <span data-en="Customer Information">Informasi Pelanggan</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-en="Registration Date">Tanggal Registrasi</label>
                        <input type="date" name="registration_date"
                            class="form-control @error('registration_date') is-invalid @enderror"
                            value="{{ old('registration_date', $customer->registration_date?->format('Y-m-d')) }}">
                        @error('registration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Customer Code">Kode Pelanggan</label>
                        <input type="text" name="customer_code"
                            class="form-control @error('customer_code') is-invalid @enderror"
                            value="{{ old('customer_code', $customer->customer_code) }}">
                        @error('customer_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">
                            <span data-en="Customer Name">Nama Pelanggan</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $customer->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Customer Type">Jenis Pelanggan</label>
                        <select name="customer_type_id" class="form-select @error('customer_type_id') is-invalid @enderror">
                            <option value="" data-en="— Select —">— Pilih —</option>
                            @foreach ($customerTypes as $ct)
                                <option value="{{ $ct->id }}"
                                    {{ old('customer_type_id', $customer->customer_type_id) == $ct->id ? 'selected' : '' }}>
                                    {{ $ct->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Business Type">Jenis Usaha</label>
                        <select name="business_type_id" class="form-select @error('business_type_id') is-invalid @enderror">
                            <option value="" data-en="— Select —">— Pilih —</option>
                            @foreach ($businessTypes as $bt)
                                <option value="{{ $bt->id }}"
                                    {{ old('business_type_id', $customer->business_type_id) == $bt->id ? 'selected' : '' }}>
                                    {{ $bt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Phone">Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $customer->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Email">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $customer->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="lkp-toggle-wrap" style="margin-top: 1.85rem;">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                            <label class="lkp-toggle-label" for="is_active">
                                <span data-en="Active">Aktif</span>
                                <small data-en="Customer is active">Pelanggan dapat digunakan dalam transaksi</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Legalitas --}}
        <div class="lkp-form-card mb-4">
            <div class="lkp-form-header">
                <i class="fas fa-file-invoice"></i>
                <span data-en="Legality">Legalitas</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-en="NPWP Number">Nomor NPWP</label>
                        <input type="text" name="npwp_number"
                            class="form-control @error('npwp_number') is-invalid @enderror"
                            value="{{ old('npwp_number', $customer->npwp_number) }}">
                        @error('npwp_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="NPWP File">File NPWP</label>
                        <input type="file" name="npwp_file"
                            class="form-control @error('npwp_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        @error('npwp_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($customer->npwp_file_name)
                            <div class="mb-1">
                                <small class="text-muted">
                                    <i class="fas fa-file me-1"></i>{{ $customer->npwp_file_name }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="NIB Number">Nomor NIB</label>
                        <input type="text" name="nib_number"
                            class="form-control @error('nib_number') is-invalid @enderror"
                            value="{{ old('nib_number', $customer->nib_number) }}">
                        @error('nib_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="NIB File">File NIB</label>
                        <input type="file" name="nib_file"
                            class="form-control @error('nib_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        @error('nib_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($customer->nib_file_name)
                            <div class="mb-1">
                                <small class="text-muted">
                                    <i class="fas fa-file me-1"></i>{{ $customer->nib_file_name }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="lkp-form-card mb-4">
            <div class="lkp-form-header">
                <i class="fas fa-map-marker-alt"></i>
                <span data-en="Address">Alamat</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-en="Province">Provinsi</label>
                        <input type="text" name="province"
                            class="form-control @error('province') is-invalid @enderror"
                            value="{{ old('province', $customer->province) }}">
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="City">Kota</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city', $customer->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Kecamatan">Kecamatan</label>
                        <input type="text" name="kecamatan"
                            class="form-control @error('kecamatan') is-invalid @enderror"
                            value="{{ old('kecamatan', $customer->kecamatan) }}">
                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Kelurahan">Kelurahan</label>
                        <input type="text" name="kelurahan"
                            class="form-control @error('kelurahan') is-invalid @enderror"
                            value="{{ old('kelurahan', $customer->kelurahan) }}">
                        @error('kelurahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="RT/RW">RT/RW</label>
                        <input type="text" name="rt_rw" class="form-control @error('rt_rw') is-invalid @enderror"
                            value="{{ old('rt_rw', $customer->rt_rw) }}" placeholder="001/002">
                        @error('rt_rw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Postal Code">Kode Pos</label>
                        <input type="text" name="postal_code"
                            class="form-control @error('postal_code') is-invalid @enderror"
                            value="{{ old('postal_code', $customer->postal_code) }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Street Address">Alamat Jalan</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- PIC --}}
        <div class="lkp-form-card mb-4">
            <div class="lkp-form-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-user-tie me-2"></i>
                    <span data-en="Person In Charge (PIC)">Penanggung Jawab (PIC)</span>
                </span>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addPicBtn">
                    <i class="fas fa-plus me-1"></i>
                    <span data-en="Add PIC">Tambah PIC</span>
                </button>
            </div>
            <div class="lkp-form-body" id="picContainer">
                @forelse($customer->pics as $picIdx => $pic)
                    <div class="pic-row mb-3 p-3 border rounded" data-index="{{ $picIdx }}">
                        <input type="hidden" name="pics[{{ $picIdx }}][id]" value="{{ $pic->id }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-muted">PIC #{{ $picIdx + 1 }}</strong>
                            @if ($picIdx > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger remove-pic">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label form-label-sm" data-en="Name">Nama</label>
                                <input type="text" name="pics[{{ $picIdx }}][name]"
                                    class="form-control form-control-sm" value="{{ $pic->name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Position">Jabatan</label>
                                <input type="text" name="pics[{{ $picIdx }}][position]"
                                    class="form-control form-control-sm" value="{{ $pic->position }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Department">Departemen</label>
                                <input type="text" name="pics[{{ $picIdx }}][department]"
                                    class="form-control form-control-sm" value="{{ $pic->department }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Phone">Telepon</label>
                                <input type="text" name="pics[{{ $picIdx }}][phone]"
                                    class="form-control form-control-sm" value="{{ $pic->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm" data-en="Email">Email</label>
                                <input type="email" name="pics[{{ $picIdx }}][email]"
                                    class="form-control form-control-sm" value="{{ $pic->email }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm d-block">&nbsp;</label>
                                <div class="lkp-toggle-wrap">
                                    <input class="form-check-input" type="checkbox"
                                        name="pics[{{ $picIdx }}][is_primary]" value="1"
                                        id="pic_primary_{{ $picIdx }}" {{ $pic->is_primary ? 'checked' : '' }}>
                                    <label class="lkp-toggle-label" for="pic_primary_{{ $picIdx }}">
                                        <span data-en="Primary PIC">PIC Utama</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="pic-row mb-3 p-3 border rounded" data-index="0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-muted">PIC #1</strong>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Nama</label>
                                <input type="text" name="pics[0][name]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm">Jabatan</label>
                                <input type="text" name="pics[0][position]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm">Departemen</label>
                                <input type="text" name="pics[0][department]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm">Telepon</label>
                                <input type="text" name="pics[0][phone]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-sm">Email</label>
                                <input type="email" name="pics[0][email]" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-sm d-block">&nbsp;</label>
                                <div class="lkp-toggle-wrap">
                                    <input class="form-check-input" type="checkbox" name="pics[0][is_primary]"
                                        value="1" id="pic_primary_0" checked>
                                    <label class="lkp-toggle-label" for="pic_primary_0">
                                        <span>PIC Utama</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="lkp-form-actions">
                <a href="{{ route('master-data.administration.customers.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i><span data-en="Cancel">Batal</span>
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i><span data-en="Save Changes">Simpan Perubahan</span>
                </button>
            </div>
        </div>

    </form>

@endsection
@push('scripts')
    <script>
        let picCount = {{ $customer->pics->count() ?: 1 }};
        document.getElementById('addPicBtn').addEventListener('click', function() {
            const idx = picCount++;
            const html = `
    <div class="pic-row mb-3 p-3 border rounded" data-index="${idx}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong class="text-muted">PIC #${idx + 1}</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-pic">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-12"><label class="form-label form-label-sm">Nama</label><input type="text" name="pics[${idx}][name]" class="form-control form-control-sm"></div>
            <div class="col-md-6"><label class="form-label form-label-sm">Jabatan</label><input type="text" name="pics[${idx}][position]" class="form-control form-control-sm"></div>
            <div class="col-md-6"><label class="form-label form-label-sm">Departemen</label><input type="text" name="pics[${idx}][department]" class="form-control form-control-sm"></div>
            <div class="col-md-6"><label class="form-label form-label-sm">Telepon</label><input type="text" name="pics[${idx}][phone]" class="form-control form-control-sm"></div>
            <div class="col-md-6"><label class="form-label form-label-sm">Email</label><input type="email" name="pics[${idx}][email]" class="form-control form-control-sm"></div>
            <div class="col-md-12">
                <label class="form-label form-label-sm d-block">&nbsp;</label>
                <div class="lkp-toggle-wrap">
                    <input class="form-check-input" type="checkbox" name="pics[${idx}][is_primary]" value="1" id="pic_primary_${idx}">
                    <label class="lkp-toggle-label" for="pic_primary_${idx}"><span>PIC Utama</span></label>
                </div>
            </div>
        </div>
    </div>`;
            document.getElementById('picContainer').insertAdjacentHTML('beforeend', html);
        });
        document.getElementById('picContainer').addEventListener('click', function(e) {
            if (e.target.closest('.remove-pic')) {
                e.target.closest('.pic-row').remove();
            }
        });
    </script>
@endpush
