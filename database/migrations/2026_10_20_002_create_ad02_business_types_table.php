<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD02 — Master: Jenis Usaha
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad02_business_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik jenis usaha');
            $table->string('name', 100)->comment('Nama jenis usaha');
            $table->text('description')->nullable()->comment('Deskripsi jenis usaha');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad02_business_types');
    }
};
