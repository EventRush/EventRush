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
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('utilisateur_id')->constrained();
            $table->foreignId('ticket_id')->constrained();
            $table->enum('methode', ['carte', 'PayPal', 'mobile_money'])->default('mobile_money');
            $table->enum('status', ['en_attente', 'paye', 'echoue'])->default('en_attente');  // Statut du paiement
            $table->decimal('montant', 10, 2);
            $table->uuid('qr_code')->unique()->nullable();
            $table->string('reference')->unique();  // Référence unique de la transaction MoMo
            // $table->unsignedBigInteger('billet_fedapay_id')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};
