<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD01 — Master: Jenis Pelanggan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad01_customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik jenis pelanggan');
            $table->string('name', 100)->comment('Nama jenis pelanggan');
            $table->text('description')->nullable()->comment('Deskripsi jenis pelanggan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad01_customer_types');
    }
};
