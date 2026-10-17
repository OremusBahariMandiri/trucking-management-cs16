<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── bc01_employees ─────────────────────────────────────────────────
        Schema::create('bc01_employees', function (Blueprint $table) {
            $table->id();

            $table->string('nrk', 20)->unique();
            $table->string('full_name', 100);
            $table->string('department', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('wilker', 100)->nullable();

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bc01_employees');
    }
};