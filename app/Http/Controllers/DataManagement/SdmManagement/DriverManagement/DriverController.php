<?php

namespace App\Http\Controllers\DataManagement\SdmManagement\DriverManagement;

use App\Actions\DataManagement\SdmManagement\DriverManagement\CreateDriver;
use App\Actions\DataManagement\SdmManagement\DriverManagement\DeleteDriver;
use App\Actions\DataManagement\SdmManagement\DriverManagement\UpdateDriver;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataManagement\SdmManagement\DriverManagement\StoreDriverRequest;
use App\Http\Requests\DataManagement\SdmManagement\DriverManagement\UpdateDriverRequest;
use App\Models\DataManagement\SdmManagement\DriverManagement\Driver;
use App\Models\DataManagement\SdmManagement\DriverManagement\DriverEmergencyContact;
use App\Services\DataManagement\SdmManagement\DriverManagement\DriverService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function __construct(
        protected DriverService $service,
        protected CreateDriver  $createAction,
        protected UpdateDriver  $updateAction,
        protected DeleteDriver  $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $drivers = $this->service->list($filters, $request->integer('per_page', 15));
        $options = $this->service->formOptions();
        $stats   = $this->service->stats();

        return view('data-management.sdm-management.driver-data.index',
            compact('drivers', 'filters', 'options', 'stats'));
    }

    public function create(): View
    {
        $options       = $this->service->formOptions();
        $ecOptions     = $this->service->emergencyContactOptions();
        $suggestedCode = $this->service->generateDriverCode();

        return view('data-management.sdm-management.driver-data.create',
            compact('options', 'ecOptions', 'suggestedCode'));
    }

    public function store(StoreDriverRequest $request): RedirectResponse
    {
        $data = $request->except(['photo', 'ktp_file', 'documents', 'certificates', '_token', '_method']);

        // File tunggal — teruskan sebagai UploadedFile ke action
        if ($request->hasFile('photo'))    $data['photo']    = $request->file('photo');
        if ($request->hasFile('ktp_file')) $data['ktp_file'] = $request->file('ktp_file');

        // Dokumen: gabung input teks + file
        $documents = [];
        foreach ($request->input('documents', []) as $i => $doc) {
            if (empty($doc['document_name'])) continue;
            if ($request->hasFile("documents.{$i}.file")) {
                $doc['file'] = $request->file("documents.{$i}.file");
            }
            $documents[] = $doc;
        }

        // Sertifikat: gabung input teks + file
        $certificates = [];
        foreach ($request->input('certificates', []) as $i => $cert) {
            if (empty($cert['certificate_name'])) continue;
            if ($request->hasFile("certificates.{$i}.file")) {
                $cert['file'] = $request->file("certificates.{$i}.file");
            }
            $certificates[] = $cert;
        }

        // Kontak darurat
        $emergencyContacts = [];
        foreach ($request->input('emergency_contacts', []) as $i => $ec) {
            if (empty($ec['full_name']) || empty($ec['phone_primary'])) continue;
            $emergencyContacts[] = $ec;
        }

        $driver = $this->createAction->handle($data, $documents, $certificates, $emergencyContacts);

        return redirect()
            ->route('data-management.sdm-management.driver-data.index')
            ->with('success', "Sopir {$driver->full_name} berhasil ditambahkan.");
    }

    public function show(Driver $driver): View
    {
        $driver = $this->service->findWithRelations($driver->id);
        $ecOptions = $this->service->emergencyContactOptions();

        return view('data-management.sdm-management.driver-data.show',
            compact('driver', 'ecOptions'));
    }

    public function edit(Driver $driver): View
    {
        $driver    = $this->service->findWithRelations($driver->id);
        $options   = $this->service->formOptions();
        $ecOptions = $this->service->emergencyContactOptions();

        return view('data-management.sdm-management.driver-data.edit',
            compact('driver', 'options', 'ecOptions'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver): RedirectResponse
    {
        $data = $request->except(['photo', 'ktp_file', 'documents', 'certificates', '_token', '_method']);

        // File tunggal
        if ($request->hasFile('photo'))    $data['photo']    = $request->file('photo');
        if ($request->hasFile('ktp_file')) $data['ktp_file'] = $request->file('ktp_file');

        // remove_photo checkbox
        $data['remove_photo'] = $request->boolean('remove_photo');

        // Dokumen baru
        $documents = [];
        foreach ($request->input('documents', []) as $i => $doc) {
            if (empty($doc['document_name'])) continue;
            if ($request->hasFile("documents.{$i}.file")) {
                $doc['file'] = $request->file("documents.{$i}.file");
            }
            $documents[] = $doc;
        }

        // Sertifikat baru
        $certificates = [];
        foreach ($request->input('certificates', []) as $i => $cert) {
            if (empty($cert['certificate_name'])) continue;
            if ($request->hasFile("certificates.{$i}.file")) {
                $cert['file'] = $request->file("certificates.{$i}.file");
            }
            $certificates[] = $cert;
        }

        // Kontak darurat baru
        $emergencyContacts = [];
        foreach ($request->input('emergency_contacts', []) as $i => $ec) {
            if (empty($ec['full_name']) || empty($ec['phone_primary'])) continue;
            $emergencyContacts[] = $ec;
        }

        // Kontak darurat yang sudah ada (update)
        $existingEmergencyContacts = $request->input('existing_emergency_contacts', []);

        $driver = $this->updateAction->handle($driver, $data, $documents, $certificates, $emergencyContacts, $existingEmergencyContacts);

        return redirect()
            ->route('data-management.sdm-management.driver-data.index')
            ->with('success', "Data sopir {$driver->full_name} berhasil diperbarui.");
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $name = $driver->full_name;
        $this->deleteAction->handle($driver);

        return redirect()
            ->route('data-management.sdm-management.driver-data.index')
            ->with('success', "Sopir {$name} berhasil dihapus.");
    }

    public function destroyDocument(Driver $driver, int $documentId): \Illuminate\Http\JsonResponse
    {
        $this->updateAction->deleteDocument($driver, $documentId);

        return response()->json(['message' => 'Dokumen berhasil dihapus.']);
    }

    public function destroyCertificate(Driver $driver, int $certificateId): \Illuminate\Http\JsonResponse
    {
        $this->updateAction->deleteCertificate($driver, $certificateId);

        return response()->json(['message' => 'Sertifikat berhasil dihapus.']);
    }

    public function destroyEmergencyContact(Driver $driver, int $contactId): \Illuminate\Http\JsonResponse
    {
        $this->updateAction->deleteEmergencyContact($driver, $contactId);

        return response()->json(['message' => 'Kontak darurat berhasil dihapus.']);
    }

    // ── PDF Export ──────────────────────────────────────────────────────────

    public function exportPdf(Driver $driver): \Symfony\Component\HttpFoundation\Response
    {
        $driver = $this->service->findWithRelations($driver->id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'exports.sdm-management.driver-cv',
            compact('driver')
        )->setPaper('a4', 'portrait');

        $filename = 'driver-cv-' . ($driver->driver_code ?? $driver->id) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    public function apiGenerateCode(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['code' => $this->service->generateDriverCode()]);
    }
}