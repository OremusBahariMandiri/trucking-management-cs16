<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA07 — Foto Kendaraan (1 kendaraan bisa banyak foto)
// Setiap foto punya kategori + keterangan bebas
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->cascadeOnDelete();

            // Kategori foto — tampil sebagai dropdown saat upload
            $table->enum('category', [
                'tampak_depan',
                'tampak_belakang',
                'tampak_samping_kiri',
                'tampak_samping_kanan',
                'interior_kabin',
                'karoseri',
                'mesin',
                'plat_nomor',
                'nomor_rangka',   // foto fisik nomor rangka
                'nomor_mesin',    // foto fisik nomor mesin
                'kerusakan',
                'dokumen',        // foto STNK, KIR, asuransi fisik
                'lainnya',
            ])->nullable()->default('lainnya');

            $table->string('caption')->nullable();            // keterangan bebas
            $table->string('file_path')->nullable();          // path storage
            $table->string('file_name')->nullable();          // nama file asli
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->unsignedTinyInteger('sort_order')->nullable()->default(0); // urutan tampil
            $table->boolean('is_primary')->default(false);   // foto utama (tampil di list/kartu)
            $table->foreignId('uploaded_by')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_photos');
    }
};