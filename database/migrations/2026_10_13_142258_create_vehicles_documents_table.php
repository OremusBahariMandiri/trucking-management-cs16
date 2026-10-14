<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA08 — Dokumen Kendaraan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa08_vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('aa06_vehicles')->cascadeOnDelete();
            $table->enum('document_type', [
                'stnk','kir','bpkb','asuransi','faktur','sio','lainnya',
            ])->nullable();
            $table->string('document_number', 50)->nullable();
            $table->string('issued_by')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa08_vehicle_documents');
    }
};