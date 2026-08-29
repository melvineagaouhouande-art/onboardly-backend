<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TABLE 5 : stagiaire_quetes (table pivot)
     * Suivi d'avancement des quêtes par chaque stagiaire/employé.
     * Chaque ligne = une quête assignée à un stagiaire avec son statut.
     */
    public function up(): void
    {
        Schema::create('stagiaire_quetes', function (Blueprint $table) {
            $table->id(); // Identifiant unique de l'assignation

            // Liens vers le stagiaire et la quête
            $table->foreignId('stagiaire_id')->constrained('stagiaires')->onDelete('cascade');
            $table->foreignId('quete_id')->constrained('quetes')->onDelete('cascade');

            // Statut de progression de la quête :
            // 'bloquee'              = pas encore accessible (quête précédente non terminée)
            // 'en_cours'             = le stagiaire travaille dessus
            // 'en_attente_validation' = soumise, en attente de validation manager
            // 'validee'              = terminée et validée
            // 'en_retard'            = dépassement de la date limite
            $table->enum('statut', [
                'bloquee',
                'en_cours',
                'en_attente_validation',
                'validee',
                'en_retard'
            ])->default('bloquee');

            // Date limite pour réaliser la quête (gestion des retards)
            $table->datetime('date_limite')->nullable();
            // Date et heure de complétion effective
            $table->datetime('termine_le')->nullable();

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaire_quetes');
    }
};
