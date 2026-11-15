<?php
namespace App\Http\Controllers\MasterData\Administration;

use App\Actions\MasterData\Administration\PackagingType\CreatePackagingType;
use App\Actions\MasterData\Administration\PackagingType\UpdatePackagingType;
use App\Actions\MasterData\Administration\PackagingType\DeletePackagingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\PackagingType\StorePackagingTypeRequest;
use App\Http\Requests\MasterData\Administration\PackagingType\UpdatePackagingTypeRequest;
use App\Models\MasterData\Administration\PackagingType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagingTypeController extends Controller
{
    public function __construct(
        private readonly CreatePackagingType $createAction,
        private readonly UpdatePackagingType $updateAction,
        private readonly DeletePackagingType $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $items = PackagingType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name','like',"%{$s}%")->orWhere('code','like',"%{$s}%"))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

        $statCounts = [
            'total'    => PackagingType::count(),
            'active'   => PackagingType::where('is_active', true)->count(),
            'inactive' => PackagingType::where('is_active', false)->count(),
        ];

        return view('master-data.administration.packaging-type.index', compact('items','statCounts'));
    }

    public function create(): View
    {
        return view('master-data.administration.packaging-type.create');
    }

    public function store(StorePackagingTypeRequest $request): RedirectResponse
    {
        $this->createAction->execute($request->validated());
        return redirect()->route('master-data.administration.packaging-types.index')
            ->with('success', 'Jenis Kemasan berhasil ditambahkan.');
    }

    public function edit(PackagingType $packaging_type): View
    {
        return view('master-data.administration.packaging-type.edit', ['item' => $packaging_type]);
    }

    public function update(UpdatePackagingTypeRequest $request, PackagingType $packaging_type): RedirectResponse
    {
        $this->updateAction->execute($packaging_type, $request->validated());
        return redirect()->route('master-data.administration.packaging-types.index')
            ->with('success', 'Jenis Kemasan berhasil diperbarui.');
    }

    public function destroy(PackagingType $packaging_type): RedirectResponse
    {
        $this->deleteAction->execute($packaging_type);
        return redirect()->route('master-data.administration.packaging-types.index')
            ->with('success', 'Jenis Kemasan berhasil dihapus.');
    }

    public function toggle(PackagingType $packaging_type): RedirectResponse
    {
        $packaging_type->update(['is_active' => !$packaging_type->is_active]);
        return back()->with('success', 'Status diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            PackagingType::where('is_active', true)
                ->when($request->input('q'), fn($q,$s) => $q->where('name','like',"%{$s}%"))
                ->orderBy('name')->get(['id','name','code'])
        );
    }
}
