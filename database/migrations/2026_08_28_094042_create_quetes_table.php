<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TABLE 4 : quetes (quests)
     * Liste des quêtes/objectifs créés par la RH au sein de chaque parcours.
     * Chaque quête rapporte des points et peut débloquer un badge.
     */
    public function up(): void
    {
        Schema::create('quetes', function (Blueprint $table) {
            $table->id(); // Identifiant unique de la quête

            // Lien vers le parcours parent
            $table->foreignId('parcours_id')->constrained('parcours')->onDelete('cascade');

            $table->string('titre', 150); // Nom de la quête (ex: "Rencontrer mon manager")
            $table->text('description')->nullable(); // Description détaillée de la quête

            // Étape / période cible de la quête
            // Indique quand la quête doit être réalisée
            $table->string('etape', 50)->default('Semaine 1'); // Ex: 'Jour 1', 'Semaine 1', 'Mois 1'

            $table->integer('points')->default(10); // Points gagnés à la validation (ex: 50, 100)
            $table->integer('ordre')->default(1); // Ordre d'affichage dans le parcours

            // Type de validation de la quête :
            // 'auto'    = validée automatiquement (ex: lecture d'un document)
            // 'manager' = doit être validée par le manager
            // 'quiz'    = validée après réussite d'un quiz
            $table->enum('type_validation', ['auto', 'manager', 'quiz'])->default('auto');

            // Badge optionnel débloqué à la fin de cette quête
            // (la table 'badges' est créée après, donc pas de contrainte FK ici)
            $table->unsignedBigInteger('badge_id')->nullable();

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('quetes');
    }
};
