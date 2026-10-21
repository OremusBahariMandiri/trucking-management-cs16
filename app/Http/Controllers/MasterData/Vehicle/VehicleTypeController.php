<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\VehicleType\StoreVehicleTypeRequest;
use App\Http\Requests\MasterData\Vehicle\VehicleType\UpdateVehicleTypeRequest;
use App\Models\MasterData\Vehicle\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\VehicleTypeExport;

class VehicleTypeController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new VehicleTypeExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.vehicle-type',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('jenis-kendaraan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new VehicleTypeExport($filters),
                'jenis-kendaraan-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $vehicleTypes = VehicleType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->latest()
            ->get();

        $statCounts = [
            'total'    => VehicleType::count(),
            'active'   => VehicleType::where('is_active', true)->count(),
            'inactive' => VehicleType::where('is_active', false)->count(),
        ];

        return view('master-data.vehicle-type.index', compact('vehicleTypes', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.vehicle-type.create');
    }

    public function store(StoreVehicleTypeRequest $request): RedirectResponse
    {
        VehicleType::create($request->validated());

        return redirect()
            ->route('master-data.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleType $vehicleType): View
    {
        return view('master-data.vehicle-type.edit', compact('vehicleType'));
    }

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->update($request->validated());

        return redirect()
            ->route('master-data.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->delete();

        return redirect()
            ->route('master-data.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil dihapus.');
    }

    public function toggle(VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->update(['is_active' => !$vehicleType->is_active]);

        return back()->with('success', 'Status tipe kendaraan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            VehicleType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}