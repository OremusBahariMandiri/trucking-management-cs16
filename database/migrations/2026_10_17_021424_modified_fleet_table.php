<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus nilai yang tidak valid sebelum mengubah tipe kolom
        // (jaga-jaga kalau ada nilai lama berupa string/enum yang tidak bisa di-cast)
        DB::statement("UPDATE aa06_vehicles SET axis_configuration = NULL WHERE axis_configuration NOT REGEXP '^[0-9]+$'");
        DB::statement("UPDATE aa06_vehicles SET ownership_status = NULL WHERE ownership_status NOT REGEXP '^[0-9]+$'");

        Schema::table('aa06_vehicles', function (Blueprint $table) {
            // Ubah tipe kolom ke unsignedBigInteger (nullable agar data lama aman)
            $table->unsignedBigInteger('axis_configuration')->nullable()->change();
            $table->unsignedBigInteger('ownership_status')->nullable()->change();

            // Tambahkan foreign key constraint
            $table->foreign('axis_configuration')
                  ->references('id')
                  ->on('aa09_vehicle_axis_configurations')
                  ->nullOnDelete();

            $table->foreign('ownership_status')
                  ->references('id')
                  ->on('aa10_ownership_statuses')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            // Lepas foreign key dulu sebelum revert tipe kolom
            $table->dropForeign(['axis_configuration']);
            $table->dropForeign(['ownership_status']);

            // Kembalikan ke string (sesuaikan dengan tipe asal di DB kamu)
            $table->string('axis_configuration')->nullable()->change();
            $table->string('ownership_status')->nullable()->change();
        });
    }
};