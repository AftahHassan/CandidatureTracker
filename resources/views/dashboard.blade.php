<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $userId = auth()->id();

        // Stats candidatures
        $total      = \App\Models\Candidature::where('user_id', $userId)->count();
        $envoyees   = \App\Models\Candidature::where('user_id', $userId)->where('statut', 'envoyee')->count();
        $entretiens = \App\Models\Candidature::where('user_id', $userId)->where('statut', 'entretien')->count();
        $offres     = \App\Models\Candidature::where('user_id', $userId)->where('statut', 'offre')->count();
        $refus      = \App\Models\Candidature::where('user_id', $userId)->where('statut', 'refus')->count();
        $archivees  = \App\Models\Candidature::onlyTrashed()->where('user_id', $userId)->count();

        // Prochains entretiens
        $prochains = \App\Models\Entretien::whereHas('candidature', fn($q) => $q->where('user_id', $userId))
            ->with('candidature')
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->take(3)
            ->get();

        // Candidatures récentes
        $recentes = \App\Models\Candidature::where('user_id', $userId)
            ->with('entretiens')
            ->latest()
            ->take(5)
            ->get();

        $types = [
            'telephone'  => '📞 Téléphone',
            'visio'      => '💻 Visio',
            'presentiel' => '🏢 Présentiel',
            'technique'  => '⚙️ Technique',
            'rh'         => '👥 RH',
        ];
    @endphp

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Bonjour, {{ auth()->user()->name }} 👋</h1>
            <p style="font-size:14px;color:var(--text-secondary);margin-top:4px;">
                Voici un aperçu de ta recherche d'emploi
            </p>
        </div>
        <a href="{{ route('candidatures.create') }}" class="btn btn-primary">
            ＋ Nouvelle candidature
        </a>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;">

        <div class="card" style="padding:16px 18px;box-shadow:none;">
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                📨 Total
            </div>
            <div style="font-size:26px;font-weight:700;color:var(--text-primary);">{{ $total }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">candidatures</div>
        </div>

        <div class="card" style="padding:16px 18px;box-shadow:none;">
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                📅 Entretiens
            </div>
            <div style="font-size:26px;font-weight:700;color:var(--primary);">{{ $entretiens }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">
                taux {{ $total > 0 ? round($entretiens / $total * 100) : 0 }}%
            </div>
        </div>

        <div class="card" style="padding:16px 18px;box-shadow:none;">
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                ✅ Offres
            </div>
            <div style="font-size:26px;font-weight:700;color:#10B981;">{{ $offres }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">
                taux {{ $total > 0 ? round($offres / $total * 100) : 0 }}%
            </div>
        </div>

        <div class="card" style="padding:16px 18px;box-shadow:none;">
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                ❌ Refus
            </div>
            <div style="font-size:26px;font-weight:700;color:#EF4444;">{{ $refus }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">
                taux {{ $total > 0 ? round($refus / $total * 100) : 0 }}%
            </div>
        </div>

        <div class="card" style="padding:16px 18px;box-shadow:none;">
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                📦 Archivées
            </div>
            <div style="font-size:26px;font-weight:700;color:var(--text-secondary);">{{ $archivees }}</div>
            <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">terminées</div>
        </div>

    </div>

    {{-- Grid 2 colonnes --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

        {{-- Progression par statut --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Progression</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                @php
                    $statsList = [
                        'envoyee'   => ['label' => 'Envoyées',   'count' => $envoyees,   'color' => '#3B82F6'],
                        'entretien' => ['label' => 'Entretiens', 'count' => $entretiens, 'color' => 'var(--primary)'],
                        'offre'     => ['label' => 'Offres',     'count' => $offres,     'color' => '#10B981'],
                        'refus'     => ['label' => 'Refus',      'count' => $refus,      'color' => '#EF4444'],
                    ];
                @endphp

                @foreach($statsList as $s)
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                            <span style="color:var(--text-secondary);">{{ $s['label'] }}</span>
                            <span style="font-weight:600;color:var(--text-primary);">{{ $s['count'] }}</span>
                        </div>
                        <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;background:{{ $s['color'] }};border-radius:3px;width:{{ $total > 0 ? round($s['count'] / $total * 100) : 0 }}%;transition:.4s;"></div>
                        </div>
                    </div>
                @endforeach

                @if($total === 0)
                    <p style="font-size:13px;color:var(--text-muted);text-align:center;padding:16px 0;">
                        Aucune donnée pour le moment.
                    </p>
                @endif

            </div>
        </div>

        {{-- Répartition priorités --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Répartition des priorités</span>
            </div>
            <div class="card-body">
                @php
                    $pFaible  = \App\Models\Candidature::where('user_id', $userId)->where('priorite', 'faible')->count();
                    $pMoyenne = \App\Models\Candidature::where('user_id', $userId)->where('priorite', 'moyenne')->count();
                    $pHaute   = \App\Models\Candidature::where('user_id', $userId)->where('priorite', 'haute')->count();
                @endphp

                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                            <span style="color:var(--text-secondary);">🔴 Haute</span>
                            <span style="font-weight:600;color:var(--text-primary);">{{ $pHaute }}</span>
                        </div>
                        <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;background:#EF4444;border-radius:3px;width:{{ $total > 0 ? round($pHaute / $total * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                            <span style="color:var(--text-secondary);">🔵 Moyenne</span>
                            <span style="font-weight:600;color:var(--text-primary);">{{ $pMoyenne }}</span>
                        </div>
                        <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;background:#3B82F6;border-radius:3px;width:{{ $total > 0 ? round($pMoyenne / $total * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                            <span style="color:var(--text-secondary);">🟢 Faible</span>
                            <span style="font-weight:600;color:var(--text-primary);">{{ $pFaible }}</span>
                        </div>
                        <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;background:#10B981;border-radius:3px;width:{{ $total > 0 ? round($pFaible / $total * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                </div>

                @if($total === 0)
                    <p style="font-size:13px;color:var(--text-muted);text-align:center;padding:16px 0;">
                        Aucune donnée pour le moment.
                    </p>
                @endif

            </div>
        </div>

    </div>

    {{-- Prochains entretiens --}}
    <div style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <div style="font-size:15px;font-weight:600;color:var(--text-primary);">Prochains entretiens</div>
            <a href="{{ route('entretiens.index') }}"
               style="font-size:13px;color:var(--primary);">Voir tous →</a>
        </div>

        @if($prochains->isEmpty())
            <div class="card">
                <div class="empty-state" style="padding:32px;">
                    <div class="empty-state-icon">📅</div>
                    <div class="empty-state-title">Aucun entretien à venir</div>
                    <p class="empty-state-text">Ajoutez un entretien depuis le détail d'une candidature.</p>
                </div>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
                @foreach($prochains as $e)
                    @php $date = \Carbon\Carbon::parse($e->date_heure); @endphp
                    <div class="card" style="padding:16px;border-top:3px solid var(--primary);">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                            <span class="badge badge-entretien">{{ ucfirst($date->diffForHumans()) }}</span>
                            <span style="font-size:12px;color:var(--text-muted);">{{ $date->format('H:i') }}</span>
                        </div>
                        <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">
                            {{ $e->candidature->entreprise }}
                        </div>
                        <div style="font-size:12.5px;color:var(--text-secondary);margin-bottom:8px;">
                            {{ $e->candidature->poste }}
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);">
                            {{ $types[$e->type] ?? $e->type }} · {{ $date->format('d/m/Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Candidatures récentes --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <div style="font-size:15px;font-weight:600;color:var(--text-primary);">Candidatures récentes</div>
            <a href="{{ route('candidatures.index') }}"
               style="font-size:13px;color:var(--primary);">Voir toutes →</a>
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
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentes as $c)
                            @php
                                $statuts   = ['envoyee' => 'Envoyée', 'relance' => 'Relancée', 'entretien' => 'Entretien', 'offre' => 'Offre reçue', 'refus' => 'Refus'];
                                $priorites = ['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute'];
                            @endphp
                            <tr>
                                <td style="font-weight:500;">{{ $c->entreprise }}</td>
                                <td style="color:var(--text-secondary);font-size:13px;">{{ $c->poste }}</td>
                                <td><span class="badge badge-{{ $c->statut }}">{{ $statuts[$c->statut] ?? $c->statut }}</span></td>
                                <td><span class="badge badge-{{ $c->priorite }}">{{ $priorites[$c->priorite] ?? $c->priorite }}</span></td>
                                <td style="font-size:13px;color:var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($c->date_candidature)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('candidatures.show', $c) }}"
                                       class="btn btn-outline btn-sm">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state" style="padding:32px;">
                                        <div class="empty-state-icon">📭</div>
                                        <div class="empty-state-title">Aucune candidature</div>
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
    </div>

</x-app-layout>