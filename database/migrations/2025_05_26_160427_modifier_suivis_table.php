<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suivis', function (Blueprint $table) {
            //
        $table->foreignId('suivi_id')->constrained('utilisateurs')->onDelete('cascade');
        $table->dropColumn('organisateur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suivis', function (Blueprint $table) {
            //
        });
    }
};
