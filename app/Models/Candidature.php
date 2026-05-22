<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'entreprise',
        'poste',
        'url_offre',
        'statut',
        'priorite',
        'notes',
        'date_candidature',
        'fichier',
    ];

    // Une candidature appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une candidature a plusieurs entretiens
    public function entretiens()
    {
        return $this->hasMany(Entretien::class);
    }
}