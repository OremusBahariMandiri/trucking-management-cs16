<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD07 — Master: Jenis Kemasan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad07_packaging_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik jenis kemasan');
            $table->string('name', 100)->comment('Nama jenis kemasan');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad07_packaging_types');
    }
};
