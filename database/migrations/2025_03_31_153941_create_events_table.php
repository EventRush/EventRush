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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('organisateur_id')->constrained('organisateur_profiles')->onDelete('cascade')->nullable();
            $table->string('titre');
            $table->text('description');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('lieu');
            $table->enum('statut', ['brouillon', 'publié', 'annulé'])->default('brouillon');
            $table->string('affiche')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
