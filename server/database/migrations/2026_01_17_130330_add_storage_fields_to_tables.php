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
        Schema::table('courriers', function (Blueprint $table) {
            $table->string('numero_boite')->nullable()->after('description');
            $table->string('rayonnage')->nullable()->after('numero_boite');
        });

        Schema::table('lignes', function (Blueprint $table) {
            $table->string('numero_boite')->nullable()->after('observations');
            $table->string('rayonnage')->nullable()->after('numero_boite');
        });

        Schema::table('postes', function (Blueprint $table) {
            $table->string('numero_boite')->nullable()->after('observations');
            $table->string('rayonnage')->nullable()->after('numero_boite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courriers', function (Blueprint $table) {
            $table->dropColumn(['numero_boite', 'rayonnage']);
        });

        Schema::table('lignes', function (Blueprint $table) {
            $table->dropColumn(['numero_boite', 'rayonnage']);
        });

        Schema::table('postes', function (Blueprint $table) {
            $table->dropColumn(['numero_boite', 'rayonnage']);
        });
    }
};
