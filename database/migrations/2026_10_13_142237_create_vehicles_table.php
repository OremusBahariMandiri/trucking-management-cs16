<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA06 — Tabel Utama: Kendaraan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa06_vehicles', function (Blueprint $table) {
            $table->id();

            // TAB 1: INFORMASI UMUM
            $table->string('vehicle_code', 20)->nullable()->unique();
            $table->string('police_number', 20)->nullable()->unique();
            $table->string('hull_number', 30)->nullable()->unique();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('aa01_vehicle_types')->nullOnDelete();
            $table->foreignId('vehicle_brand_id')->nullable()->constrained('aa02_vehicle_brands')->nullOnDelete();
            $table->foreignId('vehicle_model_id')->nullable()->constrained('aa03_vehicle_models')->nullOnDelete();
            $table->string('model_name')->nullable();
            $table->smallInteger('manufacture_year')->nullable();
            $table->string('color', 50)->nullable();
            $table->enum('vehicle_status', ['active','inactive','service','broken','sold'])->nullable()->default('active');

            // TAB 2: SPESIFIKASI TEKNIS
            $table->string('chassis_number', 50)->nullable()->unique();
            $table->string('engine_number', 50)->nullable()->unique();
            $table->foreignId('fuel_type_id')->nullable()->constrained('aa04_fuel_types')->nullOnDelete();
            $table->unsignedInteger('engine_capacity_cc')->nullable();
            $table->enum('axis_configuration', ['4x2','6x2','6x4','8x4','10x4','other'])->nullable();
            $table->unsignedTinyInteger('wheel_count')->nullable();
            $table->decimal('load_capacity_ton', 8, 2)->nullable();
            $table->decimal('gross_vehicle_weight_ton', 8, 2)->nullable();
            $table->decimal('tare_weight_ton', 8, 2)->nullable();
            $table->foreignId('body_type_id')->nullable()->constrained('aa05_body_types')->nullOnDelete();
            $table->string('body_dimension', 50)->nullable();

            // TAB 3: KEPEMILIKAN & PENUGASAN
            $table->enum('ownership_status', ['company','rental','leasing','kerjasama'])->nullable();
            $table->smallInteger('acquisition_year')->nullable();
            $table->decimal('acquisition_value', 15, 2)->nullable();
            $table->string('leasing_company')->nullable();
            $table->date('leasing_end_date')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreignId('driver_id')->nullable()->references('id')->on('users')->nullOnDelete();

            // TAB 4: OPERASIONAL
            $table->unsignedInteger('last_odometer_km')->nullable()->default(0);
            $table->date('last_service_date')->nullable();
            $table->unsignedInteger('service_interval_km')->nullable();
            $table->string('gps_tracker_id')->nullable();
            $table->string('gps_provider')->nullable();
            $table->enum('gps_status', ['active','offline','not_installed'])->nullable()->default('not_installed');

            // TAB 5: DOKUMEN & LEGALITAS
            $table->string('stnk_number', 30)->nullable();
            $table->date('tax_expiry_date')->nullable();
            $table->string('kir_number', 30)->nullable();
            $table->date('kir_expiry_date')->nullable();
            $table->string('bpkb_number', 30)->nullable();
            $table->string('insurance_policy_number', 50)->nullable();
            $table->string('insurance_company')->nullable();
            $table->date('insurance_expiry_date')->nullable();

            // TAB 7: CATATAN
            $table->text('notes')->nullable();

            // AUDIT
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa06_vehicles');
    }
};