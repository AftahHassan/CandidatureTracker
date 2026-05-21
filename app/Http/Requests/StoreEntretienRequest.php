<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntretienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'               => 'required|in:telephone,visio,presentiel,technique,rh',
            'date_heure'         => 'required|date',
            'notes_preparation'  => 'nullable|string',
            'resultat'           => 'nullable|in:en_attente,positif,negatif',
        ];
    }
}