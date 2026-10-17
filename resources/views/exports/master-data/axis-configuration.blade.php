<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Konfigurasi Sumbu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #0f172a; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1d4ed8; padding-bottom: 12px; }
        .header h2 { font-size: 16px; font-weight: bold; color: #1d4ed8; margin-bottom: 4px; }
        .header p { font-size: 9px; color: #64748b; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 9px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1d4ed8; color: #fff; }
        thead th { padding: 7px 8px; text-align: left; font-weight: bold; font-size: 9px; }
        tbody tr:nth-child(even) { background: #f1f5f9; }
        tbody tr:hover { background: #dbeafe; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .footer { margin-top: 16px; text-align: right; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Konfigurasi Sumbu</h2>
        <p>Sistem Manajemen Trucking CS16</p>
    </div>

    <div class="meta">
        <span>Total Data: <strong>{ $items->count() }</strong></span>
        <span>Dicetak: { now()->format('d/m/Y H:i') }</span>
    </div>

    <table>
        <thead>
            <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->code ?? '—' }}</td>
                        <td>{{ $item->name ?? '—' }}</td>
                        <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                        <td>{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman ini digenerate otomatis oleh sistem &mdash; { now()->format('d F Y, H:i') } WIB
    </div>
</body>
</html>
