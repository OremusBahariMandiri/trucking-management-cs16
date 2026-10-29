<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\BodyType\CreateBodyType;
use App\Actions\MasterData\Vehicle\BodyType\DeleteBodyType;
use App\Actions\MasterData\Vehicle\BodyType\UpdateBodyType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\BodyType\StoreBodyTypeRequest;
use App\Http\Requests\MasterData\Vehicle\BodyType\UpdateBodyTypeRequest;
use App\Models\MasterData\Vehicle\BodyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BodyTypeController extends Controller
{
    public function __construct(
        private readonly CreateBodyType $createBodyType,
        private readonly UpdateBodyType $updateBodyType,
        private readonly DeleteBodyType $deleteBodyType,
    ) {}

    public function index(Request $request): View
    {
        $bodyTypes = BodyType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")
            )
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

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
        $this->createBodyType->execute($request->validated());

        return redirect()->route('master-data.body-types.index')->with('success', 'Jenis bodi kendaraan berhasil ditambahkan.');
    }

    public function edit(BodyType $bodyType): View
    {
        return view('master-data.body-type.edit', compact('bodyType'));
    }

    public function update(UpdateBodyTypeRequest $request, BodyType $bodyType): RedirectResponse
    {
        $this->updateBodyType->execute($bodyType, $request->validated());

        return redirect()->route('master-data.body-types.index')->with('success', 'Jenis bodi kendaraan berhasil diperbarui.');
    }

    public function destroy(BodyType $bodyType): RedirectResponse
    {
        $this->deleteBodyType->execute($bodyType);

        return redirect()->route('master-data.body-types.index')->with('success', 'Jenis bodi kendaraan berhasil dihapus.');
    }

    public function toggle(BodyType $bodyType): RedirectResponse
    {
        $bodyType->update(['is_active' => !$bodyType->is_active]);

        return back()->with('success', 'Status jenis bodi kendaraan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            BodyType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}
