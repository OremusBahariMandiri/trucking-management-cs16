<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA04 — Lookup: Jenis Bahan Bakar
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa04_fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa04_fuel_types');
    }
};