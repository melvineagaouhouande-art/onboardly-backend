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
        Schema::create('stagiaire_badges', function (Blueprint $table) {
    $table->id(); // Identifiant unique du badge débloqué (id)
    $table->foreignId('stagiaire_id')->constrained('stagiaires')->onDelete('cascade'); // Lié à l'id de la table stagiaires
    $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade'); // Lié à l'id de la table badges
    $table->timestamp('debloque_le')->useCurrent();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaire_badges');
    }
};
