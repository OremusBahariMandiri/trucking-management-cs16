<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\BodyType\StoreBodyTypeRequest;
use App\Http\Requests\MasterData\Vehicle\BodyType\UpdateBodyTypeRequest;
use App\Models\MasterData\Vehicle\BodyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\MasterData\Vehicle\BodyTypeExport;

class BodyTypeController extends Controller
{
    public function index(Request $request)
    {
        // ── Export ─────────────────────────────────────────────────
        $filters = [
            'q' => $request->input('q'),
            'status' => $request->input('status'),
        ];

        if ($request->input('export') === 'pdf') {
            $items = (new BodyTypeExport($filters))->collection();
            $pdf   = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                'exports.master-data.body-type',
                compact('items')
            )->setPaper('a4', 'landscape');

            return $pdf->stream('jenis-karoseri-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->input('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new BodyTypeExport($filters),
                'jenis-karoseri-' . now()->format('Ymd') . '.xlsx'
            );
        }

        // ── Normal index ────────────────────────────────────────────
        $bodyTypes = BodyType::query()
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
            'total'    => BodyType::count(),
            'active'   => BodyType::where('is_active', true)->count(),
            'inactive' => BodyType::where('is_active', false)->count(),
        ];

        return view('master-data.body-type.index', compact('bodyTypes', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.body-type.create');
    }

    public function store(StoreBodyTypeRequest $request): RedirectResponse
    {
        BodyType::create($request->validated());

        return redirect()
            ->route('master-data.body-types.index')
            ->with('success', 'Jenis karoseri berhasil ditambahkan.');
    }

    public function edit(BodyType $bodyType): View
    {
        return view('master-data.body-type.edit', compact('bodyType'));
    }

    public function update(UpdateBodyTypeRequest $request, BodyType $bodyType): RedirectResponse
    {
        $bodyType->update($request->validated());

        return redirect()
            ->route('master-data.body-types.index')
            ->with('success', 'Jenis karoseri berhasil diperbarui.');
    }

    public function destroy(BodyType $bodyType): RedirectResponse
    {
        $bodyType->delete();

        return redirect()
            ->route('master-data.body-types.index')
            ->with('success', 'Jenis karoseri berhasil dihapus.');
    }

    public function toggle(BodyType $bodyType): RedirectResponse
    {
        $bodyType->update(['is_active' => !$bodyType->is_active]);

        return back()->with('success', 'Status jenis karoseri diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            BodyType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }
}