<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Entretien;
use App\Http\Requests\StoreEntretienRequest;
use App\Http\Requests\UpdateEntretienRequest;

class EntretienController extends Controller
{
    // Enregistrer un entretien
    public function store(StoreEntretienRequest $request, Candidature $candidature)
    {
        // Vérifier que la candidature appartient au user connecté
        $this->authorize('view', $candidature);

        $candidature->entretiens()->create($request->validated());

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Entretien ajouté avec succès !');
    }

    // Formulaire de modification
    public function edit(Candidature $candidature, Entretien $entretien)
    {
        $this->authorize('update', $entretien);

        return view('entretiens.edit', compact('candidature', 'entretien'));
    }

    // Modifier en base
    public function update(UpdateEntretienRequest $request, Candidature $candidature, Entretien $entretien)
    {
        $this->authorize('update', $entretien);

        $entretien->update($request->validated());

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Entretien modifié avec succès !');
    }

    // Supprimer
    public function destroy(Candidature $candidature, Entretien $entretien)
    {
        $this->authorize('delete', $entretien);

        $entretien->delete();

        return redirect()->route('candidatures.show', $candidature)
            ->with('success', 'Entretien supprimé.');
    }
}