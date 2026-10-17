<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menambahkan kolom `code` ke tabel master kendaraan yang belum memilikinya:
// aa02_vehicle_brands, aa03_vehicle_models, aa04_fuel_types,
// aa09_vehicle_axis_configurations, aa10_ownership_statuses
return new class extends Migration
{
    public function up(): void
    {
        // AA02 — Merek Kendaraan
        Schema::table('aa02_vehicle_brands', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('id');
        });

        // AA03 — Model Kendaraan
        Schema::table('aa03_vehicle_models', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('id');
        });

        // AA04 — Jenis Bahan Bakar
        Schema::table('aa04_fuel_types', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('id');
        });

        // AA09 — Konfigurasi Sumbu
        Schema::table('aa09_vehicle_axis_configurations', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('id');
        });

        // AA10 — Status Kepemilikan
        Schema::table('aa10_ownership_statuses', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('aa02_vehicle_brands', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        Schema::table('aa03_vehicle_models', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        Schema::table('aa04_fuel_types', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        Schema::table('aa09_vehicle_axis_configurations', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        Schema::table('aa10_ownership_statuses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
