<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD06 — Master: Kategori Barang
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad06_goods_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik kategori barang');
            $table->string('name', 100)->comment('Nama kategori barang');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad06_goods_categories');
    }
};
