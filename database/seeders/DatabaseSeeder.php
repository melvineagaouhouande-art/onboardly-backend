<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stagiaire;
use App\Models\Parcours;
use App\Models\Quete;
use App\Models\Badge;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer le parcours
        $parcours = Parcours::create([
            'titre' => 'Parcours d’intégration Développeur',
            'description' => 'Découverte et prise en main des outils de l’entreprise',
        ]);

        // 2. Créer le stagiaire
        $stagiaire = Stagiaire::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'poste' => 'Développeur Web',
            'departement' => 'Informatique',
            'points' => 100,
        ]);

        // 3. Créer plusieurs quêtes
        Quete::create([
            'parcours_id' => $parcours->id,
            'titre' => 'Configurer l’environnement de dev',
            'description' => 'Installer VS Code, Git et Docker sur le poste.',
            'points' => 50,
            'ordre' => 1,
        ]);

        Quete::create([
            'parcours_id' => $parcours->id,
            'titre' => 'Rencontrer l’équipe',
            'description' => 'Faire une présentation avec l’équipe technique.',
            'points' => 30,
            'ordre' => 2,
        ]);

        Quete::create([
            'parcours_id' => $parcours->id,
            'titre' => 'Première Pull Request',
            'description' => 'Soumettre une première contribution sur le dépôt Git.',
            'points' => 100,
            'ordre' => 3,
        ]);

        // 4. Créer des badges
        Badge::create([
            'nom' => 'Bienvenue !',
            'description' => 'Attribué lors du premier jour d’arrivée.',
            'icone' => '🎉',
        ]);

        Badge::create([
            'nom' => 'Développeur en herbe',
            'description' => 'Attribué après avoir accumulé 100 points.',
            'icone' => '🚀',
        ]);
    }
}