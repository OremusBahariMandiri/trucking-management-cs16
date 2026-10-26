<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bd01_02_order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('bd01_01_orders')->cascadeOnDelete();
            $table->unsignedBigInteger('goods_type_id')->nullable();
            $table->string('goods_type_name', 100)->nullable();
            $table->unsignedBigInteger('goods_category_id')->nullable();
            $table->string('goods_category_name', 100)->nullable();
            $table->unsignedBigInteger('packaging_type_id')->nullable();
            $table->string('packaging_type_name', 100)->nullable();
            $table->text('loading_address')->nullable();
            $table->text('unloading_address')->nullable();
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->string('vehicle_type_name', 100)->nullable();
            $table->decimal('vehicle_capacity', 10, 2)->nullable();
            $table->integer('vehicle_quantity')->default(1);
            $table->decimal('total_volume', 10, 2)->nullable();
            $table->string('measurement_unit', 30)->default('Ton');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bd01_02_order_shipments');
    }
};
