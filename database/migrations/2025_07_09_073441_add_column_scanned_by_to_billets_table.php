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
        Schema::table('billets', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('scanned_by')->nullable()->after('scanned_at');
            $table->foreign('scanned_by')->references('id')->on('utilisateurs')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            //
        });
    }
};
