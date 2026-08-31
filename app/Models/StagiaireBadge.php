<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagiaireBadge extends Model
{
    protected $table = 'stagiaire_badges'; // On précise le nom de la table
    protected $fillable = ['stagiaire_id', 'badge_id', 'debloque_le'];

    /**
     * Relation avec le stagiaire.
     */
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'stagiaire_id');
    }

    /**
     * Relation avec le badge.
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }
}
