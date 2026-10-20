<?php

namespace App\Http\Controllers\MasterData\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\BusinessType\StoreBusinessTypeRequest;
use App\Http\Requests\MasterData\Administration\BusinessType\UpdateBusinessTypeRequest;
use App\Models\MasterData\Administration\BusinessType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessTypeController extends Controller
{
    public function index(Request $request): View
    {
        $businessTypes = BusinessType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('is_active', $request->input('status'))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statCounts = [
            'total'    => BusinessType::count(),
            'active'   => BusinessType::where('is_active', true)->count(),
            'inactive' => BusinessType::where('is_active', false)->count(),
        ];

        return view('master-data.administration.business-type.index', compact('businessTypes', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.administration.business-type.create');
    }

    public function store(StoreBusinessTypeRequest $request): RedirectResponse
    {
        BusinessType::create($request->validated());

        return redirect()
            ->route('master-data.administration.business-types.index')
            ->with('success', 'Jenis usaha berhasil ditambahkan.');
    }

    public function edit(BusinessType $businessType): View
    {
        return view('master-data.administration.business-type.edit', compact('businessType'));
    }

    public function update(UpdateBusinessTypeRequest $request, BusinessType $businessType): RedirectResponse
    {
        $businessType->update($request->validated());

        return redirect()
            ->route('master-data.administration.business-types.index')
            ->with('success', 'Jenis usaha berhasil diperbarui.');
    }

    public function destroy(BusinessType $businessType): RedirectResponse
    {
        $businessType->delete();

        return redirect()
            ->route('master-data.administration.business-types.index')
            ->with('success', 'Jenis usaha berhasil dihapus.');
    }

    public function toggle(BusinessType $businessType): RedirectResponse
    {
        $businessType->update(['is_active' => !$businessType->is_active]);

        return back()->with('success', 'Status jenis usaha diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            BusinessType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}
