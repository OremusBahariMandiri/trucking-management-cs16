<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\VehicleModel\CreateVehicleModel;
use App\Actions\MasterData\Vehicle\VehicleModel\DeleteVehicleModel;
use App\Actions\MasterData\Vehicle\VehicleModel\UpdateVehicleModel;
use App\Exports\MasterData\Vehicle\VehicleModelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\VehicleModel\StoreVehicleModelRequest;
use App\Http\Requests\MasterData\Vehicle\VehicleModel\UpdateVehicleModelRequest;
use App\Models\MasterData\Vehicle\VehicleBrand;
use App\Models\MasterData\Vehicle\VehicleModel;
use App\Models\MasterData\Vehicle\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleModelController extends Controller
{
    public function __construct(
        private readonly CreateVehicleModel $createVehicleModel,
        private readonly UpdateVehicleModel $updateVehicleModel,
        private readonly DeleteVehicleModel $deleteVehicleModel,
    ) {}

    public function index(Request $request)
    {
        $filters = ['q' => $request->input('q'), 'status' => $request->input('status')];

        if ($request->input('export') === 'pdf') {
            $items = (new VehicleModelExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.master-data.vehicle-model', compact('items'))->setPaper('a4', 'landscape');
            return $pdf->stream('model-kendaraan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new VehicleModelExport($filters), 'model-kendaraan-' . now()->format('Ymd') . '.xlsx');
        }

        $vehicleModels = VehicleModel::query()
            ->with(['brand', 'vehicleType'])
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$s}%"))
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

        $statCounts = [
            'total'    => VehicleModel::count(),
            'active'   => VehicleModel::where('is_active', true)->count(),
            'inactive' => VehicleModel::where('is_active', false)->count(),
        ];

        return view('master-data.vehicle-model.index', compact('vehicleModels', 'statCounts'));
    }

    public function create(): View
    {
        $brands       = VehicleBrand::active()->orderBy('name')->get();
        $vehicleTypes = VehicleType::active()->orderBy('name')->get();

        return view('master-data.vehicle-model.create', compact('brands', 'vehicleTypes'));
    }

    public function store(StoreVehicleModelRequest $request): RedirectResponse
    {
        $this->createVehicleModel->execute($request->validated());

        return redirect()->route('master-data.vehicle-models.index')->with('success', 'Model kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleModel $vehicleModel): View
    {
        $brands       = VehicleBrand::active()->orderBy('name')->get();
        $vehicleTypes = VehicleType::active()->orderBy('name')->get();

        return view('master-data.vehicle-model.edit', compact('vehicleModel', 'brands', 'vehicleTypes'));
    }

    public function update(UpdateVehicleModelRequest $request, VehicleModel $vehicleModel): RedirectResponse
    {
        $this->updateVehicleModel->execute($vehicleModel, $request->validated());

        return redirect()->route('master-data.vehicle-models.index')->with('success', 'Model kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleModel $vehicleModel): RedirectResponse
    {
        $this->deleteVehicleModel->execute($vehicleModel);

        return redirect()->route('master-data.vehicle-models.index')->with('success', 'Model kendaraan berhasil dihapus.');
    }

    public function toggle(VehicleModel $vehicleModel): RedirectResponse
    {
        $vehicleModel->update(['is_active' => !$vehicleModel->is_active]);

        return back()->with('success', 'Status model kendaraan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            VehicleModel::active()
                ->with('brand:id,name')
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->when($request->input('brand_id'), fn($q, $id) => $q->where('vehicle_brand_id', $id))
                ->orderBy('name')
                ->get(['id', 'name', 'vehicle_brand_id'])
        );
    }
}
