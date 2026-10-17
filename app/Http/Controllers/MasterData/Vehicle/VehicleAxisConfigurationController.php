<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\StoreAxisConfigurationRequest;
use App\Http\Requests\MasterData\Vehicle\AxisConfiguration\UpdateAxisConfigurationRequest;
use App\Models\MasterData\Vehicle\VehicleAxisConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleAxisConfigurationController extends Controller
{
    public function index(Request $request): View
    {
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
            ->paginate(15)
            ->withQueryString();

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
