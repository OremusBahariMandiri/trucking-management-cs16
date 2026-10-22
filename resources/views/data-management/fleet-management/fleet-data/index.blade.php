@extends('layouts.admin')

@section('title', 'Daftar Kendaraan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Fleet Management">Manajemen Kendaraan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Fleet">Kendaraan</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('data-management.fleet-data.create') }}"
            class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add">Tambah</span>
        </a>
    </div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="veh-table-card">
        <div class="veh-table-header">
            <h6>
                <i class="fas fa-list-alt"></i>
                <span data-en="Vehicle List">Daftar Kendaraan</span>
            </h6>
            <small style="color:var(--text-faint)">
                {{ $fleets->total() }}
                <span data-en="records">data</span>
            </small>
        </div>

        @if ($fleets->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-truck"></i></div>
                <h6 data-en="No vehicles found">Tidak ada kendaraan ditemukan</h6>
                <p data-en="Try adjusting your filter or add a new vehicle.">
                    Coba ubah filter atau tambah kendaraan baru.
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="NO">NO</th>
                            <th data-en="Code / Plate">Kode / No Pol</th>
                            <th data-en="Brand / Model">Merek / Model</th>
                            <th data-en="Type">Jenis</th>
                            <th data-en="Year">Tahun</th>
                            <th data-en="Status">Status</th>
                            <th data-en="Ownership">Kepemilikan</th>
                            <th data-en="Ownership">File</th>
                            <th class="text-end" data-en="Actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fleets as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="vehicle-code">{{ $v->vehicle_code ?? '-' }}</span>
                                    <span class="vehicle-police">{{ $v->police_number }}</span>
                                </td>
                                <td>
                                    <span class="vehicle-brand">{{ $v->brand?->name ?? '-' }}</span>
                                    <span class="vehicle-model">{{ $v->model?->name ?? ($v->model_name ?? '-') }}</span>
                                </td>
                                <td style="font-size:.83rem;color:var(--text-muted)">{{ $v->type?->name ?? '-' }}</td>
                                <td style="font-size:.83rem;color:var(--text-muted)">{{ $v->manufacture_year ?? '-' }}</td>
                                <td>
                                    <span class="badge-status status-{{ $v->vehicle_status ?? 'inactive' }}">
                                        {{ $v->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-ownership">{{ $v->ownership_label ?? '-' }}</span>
                                </td>
                                <td> <a href="{{ route('data-management.fleet-data.pdf', $v) }}"
                                        class="btn btn-sm btn-outline-danger" title="Unduh PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('data-management.fleet-data.show', $v) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('data-management.fleet-data.edit', $v) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form method="POST" action="{{ route('data-management.fleet-data.destroy', $v) }}"
                                            onsubmit="return confirm('Hapus kendaraan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="veh-table-footer">
                <span>
                    <span data-en="Showing">Menampilkan</span>
                    {{ $fleets->firstItem() }}–{{ $fleets->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $fleets->total() }}
                </span>
                {{ $fleets->withQueryString()->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    @vite('resources/js/pages/vehicle.js')
@endpush
