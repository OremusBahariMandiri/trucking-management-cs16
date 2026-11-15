<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD04 — Master: Penanggung Jawab (PIC) Pelanggan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad04_person_in_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('ad03_customers')->cascadeOnDelete();
            $table->string('name', 150)->comment('Nama Penanggung Jawab');
            $table->string('position', 100)->nullable()->comment('Jabatan');
            $table->string('department', 100)->nullable()->comment('Departemen');
            $table->string('phone', 30)->nullable()->comment('Nomor Telepon PIC');
            $table->string('email', 150)->nullable()->comment('Email PIC');
            $table->boolean('is_primary')->default(false)->comment('PIC Utama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad04_person_in_charges');
    }
};
