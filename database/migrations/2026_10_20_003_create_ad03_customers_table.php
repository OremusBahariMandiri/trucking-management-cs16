<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AD03 — Master: Data Pelanggan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad03_customers', function (Blueprint $table) {
            $table->id();
            $table->date('registration_date')->comment('Tanggal Registrasi Pelanggan');
            $table->string('customer_code', 30)->unique()->comment('Nomor identitas unik pelanggan');
            $table->string('name', 150)->comment('Nama perusahaan atau perorangan');
            $table->foreignId('customer_type_id')->nullable()->constrained('ad01_customer_types')->nullOnDelete();
            $table->foreignId('business_type_id')->nullable()->constrained('ad02_business_types')->nullOnDelete();

            // Dokumen Legal
            $table->string('npwp_number', 30)->nullable()->comment('Nomor NPWP');
            $table->string('npwp_file_path')->nullable()->comment('File NPWP');
            $table->string('npwp_file_name')->nullable();
            $table->string('nib_number', 30)->nullable()->comment('Nomor Induk Berusaha');
            $table->string('nib_file_path')->nullable()->comment('File NIB');
            $table->string('nib_file_name')->nullable();

            // Alamat
            $table->text('address')->nullable()->comment('Alamat Perusahaan');
            $table->string('rt_rw', 10)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('city', 100)->nullable()->comment('Kota atau Kabupaten');
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();

            // Kontak
            $table->string('phone', 30)->nullable()->comment('Nomor Telepon Perusahaan');
            $table->string('email', 150)->nullable()->comment('Alamat Email Perusahaan');

            $table->boolean('is_active')->default(true)->comment('Status Pelanggan');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad03_customers');
    }
};
