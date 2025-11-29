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
            // Rendre utilisateur_id nullable pour permettre les vues anonymes
            $table->dropForeign(['utilisateur_id']);
            // $table->dropUnique(['utilisateur_id', 'event_id']);

            $table->foreignId('utilisateur_id')
                ->nullable()
                ->change();

            // Nouvelle colonne adresse_ip
            $table->string('adresse_ip')->nullable()->after('utilisateur_id');

            // Nouvelle contrainte unique IP + event
            $table->unique(['adresse_ip', 'event_id'], 'unique_ip_event');

            // Nouvelle contrainte unique utilisateur + event
            $table->unique(['utilisateur_id', 'event_id'], 'unique_user_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_vues', function (Blueprint $table) {
            //
             // Suppression des nouvelles contraintes
            $table->dropUnique('unique_ip_event');
            $table->dropUnique('unique_user_event');

            // Suppression de la colonne IP
            $table->dropColumn('adresse_ip');

            // Restaurer l’ancien état
            $table->dropForeign(['utilisateur_id']);

            $table->foreignId('utilisateur_id')
                ->nullable(false)
                ->change();

            $table->unique(['utilisateur_id', 'event_id']);
        });
    }
};
