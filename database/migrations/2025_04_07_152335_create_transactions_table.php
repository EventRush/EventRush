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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('souscription_id')->nullable()->constrained()->onDelete('cascade');  // Pour lier la transaction à une souscription
            $table->string('reference')->unique();  // Référence unique de la transaction MoMo
            $table->enum('methode', ['carte', 'PayPal', 'mobile_money'])->default('mobile_money');
            $table->enum('status', ['en_attente', 'réussi', 'échoué'])->default('en_attente');  // Statut du paiement
            $table->decimal('montant', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
