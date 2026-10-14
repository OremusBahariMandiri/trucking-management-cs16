<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA07 — Foto Kendaraan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa07_vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('aa06_vehicles')->cascadeOnDelete();
            $table->enum('category', [
                'tampak_depan','tampak_belakang','tampak_samping_kiri','tampak_samping_kanan',
                'interior_kabin','karoseri','mesin','plat_nomor','nomor_rangka',
                'nomor_mesin','kerusakan','dokumen','lainnya',
            ])->nullable()->default('lainnya');
            $table->string('caption')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->unsignedTinyInteger('sort_order')->nullable()->default(0);
            $table->boolean('is_primary')->default(false);
            $table->foreignId('uploaded_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa07_vehicle_photos');
    }
};