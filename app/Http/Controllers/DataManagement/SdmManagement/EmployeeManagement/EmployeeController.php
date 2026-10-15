<?php

namespace App\Http\Controllers\DataManagement\SdmManagement\EmployeeManagement;

use App\Actions\DataManagement\SdmManagement\EmployeeManagement\CreateEmployee;
use App\Actions\DataManagement\SdmManagement\EmployeeManagement\DeleteEmployee;
use App\Actions\DataManagement\SdmManagement\EmployeeManagement\UpdateEmployee;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataManagement\SdmManagement\EmployeeManagement\StoreEmployeeRequest;
use App\Http\Requests\DataManagement\SdmManagement\EmployeeManagement\UpdateEmployeeRequest;
use App\Models\DataManagement\SdmManagement\EmployeeManagement\Employee;
use App\Services\DataManagement\SdmManagement\EmployeeManagement\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $service,
        protected CreateEmployee  $createAction,
        protected UpdateEmployee  $updateAction,
        protected DeleteEmployee  $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $filters   = $request->only(['search']);
        $employees = $this->service->list($filters, $request->integer('per_page', 15));
        $stats     = $this->service->stats();

        return view('data-management.sdm-management.employee-data.index',
            compact('employees', 'filters', 'stats'));
    }

    public function create(): View
    {
        $suggestedNrk = $this->service->generateNrk();

        return view('data-management.sdm-management.employee-data.create',
            compact('suggestedNrk'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $employee = $this->createAction->handle($data);

        return redirect()
            ->route('data-management.sdm-management.employee-data.index')
            ->with('success', "Karyawan {$employee->full_name} berhasil ditambahkan.");
    }

    public function show(Employee $employee): View
    {
        return view('data-management.sdm-management.employee-data.show',
            compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        return view('data-management.sdm-management.employee-data.edit',
            compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $employee = $this->updateAction->handle($employee, $data);

        return redirect()
            ->route('data-management.sdm-management.employee-data.index')
            ->with('success', "Data karyawan {$employee->full_name} berhasil diperbarui.");
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $name = $employee->full_name;
        $this->deleteAction->handle($employee);

        return redirect()
            ->route('data-management.sdm-management.employee-data.index')
            ->with('success', "Karyawan {$name} berhasil dihapus.");
    }

    public function apiGenerateNrk(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['nrk' => $this->service->generateNrk()]);
    }
}