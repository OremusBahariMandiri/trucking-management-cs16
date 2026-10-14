<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA02 — Lookup: Merek Kendaraan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa02_vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('country_of_origin', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa02_vehicle_brands');
    }
};