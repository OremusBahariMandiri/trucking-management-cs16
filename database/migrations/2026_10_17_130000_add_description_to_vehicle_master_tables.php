<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // vehicle-brand
        Schema::table('aa02_vehicle_brands', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });

        // vehicle-model
        Schema::table('aa03_vehicle_models', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });

        // fuel-type
        Schema::table('aa04_fuel_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });

        // body-type
        Schema::table('aa05_body_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('aa02_vehicle_brands', fn($t) => $t->dropColumn('description'));
        Schema::table('aa03_vehicle_models', fn($t) => $t->dropColumn('description'));
        Schema::table('aa04_fuel_types',     fn($t) => $t->dropColumn('description'));
        Schema::table('aa05_body_types',     fn($t) => $t->dropColumn('description'));
    }
};
