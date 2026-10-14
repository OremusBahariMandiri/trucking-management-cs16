<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA03 — Lookup: Model Kendaraan (relasi ke brand)
// Contoh: Hino → Ranger, 500FT | Isuzu → ELF NMR, Giga
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->smallInteger('year_start')->nullable();
            $table->smallInteger('year_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
    }
};