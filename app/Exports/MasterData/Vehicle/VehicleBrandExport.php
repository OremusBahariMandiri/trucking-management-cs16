<?php

namespace App\Exports\MasterData\Vehicle;

use App\Models\MasterData\Vehicle\VehicleBrand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VehicleBrandExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function collection(): \Illuminate\Support\Collection
    {
        $filters = $this->filters;
        return VehicleBrand::query()
            ->when($filters['q'] ?? null, fn($q,$s) => $q->where('name','like',"%{$s}%")->orWhere('country_of_origin','like',"%{$s}%"))
            ->when(isset($filters['status']) && $filters['status'] !== '', fn($q) => $q->where('is_active', $filters['status']))
            ->latest()->get();
    }

    public function headings(): array
    {
        return ["No", "Kode", "Nama Merek", "Negara Asal", "Deskripsi", "Status"];
    }

    public function map($item): array
    {
        static $index = 0;
        return [
                    $index + 1,
                    $item->code ?? '—',
                    $item->name ?? '—',
                    $item->country_of_origin ?? '—',
                    $item->description ?? '—',
                    $item->is_active ? 'Aktif' : 'Tidak Aktif'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1D4ED8']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Merek Kendaraan';
    }
}
