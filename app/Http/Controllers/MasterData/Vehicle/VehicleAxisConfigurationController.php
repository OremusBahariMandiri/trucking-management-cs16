<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\VehicleAxisConfiguration\CreateVehicleAxisConfiguration;
use App\Actions\MasterData\Vehicle\VehicleAxisConfiguration\DeleteVehicleAxisConfiguration;
use App\Actions\MasterData\Vehicle\VehicleAxisConfiguration\UpdateVehicleAxisConfiguration;
use App\Exports\MasterData\Vehicle\AxisConfigurationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\StoreAxisConfigurationRequest;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\UpdateAxisConfigurationRequest;
use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleAxisConfigurationController extends Controller
{
    public function __construct(
        private readonly CreateVehicleAxisConfiguration $createVehicleAxisConfiguration,
        private readonly UpdateVehicleAxisConfiguration $updateVehicleAxisConfiguration,
        private readonly DeleteVehicleAxisConfiguration $deleteVehicleAxisConfiguration,
    ) {}

    public function index(Request $request)
    {
        $filters = ['q' => $request->input('q'), 'status' => $request->input('status')];

        if ($request->input('export') === 'pdf') {
            $items = (new AxisConfigurationExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.master-data.axis-configuration', compact('items'))->setPaper('a4', 'landscape');
            return $pdf->stream('konfigurasi-sumbu-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new AxisConfigurationExport($filters), 'konfigurasi-sumbu-' . now()->format('Ymd') . '.xlsx');
        }

        $items = VehicleAxisConfiguration::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

        $statCounts = [
            'total'    => VehicleAxisConfiguration::count(),
            'active'   => VehicleAxisConfiguration::where('is_active', true)->count(),
            'inactive' => VehicleAxisConfiguration::where('is_active', false)->count(),
        ];

        return view('master-data.axis-configuration.index', compact('items', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.axis-configuration.create');
    }

    public function store(StoreAxisConfigurationRequest $request): RedirectResponse
    {
        $this->createVehicleAxisConfiguration->execute($request->validated());

        return redirect()->route('master-data.axis-configurations.index')->with('success', 'Konfigurasi sumbu kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleAxisConfiguration $axisConfiguration): View
    {
        return view('master-data.axis-configuration.edit', compact('axisConfiguration'));
    }

    public function update(UpdateAxisConfigurationRequest $request, VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $this->updateVehicleAxisConfiguration->execute($axisConfiguration, $request->validated());

        return redirect()->route('master-data.axis-configurations.index')->with('success', 'Konfigurasi sumbu kendaraan berhasil diperbarui.');
    }

    public function destroy(VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $this->deleteVehicleAxisConfiguration->execute($axisConfiguration);

        return redirect()->route('master-data.axis-configurations.index')->with('success', 'Konfigurasi sumbu kendaraan berhasil dihapus.');
    }

    public function toggle(VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $axisConfiguration->update(['is_active' => !$axisConfiguration->is_active]);

        return back()->with('success', 'Status konfigurasi sumbu kendaraan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            VehicleAxisConfiguration::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}
