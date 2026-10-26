@extends('layouts.admin')
@section('title', 'Jenis Barang')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')
@php
  $canCreate = auth()->user()->hasPermission('master-data.administration.goods-type.create');
  $canEdit   = auth()->user()->hasPermission('master-data.administration.goods-type.edit');
  $canDelete = auth()->user()->hasPermission('master-data.administration.goods-type.delete');
@endphp

<div class="veh-page-header mb-4">
  <div>
    <div class="veh-title" data-en="Goods Types">Jenis Barang</div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
        <li class="breadcrumb-item" data-en="Master Data">Data Master</li>
        <li class="breadcrumb-item" data-en="Administration">Administrasi</li>
        <li class="breadcrumb-item active" data-en="Goods Types">Jenis Barang</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex align-items-center gap-2">
    @if($canCreate)
    <a href="{{ route('master-data.administration.goods-types.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
      <i class="fas fa-plus"></i><span data-en="Add">Tambah</span>
    </a>
    @else
    <button class="btn btn-primary btn-sm" disabled><i class="fas fa-plus"></i> <span>Tambah</span></button>
    @endif
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
  {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="veh-table-card">
  <div class="veh-table-header">
    <span data-en="Goods Types List">Daftar Jenis Barang</span>
    <small style="color:var(--text-faint)">{{ $items->count() }} <span data-en="records">data</span></small>
  </div>

  @if($items->isEmpty())
  <div class="p-4 text-center" style="color:var(--text-muted)">
    <i class="fas fa-box fa-2x mb-2 d-block" style="opacity:.3"></i>
    <span data-en="No data found">Belum ada data Jenis Barang.</span>
  </div>
  @else
  <div class="table-responsive">
    <table id="masterTable" class="table" data-lookup-table data-col-targets='[0,4]' style="width:100%">
      <thead>
        <tr>
          <th style="width:50px" data-en="No">No</th>
          <th data-en="Code">Kode</th>
          <th data-en="Name">Nama</th>
          <th data-en="Description">Deskripsi</th>
          <th data-en="Status">Status</th>
          <th class="text-end" style="width:100px" data-en="Actions">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
        <tr>
          <td></td>
          <td>{{ $item->code ?: '—' }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
          <td>
            @if($item->is_active)
              <span class="ct-status-active" data-en="Active">Aktif</span>
            @else
              <span class="ct-status-inactive" data-en="Inactive">Tidak Aktif</span>
            @endif
          </td>
          <td>
            <div class="d-flex justify-content-end gap-1">
              @if($canEdit)
              <a href="{{ route('master-data.administration.goods-types.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                <i class="fas fa-pen"></i>
              </a>
              @else
              <button class="btn btn-sm btn-outline-primary" disabled><i class="fas fa-pen"></i></button>
              @endif
              @if($canDelete)
              <form method="POST" action="{{ route('master-data.administration.goods-types.destroy', $item) }}" class="js-delete-form" data-name="{{ $item->name }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
              @else
              <button class="btn btn-sm btn-outline-danger" disabled><i class="fas fa-trash"></i></button>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection
