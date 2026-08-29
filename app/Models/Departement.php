<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = ['nom'];

    /**
     * Relation avec les utilisateurs du département.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'departement_id');
    }

    /**
     * Relation avec les parcours du département.
     */
    public function parcours()
    {
        return $this->hasMany(Parcours::class, 'departement_id');
    }
}
