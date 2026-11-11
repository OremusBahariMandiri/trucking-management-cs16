<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// BD01_01 — Pesanan / Purchase Order
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bd01_01_orders', function (Blueprint $table) {
            $table->id();

            // A. Informasi Pelanggan
            $table->string('po_number', 50)->unique()->comment('Nomor PO');
            $table->date('po_date')->comment('Tanggal PO');
            $table->foreignId('customer_id')->nullable()->constrained('ad03_customers')->nullOnDelete();
            $table->string('customer_name', 150)->nullable()->comment('Nama pelanggan (snapshot)');
            $table->text('customer_address')->nullable()->comment('Alamat pelanggan (snapshot)');
            $table->string('pic_name', 150)->nullable()->comment('PIC Pemesan');
            $table->string('pic_phone', 30)->nullable()->comment('Nomor Telepon PIC');

            // B. Pemesanan Angkutan
            $table->foreignId('goods_type_id')->nullable()->constrained('ad05_goods_types')->nullOnDelete();
            $table->string('goods_type_name', 100)->nullable()->comment('Jenis barang (snapshot)');
            $table->foreignId('goods_category_id')->nullable()->constrained('ad06_goods_categories')->nullOnDelete();
            $table->string('goods_category_name', 100)->nullable()->comment('Kategori barang (snapshot)');
            $table->foreignId('packaging_type_id')->nullable()->constrained('ad07_packaging_types')->nullOnDelete();
            $table->string('packaging_type_name', 100)->nullable()->comment('Jenis kemasan (snapshot)');
            $table->text('loading_address')->nullable()->comment('Alamat Muat');
            $table->text('unloading_address')->nullable()->comment('Alamat Bongkar');
            $table->date('loading_date')->nullable()->comment('Tanggal Muat');
            $table->date('unloading_date')->nullable()->comment('Tanggal Bongkar');
            $table->string('vehicle_type', 100)->nullable()->comment('Jenis Kendaraan');
            $table->decimal('vehicle_capacity', 10, 2)->nullable()->comment('Kapasitas Kendaraan (ton)');
            $table->integer('vehicle_quantity')->nullable()->comment('Jumlah Kendaraan');
            $table->decimal('total_volume', 12, 2)->nullable()->comment('Total Volume Angkut');
            $table->string('measurement_unit', 30)->nullable()->comment('Satuan Pengukuran');

            // C. Rincian Biaya
            $table->string('cost_description', 200)->nullable()->comment('Uraian biaya');
            $table->decimal('cost_qty', 12, 2)->nullable()->comment('Qty');
            $table->decimal('cost_unit_price', 15, 2)->nullable()->comment('Harga Satuan');
            $table->decimal('cost_subtotal', 15, 2)->nullable()->comment('Sub Total');
            $table->decimal('ppn_percent', 5, 2)->default(0)->comment('PPN %');
            $table->decimal('ppn_amount', 15, 2)->default(0)->comment('PPN Rp');
            $table->decimal('total_amount', 15, 2)->nullable()->comment('Total');
            $table->string('payment_method', 100)->nullable()->comment('Metode Pembayaran');
            $table->date('payment_due_date')->nullable()->comment('Tanggal Jatuh Tempo');

            // D. Catatan
            $table->text('special_notes')->nullable()->comment('Catatan dan Permintaan Khusus');

            // E. Persetujuan
            $table->date('submission_date')->nullable()->comment('Tanggal Pengajuan');
            $table->enum('approval_status', ['pending','approved','rejected'])->default('pending');
            $table->text('approval_notes')->nullable()->comment('Catatan Persetujuan');
            $table->date('approval_date')->nullable()->comment('Tanggal Persetujuan');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bd01_01_orders');
    }
};
