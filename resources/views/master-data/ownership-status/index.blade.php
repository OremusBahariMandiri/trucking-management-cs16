@extends('layouts.admin')

@section('title', 'Ownership Statuses')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Ownership Statuses">Status Kepemilikan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Ownership Statuses">Status Kepemilikan</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('master-data.ownership-statuses.create') }}"
            class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add Ownership Status">Tambah Status</span>
        </a>
    </div>

    {{-- Table --}}
    <div class="veh-table-card">
        <div class="veh-table-header">
            <h6>
                <i class="fas fa-building"></i>
                <span data-en="Ownership Status List">Daftar Status Kepemilikan</span>
            </h6>
            <small style="color:var(--text-faint)">
                {{ $items->total() }} <span data-en="records">data</span>
            </small>
        </div>

        @if ($items->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-building"></i></div>
                <h6 data-en="No ownership statuses found">Belum ada status kepemilikan</h6>
                <p data-en="Add the first ownership status.">Tambah status kepemilikan pertama.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="No">No</th>
                            <th data-en="Code">Kode</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Status">Status</th>
                            <th class="text-end" data-en="Actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="lkp-sub">{{ $loop->iteration }}</td>
                                <td>
                                    @if($item->code)
                                        <span class="lkp-code-badge">{{ $item->code }}</span>
                                    @else
                                        <span class="lkp-sub">—</span>
                                    @endif
                                </td>
                                <td><span class="lkp-name">{{ $item->name }}</span></td>
                                <td>
                                    <span class="badge-status {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                                        <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('master-data.ownership-statuses.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form method="POST"
                                            action="{{ route('master-data.ownership-statuses.destroy', $item) }}"
                                            onsubmit="return confirm('Delete this ownership status?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                    {{ $items->firstItem() }}–{{ $items->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $items->total() }}
                </span>
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </div>

@endsection
