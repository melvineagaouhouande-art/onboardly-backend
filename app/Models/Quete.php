<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quete extends Model
{
    protected $fillable = [
        'parcours_id', 'titre', 'description', 
        'points', 'ordre', 'etape', 'type_validation', 'badge_id'
    ];

    /**
     * Relation avec le parcours parent.
     */
    public function parcours()
    {
        return $this->belongsTo(Parcours::class, 'parcours_id');
    }

    /**
     * Relation avec le badge associé.
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }

    /**
     * Relation avec le quiz associé.
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'quest_id');
    }
}
