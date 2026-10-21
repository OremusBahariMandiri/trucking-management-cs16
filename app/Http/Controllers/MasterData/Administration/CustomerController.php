<?php

namespace App\Http\Controllers\MasterData\Administration;

use App\Actions\MasterData\Administration\CreateCustomer;
use App\Actions\MasterData\Administration\DeleteCustomer;
use App\Actions\MasterData\Administration\UpdateCustomer;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Administration\Customer\StoreCustomerRequest;
use App\Http\Requests\MasterData\Administration\Customer\UpdateCustomerRequest;
use App\Models\MasterData\Administration\BusinessType;
use App\Models\MasterData\Administration\Customer;
use App\Models\MasterData\Administration\CustomerType;
use App\Services\MasterData\Administration\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerService $service,
        private readonly CreateCustomer  $createAction,
        private readonly UpdateCustomer  $updateAction,
        private readonly DeleteCustomer  $deleteAction,
    ) {}

    // ── Index ──────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $filters = [
            'q'                => $request->input('q'),
            'status'           => $request->input('status'),
            'customer_type_id' => $request->input('customer_type_id'),
        ];

        $customers     = $this->service->paginatedList($filters);
        $statCounts    = $this->service->statCounts();
        $customerTypes = CustomerType::active()->orderBy('name')->get(['id', 'name']);
        $businessTypes = BusinessType::active()->orderBy('name')->get(['id', 'name']);

        return view('master-data.administration.customer.index', compact('customers', 'statCounts', 'customerTypes', 'businessTypes', 'filters'));
    }

    // ── Create ─────────────────────────────────────────────────────────────

    public function create(): View
    {
        $customerTypes = CustomerType::active()->orderBy('name')->get(['id', 'name']);
        $businessTypes = BusinessType::active()->orderBy('name')->get(['id', 'name']);

        return view('master-data.administration.customer.create', compact('customerTypes', 'businessTypes'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = $this->createAction->handle(
            $request->except(['npwp_file', 'nib_file', '_token']),
            $request->file('npwp_file'),
            $request->file('nib_file'),
        );

        return redirect()
            ->route('master-data.administration.customers.index', $customer)
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    // ── Show ───────────────────────────────────────────────────────────────

    public function show(Customer $customer): View
    {
        $customer->load(['customerType', 'businessType', 'pics', 'createdBy', 'updatedBy']);

        return view('master-data.administration.customer.show', compact('customer'));
    }

    // ── Edit ───────────────────────────────────────────────────────────────

    public function edit(Customer $customer): View
    {
        $customer->load(['customerType', 'businessType', 'pics']);
        $customerTypes = CustomerType::active()->orderBy('name')->get(['id', 'name']);
        $businessTypes = BusinessType::active()->orderBy('name')->get(['id', 'name']);

        return view('master-data.administration.customer.edit', compact('customer', 'customerTypes', 'businessTypes'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->updateAction->handle(
            $customer,
            $request->except(['npwp_file', 'nib_file', '_token', '_method']),
            $request->file('npwp_file'),
            $request->file('nib_file'),
        );

        return redirect()
            ->route('master-data.administration.customers.index', $customer)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // ── Destroy ────────────────────────────────────────────────────────────

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->deleteAction->handle($customer);

        return redirect()
            ->route('master-data.administration.customers.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }

    // ── Toggle ─────────────────────────────────────────────────────────────

    public function toggle(Customer $customer): RedirectResponse
    {
        $customer->update(['is_active' => !$customer->is_active]);

        return back()->with('success', 'Status pelanggan diperbarui.');
    }
}
