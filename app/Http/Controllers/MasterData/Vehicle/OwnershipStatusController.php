<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\OwnershipStatus\StoreOwnershipStatusRequest;
use App\Http\Requests\MasterData\Vehicle\OwnershipStatus\UpdateOwnershipStatusRequest;
use App\Models\MasterData\Vehicle\OwnershipStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\OwnershipStatusExport;

class OwnershipStatusController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new OwnershipStatusExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.ownership-status',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('status-kepemilikan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new OwnershipStatusExport($filters),
                'status-kepemilikan-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $items = OwnershipStatus::query()
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
            'total'    => OwnershipStatus::count(),
            'active'   => OwnershipStatus::where('is_active', true)->count(),
            'inactive' => OwnershipStatus::where('is_active', false)->count(),
        ];

        return view('master-data.ownership-status.index', compact('items', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.ownership-status.create');
    }

    public function store(StoreOwnershipStatusRequest $request): RedirectResponse
    {
        OwnershipStatus::create($request->validated());

        return redirect()
            ->route('master-data.ownership-statuses.index')
            ->with('success', 'Ownership status added successfully.');
    }

    public function edit(OwnershipStatus $ownershipStatus): View
    {
        return view('master-data.ownership-status.edit', compact('ownershipStatus'));
    }

    public function update(UpdateOwnershipStatusRequest $request, OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $ownershipStatus->update($request->validated());

        return redirect()
            ->route('master-data.ownership-statuses.index')
            ->with('success', 'Ownership status updated successfully.');
    }

    public function destroy(OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $ownershipStatus->delete();

        return redirect()
            ->route('master-data.ownership-statuses.index')
            ->with('success', 'Ownership status deleted successfully.');
    }

    public function toggle(OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $ownershipStatus->update(['is_active' => ! $ownershipStatus->is_active]);

        return back()->with('success', 'Ownership status updated.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            OwnershipStatus::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}
