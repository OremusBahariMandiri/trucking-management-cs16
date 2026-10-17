<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── bb01_drivers ───────────────────────────────────────────────────
        Schema::create('bb01_drivers', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('driver_code', 20)->unique();
            $table->string('full_name', 100);
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->unsignedTinyInteger('children_count')->default(0);
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->date('join_date')->nullable();
            $table->string('photo_path', 255)->nullable();

            // Data KTP
            $table->string('nik', 16)->unique()->nullable();
            $table->string('ktp_file_path', 255)->nullable();

            // Alamat KTP
            $table->text('ktp_address')->nullable();
            $table->string('ktp_village', 100)->nullable();
            $table->string('ktp_district', 100)->nullable();
            $table->string('ktp_city', 100)->nullable();
            $table->string('ktp_province', 100)->nullable();
            $table->string('ktp_postal_code', 10)->nullable();

            // Alamat Domisili
            $table->boolean('domicile_same_as_ktp')->default(false);
            $table->text('domicile_address')->nullable();
            $table->string('domicile_village', 100)->nullable();
            $table->string('domicile_district', 100)->nullable();
            $table->string('domicile_city', 100)->nullable();
            $table->string('domicile_province', 100)->nullable();
            $table->string('domicile_postal_code', 10)->nullable();

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── bb02_driver_documents ──────────────────────────────────────────
        Schema::create('bb02_driver_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('document_name', 100);
            $table->string('document_number', 50)->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('bb01_drivers')->cascadeOnDelete();
            $table->index(['driver_id', 'expiry_date']);
        });

        // ── bb03_driver_certificates ───────────────────────────────────────
        Schema::create('bb03_driver_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('certificate_name', 100);
            $table->string('certificate_number', 50)->nullable();
            $table->string('issuer', 100)->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('bb01_drivers')->cascadeOnDelete();
            $table->index(['driver_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bb03_driver_certificates');
        Schema::dropIfExists('bb02_driver_documents');
        Schema::dropIfExists('bb01_drivers');
    }
};