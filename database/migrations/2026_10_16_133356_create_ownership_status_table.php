<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aa10_ownership_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();       // e.g. "Company Owned"
            $table->string('description', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aa10_ownership_statuses');
    }
};
