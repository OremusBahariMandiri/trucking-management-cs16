<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            $table->decimal('gps_last_lat', 10, 7)->nullable()->after('gps_status');
            $table->decimal('gps_last_lng', 10, 7)->nullable()->after('gps_last_lat');
            $table->unsignedSmallInteger('gps_last_speed')->nullable()->after('gps_last_lng');
            $table->tinyInteger('gps_last_acc')->nullable()->comment('0=off,1=on')->after('gps_last_speed');
            $table->timestamp('gps_last_seen')->nullable()->after('gps_last_acc');
            $table->text('gps_last_address')->nullable()->after('gps_last_seen');
        });
    }

    public function down(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'gps_last_lat', 'gps_last_lng', 'gps_last_speed',
                'gps_last_acc', 'gps_last_seen', 'gps_last_address',
            ]);
        });
    }
};
