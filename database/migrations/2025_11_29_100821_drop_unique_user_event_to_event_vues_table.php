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
        Schema::table('event_vues', function (Blueprint $table) {
            //
             
            // Nouvelle contrainte unique utilisateur + event
            $table->dropUnique('unique_user_event');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_vues', function (Blueprint $table) {
            //
            $table->unique(['utilisateur_id', 'event_id']);
        });
    }
};
