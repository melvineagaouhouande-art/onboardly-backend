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
        Schema::create('stagiaire_quetes', function (Blueprint $table) {
    $table->id(); 
    // Ici, le nom 'stagiaire_id' précise clairement l'ID du stagiaire lié
    $table->foreignId('stagiaire_id')->constrained('stagiaires')->onDelete('cascade'); 
    $table->foreignId('quete_id')->constrained('quetes')->onDelete('cascade');
    $table->enum('statut', ['en_attente', 'en_cours', 'termine', 'alerte'])->default('en_attente');
    $table->timestamp('termine_le')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaire_quetes');
    }
};
