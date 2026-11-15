<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            // DECIMAL(20,2) → bisa tampung hingga 999 kuadriliun
            $table->decimal('acquisition_value', 20, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('aa06_vehicles', function (Blueprint $table) {
            $table->decimal('acquisition_value', 15, 2)->nullable()->change();
        });
    }
};
