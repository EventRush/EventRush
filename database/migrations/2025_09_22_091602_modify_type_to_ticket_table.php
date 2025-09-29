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
        Schema::table('tickets', function (Blueprint $table) {
            DB::statement('ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_type_check');
            $table->string('type')->default('standart')->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            //
        });
    }
};
