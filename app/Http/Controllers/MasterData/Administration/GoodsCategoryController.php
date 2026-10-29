<?php
namespace App\Http\Controllers\MasterData\Administration;

use App\Actions\MasterData\Administration\GoodsCategory\CreateGoodsCategory;
use App\Actions\MasterData\Administration\GoodsCategory\UpdateGoodsCategory;
use App\Actions\MasterData\Administration\GoodsCategory\DeleteGoodsCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\GoodsCategory\StoreGoodsCategoryRequest;
use App\Http\Requests\MasterData\Administration\GoodsCategory\UpdateGoodsCategoryRequest;
use App\Models\MasterData\Administration\GoodsCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsCategoryController extends Controller
{
    public function __construct(
        private readonly CreateGoodsCategory $createAction,
        private readonly UpdateGoodsCategory $updateAction,
        private readonly DeleteGoodsCategory $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $items = GoodsCategory::query()
            ->when($request->input('q'), fn($q, $s) =>
                $q->where('name','like',"%{$s}%")->orWhere('code','like',"%{$s}%"))
            ->when($request->filled('status'), fn($q) => $q->where('is_active', $request->input('status')))
            ->latest()->get();

        $statCounts = [
            'total'    => GoodsCategory::count(),
            'active'   => GoodsCategory::where('is_active', true)->count(),
            'inactive' => GoodsCategory::where('is_active', false)->count(),
        ];

        return view('master-data.administration.goods-category.index', compact('items','statCounts'));
    }

    public function create(): View
    {
        return view('master-data.administration.goods-category.create');
    }

    public function store(StoreGoodsCategoryRequest $request): RedirectResponse
    {
        $this->createAction->execute($request->validated());
        return redirect()->route('master-data.administration.goods-categories.index')
            ->with('success', 'Kategori Barang berhasil ditambahkan.');
    }

    public function edit(GoodsCategory $goods_category): View
    {
        return view('master-data.administration.goods-category.edit', ['item' => $goods_category]);
    }

    public function update(UpdateGoodsCategoryRequest $request, GoodsCategory $goods_category): RedirectResponse
    {
        $this->updateAction->execute($goods_category, $request->validated());
        return redirect()->route('master-data.administration.goods-categories.index')
            ->with('success', 'Kategori Barang berhasil diperbarui.');
    }

    public function destroy(GoodsCategory $goods_category): RedirectResponse
    {
        $this->deleteAction->execute($goods_category);
        return redirect()->route('master-data.administration.goods-categories.index')
            ->with('success', 'Kategori Barang berhasil dihapus.');
    }

    public function toggle(GoodsCategory $goods_category): RedirectResponse
    {
        $goods_category->update(['is_active' => !$goods_category->is_active]);
        return back()->with('success', 'Status diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            GoodsCategory::where('is_active', true)
                ->when($request->input('q'), fn($q,$s) => $q->where('name','like',"%{$s}%"))
                ->orderBy('name')->get(['id','name','code'])
        );
    }
}
