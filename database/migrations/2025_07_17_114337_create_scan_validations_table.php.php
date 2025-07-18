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
        Schema::create('billet_scans_en_attente', function (Blueprint $table) {
    $table->id();
    $table->foreignId('billet_id')->constrained()->onDelete('cascade');
    $table->foreignId('scanneur_id')->constrained('utilisateurs')->onDelete('cascade');
    $table->timestamp('initiated_at');
    $table->string('token')->unique(); 
    $table->enum('status', ['en_attente', 'validé', 'rejeté'])->default('en_attente');
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
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
