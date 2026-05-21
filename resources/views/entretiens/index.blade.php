<x-app-layout>
    <x-slot name="title">Entretiens</x-slot>

    {{-- Header --}}
    <div class="page-header">
        <div>
            <div class="breadcrumb" style="margin-bottom:8px;">
                <span>Entretiens</span>
            </div>
            <h1 class="page-title">Mes Entretiens</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:20px;">
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'avenir']) }}"
           style="padding:10px 18px;font-size:14px;font-weight:500;border-bottom:2px solid {{ !request('filter') || request('filter') === 'avenir' ? 'var(--primary)' : 'transparent' }};color:{{ !request('filter') || request('filter') === 'avenir' ? 'var(--primary)' : 'var(--text-secondary)' }};text-decoration:none;transition:all .15s;">
            À venir
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'passes']) }}"
           style="padding:10px 18px;font-size:14px;font-weight:500;border-bottom:2px solid {{ request('filter') === 'passes' ? 'var(--primary)' : 'transparent' }};color:{{ request('filter') === 'passes' ? 'var(--primary)' : 'var(--text-secondary)' }};text-decoration:none;transition:all .15s;">
            Passés
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'tous']) }}"
           style="padding:10px 18px;font-size:14px;font-weight:500;border-bottom:2px solid {{ request('filter') === 'tous' ? 'var(--primary)' : 'transparent' }};color:{{ request('filter') === 'tous' ? 'var(--primary)' : 'var(--text-secondary)' }};text-decoration:none;transition:all .15s;">
            Tous
        </a>
    </div>

    {{-- Grille entretiens --}}
    @php
        $now = now();
        $filtered = $entretiens->filter(function($e) use ($now) {
            $filter = request('filter', 'avenir');
            $date = \Carbon\Carbon::parse($e->date_heure);
            if ($filter === 'avenir')  return $date->isFuture();
            if ($filter === 'passes')  return $date->isPast();
            return true;
        });
        $types = [
            'telephone'  => '📞 Téléphone',
            'visio'      => '💻 Visio',
            'presentiel' => '🏢 Présentiel',
            'technique'  => '⚙️ Technique',
            'rh'         => '👥 RH',
        ];
        $resultats  = ['en_attente' => 'En attente', 'positif' => 'Positif', 'negatif' => 'Négatif'];
        $badgeRes   = ['en_attente' => 'badge-relance', 'positif' => 'badge-offre', 'negatif' => 'badge-refus'];
        $borderRes  = ['en_attente' => '#F59E0B', 'positif' => '#10B981', 'negatif' => '#EF4444'];
    @endphp

    @forelse($filtered as $entretien)
        @php
            $date      = \Carbon\Carbon::parse($entretien->date_heure);
            $isFuture  = $date->isFuture();
            $borderColor = isset($entretien->resultat) && $entretien->resultat
                ? ($borderRes[$entretien->resultat] ?? 'var(--border)')
                : ($isFuture ? 'var(--primary)' : 'var(--border)');
        @endphp
        <div class="card" style="margin-bottom:14px;border-left:3px solid {{ $borderColor }};">
            <div class="card-body" style="padding:18px 20px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">

                    {{-- Infos principales --}}
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                            <span style="font-size:15px;font-weight:600;color:var(--text-primary);">
                                {{ $entretien->candidature->entreprise }}
                            </span>
                            <span style="font-size:13px;color:var(--text-secondary);">
                                — {{ $entretien->candidature->poste }}
                            </span>
                        </div>

                        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                            {{-- Type --}}
                            <span style="font-size:13px;color:var(--text-secondary);display:flex;align-items:center;gap:4px;">
                                {{ $types[$entretien->type] ?? $entretien->type }}
                            </span>

                            {{-- Date --}}
                            <span style="font-size:13px;color:var(--text-secondary);display:flex;align-items:center;gap:4px;">
                                📅 {{ $date->format('d/m/Y à H:i') }}
                            </span>

                            {{-- Dans combien de temps --}}
                            @if($isFuture)
                                <span class="badge badge-entretien">
                                    Dans {{ $date->diffForHumans() }}
                                </span>
                            @else
                                <span style="font-size:12px;color:var(--text-muted);">
                                    {{ ucfirst($date->diffForHumans()) }}
                                </span>
                            @endif

                            {{-- Résultat --}}
                            @if($entretien->resultat)
                                <span class="badge {{ $badgeRes[$entretien->resultat] ?? '' }}">
                                    {{ $resultats[$entretien->resultat] ?? $entretien->resultat }}
                                </span>
                            @endif
                        </div>

                        {{-- Notes --}}
                        @if($entretien->notes_preparation)
                            <div style="margin-top:10px;padding:10px 12px;background:var(--bg);border-radius:var(--radius-sm);font-size:13px;color:var(--text-secondary);line-height:1.6;">
                                📝 {{ $entretien->notes_preparation }}
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:8px;flex-shrink:0;">
                        <a href="{{ route('candidatures.show', $entretien->candidature) }}"
                           class="btn btn-outline btn-sm">
                            Voir candidature
                        </a>
                        <a href="{{ route('entretiens.edit', [$entretien->candidature, $entretien]) }}"
                           class="btn btn-outline btn-sm">
                            ✏️ Modifier
                        </a>
                        <form method="POST"
                              action="{{ route('entretiens.destroy', [$entretien->candidature, $entretien]) }}">
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
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">📅</div>
                <div class="empty-state-title">Aucun entretien
                    @if(!request('filter') || request('filter') === 'avenir') à venir
                    @elseif(request('filter') === 'passes') passé
                    @endif
                </div>
                <p class="empty-state-text">
                    Ajoutez des entretiens depuis le
                    <a href="{{ route('candidatures.index') }}" style="color:var(--primary);">
                        détail d'une candidature
                    </a>.
                </p>
            </div>
        </div>
    @endforelse

</x-app-layout>