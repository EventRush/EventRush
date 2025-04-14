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
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisateur_id')->constrained('organisateur_profiles')->onDelete('cascade');
            $table->enum('type', ['gratuit', 'standard', 'premium'])->default('gratuit');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['actif', 'expiré', 'annulé'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};
