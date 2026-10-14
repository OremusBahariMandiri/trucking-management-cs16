<?php

namespace App\Http\Controllers\MasterData\Vehicle;

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
    public function index(Request $request): View
    {
        $vehicleModels = VehicleModel::query()
            ->with(['brand', 'vehicleType'])
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->when($request->filled('brand_id'), fn($q) =>
                $q->where('vehicle_brand_id', $request->input('brand_id'))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statCounts = [
            'total'    => VehicleModel::count(),
            'active'   => VehicleModel::where('is_active', true)->count(),
            'inactive' => VehicleModel::where('is_active', false)->count(),
        ];

        $brands = VehicleBrand::active()->orderBy('name')->get();

        return view('master-data.vehicle-model.index', compact('vehicleModels', 'statCounts', 'brands'));
    }

    public function create(): View
    {
        $brands       = VehicleBrand::active()->orderBy('name')->get();
        $vehicleTypes = VehicleType::active()->orderBy('name')->get();

        return view('master-data.vehicle-model.create', compact('brands', 'vehicleTypes'));
    }

    public function store(StoreVehicleModelRequest $request): RedirectResponse
    {
        VehicleModel::create($request->validated());

        return redirect()
            ->route('master-data.vehicle-models.index')
            ->with('success', 'Model kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleModel $vehicleModel): View
    {
        $brands       = VehicleBrand::active()->orderBy('name')->get();
        $vehicleTypes = VehicleType::active()->orderBy('name')->get();

        return view('master-data.vehicle-model.edit', compact('vehicleModel', 'brands', 'vehicleTypes'));
    }

    public function update(UpdateVehicleModelRequest $request, VehicleModel $vehicleModel): RedirectResponse
    {
        $vehicleModel->update($request->validated());

        return redirect()
            ->route('master-data.vehicle-models.index')
            ->with('success', 'Model kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleModel $vehicleModel): RedirectResponse
    {
        $vehicleModel->delete();

        return redirect()
            ->route('master-data.vehicle-models.index')
            ->with('success', 'Model kendaraan berhasil dihapus.');
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
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->when($request->filled('brand_id'), fn($q) =>
                    $q->where('vehicle_brand_id', $request->input('brand_id'))
                )
                ->orderBy('name')
                ->get(['id', 'name', 'vehicle_brand_id'])
        );
    }
}