<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\OwnershipStatus\CreateOwnershipStatus;
use App\Actions\MasterData\Vehicle\OwnershipStatus\DeleteOwnershipStatus;
use App\Actions\MasterData\Vehicle\OwnershipStatus\UpdateOwnershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\OwnershipStatus\StoreOwnershipStatusRequest;
use App\Http\Requests\MasterData\Vehicle\OwnershipStatus\UpdateOwnershipStatusRequest;
use App\Models\MasterData\Vehicle\OwnershipStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnershipStatusController extends Controller
{
    public function __construct(
        private readonly CreateOwnershipStatus $createOwnershipStatus,
        private readonly UpdateOwnershipStatus $updateOwnershipStatus,
        private readonly DeleteOwnershipStatus $deleteOwnershipStatus,
    ) {}

    public function index(Request $request): View
    {
        $items = OwnershipStatus::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

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
        $this->createOwnershipStatus->execute($request->validated());

        return redirect()->route('master-data.ownership-statuses.index')->with('success', 'Status kepemilikan berhasil ditambahkan.');
    }

    public function edit(OwnershipStatus $ownershipStatus): View
    {
        return view('master-data.ownership-status.edit', compact('ownershipStatus'));
    }

    public function update(UpdateOwnershipStatusRequest $request, OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $this->updateOwnershipStatus->execute($ownershipStatus, $request->validated());

        return redirect()->route('master-data.ownership-statuses.index')->with('success', 'Status kepemilikan berhasil diperbarui.');
    }

    public function destroy(OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $this->deleteOwnershipStatus->execute($ownershipStatus);

        return redirect()->route('master-data.ownership-statuses.index')->with('success', 'Status kepemilikan berhasil dihapus.');
    }

    public function toggle(OwnershipStatus $ownershipStatus): RedirectResponse
    {
        $ownershipStatus->update(['is_active' => !$ownershipStatus->is_active]);

        return back()->with('success', 'Status kepemilikan diperbarui.');
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
