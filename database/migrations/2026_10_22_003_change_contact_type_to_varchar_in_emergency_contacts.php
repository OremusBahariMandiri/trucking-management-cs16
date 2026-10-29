<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL tidak support langsung ALTER COLUMN dari ENUM ke VARCHAR,
        // jadi gunakan raw statement
        DB::statement("ALTER TABLE bb04_driver_emergency_contacts
            MODIFY COLUMN contact_type VARCHAR(50) NOT NULL DEFAULT 'keluarga'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bb04_driver_emergency_contacts
            MODIFY COLUMN contact_type ENUM('keluarga','teman','rekan_kerja','lainnya') NOT NULL DEFAULT 'keluarga'");
    }
};
