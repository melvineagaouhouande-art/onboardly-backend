<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['quest_id', 'titre', 'seuil_reussite'];

    /**
     * Relation avec la quête associée.
     */
    public function quete()
    {
        return $this->belongsTo(Quete::class, 'quest_id');
    }

    /**
     * Relation avec les questions du quiz.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    /**
     * Relation avec les résultats des tentatives des utilisateurs.
     */
    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }
}
