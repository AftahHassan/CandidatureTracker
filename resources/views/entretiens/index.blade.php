<x-app-layout>
    <x-slot name="title">Entretiens</x-slot>

    <div class="page-header">
        <h1 class="page-title">Mes Entretiens</h1>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Type</th>
                        <th>Date & Heure</th>
                        <th>Résultat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entretiens as $entretien)
                        <tr>
                            <td style="font-weight:500;">
                                {{ $entretien->candidature->entreprise }}
                            </td>
                            <td style="color:var(--text-secondary);">
                                {{ $entretien->candidature->poste }}
                            </td>
                            <td>
                                @php
                                    $types = ['telephone' => '📞 Téléphone', 'visio' => '💻 Visio', 'presentiel' => '🏢 Présentiel', 'technique' => '⚙️ Technique', 'rh' => '👥 RH'];
                                @endphp
                                {{ $types[$entretien->type] ?? $entretien->type }}
                            </td>
                            <td style="font-size:13px; color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($entretien->date_heure)->format('d/m/Y à H:i') }}
                            </td>
                            <td>
                                @if($entretien->resultat)
                                    @php
                                        $badgeClass = ['en_attente' => 'badge-relance', 'positif' => 'badge-offre', 'negatif' => 'badge-refus'];
                                        $resultats  = ['en_attente' => '⏳ En attente', 'positif' => '✅ Positif', 'negatif' => '❌ Négatif'];
                                    @endphp
                                    <span class="badge {{ $badgeClass[$entretien->resultat] ?? '' }}">
                                        {{ $resultats[$entretien->resultat] ?? $entretien->resultat }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted); font-size:13px;">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('candidatures.show', $entretien->candidature) }}"
                                   class="btn btn-outline btn-sm">Voir candidature</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📅</div>
                                    <div class="empty-state-title">Aucun entretien planifié</div>
                                    <p class="empty-state-text">Ajoutez des entretiens depuis le détail d'une candidature.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>