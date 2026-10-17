<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa09_vehicle_axis_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();        // e.g. "4x2", "6x4"   
            $table->string('description', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa09_vehicle_axis_configurations');
    }
};
