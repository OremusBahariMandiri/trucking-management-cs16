<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bd01_03_order_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('bd01_01_orders')->cascadeOnDelete();
            $table->string('cost_description', 200)->nullable();
            $table->decimal('cost_qty', 10, 2)->default(0);
            $table->decimal('cost_unit_price', 15, 2)->default(0);
            $table->decimal('cost_subtotal', 15, 2)->default(0);
            $table->decimal('ppn_percent', 5, 2)->default(0);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bd01_03_order_costs');
    }
};
