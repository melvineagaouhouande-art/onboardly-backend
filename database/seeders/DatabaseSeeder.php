<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stagiaire;
use App\Models\Parcours;
use App\Models\Quete;
use App\Models\Badge;
use App\Models\Departement;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed de la base de données.
     * Génère des données initiales cohérentes avec les 11 tables relationnelles.
     */
    public function run(): void
    {
        // 1. Créer les départements
        $it = Departement::create(['nom' => 'IT / Tech']);
        $rh = Departement::create(['nom' => 'Ressources Humaines']);
        $sales = Departement::create(['nom' => 'Marketing & Ventes']);
        $finance = Departement::create(['nom' => 'Finance & Admin']);

        // 2. Créer des parcours d'intégration
        $parcoursDev = Parcours::create([
            'titre' => 'Parcours Développeur Web',
            'description' => 'Programme complet de découverte technique et de prise en main de l\'environnement de développement.',
            'icone' => '💻',
            'departement_id' => $it->id,
        ]);

        $parcoursRh = Parcours::create([
            'titre' => 'Parcours Assistant RH',
            'description' => 'Processus d\'onboarding au sein du pôle des ressources humaines.',
            'icone' => '👥',
            'departement_id' => $rh->id,
        ]);

        // 3. Créer des utilisateurs de démonstration (Mots de passe par défaut : password)
        // Administrateur RH
        $adminUser = User::create([
            'nom' => 'RH',
            'prenom' => 'Sophie',
            'email' => 'rh@entreprise.com',
            'password' => Hash::make('password'),
            'role' => 'admin_rh',
            'statut_compte' => 'actif',
            'departement_id' => $rh->id,
            'email_verified_at' => now(),
        ]);

        // Manager
        $managerUser = User::create([
            'nom' => 'Manager',
            'prenom' => 'Marc',
            'email' => 'marc@entreprise.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'statut_compte' => 'actif',
            'departement_id' => $it->id,
            'email_verified_at' => now(),
        ]);

        // Employé (Stagiaire)
        $employeeUser = User::create([
            'nom' => 'Employé',
            'prenom' => 'Léa',
            'email' => 'lea@entreprise.com',
            'password' => Hash::make('password'),
            'role' => 'employe',
            'statut_contrat' => 'Stagiaire',
            'date_arrivee' => now(),
            'statut_compte' => 'actif', // Utilisateur actif de test
            'departement_id' => $it->id,
            'parcours_id' => $parcoursDev->id,
            'points_totaux' => 80,
            'niveau' => 1,
            'titre_niveau' => 'Nouvelle recrue',
            'email_verified_at' => now(),
        ]);

        // Employé en attente de validation RH
        $pendingUser = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean@entreprise.com',
            'password' => Hash::make('password'),
            'role' => 'employe',
            'statut_contrat' => 'Stagiaire',
            'date_arrivee' => now(),
            'statut_compte' => 'en_attente', // En attente de validation
            'departement_id' => $it->id,
            'email_verified_at' => now(), // Email vérifié mais compte en attente d'activation RH
        ]);

        // 4. Créer les profils Stagiaires associés
        $stagiaireLea = Stagiaire::create([
            'user_id' => $employeeUser->id,
            'nom' => 'Employé',
            'prenom' => 'Léa',
            'email' => 'lea@entreprise.com',
            'poste' => 'Développeur Junior',
            'departement' => 'IT / Tech',
            'points' => 80,
            'date_debut' => now(),
        ]);

        $stagiaireJean = Stagiaire::create([
            'user_id' => $pendingUser->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean@entreprise.com',
            'poste' => 'Stagiaire Dev',
            'departement' => 'IT / Tech',
            'points' => 0,
            'date_debut' => now(),
        ]);

        // 5. Créer des badges
        $badgeWelcome = Badge::create([
            'nom' => 'Bienvenue à bord',
            'description' => 'Attribué dès la création active du compte.',
            'icone' => '🎉',
            'points_requis' => 0,
        ]);

        $badgeSecurity = Badge::create([
            'nom' => 'Expert Sécurité',
            'description' => 'Attribué pour avoir réussi le quiz de sécurité informatique avec 80% ou plus.',
            'icone' => '🛡️',
            'points_requis' => 50,
        ]);

        $badgeIntegration = Badge::create([
            'nom' => 'Explorateur social',
            'description' => 'Attribué après validation des quêtes de rencontre.',
            'icone' => '🤝',
            'points_requis' => 100,
        ]);

        // 6. Créer des quêtes pour le parcours Développeur
        Quete::create([
            'parcours_id' => $parcoursDev->id,
            'titre' => 'Rencontrer mon manager & parrain',
            'description' => 'Prendre un café virtuel ou physique de 15 minutes pour faire connaissance.',
            'etape' => 'Jour 1',
            'points' => 30,
            'ordre' => 1,
            'type_validation' => 'manager',
            'badge_id' => $badgeWelcome->id,
        ]);

        Quete::create([
            'parcours_id' => $parcoursDev->id,
            'titre' => 'Quiz sur la sécurité informatique',
            'description' => 'Répondre au questionnaire concernant le protocole de cybersécurité interne.',
            'etape' => 'Semaine 1',
            'points' => 50,
            'ordre' => 2,
            'type_validation' => 'quiz',
            'badge_id' => $badgeSecurity->id,
        ]);

        Quete::create([
            'parcours_id' => $parcoursDev->id,
            'titre' => 'Configurer mon environnement local',
            'description' => 'Installer Docker, clone les projets de l\'équipe et configurer la base de données.',
            'etape' => 'Semaine 1',
            'points' => 40,
            'ordre' => 3,
            'type_validation' => 'auto',
        ]);
    }
}