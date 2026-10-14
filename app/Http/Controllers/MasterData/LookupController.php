<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class LookupController extends Controller
{
    abstract protected function modelClass(): string;
    abstract protected function routePrefix(): string;
    abstract protected function viewPrefix(): string;
    abstract protected function storeRequest(): string;
    abstract protected function updateRequest(): string;

    protected function resourceLabel(): string
    {
        return 'Data';
    }

    protected function apiFields(): array
    {
        return ['id', 'name'];
    }

    public function index(Request $request)
    {
        $model  = $this->modelClass();
        $search = $request->get('search');

        $items = $model::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view($this->viewPrefix() . '.index', compact('items', 'search'));
    }

    public function create()
    {
        return view($this->viewPrefix() . '.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = app($this->storeRequest())->validated();

        ($this->modelClass())::create($validated);

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->resourceLabel() . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = ($this->modelClass())::findOrFail($id);

        return view($this->viewPrefix() . '.edit', compact('item'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = app($this->updateRequest())->validated();

        ($this->modelClass())::findOrFail($id)->update($validated);

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->resourceLabel() . ' berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $item = ($this->modelClass())::findOrFail($id);

        if (method_exists($item, 'vehicles') && $item->vehicles()->exists()) {
            return back()->with(
                'error',
                $this->resourceLabel() . ' tidak dapat dihapus karena masih digunakan oleh kendaraan.'
            );
        }

        $item->delete();

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->resourceLabel() . ' berhasil dihapus.');
    }

    public function toggleActive($id): JsonResponse
    {
        $item = ($this->modelClass())::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $item->is_active,
            'label'     => $item->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    public function apiList(Request $request): JsonResponse
    {
        $search = $request->get('q', '');
        $limit  = min((int) $request->get('limit', 30), 100);

        $items = ($this->modelClass())::query()
            ->where('is_active', true)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->limit($limit)
            ->get($this->apiFields());

        $results = $items->map(fn($item) => array_merge(
            ['id' => $item->id, 'text' => $item->name],
            collect($this->apiFields())
                ->filter(fn($f) => !in_array($f, ['id', 'name']))
                ->mapWithKeys(fn($f) => [$f => $item->$f])
                ->toArray()
        ));

        return response()->json([
            'results' => $results,
            'total'   => $results->count(),
        ]);
    }
}