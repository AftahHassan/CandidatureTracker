<x-app-layout>
    <x-slot name="title">Modifier l'entretien</x-slot>

    {{-- Header --}}
    <div class="breadcrumb" style="margin-bottom:8px;">
        <a href="{{ route('candidatures.index') }}">Candidatures</a>
        <span style="margin:0 4px;">›</span>
        <a href="{{ route('candidatures.show', $candidature) }}">{{ $candidature->entreprise }}</a>
        <span style="margin:0 4px;">›</span>
        <span>Modifier l'entretien</span>
    </div>
    <h1 class="page-title" style="margin-bottom:24px;">Modifier l'entretien</h1>

    <div class="card" style="max-width:640px;">
        <div class="card-body">
            <form method="POST"
                  action="{{ route('entretiens.update', [$candidature, $entretien]) }}">
                @csrf
                @method('PUT')

                {{-- Type + Date --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="type">Type d'entretien</label>
                        <select id="type" name="type" class="form-control" required>
                            @foreach([
                                'telephone'  => '📞 Téléphone',
                                'visio'      => '💻 Visio',
                                'presentiel' => '🏢 Présentiel',
                                'technique'  => '⚙️ Technique',
                                'rh'         => '👥 RH'
                            ] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('type', $entretien->type) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="date_heure">Date et heure</label>
                        <input type="datetime-local" id="date_heure" name="date_heure"
                               class="form-control @error('date_heure') is-invalid @enderror"
                               value="{{ old('date_heure', \Carbon\Carbon::parse($entretien->date_heure)->format('Y-m-d\TH:i')) }}"
                               required>
                        @error('date_heure')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Résultat --}}
                <div class="form-group">
                    <label class="form-label" for="resultat">
                        Résultat <span class="optional">(optionnel)</span>
                    </label>
                    <select id="resultat" name="resultat" class="form-control">
                        <option value="">-- En attente --</option>
                        @foreach([
                            'en_attente' => '⏳ En attente',
                            'positif'    => '✅ Positif',
                            'negatif'    => '❌ Négatif'
                        ] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('resultat', $entretien->resultat) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('resultat')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Notes de préparation --}}
                <div class="form-group">
                    <label class="form-label" for="notes_preparation">
                        Notes de préparation <span class="optional">(optionnel)</span>
                    </label>
                    <textarea id="notes_preparation" name="notes_preparation"
                              class="form-control @error('notes_preparation') is-invalid @enderror"
                              placeholder="Questions à préparer, points importants...">{{ old('notes_preparation', $entretien->notes_preparation) }}</textarea>
                    @error('notes_preparation')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; justify-content:flex-end; padding-top:12px; border-top:1px solid var(--border);">
                    <a href="{{ route('candidatures.show', $candidature) }}" class="btn btn-outline">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>