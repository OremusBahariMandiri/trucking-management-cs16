<?php

namespace App\Http\Controllers\MasterData\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\CustomerType\StoreCustomerTypeRequest;
use App\Http\Requests\MasterData\Administration\CustomerType\UpdateCustomerTypeRequest;
use App\Models\MasterData\Administration\CustomerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerTypeController extends Controller
{
    public function index(Request $request): View
    {
        $customerTypes = CustomerType::query()
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
            'total'    => CustomerType::count(),
            'active'   => CustomerType::where('is_active', true)->count(),
            'inactive' => CustomerType::where('is_active', false)->count(),
        ];

        return view('master-data.administration.customer-type.index', compact('customerTypes', 'statCounts'));
    }

    public function create(): View
    {
        return view('master-data.administration.customer-type.create');
    }

    public function store(StoreCustomerTypeRequest $request): RedirectResponse
    {
        CustomerType::create($request->validated());

        return redirect()
            ->route('master-data.administration.customer-types.index')
            ->with('success', 'Jenis pelanggan berhasil ditambahkan.');
    }

    public function edit(CustomerType $customerType): View
    {
        return view('master-data.administration.customer-type.edit', compact('customerType'));
    }

    public function update(UpdateCustomerTypeRequest $request, CustomerType $customerType): RedirectResponse
    {
        $customerType->update($request->validated());

        return redirect()
            ->route('master-data.administration.customer-types.index')
            ->with('success', 'Jenis pelanggan berhasil diperbarui.');
    }

    public function destroy(CustomerType $customerType): RedirectResponse
    {
        $customerType->delete();

        return redirect()
            ->route('master-data.administration.customer-types.index')
            ->with('success', 'Jenis pelanggan berhasil dihapus.');
    }

    public function toggle(CustomerType $customerType): RedirectResponse
    {
        $customerType->update(['is_active' => !$customerType->is_active]);

        return back()->with('success', 'Status jenis pelanggan diperbarui.');
    }

    public function apiList(Request $request)
    {
        return response()->json(
            CustomerType::active()
                ->when($request->input('q'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
        );
    }
}
