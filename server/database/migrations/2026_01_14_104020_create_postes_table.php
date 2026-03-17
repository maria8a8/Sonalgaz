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
        Schema::create('postes', function (Blueprint $table) {
            $table->id();
            $table->enum('categorie', ['genie_civil', 'isometrique', 'soudure', 'tuyauterie', 'protection']);
            $table->string('code_poste');
            $table->string('localisation');
            $table->date('date_realisation');
            $table->string('entreprise');
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
        Schema::dropIfExists('postes');
    }
};
