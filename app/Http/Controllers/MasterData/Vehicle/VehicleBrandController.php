<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\VehicleBrand\StoreVehicleBrandRequest;
use App\Http\Requests\MasterData\Vehicle\VehicleBrand\UpdateVehicleBrandRequest;
use App\Models\MasterData\Vehicle\VehicleBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\VehicleBrandExport;

class VehicleBrandController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new VehicleBrandExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.vehicle-brand',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('merek-kendaraan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new VehicleBrandExport($filters),
                'merek-kendaraan-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $vehicleBrands = VehicleBrand::query()
            ->withCount('models')
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('country_of_origin', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statCounts = [
            'total'    => VehicleBrand::count(),
            'active'   => VehicleBrand::where('is_active', true)->count(),
            'inactive' => VehicleBrand::where('is_active', false)->count(),
        ];

        return view('master-data.vehicle-brand.index', compact('vehicleBrands', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.vehicle-brand.create');
    }

    public function store(StoreVehicleBrandRequest $request): RedirectResponse
    {
        VehicleBrand::create($request->validated());

        return redirect()
            ->route('master-data.vehicle-brands.index')
            ->with('success', 'Merek kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleBrand $vehicleBrand): View
    {
        return view('master-data.vehicle-brand.edit', compact('vehicleBrand'));
    }

    public function update(UpdateVehicleBrandRequest $request, VehicleBrand $vehicleBrand): RedirectResponse
    {
        $vehicleBrand->update($request->validated());

        return redirect()
            ->route('master-data.vehicle-brands.index')
            ->with('success', 'Merek kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleBrand $vehicleBrand): RedirectResponse
    {
        $vehicleBrand->delete();

        return redirect()
            ->route('master-data.vehicle-brands.index')
            ->with('success', 'Merek kendaraan berhasil dihapus.');
    }

    public function toggle(VehicleBrand $vehicleBrand): RedirectResponse
    {
        $vehicleBrand->update(['is_active' => !$vehicleBrand->is_active]);

        return back()->with('success', 'Status merek kendaraan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            VehicleBrand::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'country_of_origin'])
        );
    }
}