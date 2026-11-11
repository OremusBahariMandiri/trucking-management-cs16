<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── bb04_driver_emergency_contacts ─────────────────────────────────
        Schema::create('bb04_driver_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');

            // Jenis & Status
            $table->enum('contact_type', ['keluarga', 'teman', 'rekan_kerja', 'lainnya'])->default('keluarga');
            $table->enum('contact_status', ['aktif', 'tidak_aktif'])->default('aktif');

            // Identitas
            $table->string('full_name', 100);
            $table->string('nik', 16)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('religion', ['islam', 'kristen', 'katolik', 'hindu', 'budha', 'konghucu'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();

            // Kontak
            $table->string('phone_primary', 20);
            $table->string('phone_alternative', 20)->nullable();

            // Alamat
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->string('rt_rw', 20)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('full_address')->nullable();

            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('bb01_drivers')->cascadeOnDelete();
            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bb04_driver_emergency_contacts');
    }
};
