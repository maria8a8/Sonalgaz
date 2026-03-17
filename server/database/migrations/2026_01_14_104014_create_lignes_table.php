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
        Schema::create('lignes', function (Blueprint $table) {
            $table->id();
            $table->enum('plan_type', ['vue_plan', 'profil_long', 'point_singulier', 'carte_generale', 'schema_equipement', 'terrain']);
            $table->string('region');
            $table->string('type_reseau');
            $table->string('numero_planche');
            $table->string('nom_ligne');
            $table->string('district');
            $table->decimal('distance_gc', 10, 2);
            $table->string('echelle');
            $table->date('date_creation');
            $table->string('entreprise_realisatrice');
            $table->string('numero_contrat');
            $table->text('mots_cles');
            $table->text('observations')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes');
    }
};
