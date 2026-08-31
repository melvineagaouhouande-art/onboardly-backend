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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            // Lien vers la quête correspondante dans quests (ici la table s'appelle 'quetes')
            $table->foreignId('quest_id')->constrained('quetes')->onDelete('cascade');
            $table->string('titre', 150);
            $table->integer('seuil_reussite')->default(80); // Pourcentage minimum de réussite (ex: 80)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
