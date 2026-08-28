<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    protected $fillable = [
        'user_id', 'nom', 'prenom', 'email', 
        'poste', 'departement', 'points', 
        'avatar', 'date_debut'
    ];
}
