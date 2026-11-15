<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\VehicleType\CreateVehicleType;
use App\Actions\MasterData\Vehicle\VehicleType\DeleteVehicleType;
use App\Actions\MasterData\Vehicle\VehicleType\UpdateVehicleType;
use App\Exports\MasterData\Vehicle\VehicleTypeExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\VehicleType\StoreVehicleTypeRequest;
use App\Http\Requests\MasterData\Vehicle\VehicleType\UpdateVehicleTypeRequest;
use App\Models\MasterData\Vehicle\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    public function __construct(
        private readonly CreateVehicleType $createVehicleType,
        private readonly UpdateVehicleType $updateVehicleType,
        private readonly DeleteVehicleType $deleteVehicleType,
    ) {}

    public function index(Request $request)
    {
        $filters = ['q' => $request->input('q'), 'status' => $request->input('status')];

        if ($request->input('export') === 'pdf') {
            $items = (new VehicleTypeExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.master-data.vehicle-type', compact('items'))->setPaper('a4', 'landscape');
            return $pdf->stream('jenis-kendaraan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new VehicleTypeExport($filters), 'jenis-kendaraan-' . now()->format('Ymd') . '.xlsx');
        }

        $vehicleTypes = VehicleType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

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
        $this->createVehicleType->execute($request->validated());

        return redirect()->route('master-data.vehicle-types.index')->with('success', 'Tipe kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleType $vehicleType): View
    {
        return view('master-data.vehicle-type.edit', compact('vehicleType'));
    }

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType): RedirectResponse
    {
        $this->updateVehicleType->execute($vehicleType, $request->validated());

        return redirect()->route('master-data.vehicle-types.index')->with('success', 'Tipe kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $this->deleteVehicleType->execute($vehicleType);

        return redirect()->route('master-data.vehicle-types.index')->with('success', 'Tipe kendaraan berhasil dihapus.');
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
