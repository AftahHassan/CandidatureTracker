<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    protected $fillable = [
        'candidature_id',
        'type',
        'date_heure',
        'notes_preparation',
        'resultat',
    ];

    // Un entretien appartient à une candidature
    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }
}