<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA06 — Tabel Utama: Kendaraan
// Relasi ke: vehicle_types, vehicle_brands, vehicle_models, fuel_types, body_types, users
// FK ke departments & branches ditambahkan setelah tabel AC01/AC02 dibuat
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // ── TAB 1: INFORMASI UMUM ─────────────────────────────────────
            $table->string('vehicle_code', 20)->nullable()->unique();      // TRK-2024-001
            $table->string('police_number', 20)->nullable()->unique();     // B 1234 XYZ
            $table->string('hull_number', 30)->nullable()->unique();       // Nomor Lambung internal
            $table->foreignId('vehicle_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_model_id')->nullable()->constrained()->nullOnDelete();
            $table->string('model_name')->nullable();                      // bebas ketik jika model belum ada
            $table->smallInteger('manufacture_year')->nullable();
            $table->string('color', 50)->nullable();
            $table->enum('vehicle_status', [
                'active',    // Aktif beroperasi
                'inactive',  // Tidak aktif
                'service',   // Sedang servis/perawatan
                'broken',    // Rusak
                'sold',      // Sudah dijual
            ])->nullable()->default('active');

            // ── TAB 2: SPESIFIKASI TEKNIS ─────────────────────────────────
            $table->string('chassis_number', 50)->nullable()->unique();   // Nomor Rangka
            $table->string('engine_number', 50)->nullable()->unique();    // Nomor Mesin
            $table->foreignId('fuel_type_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('engine_capacity_cc')->nullable();    // CC mesin
            $table->enum('axis_configuration', [
                '4x2', '6x2', '6x4', '8x4', '10x4', 'other'
            ])->nullable();
            $table->unsignedTinyInteger('wheel_count')->nullable();       // Jumlah Roda
            $table->decimal('load_capacity_ton', 8, 2)->nullable();       // Kapasitas Muatan (ton)
            $table->decimal('gross_vehicle_weight_ton', 8, 2)->nullable(); // GVW / Berat Kotor
            $table->decimal('tare_weight_ton', 8, 2)->nullable();         // Berat Kosong
            $table->foreignId('body_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('body_dimension', 50)->nullable();             // P x L x T karoseri

            // ── TAB 3: KEPEMILIKAN & PENUGASAN ───────────────────────────
            $table->enum('ownership_status', [
                'company',    // Milik perusahaan
                'rental',     // Sewa
                'leasing',    // Leasing/kredit
                'kerjasama',  // Unit kerjasama/mitra
            ])->nullable();
            $table->smallInteger('acquisition_year')->nullable();
            $table->decimal('acquisition_value', 15, 2)->nullable();     // Nilai Perolehan
            $table->string('leasing_company')->nullable();                // Nama perusahaan leasing
            $table->date('leasing_end_date')->nullable();                 // Akhir periode leasing
            // department_id & branch_id ditambah via FK setelah tabel AC dibuat
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreignId('driver_id')->nullable()
                  ->references('id')->on('users')->nullOnDelete(); // Pengemudi utama

            // ── TAB 4: OPERASIONAL ────────────────────────────────────────
            $table->unsignedInteger('last_odometer_km')->nullable()->default(0);
            $table->date('last_service_date')->nullable();
            $table->unsignedInteger('service_interval_km')->nullable();  // Interval servis (km)
            $table->string('gps_tracker_id')->nullable();                // ID perangkat GPS
            $table->string('gps_provider')->nullable();                  // Teltonika, Queclink, dll.
            $table->enum('gps_status', [
                'active',        // Aktif terpasang & online
                'offline',       // Terpasang tapi offline
                'not_installed', // Belum/tidak terpasang
            ])->nullable()->default('not_installed');

            // ── TAB 5: DOKUMEN & LEGALITAS ────────────────────────────────
            $table->string('stnk_number', 30)->nullable();
            $table->date('tax_expiry_date')->nullable();                  // Jatuh tempo pajak STNK
            $table->string('kir_number', 30)->nullable();
            $table->date('kir_expiry_date')->nullable();                  // Jatuh tempo KIR
            $table->string('bpkb_number', 30)->nullable();
            $table->string('insurance_policy_number', 50)->nullable();   // Nomor polis asuransi
            $table->string('insurance_company')->nullable();              // Nama perusahaan asuransi
            $table->date('insurance_expiry_date')->nullable();

            // ── TAB 7: CATATAN ────────────────────────────────────────────
            $table->text('notes')->nullable();

            // ── AUDIT ─────────────────────────────────────────────────────
            $table->foreignId('created_by')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};