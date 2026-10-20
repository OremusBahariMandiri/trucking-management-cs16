<?php

namespace App\Http\Controllers\DataManagement\FleetManagement;

use App\Actions\DataManagement\FleetManagement\CreateFleet;
use App\Actions\DataManagement\FleetManagement\DeleteFleet;
use App\Actions\DataManagement\FleetManagement\UpdateFleet;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataManagement\FleetManagement\StoreFleetRequest;
use App\Http\Requests\DataManagement\FleetManagement\UpdateFleetRequest;
use App\Http\Resources\DataManagement\FleetManagement\FleetResource;
use App\Models\DataManagement\FleetManagement\Fleet;
use App\Services\DataManagement\FleetManagement\FleetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FleetController extends Controller
{
    public function __construct(
        protected FleetService $service,
        protected CreateFleet  $createAction,
        protected UpdateFleet  $updateAction,
        protected DeleteFleet  $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'type_id', 'brand_id', 'ownership']);
        $fleets  = $this->service->list($filters, $request->integer('per_page', 15));
        $options = $this->service->formOptions();
        $stats   = $this->service->stats();

        return view('data-management.fleet-management.fleet-data.index', compact('fleets', 'filters', 'options', 'stats'));
    }

    public function create(): View
    {
        $options       = $this->service->formOptions();
        $suggestedCode = $this->service->generateVehicleCode();

        return view('data-management.fleet-management.fleet-data.create', compact('options', 'suggestedCode'));
    }

    public function store(StoreFleetRequest $request): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['photos', 'document_files'])
            ->toArray();

        $fleet = $this->createAction->handle(
            $data,
            $request->preparedPhotos(),
            $request->preparedDocumentFiles(),
        );

        return redirect()
            ->route('data-management.fleet-data.index')
            ->with('success', "Kendaraan {$fleet->vehicle_code} berhasil ditambahkan.");
    }

    public function show(Fleet $fleet): View
    {
        $fleet = $this->service->findWithRelations($fleet->id);

        return view('data-management.fleet-management.fleet-data.show', compact('fleet'));
    }

    public function edit(Fleet $fleet): View
    {
        $fleet   = $this->service->findWithRelations($fleet->id);
        $options = $this->service->formOptions();

        return view('data-management.fleet-management.fleet-data.edit', compact('fleet', 'options'));
    }

    public function update(UpdateFleetRequest $request, Fleet $fleet): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['photos', 'document_files'])
            ->toArray();

        $fleet = $this->updateAction->handle(
            $fleet,
            $data,
            $request->preparedPhotos(),
            $request->preparedDocumentFiles(),
        );

        return redirect()
            ->route('data-management.fleet-data.index')
            ->with('success', "Kendaraan {$fleet->vehicle_code} berhasil diperbarui.");
    }

    public function destroy(Fleet $fleet): RedirectResponse
    {
        $code = $fleet->vehicle_code ?? $fleet->police_number;

        $this->deleteAction->handle($fleet);

        return redirect()
            ->route('data-management.fleet-data.index')
            ->with('success', "Kendaraan {$code} berhasil dihapus.");
    }

    // ── Photo routes ────────────────────────────────────────────────────────

    public function destroyPhoto(Fleet $fleet, int $photoId): RedirectResponse
    {
        $this->updateAction->deletePhoto($fleet, $photoId);

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function setPrimaryPhoto(Fleet $fleet, int $photoId): RedirectResponse
    {
        $this->updateAction->setPrimaryPhoto($fleet, $photoId);

        return back()->with('success', 'Foto utama berhasil diubah.');
    }

    // ── Document file routes ────────────────────────────────────────────────

    /**
     * Hapus file lampiran satu dokumen (STNK / KIR / BPKB / Asuransi).
     * Route: DELETE fleet/{fleet}/document-file/{type}
     */
    public function destroyDocumentFile(Fleet $fleet, string $documentType): RedirectResponse
    {
        $this->updateAction->deleteDocumentFile($fleet, $documentType);

        return back()->with('success', 'File dokumen berhasil dihapus.');
    }

    // ── API endpoints ───────────────────────────────────────────────────────

    public function apiSearch(Request $request)
    {
        $fleets = Fleet::query()
            ->with('brand')
            ->search($request->input('q'))
            ->active()
            ->limit(20)
            ->get();

        return FleetResource::collection($fleets);
    }

    public function apiShow(Fleet $fleet)
    {
        $fleet = $this->service->findWithRelations($fleet->id);

        return new FleetResource($fleet);
    }

    // ── PDF Export ──────────────────────────────────────────────────────────

    public function exportPdf(Fleet $fleet): \Symfony\Component\HttpFoundation\Response
    {
        $fleet = $this->service->findWithRelations($fleet->id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'exports.fleet-management.fleet-particular',
            compact('fleet')
        )->setPaper('a4', 'portrait');

        $filename = 'vehicle-particular-' . ($fleet->vehicle_code ?? $fleet->police_number) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

}
