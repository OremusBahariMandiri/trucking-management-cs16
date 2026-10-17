<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            $table->unsignedInteger('initial_odometer_km')->nullable()->after('vehicle_status');
            $table->date('initial_odometer_date')->nullable()->after('initial_odometer_km');
            $table->date('last_odometer_date')->nullable()->after('last_odometer_km');
        });
    }

    public function down(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'initial_odometer_km',
                'initial_odometer_date',
                'last_odometer_date',
            ]);
        });
    }
};