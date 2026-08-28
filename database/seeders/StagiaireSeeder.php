use App\Models\Stagiaire;

public function run(): void
{
    Stagiaire::create([
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.dupont@example.com',
        'poste' => 'Développeur Web',
        'departement' => 'Informatique',
        'points' => 50,
    ]);
}