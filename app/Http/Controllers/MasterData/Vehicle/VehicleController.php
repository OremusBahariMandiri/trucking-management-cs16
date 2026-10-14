<?php

namespace App\Http\Controllers\MasterData\Vehicle;

use App\Actions\MasterData\Vehicle\CreateVehicle;
use App\Actions\MasterData\Vehicle\DeleteVehicle;
use App\Actions\MasterData\Vehicle\UpdateVehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Vehicle\StoreVehicleRequest;
use App\Http\Requests\MasterData\Vehicle\UpdateVehicleRequest;
use App\Http\Resources\MasterData\Vehicle\VehicleResource;
use App\Models\MasterData\Vehicle\Vehicle;
use App\Services\MasterData\Vehicle\VehicleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $service,
        protected CreateVehicle  $createAction,
        protected UpdateVehicle  $updateAction,
        protected DeleteVehicle  $deleteAction,
    ) {}

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $filters  = $request->only(['search', 'status', 'type_id', 'brand_id', 'ownership']);
        $vehicles = $this->service->list($filters, $request->integer('per_page', 15));
        $options  = $this->service->formOptions();
        $stats    = $this->service->stats();

        return view('master-data.vehicle.index', compact('vehicles', 'filters', 'options', 'stats'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────────────────

    public function create(): View
    {
        $options       = $this->service->formOptions();
        $suggestedCode = $this->service->generateVehicleCode();

        return view('master-data.vehicle.create', compact('options', 'suggestedCode'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $data    = $request->except(['photos', '_token', '_method']);
        $photos  = $this->preparePhotos($request);

        $vehicle = $this->createAction->handle($data, $photos);

        return redirect()
            ->route('master-data.kendaraan.show', $vehicle)
            ->with('success', "Kendaraan {$vehicle->vehicle_code} berhasil ditambahkan.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────────────────

    public function show(Vehicle $vehicle): View
    {
        $vehicle = $this->service->findWithRelations($vehicle->id);

        return view('master-data.vehicle.show', compact('vehicle'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────────────────

    public function edit(Vehicle $vehicle): View
    {
        $vehicle = $this->service->findWithRelations($vehicle->id);
        $options = $this->service->formOptions();

        return view('master-data.vehicle.edit', compact('vehicle', 'options'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data    = $request->except(['photos', '_token', '_method']);
        $photos  = $this->preparePhotos($request);

        $vehicle = $this->updateAction->handle($vehicle, $data, $photos);

        return redirect()
            ->route('master-data.kendaraan.index', $vehicle)
            ->with('success', "Kendaraan {$vehicle->vehicle_code} berhasil diperbarui.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────────────────────

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $code = $vehicle->vehicle_code ?? $vehicle->police_number;

        $this->deleteAction->handle($vehicle);

        return redirect()
            ->route('master-data.kendaraan.index')
            ->with('success', "Kendaraan {$code} berhasil dihapus.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PHOTO MANAGEMENT
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Hapus satu foto kendaraan.
     */
    public function destroyPhoto(Vehicle $vehicle, int $photoId): RedirectResponse
    {
        $this->updateAction->deletePhoto($vehicle, $photoId);

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Set foto sebagai foto utama.
     */
    public function setPrimaryPhoto(Vehicle $vehicle, int $photoId): RedirectResponse
    {
        $this->updateAction->setPrimaryPhoto($vehicle, $photoId);

        return back()->with('success', 'Foto utama berhasil diubah.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API / AJAX
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Endpoint JSON untuk autocomplete / select2 (search kendaraan).
     */
    public function apiSearch(Request $request)
    {
        $vehicles = Vehicle::query()
            ->with('brand')
            ->search($request->input('q'))
            ->active()
            ->limit(20)
            ->get();

        return VehicleResource::collection($vehicles);
    }

    /**
     * Ambil detail kendaraan (JSON) — untuk modal preview.
     */
    public function apiShow(Vehicle $vehicle)
    {
        $vehicle = $this->service->findWithRelations($vehicle->id);

        return new VehicleResource($vehicle);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Konversi array upload files ke format yang dimengerti CreateVehicle / UpdateVehicle action.
     *
     * @return array<int, array{file: \Illuminate\Http\UploadedFile, category: string|null, caption: string|null}>
     */
    private function preparePhotos(Request $request): array
    {
        if (! $request->hasFile('photos')) {
            return [];
        }

        $photos = [];
        foreach ($request->file('photos') as $index => $item) {
            if (! isset($item['file'])) {
                continue;
            }
            $photos[] = [
                'file'     => $item['file'],
                'category' => $request->input("photos.{$index}.category"),
                'caption'  => $request->input("photos.{$index}.caption"),
            ];
        }

        return $photos;
    }
}