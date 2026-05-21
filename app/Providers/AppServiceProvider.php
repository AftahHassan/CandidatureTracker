<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Policies\CandidaturePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Entretien;
use App\Policies\EntretienPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Enregistrement de la Policy
        Gate::policy(Candidature::class, CandidaturePolicy::class);
        Gate::policy(Entretien::class, EntretienPolicy::class);
    }
}