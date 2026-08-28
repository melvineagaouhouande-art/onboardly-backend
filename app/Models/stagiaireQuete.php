<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class stagiaireQuete extends Model
{
    protected $table = 'stagiaire_quetes'; // On précise le nom de la table
    protected $fillable = ['stagiaire_id', 'quete_id', 'statut', 'termine_le'];
}
