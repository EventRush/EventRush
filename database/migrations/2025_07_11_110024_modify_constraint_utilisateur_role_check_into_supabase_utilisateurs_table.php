<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        // DB::statement("ALTER TABLE utilisateurs DROP CONSTRAINT utilisateurs_role_check");
        // DB::statement("ALTER TABLE utilisateurs ADD CONSTRAINT utilisateurs_role_check CHECK (role IN ('client', 'organisateur', 'scanneur', 'admin'))");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
