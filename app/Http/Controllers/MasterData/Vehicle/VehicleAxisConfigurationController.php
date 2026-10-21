<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\StoreAxisConfigurationRequest;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\UpdateAxisConfigurationRequest;
use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\AxisConfigurationExport;

class VehicleAxisConfigurationController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new AxisConfigurationExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.axis-configuration',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('konfigurasi-sumbu-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new AxisConfigurationExport($filters),
                'konfigurasi-sumbu-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $items = VehicleAxisConfiguration::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->latest()
            ->get();

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
        VehicleAxisConfiguration::create($request->validated());

        return redirect()
            ->route('master-data.axis-configurations.index')
            ->with('success', 'Axis configuration added successfully.');
    }

    public function edit(VehicleAxisConfiguration $axisConfiguration): View
    {
        return view('master-data.axis-configuration.edit', compact('axisConfiguration'));
    }

    public function update(UpdateAxisConfigurationRequest $request, VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $axisConfiguration->update($request->validated());

        return redirect()
            ->route('master-data.axis-configurations.index')
            ->with('success', 'Axis configuration updated successfully.');
    }

    public function destroy(VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $axisConfiguration->delete();

        return redirect()
            ->route('master-data.axis-configurations.index')
            ->with('success', 'Axis configuration deleted successfully.');
    }

    public function toggle(VehicleAxisConfiguration $axisConfiguration): RedirectResponse
    {
        $axisConfiguration->update(['is_active' => ! $axisConfiguration->is_active]);

        return back()->with('success', 'Axis configuration status updated.');
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
