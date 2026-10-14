<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AA08 — Dokumen Kendaraan (scan/file dokumen fisik)
// Tabel terpisah agar setiap dokumen bisa disimpan multi-versi (perpanjangan STNK, KIR, dll.)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->cascadeOnDelete();

            $table->enum('document_type', [
                'stnk',      // Surat Tanda Nomor Kendaraan
                'kir',       // Kartu Uji Berkala
                'bpkb',      // Buku Pemilik Kendaraan Bermotor
                'asuransi',  // Polis Asuransi
                'faktur',    // Faktur pembelian
                'sio',       // Surat Izin Operasi (kendaraan angkutan)
                'lainnya',
            ])->nullable();

            $table->string('document_number', 50)->nullable(); // Nomor dokumen
            $table->string('issued_by')->nullable();           // Penerbit (Samsat, Dishub, dll.)
            $table->date('issue_date')->nullable();            // Tanggal terbit
            $table->date('expiry_date')->nullable();           // Tanggal kadaluarsa
            $table->string('file_path')->nullable();           // path scan/file dokumen
            $table->string('file_name')->nullable();
            $table->unsignedInteger('file_size_kb')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_documents');
    }
};