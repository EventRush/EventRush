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
            // DB::statement("ALTER TABLE utilisateurs MODIFY COLUMN role ENUM('client', 'organisateur', 'scanneur', 'admin') NOT NULL");
       
            Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('role')->default('client')->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            //
             // Optionnel : revenir à l’ancienne version
            // DB::statement("ALTER TABLE tickets MODIFY COLUMN type ENUM('vip', 'normal') NOT NULL");

        });
    }
};
