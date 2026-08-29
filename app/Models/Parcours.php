<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcours extends Model
{
    protected $fillable = [
        'titre', 'description', 'icone', 'departement_id'
    ];

    /**
     * Relation avec le département associé.
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    /**
     * Relation avec les quêtes de ce parcours.
     */
    public function quetes()
    {
        return $this->hasMany(Quete::class, 'parcours_id');
    }

    /**
     * Relation avec les utilisateurs qui suivent ce parcours.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'parcours_id');
    }
}
