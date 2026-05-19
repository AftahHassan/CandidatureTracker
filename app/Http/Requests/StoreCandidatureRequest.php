<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // l'auth est gérée par le middleware
    }

    public function rules(): array
    {
        return [
            'entreprise'       => 'required|string|max:255',
            'poste'            => 'required|string|max:255',
            'url_offre'        => 'nullable|url',
            'statut'           => 'required|in:envoyee,relance,entretien,offre,refus',
            'priorite'         => 'required|in:faible,moyenne,haute',
            'notes'            => 'nullable|string',
            'date_candidature' => 'required|date',
        ];
    }
}