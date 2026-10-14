<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA05 — Lookup: Jenis Karoseri
// Contoh: Dump, Bak Terbuka, Tangki, Flatbed, Wingbox, Reefer, Kontainer
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable()->unique(); // DUMP, BAK, TGK, FLT, WB, REEF, CNT
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_types');
    }
};