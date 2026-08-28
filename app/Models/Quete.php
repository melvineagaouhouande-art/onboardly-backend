<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quete extends Model
{
    protected $fillable = [
        'parcours_id', 'titre', 'description', 
        'points', 'ordre'
    ];
}
