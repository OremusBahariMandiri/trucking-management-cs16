<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable()->constrained()->nullOnDelete()
                ->after('vehicle_brand_id');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\MasterData\Vehicle\VehicleType::class);
            $table->dropColumn('vehicle_type_id');
        });
    }
};
