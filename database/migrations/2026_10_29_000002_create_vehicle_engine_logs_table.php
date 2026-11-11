<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa06_vehicle_engine_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->nullable()->index();
            $table->string('gps_tracker_id', 50)->index();
            $table->enum('event', ['on', 'off']);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->tinyInteger('speed')->unsigned()->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['gps_tracker_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa06_vehicle_engine_logs');
    }
};
