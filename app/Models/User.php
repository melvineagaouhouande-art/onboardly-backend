<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'statut_contrat',
        'code_invitation',
        'date_arrivee',
        'statut_compte',
        'departement_id',
        'parcours_id',
        'points_totaux',
        'niveau',
        'titre_niveau',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Obtenir l'identifiant qui sera stocké dans le sujet du JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retourner un tableau clé-valeur contenant les claims personnalisés à ajouter au JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'statut_compte' => $this->statut_compte,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
        ];
    }

    /**
     * Relation avec le département.
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    /**
     * Relation avec le parcours d'onboarding.
     */
    public function parcours()
    {
        return $this->belongsTo(Parcours::class, 'parcours_id');
    }

    /**
     * Relation avec le profil Stagiaire si l'utilisateur en a un.
     */
    public function stagiaire()
    {
        return $this->hasOne(Stagiaire::class, 'user_id');
    }
}

