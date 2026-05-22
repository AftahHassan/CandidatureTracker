<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CandidatureController
{
    public function index(Request $request)
    {
        $query = Candidature::where('user_id', auth()->id())
                            ->with('entretiens');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $candidatures = $query->latest()->get();

        return view('candidatures.index', compact('candidatures'));
    }

    public function create()
    {
        return view('candidatures.create');
    }

    public function store(StoreCandidatureRequest $request)
    {

        $data = $request->validated();
      // Upload du fichier si présent
        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')
                ->store('candidatures', 'public');
        }


        Candidature::create([
            ...$data,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature ajoutée avec succès !');
    }

    public function show(Candidature $candidature)
    {
        Gate::authorize('view', $candidature);
        $candidature->load('entretiens');
        return view('candidatures.show', compact('candidature'));
    }

    public function edit(Candidature $candidature)
    {
        Gate::authorize('update', $candidature);
        return view('candidatures.edit', compact('candidature'));
    }

    public function update(UpdateCandidatureRequest $request, Candidature $candidature)
    {
        Gate::authorize('update', $candidature);

        $data = $request->validated();
         // Nouveau fichier uploadé
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier du disque
            if ($candidature->fichier) {
                Storage::disk('public')->delete($candidature->fichier);
            }
            $data['fichier'] = $request->file('fichier')
                ->store('candidatures', 'public');
        }

        $candidature->update($data);

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature modifiée avec succès !');
    }

    public function archive($id)
    {
        $candidature = Candidature::findOrFail($id);
        Gate::authorize('delete', $candidature);
        $candidature->delete();

        return redirect()->route('candidatures.index')
            ->with('success', 'Candidature archivée.');
    }

    public function archives()
    {
        $candidatures = Candidature::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('candidatures.archives', compact('candidatures'));
    }

    public function restore($id)
    {
        $candidature = Candidature::onlyTrashed()->findOrFail($id);
        Gate::authorize('delete', $candidature);
        $candidature->restore();

        return redirect()->route('candidatures.archives')
            ->with('success', 'Candidature restaurée.');
    }

    public function destroy(Candidature $candidature)
    {
        Gate::authorize('delete', $candidature);
         // Supprimer le fichier du disque
        if ($candidature->fichier) {
            Storage::disk('public')->delete($candidature->fichier);
        }
        
        $candidature->forceDelete();

        return redirect()->route('candidatures.index');
    }
}