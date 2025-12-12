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
        Schema::table('commentaires', function (Blueprint $table) {

            // 1️⃣ Ajouter les colonnes morph
            $table->unsignedBigInteger('commentable_id')->nullable()->after('id');
            $table->string('commentable_type')->nullable()->after('commentable_id');

        });

        // 2️⃣ Migrer les anciennes données
        DB::table('commentaires')->update([
            'commentable_id' => DB::raw('event_id'),
            'commentable_type' => 'App\\Models\\Event'
        ]);

        // 3️⃣ Supprimer l'ancienne colonne event_id
        Schema::table('commentaires', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
