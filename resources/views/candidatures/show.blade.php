<x-app-layout>
    <x-slot name="title">{{ $candidature->entreprise }}</x-slot>

    {{-- Header --}}
    <div class="breadcrumb" style="margin-bottom:8px;">
        <a href="{{ route('candidatures.index') }}">Candidatures</a>
        <span style="margin:0 4px;">›</span>
        <span>{{ $candidature->entreprise }}</span>
    </div>

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $candidature->entreprise }}</h1>
            <div style="font-size:14px; color:var(--text-secondary); margin-top:4px;">
                {{ $candidature->poste }}
            </div>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('candidatures.edit', $candidature) }}" class="btn btn-outline">
                ✏️ Modifier
            </a>
            <form method="POST" action="{{ route('candidatures.archive', $candidature->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Archiver cette candidature ?')">
                    📦 Archiver
                </button>
            </form>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

        {{-- Infos principales --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informations</span>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:14px;">

                <div style="display:flex; justify-content:space-between;">
                    <span style="font-size:13px; color:var(--text-secondary);">Statut</span>
                    @php
                        $statuts = ['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'];
                    @endphp
                    <span class="badge badge-{{ $candidature->statut }}">
                        {{ $statuts[$candidature->statut] ?? $candidature->statut }}
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between;">
                    <span style="font-size:13px; color:var(--text-secondary);">Priorité</span>
                    @php
                        $priorites = ['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute'];
                    @endphp
                    <span class="badge badge-{{ $candidature->priorite }}">
                        {{ $priorites[$candidature->priorite] ?? $candidature->priorite }}
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between;">
                    <span style="font-size:13px; color:var(--text-secondary);">Date</span>
                    <span style="font-size:13px; font-weight:500;">
                        {{ \Carbon\Carbon::parse($candidature->date_candidature)->format('d/m/Y') }}
                    </span>
                </div>

                @if($candidature->url_offre)
                    <div style="display:flex; justify-content:space-between;">
                        <span style="font-size:13px; color:var(--text-secondary);">Offre</span>
                        <a href="{{ $candidature->url_offre }}" target="_blank"
                           style="font-size:13px; color:var(--primary);">
                            🔗 Voir l'offre
                        </a>
                    </div>
                @endif

                @if($candidature->fichier)
                    <div style="display:flex; justify-content:space-between;">
                        <span style="font-size:13px; color:var(--text-secondary);">Document</span>
                        <a href="{{ Storage::url($candidature->fichier) }}" target="_blank"
                           style="font-size:13px; color:var(--primary);">
                            📄 Télécharger
                        </a>
                    </div>
                @endif

            </div>
        </div>

        {{-- Notes --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Notes</span>
            </div>
            <div class="card-body">
                @if($candidature->notes)
                    <p style="font-size:14px; color:var(--text-secondary); line-height:1.7; white-space:pre-wrap;">{{ $candidature->notes }}</p>
                @else
                    <p style="font-size:14px; color:var(--text-muted); font-style:italic;">Aucune note ajoutée.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── Section Entretiens ─── --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Entretiens</span>
            <span style="font-size:12px; color:var(--text-muted);">
                {{ $candidature->entretiens->count() }} entretien(s)
            </span>
        </div>
        <div class="card-body">

            {{-- Formulaire ajout entretien --}}
            <div style="background:var(--bg); border-radius:var(--radius-md); padding:20px; margin-bottom:20px;">
                <div style="font-size:14px; font-weight:500; margin-bottom:16px;">➕ Ajouter un entretien</div>
                <form method="POST" action="{{ route('entretiens.store', $candidature) }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach(['telephone' => '📞 Téléphone', 'visio' => '💻 Visio', 'presentiel' => '🏢 Présentiel', 'technique' => '⚙️ Technique', 'rh' => '👥 RH'] as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date et heure</label>
                            <input type="datetime-local" name="date_heure"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Résultat <span class="optional">(optionnel)</span>
                        </label>
                        <select name="resultat" class="form-control">
                            <option value="">-- En attente --</option>
                            @foreach(['en_attente' => '⏳ En attente', 'positif' => '✅ Positif', 'negatif' => '❌ Négatif'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Notes de préparation <span class="optional">(optionnel)</span>
                        </label>
                        <textarea name="notes_preparation" class="form-control"
                                  placeholder="Questions à préparer, points clés..."></textarea>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-primary">Ajouter l'entretien</button>
                    </div>
                </form>
            </div>

            {{-- Liste des entretiens --}}
            @forelse($candidature->entretiens as $entretien)
                <div style="border:1px solid var(--border); border-radius:var(--radius-md); padding:16px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <div style="font-weight:500; font-size:14px; margin-bottom:4px;">
                                @php
                                    $types = ['telephone' => '📞 Téléphone', 'visio' => '💻 Visio', 'presentiel' => '🏢 Présentiel', 'technique' => '⚙️ Technique', 'rh' => '👥 RH'];
                                @endphp
                                {{ $types[$entretien->type] ?? $entretien->type }}
                                <span style="font-weight:400; color:var(--text-secondary); font-size:13px;">
                                    — {{ \Carbon\Carbon::parse($entretien->date_heure)->format('d/m/Y à H:i') }}
                                </span>
                            </div>
                            @if($entretien->resultat)
                                @php
                                    $resultats = ['en_attente' => 'En attente', 'positif' => 'Positif', 'negatif' => 'Négatif'];
                                    $badgeClass = ['en_attente' => 'badge-relance', 'positif' => 'badge-offre', 'negatif' => 'badge-refus'];
                                @endphp
                                <span class="badge {{ $badgeClass[$entretien->resultat] ?? '' }}">
                                    {{ $resultats[$entretien->resultat] ?? $entretien->resultat }}
                                </span>
                            @endif
                            @if($entretien->notes_preparation)
                                <p style="margin-top:8px; font-size:13px; color:var(--text-secondary); line-height:1.6;">
                                    📝 {{ $entretien->notes_preparation }}
                                </p>
                            @endif
                        </div>
                        <div style="display:flex; gap:8px; flex-shrink:0; margin-left:16px;">
                            <a href="{{ route('entretiens.edit', [$candidature, $entretien]) }}"
                               class="btn btn-outline btn-sm">✏️</a>
                            <form method="POST"
                                  action="{{ route('entretiens.destroy', [$candidature, $entretien]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Supprimer cet entretien ?')">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:32px;">
                    <div class="empty-state-icon">📅</div>
                    <div class="empty-state-title">Aucun entretien planifié</div>
                    <p class="empty-state-text">Utilisez le formulaire ci-dessus pour en ajouter un.</p>
                </div>
            @endforelse

        </div>
    </div>

</x-app-layout>