<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\VehicleBrand\CreateVehicleBrand;
use App\Actions\MasterData\Vehicle\VehicleBrand\DeleteVehicleBrand;
use App\Actions\MasterData\Vehicle\VehicleBrand\UpdateVehicleBrand;
use App\Exports\MasterData\Vehicle\VehicleBrandExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\VehicleBrand\StoreVehicleBrandRequest;
use App\Http\Requests\MasterData\Vehicle\VehicleBrand\UpdateVehicleBrandRequest;
use App\Models\MasterData\Vehicle\VehicleBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleBrandController extends Controller
{
    public function __construct(
        private readonly CreateVehicleBrand $createVehicleBrand,
        private readonly UpdateVehicleBrand $updateVehicleBrand,
        private readonly DeleteVehicleBrand $deleteVehicleBrand,
    ) {}

    public function index(Request $request)
    {
        $filters = ['q' => $request->input('q'), 'status' => $request->input('status')];

        if ($request->input('export') === 'pdf') {
            $items = (new VehicleBrandExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.master-data.vehicle-brand', compact('items'))->setPaper('a4', 'landscape');
            return $pdf->stream('merek-kendaraan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new VehicleBrandExport($filters), 'merek-kendaraan-' . now()->format('Ymd') . '.xlsx');
        }

        $vehicleBrands = VehicleBrand::query()
            ->withCount('models')
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('country_of_origin', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

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
        $this->createVehicleBrand->execute($request->validated());

        return redirect()->route('master-data.vehicle-brands.index')->with('success', 'Merek kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleBrand $vehicleBrand): View
    {
        return view('master-data.vehicle-brand.edit', compact('vehicleBrand'));
    }

    public function update(UpdateVehicleBrandRequest $request, VehicleBrand $vehicleBrand): RedirectResponse
    {
        $this->updateVehicleBrand->execute($vehicleBrand, $request->validated());

        return redirect()->route('master-data.vehicle-brands.index')->with('success', 'Merek kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleBrand $vehicleBrand): RedirectResponse
    {
        $this->deleteVehicleBrand->execute($vehicleBrand);

        return redirect()->route('master-data.vehicle-brands.index')->with('success', 'Merek kendaraan berhasil dihapus.');
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
