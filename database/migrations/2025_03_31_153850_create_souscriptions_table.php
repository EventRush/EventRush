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
            $table->foreignId('utilisateur_id')->Constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('plans_souscription_id')->constrained('plans_souscription')->onDelete('cascade');
            // $table->enum('type', ['gratuit', 'standard', 'premium'])->default('gratuit');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['actif', 'expiré', 'annulé'])->default('actif');
            $table->enum('methode', ['carte', 'PayPal', 'mobile_money'])->default('mobile_money');
            $table->enum('statut_paiement', ['en_attente', 'success', 'echoue'])->default('en_attente');  // Statut du paiement
            $table->decimal('montant', 10, 2);
            $table->string('reference')->unique();  // Référence unique de la transaction MoMo
            $table->unsignedBigInteger('souscription_fedapay_id')->nullable();
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
