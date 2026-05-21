<x-app-layout>
    <x-slot name="title">Nouvelle candidature</x-slot>

    {{-- Header --}}
    <div class="breadcrumb" style="margin-bottom:8px;">
        <a href="{{ route('candidatures.index') }}">Candidatures</a>
        <span style="margin:0 4px;">›</span>
        <span>Nouvelle candidature</span>
    </div>
    <h1 class="page-title" style="margin-bottom:24px;">Nouvelle candidature</h1>

    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('candidatures.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Entreprise + Poste --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="entreprise">Nom de l'entreprise</label>
                        <input type="text" id="entreprise" name="entreprise"
                               class="form-control @error('entreprise') is-invalid @enderror"
                               placeholder="ex: Google, SNCF..."
                               value="{{ old('entreprise') }}" required>
                        @error('entreprise')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="poste">Poste visé</label>
                        <input type="text" id="poste" name="poste"
                               class="form-control @error('poste') is-invalid @enderror"
                               placeholder="ex: Développeur Laravel..."
                               value="{{ old('poste') }}" required>
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
                                   placeholder="https://linkedin.com/jobs/..."
                                   value="{{ old('url_offre') }}">
                        </div>
                        @error('url_offre')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="date_candidature">Date de candidature</label>
                        <input type="date" id="date_candidature" name="date_candidature"
                               class="form-control @error('date_candidature') is-invalid @enderror"
                               value="{{ old('date_candidature', date('Y-m-d')) }}" required>
                        @error('date_candidature')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Statut + Priorité --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="statut">Statut</label>
                        <select id="statut" name="statut"
                                class="form-control @error('statut') is-invalid @enderror" required>
                            @foreach(['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'] as $val => $label)
                                <option value="{{ $val }}" {{ old('statut', 'envoyee') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priorité</label>
                        <div class="priority-group">
                            @foreach(['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute'] as $val => $label)
                                <label class="priority-btn {{ old('priorite', 'moyenne') === $val ? 'active-'.$val : '' }}"
                                       style="cursor:pointer;">
                                    <input type="radio" name="priorite" value="{{ $val }}"
                                           style="display:none;"
                                           {{ old('priorite', 'moyenne') === $val ? 'checked' : '' }}
                                           onchange="updatePriority(this)">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        @error('priorite')
                            <div class="form-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- CV Upload --}}
                <div class="form-group">
                    <label class="form-label" for="fichier">
                        CV / Lettre de motivation <span class="optional">(optionnel)</span>
                    </label>
                    <div class="upload-zone" onclick="document.getElementById('fichier').click()">
                        <div class="upload-zone-icon">📎</div>
                        <div class="upload-zone-text">Cliquer pour uploader ou glisser-déposer</div>
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
                    <textarea id="notes" name="notes"
                              class="form-control @error('notes') is-invalid @enderror"
                              placeholder="Infos importantes, contacts, prérequis...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="form-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; justify-content:flex-end; padding-top:8px; border-top:1px solid var(--border);">
                    <a href="{{ route('candidatures.index') }}" class="btn btn-outline">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Enregistrer la candidature
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