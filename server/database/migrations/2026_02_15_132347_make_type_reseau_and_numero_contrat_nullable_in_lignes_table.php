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
        Schema::table('lignes', function (Blueprint $table) {
            $table->string('type_reseau')->nullable()->change();
            $table->string('numero_contrat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lignes', function (Blueprint $table) {
            $table->string('type_reseau')->nullable(false)->change();
            $table->string('numero_contrat')->nullable(false)->change();
        });
    }
};
