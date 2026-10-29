<?php
namespace App\Http\Controllers\MasterData\Administration;

use App\Actions\MasterData\Administration\GoodsType\CreateGoodsType;
use App\Actions\MasterData\Administration\GoodsType\UpdateGoodsType;
use App\Actions\MasterData\Administration\GoodsType\DeleteGoodsType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\GoodsType\StoreGoodsTypeRequest;
use App\Http\Requests\MasterData\Administration\GoodsType\UpdateGoodsTypeRequest;
use App\Models\MasterData\Administration\GoodsType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsTypeController extends Controller
{
    public function __construct(
        private readonly CreateGoodsType $createAction,
        private readonly UpdateGoodsType $updateAction,
        private readonly DeleteGoodsType $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $items = GoodsType::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name','like',"%{$s}%")->orWhere('code','like',"%{$s}%"))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

        $statCounts = [
            'total'    => GoodsType::count(),
            'active'   => GoodsType::where('is_active', true)->count(),
            'inactive' => GoodsType::where('is_active', false)->count(),
        ];

        return view('master-data.administration.goods-type.index', compact('items','statCounts'));
    }

    public function create(): View
    {
        return view('master-data.administration.goods-type.create');
    }

    public function store(StoreGoodsTypeRequest $request): RedirectResponse
    {
        $this->createAction->execute($request->validated());
        return redirect()->route('master-data.administration.goods-types.index')
            ->with('success', 'Jenis Barang berhasil ditambahkan.');
    }

    public function edit(GoodsType $goods_type): View
    {
        return view('master-data.administration.goods-type.edit', ['item' => $goods_type]);
    }

    public function update(UpdateGoodsTypeRequest $request, GoodsType $goods_type): RedirectResponse
    {
        $this->updateAction->execute($goods_type, $request->validated());
        return redirect()->route('master-data.administration.goods-types.index')
            ->with('success', 'Jenis Barang berhasil diperbarui.');
    }

    public function destroy(GoodsType $goods_type): RedirectResponse
    {
        $this->deleteAction->execute($goods_type);
        return redirect()->route('master-data.administration.goods-types.index')
            ->with('success', 'Jenis Barang berhasil dihapus.');
    }

    public function toggle(GoodsType $goods_type): RedirectResponse
    {
        $goods_type->update(['is_active' => !$goods_type->is_active]);
        return back()->with('success', 'Status diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            GoodsType::where('is_active', true)
                ->when($request->input('q'), fn($q,$s) => $q->where('name','like',"%{$s}%"))
                ->orderBy('name')->get(['id','name','code'])
        );
    }
}
