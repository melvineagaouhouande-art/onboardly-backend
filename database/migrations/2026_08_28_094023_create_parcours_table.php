<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TABLE 3 : parcours
     * Programmes d'intégration spécifiques aux départements.
     * Exemples : "Parcours Développeur Web", "Parcours Commercial"
     */
    public function up(): void
    {
        Schema::create('parcours', function (Blueprint $table) {
            $table->id(); // Identifiant unique du parcours
            $table->string('titre', 150); // Intitulé du parcours (ex: Développeur Web)
            $table->text('description')->nullable(); // Description détaillée des objectifs
            $table->string('icone')->nullable(); // Icône représentative du parcours

            // Lien vers le département concerné
            // Un parcours appartient à un département (ex: parcours "Dev Web" → département "IT / Tech")
            $table->foreignId('departement_id')->nullable()->constrained('departements')->onDelete('set null');

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcours');
    }
};
