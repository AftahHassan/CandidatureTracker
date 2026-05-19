<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    // Liste des candidatures actives + filtres
    public function index(Request $request)
    {
        $query = Candidature::where('user_id', auth()->id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $candidatures = $query->latest()->get();

        return view('candidatures.index', compact('candidatures'));
    }

    // Formulaire de création
    public function create()
    {
        return view('candidatures.create');
    }

    // Enregistrer en base
    public function store(StoreCandidatureRequest $request)
    {
        Candidature::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature ajoutée avec succès !');
    }

    // Détail d'une candidature
    public function show(Candidature $candidature)
    {
        $this->authorize('view', $candidature);

        $candidature->load('entretiens'); // évite le N+1

        return view('candidatures.show', compact('candidature'));
    }

    // Formulaire de modification
    public function edit(Candidature $candidature)
    {
        $this->authorize('update', $candidature);

        return view('candidatures.edit', compact('candidature'));
    }

    // Modifier en base
    public function update(UpdateCandidatureRequest $request, Candidature $candidature)
    {
        $this->authorize('update', $candidature);

        $candidature->update($request->validated());

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature modifiée avec succès !');
    }

    // Archiver (soft delete)
    public function archive($id)
    {
        $candidature = Candidature::findOrFail($id);
        $this->authorize('delete', $candidature);

        $candidature->delete(); // soft delete grâce à SoftDeletes

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature archivée.');
    }

    // Page archives
    public function archives()
    {
        $candidatures = Candidature::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('candidatures.archives', compact('candidatures'));
    }

    // Restaurer une candidature archivée
    public function restore($id)
    {
        $candidature = Candidature::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $candidature);

        $candidature->restore();

        return redirect()->route('candidatures.archives')
            ->with('success', 'Candidature restaurée.');
    }

    // Supprimer définitivement (destroy non utilisé pour l'instant)
    public function destroy(Candidature $candidature)
    {
        $this->authorize('delete', $candidature);
        $candidature->forceDelete();

        return redirect()->route('candidatures.index');
    }
}