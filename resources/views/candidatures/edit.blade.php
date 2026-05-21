<x-app-layout>
    <x-slot name="title">Modifier la candidature</x-slot>

    {{-- Header --}}
    <div class="breadcrumb" style="margin-bottom:8px;">
        <a href="{{ route('candidatures.index') }}">Candidatures</a>
        <span style="margin:0 4px;">›</span>
        <a href="{{ route('candidatures.show', $candidature) }}">{{ $candidature->entreprise }}</a>
        <span style="margin:0 4px;">›</span>
        <span>Modifier</span>
    </div>
    <h1 class="page-title" style="margin-bottom:24px;">Modifier la candidature</h1>

    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('candidatures.update', $candidature) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Entreprise + Poste --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="entreprise">Nom de l'entreprise</label>
                        <input type="text" id="entreprise" name="entreprise"
                               class="form-control @error('entreprise') is-invalid @enderror"
                               value="{{ old('entreprise', $candidature->entreprise) }}" required>
                        @error('entreprise')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="poste">Poste visé</label>
                        <input type="text" id="poste" name="poste"
                               class="form-control @error('poste') is-invalid @enderror"
                               value="{{ old('poste', $candidature->poste) }}" required>
                        @error('poste')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- URL + Date --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="url_offre">
                            URL de l'offre <span class="optional">(optionnel)</span>
                        </label>
                        <div class="input-icon-wrap">
                            <span class="input-icon">🔗</span>
                            <input type="url" id="url_offre" name="url_offre"
                                   class="form-control @error('url_offre') is-invalid @enderror"
                                   value="{{ old('url_offre', $candidature->url_offre) }}">
                        </div>
                        @error('url_offre')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="date_candidature">Date de candidature</label>
                        <input type="date" id="date_candidature" name="date_candidature"
                               class="form-control @error('date_candidature') is-invalid @enderror"
                               value="{{ old('date_candidature', \Carbon\Carbon::parse($candidature->date_candidature)->format('Y-m-d')) }}"
                               required>
                        @error('date_candidature')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Statut + Priorité --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="statut">Statut</label>
                        <select id="statut" name="statut" class="form-control" required>
                            @foreach(['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('statut', $candidature->statut) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priorité</label>
                        <div class="priority-group">
                            @foreach(['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute'] as $val => $label)
                                @php $currentPrio = old('priorite', $candidature->priorite); @endphp
                                <label class="priority-btn {{ $currentPrio === $val ? 'active-'.$val : '' }}"
                                       style="cursor:pointer;">
                                    <input type="radio" name="priorite" value="{{ $val }}"
                                           style="display:none;"
                                           {{ $currentPrio === $val ? 'checked' : '' }}
                                           onchange="updatePriority(this)">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Fichier actuel --}}
                @if($candidature->fichier)
                    <div class="form-group">
                        <label class="form-label">Document actuel</label>
                        <div style="display:flex; align-items:center; gap:10px; padding:10px; background:var(--bg); border-radius:var(--radius-sm);">
                            <span>📄</span>
                            <a href="{{ Storage::url($candidature->fichier) }}" target="_blank"
                               style="color:var(--primary); font-size:13px;">Télécharger le fichier actuel</a>
                        </div>
                    </div>
                @endif

                {{-- Nouveau fichier --}}
                <div class="form-group">
                    <label class="form-label" for="fichier">
                        {{ $candidature->fichier ? 'Remplacer le document' : 'Ajouter un document' }}
                        <span class="optional">(optionnel)</span>
                    </label>
                    <div class="upload-zone" onclick="document.getElementById('fichier').click()">
                        <div class="upload-zone-icon">📎</div>
                        <div class="upload-zone-text">Cliquer pour uploader</div>
                        <div class="upload-zone-sub">PDF, DOCX jusqu'à 10MB</div>
                    </div>
                    <input type="file" id="fichier" name="fichier"
                           accept=".pdf,.doc,.docx" style="display:none;"
                           onchange="updateFileName(this)">
                    <div id="file-name" style="font-size:12px; color:var(--text-secondary); margin-top:6px;"></div>
                </div>

                {{-- Notes --}}
                <div class="form-group">
                    <label class="form-label" for="notes">
                        Notes <span class="optional">(optionnel)</span>
                    </label>
                    <textarea id="notes" name="notes" class="form-control"
                              placeholder="Infos importantes, contacts...">{{ old('notes', $candidature->notes) }}</textarea>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; justify-content:flex-end; padding-top:8px; border-top:1px solid var(--border);">
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

    <script>
        function updatePriority(radio) {
            document.querySelectorAll('.priority-btn').forEach(btn => {
                btn.className = 'priority-btn';
            });
            radio.parentElement.classList.add('active-' + radio.value);
        }
        function updateFileName(input) {
            const label = document.getElementById('file-name');
            if (input.files.length > 0) {
                label.textContent = '📄 ' + input.files[0].name;
            }
        }
    </script>

</x-app-layout>