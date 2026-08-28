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
       Schema::create('quetes', function (Blueprint $table) {
    $table->id(); // Identifiant unique de la quête (id)
    $table->foreignId('parcours_id')->constrained('parcours')->onDelete('cascade'); // Lié à l'id de la table parcours
    $table->string('titre');
    $table->text('description')->nullable();
    $table->integer('points')->default(10);
    $table->integer('ordre')->default(1);
    $table->string('statut')->default('todo');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quetes');
    }
};
