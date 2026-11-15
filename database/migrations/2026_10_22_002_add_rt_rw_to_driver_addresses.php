<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bb01_drivers', function (Blueprint $table) {
            $table->string('ktp_rt_rw', 20)->nullable()->after('ktp_postal_code');
            $table->string('domicile_rt_rw', 20)->nullable()->after('domicile_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('bb01_drivers', function (Blueprint $table) {
            $table->dropColumn(['ktp_rt_rw', 'domicile_rt_rw']);
        });
    }
};
