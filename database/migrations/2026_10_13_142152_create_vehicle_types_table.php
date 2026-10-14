<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA01 — Lookup: Jenis Kendaraan
// Contoh: Dump Truck, Tronton, Trailer, Wingbox, Tangki, Container
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable()->unique(); // DUMP, TRON, TRL, WB, TGK, CNT
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};