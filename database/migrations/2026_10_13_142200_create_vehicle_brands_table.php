<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA02 — Lookup: Merek Kendaraan
// Contoh: Hino, Mitsubishi Fuso, Isuzu, Volvo, Scania, Mercedes-Benz
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('country_of_origin', 5)->nullable(); // JP, SE, DE, KR
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_brands');
    }
};