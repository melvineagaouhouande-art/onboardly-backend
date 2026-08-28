<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagiaireBadge extends Model
{
    protected $table = 'stagiaire_badges'; // On précise le nom de la table
    protected $fillable = ['stagiaire_id', 'badge_id', 'debloque_le'];
}
