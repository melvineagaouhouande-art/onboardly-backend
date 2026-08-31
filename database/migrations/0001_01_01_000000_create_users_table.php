<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     * On crée d'abord la table 'departements' car 'users' en dépend (clé étrangère).
     */
    public function up(): void
    {
        // ============================================================
        // TABLE 1 : departements
        // Secteurs d'activité / Pôles de l'entreprise
        // (ex: IT / Tech, Ventes, RH, Finance, Communication)
        // ============================================================
        Schema::create('departements', function (Blueprint $table) {
            $table->id(); // Identifiant unique du département
            $table->string('nom', 100); // Nom du département (ex: IT / Tech)
            $table->timestamps(); // created_at et updated_at
        });

        // ============================================================
        // TABLE 2 : users
        // Comptes des collaborateurs, managers et administrateurs RH.
        // C'est la table principale pour l'authentification JWT.
        // ============================================================
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Identifiant unique de l'utilisateur

            // --- Informations personnelles ---
            $table->string('nom', 100); // Nom de famille (ex: BAMBA)
            $table->string('prenom', 100); // Prénom (ex: Léa)
            $table->string('email', 191)->unique(); // Email professionnel (unique)
            $table->string('password'); // Mot de passe sécurisé (haché automatiquement par Laravel)

            // --- Rôle et contrat ---
            // 3 rôles possibles : employé, manager, administrateur RH
            $table->enum('role', ['employe', 'manager', 'admin_rh'])->default('employe');
            // Type de contrat : CDI, CDD, Stagiaire, etc.
            $table->string('statut_contrat', 50)->nullable();

            // --- Intégration / Onboarding ---
            $table->string('code_invitation', 50)->nullable(); // Code d'invitation RH (ex: ONB-2026-XXXX)
            $table->date('date_arrivee')->nullable(); // Date d'intégration dans l'entreprise

            // --- Statut du compte ---
            // 'en_attente' = inscrit mais pas encore validé par la RH
            // 'actif'      = validé et opérationnel
            // 'suspendu'   = désactivé par la RH
            $table->enum('statut_compte', ['en_attente', 'actif', 'suspendu'])->default('en_attente');

            // --- Liens vers les autres tables ---
            // Département auquel appartient l'utilisateur
            $table->foreignId('departement_id')->nullable()->constrained('departements')->onDelete('set null');
            // Parcours d'onboarding attribué (la table 'parcours' est créée après,
            // donc on utilise un simple entier sans contrainte FK ici)
            $table->unsignedBigInteger('parcours_id')->nullable();

            // --- Gamification ---
            $table->integer('points_totaux')->default(0); // Total des points cumulés (ex: 1240 pts)
            $table->integer('niveau')->default(1); // Niveau gamifié actuel (ex: 3)
            $table->string('titre_niveau', 50)->default('Recrue'); // Titre du niveau (ex: Explorateur)

            // --- Vérification OTP par email ---
            $table->string('otp_code', 6)->nullable(); // Code OTP à 6 chiffres
            $table->timestamp('otp_expires_at')->nullable(); // Date d'expiration du code OTP
            $table->timestamp('email_verified_at')->nullable(); // Date de vérification de l'email

            $table->rememberToken(); // Token "Se souvenir de moi"
            $table->timestamps(); // created_at et updated_at
        });

        // ============================================================
        // Table des tokens de réinitialisation de mot de passe
        // (utilisée par Laravel pour le "Mot de passe oublié")
        // ============================================================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ============================================================
        // Table des sessions (pour le suivi des connexions)
        // ============================================================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Annuler les migrations (en ordre inverse).
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departements');
    }
};
