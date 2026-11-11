@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
.ord-badge { display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .65rem;border-radius:999px;font-size:.72rem;font-weight:600;letter-spacing:.02em; }
.ord-badge-pending  { background:rgba(var(--bs-warning-rgb),.15);color:#d98c00; }
.ord-badge-approved { background:rgba(var(--bs-success-rgb),.15);color:#28a745; }
.ord-badge-rejected { background:rgba(var(--bs-danger-rgb),.15);color:#dc3545; }
.ord-stat-row { display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.2rem; }
.ord-stat-card { flex:1;min-width:110px;background:var(--surface-card,#fff);border:1px solid var(--border,rgba(0,0,0,.1));border-radius:.75rem;padding:.8rem 1rem;text-align:center; }
.ord-stat-num { font-size:1.5rem;font-weight:700;color:var(--text); }
.ord-stat-lbl { font-size:.72rem;color:var(--text-muted);margin-top:.1rem; }
</style>
@endpush

@section('content')
@php
  $canCreate = auth()->user()->hasPermission('data-management.orders.create');
  $canEdit   = auth()->user()->hasPermission('data-management.orders.edit');
  $canDelete = auth()->user()->hasPermission('data-management.orders.delete');
@endphp

{{-- Header --}}
<div class="veh-page-header mb-4">
  <div>
    <div class="veh-title"><span data-en="Order Management">Manajemen Pesanan</span></div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
        <li class="breadcrumb-item active" data-en="Orders">Pesanan</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex align-items-center gap-2">
    @if($canCreate)
    <a href="{{ route('data-management.orders.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
      <i class="fas fa-plus"></i><span data-en="Add Order">Buat Pesanan</span>
    </a>
    @endif
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3">
  {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Table Card --}}
<div class="veh-table-card">
  <div class="veh-table-header">
    <span data-en="Order List">Daftar Pesanan</span>
    <small style="color:var(--text-faint)">{{ $orders->total() }} <span data-en="records">data</span></small>
  </div>

  @if($orders->isEmpty())
  <div class="p-5 text-center" style="color:var(--text-muted)">
    <i class="fas fa-file-invoice fa-2x mb-2 d-block" style="opacity:.3"></i>
    <span data-en="No orders found">Belum ada pesanan.</span>
  </div>
  @else
  <div class="table-responsive">
    <table class="table" style="width:100%">
      <thead>
        <tr>
          <th style="width:40px">No</th>
          <th data-en="PO Number">No. PO</th>
          <th data-en="PO Date">Tanggal</th>
          <th data-en="Customer">Pelanggan</th>
          <th data-en="Total">Total (Rp)</th>
          <th data-en="Status">Status</th>
          <th class="text-end" data-en="Actions">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $i => $order)
        <tr>
          <td>{{ $orders->firstItem() + $i }}</td>
          <td>{{ $order->po_number }}</td>
          <td>{{ $order->po_date?->format('d/m/Y') }}</td>
          <td>{{ $order->customer_name ?? $order->customer?->name ?? '—' }}</td>
          <td>{{ number_format($order->total_amount, 0, ',', '.') ?: '—' }}</td>
          <td>{{ $order->approval_status_label }}
          </td>
          <td>
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('data-management.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                <i class="fas fa-eye"></i>
              </a>
              @if($canEdit)
              <a href="{{ route('data-management.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                <i class="fas fa-pen"></i>
              </a>
              @endif
              @if($canDelete)
              <form method="POST" action="{{ route('data-management.orders.destroy', $order) }}" class="js-delete-form" data-name="{{ $order->po_number }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="p-3">{{ $orders->links() }}</div>
  @endif
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.js-delete-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var name = form.dataset.name || 'pesanan ini';
        var isEn = (localStorage.getItem('lang') || 'id') === 'en';
        truckConfirm({
            text: isEn
                ? 'Delete order <strong>' + name + '</strong>? This action cannot be undone.'
                : 'Hapus pesanan <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
