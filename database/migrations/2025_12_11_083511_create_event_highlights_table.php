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
        Schema::create('event_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('objet_statut')->default('actif'); 
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->string('media_path')->nullable(); // vidéo mp4 ou image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_highlights');
    }
};
