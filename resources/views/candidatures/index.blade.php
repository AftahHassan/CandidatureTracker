<x-app-layout>
    <x-slot name="title">Mes Candidatures</x-slot>

    {{-- Header --}}
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <span>Mes Candidatures</span>
            </div>
            <h1 class="page-title">Mes Candidatures</h1>
        </div>
        <a href="{{ route('candidatures.create') }}" class="btn btn-primary">
            ＋ Nouvelle candidature
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('candidatures.index') }}">
        <div class="filters-bar">
            <select name="statut" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'] as $val => $label)
                    <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="priorite" class="filter-select" onchange="this.form.submit()">
                <option value="">Toutes les priorités</option>
                @foreach(['faible' => 'Priorité faible', 'moyenne' => 'Priorité moyenne', 'haute' => 'Priorité haute'] as $val => $label)
                    <option value="{{ $val }}" {{ request('priorite') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            @if(request('statut') || request('priorite'))
                <a href="{{ route('candidatures.index') }}" class="btn btn-outline btn-sm">
                    ✕ Réinitialiser
                </a>
            @endif

            <span style="margin-left:auto; font-size:13px; color:var(--text-secondary);">
                {{ $candidatures->count() }} candidature(s)
            </span>
        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Date</th>
                        <th>Entretiens</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td>
                                <div style="font-weight:500;">{{ $candidature->entreprise }}</div>
                            </td>
                            <td style="color:var(--text-secondary);">{{ $candidature->poste }}</td>
                            <td>
                                @php
                                    $statuts = ['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'];
                                @endphp
                                <span class="badge badge-{{ $candidature->statut }}">
                                    {{ $statuts[$candidature->statut] ?? $candidature->statut }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $priorites = ['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute'];
                                @endphp
                                <span class="badge badge-{{ $candidature->priorite }}">
                                    {{ $priorites[$candidature->priorite] ?? $candidature->priorite }}
                                </span>
                            </td>
                            <td style="color:var(--text-secondary); font-size:13px;">
                                {{ \Carbon\Carbon::parse($candidature->date_candidature)->format('d/m/Y') }}
                            </td>
                            <td style="color:var(--text-secondary); font-size:13px;">
                                {{ $candidature->entretiens->count() }} entretien(s)
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; justify-content:flex-end;">
                                    <a href="{{ route('candidatures.show', $candidature) }}"
                                       class="btn btn-outline btn-sm">Voir</a>
                                    <a href="{{ route('candidatures.edit', $candidature) }}"
                                       class="btn btn-outline btn-sm">Modifier</a>
                                    <form method="POST"
                                          action="{{ route('candidatures.archive', $candidature->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Archiver cette candidature ?')">
                                            Archiver
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <div class="empty-state-title">Aucune candidature pour le moment</div>
                                    <p class="empty-state-text">
                                        <a href="{{ route('candidatures.create') }}"
                                           style="color:var(--primary);">Ajouter votre première candidature</a>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>