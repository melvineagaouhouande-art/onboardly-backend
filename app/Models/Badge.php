<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'nom', 'description', 'icone', 'points_requis'
    ];

    /**
     * Relation avec les quêtes qui débloquent ce badge.
     */
    public function quetes()
    {
        return $this->hasMany(Quete::class, 'badge_id');
    }
}
