<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\FuelType\StoreFuelTypeRequest;
use App\Http\Requests\MasterData\Vehicle\FuelType\UpdateFuelTypeRequest;
use App\Models\MasterData\Vehicle\FuelType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\FuelTypeExport;

class FuelTypeController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new FuelTypeExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.fuel-type',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('bahan-bakar-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new FuelTypeExport($filters),
                'bahan-bakar-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $fuelTypes = FuelType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->latest()
            ->get();

        $statCounts = [
            'total'    => FuelType::count(),
            'active'   => FuelType::where('is_active', true)->count(),
            'inactive' => FuelType::where('is_active', false)->count(),
        ];

        return view('master-data.fuel-type.index', compact('fuelTypes', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.fuel-type.create');
    }

    public function store(StoreFuelTypeRequest $request): RedirectResponse
    {
        FuelType::create($request->validated());

        return redirect()
            ->route('master-data.fuel-types.index')
            ->with('success', 'Jenis bahan bakar berhasil ditambahkan.');
    }

    public function edit(FuelType $fuelType): View
    {
        return view('master-data.fuel-type.edit', compact('fuelType'));
    }

    public function update(UpdateFuelTypeRequest $request, FuelType $fuelType): RedirectResponse
    {
        $fuelType->update($request->validated());

        return redirect()
            ->route('master-data.fuel-types.index')
            ->with('success', 'Jenis bahan bakar berhasil diperbarui.');
    }

    public function destroy(FuelType $fuelType): RedirectResponse
    {
        $fuelType->delete();

        return redirect()
            ->route('master-data.fuel-types.index')
            ->with('success', 'Jenis bahan bakar berhasil dihapus.');
    }

    public function toggle(FuelType $fuelType): RedirectResponse
    {
        $fuelType->update(['is_active' => !$fuelType->is_active]);

        return back()->with('success', 'Status jenis bahan bakar diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            FuelType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}