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
        //
    Schema::table('event_vues', function (Blueprint $table) {
    $table->dropForeign(['utilisateur_id']); 
    $table->dropColumn('utilisateur_id');    
    });

    Schema::table('event_vues', function (Blueprint $table) {
        $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
    });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('event_vues', function (Blueprint $table) {

    });
    }
};
