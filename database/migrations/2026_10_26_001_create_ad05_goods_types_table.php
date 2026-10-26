<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD05 — Master: Jenis Barang
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad05_goods_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik jenis barang');
            $table->string('name', 100)->comment('Nama jenis barang');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad05_goods_types');
    }
};
