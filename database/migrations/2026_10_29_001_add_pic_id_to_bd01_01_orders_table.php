<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bd01_01_orders', function (Blueprint $table) {
            $table->foreignId('pic_id')
                  ->nullable()
                  ->after('customer_address')
                  ->constrained('ad04_person_in_charges')
                  ->nullOnDelete()
                  ->comment('PIC terpilih dari tabel person_in_charges');
        });
    }

    public function down(): void
    {
        Schema::table('bd01_01_orders', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->dropColumn('pic_id');
        });
    }
};
