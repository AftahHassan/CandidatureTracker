<x-app-layout>
    <x-slot name="title">Archives</x-slot>

    {{-- Header --}}
    <div class="page-header">
        <div>
            <div class="breadcrumb" style="margin-bottom:8px;">
                <span>Archives</span>
            </div>
            <h1 class="page-title">Candidatures archivées</h1>
        </div>
        <a href="{{ route('candidatures.index') }}" class="btn btn-outline">
            ← Retour aux candidatures
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Date candidature</th>
                        <th>Archivée le</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr style="opacity:0.75;">
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
                            <td style="font-size:13px; color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($candidature->date_candidature)->format('d/m/Y') }}
                            </td>
                            <td style="font-size:13px; color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($candidature->deleted_at)->format('d/m/Y') }}
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('candidatures.restore', $candidature->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm">
                                        ♻️ Restaurer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📦</div>
                                    <div class="empty-state-title">Aucune candidature archivée</div>
                                    <p class="empty-state-text">
                                        Les candidatures archivées apparaîtront ici.
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