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
        Schema::create('stagiaires', function (Blueprint $table) {
    $table->id(); // Nom de la colonne : id
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
    $table->string('nom');
    $table->string('prenom');
    $table->string('email')->unique();
    $table->string('poste');
    $table->string('departement')->nullable();
    $table->integer('points')->default(0);
    $table->string('avatar')->nullable();
    $table->date('date_debut')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaires');
    }
};
