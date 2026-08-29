<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class stagiaireQuete extends Model
{
    protected $table = 'stagiaire_quetes'; // On précise le nom de la table
    protected $fillable = ['stagiaire_id', 'quete_id', 'statut', 'date_limite', 'termine_le'];

    /**
     * Relation avec le stagiaire.
     */
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'stagiaire_id');
    }

    /**
     * Relation avec la quête.
     */
    public function quete()
    {
        return $this->belongsTo(Quete::class, 'quete_id');
    }
}
