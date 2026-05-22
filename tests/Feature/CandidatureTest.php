<?php

use App\Models\User;
use App\Models\Candidature;


// ─── Helpers ──────────────────────────────────────────────────

function createUser(): User
{
    return User::factory()->create();
}

function createCandidature(User $user, array $attrs = []): Candidature
{
    return Candidature::factory()->create(array_merge([
        'user_id' => $user->id,
    ], $attrs));
}

// ─── Authentification ─────────────────────────────────────────

test('un utilisateur peut accéder à la page de connexion', function () {
    $this->get('/login')->assertStatus(200);
});

test('un utilisateur peut se connecter avec des identifiants valides', function () {
    $user = createUser();

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

test('un utilisateur ne peut pas se connecter avec un mauvais mot de passe', function () {
    $user = createUser();

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'mauvais_mdp',
    ]);

    $this->assertGuest();
});

test('un utilisateur peut se déconnecter', function () {
    $user = createUser();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});

// ─── Liste candidatures ────────────────────────────────────────

test('un utilisateur connecté peut voir sa liste de candidatures', function () {
    $user = createUser();

    $this->actingAs($user)
         ->get('/candidatures')
         ->assertStatus(200);
});

test('un utilisateur non connecté est redirigé vers login', function () {
    $this->get('/candidatures')
         ->assertRedirect('/login');
});

test('un utilisateur ne voit que ses propres candidatures', function () {
    $user1 = createUser();
    $user2 = createUser();

    createCandidature($user1, ['entreprise' => 'Google']);
    createCandidature($user2, ['entreprise' => 'Amazon']);

    $response = $this->actingAs($user1)->get('/candidatures');

    $response->assertSee('Google');
    $response->assertDontSee('Amazon');
});

// ─── Créer une candidature ─────────────────────────────────────

test('un utilisateur peut créer une candidature avec des données valides', function () {
    $user = createUser();

    $this->actingAs($user)->post('/candidatures', [
        'entreprise'       => 'Google',
        'poste'            => 'Développeur Laravel',
        'url_offre'        => 'https://google.com/jobs',
        'statut'           => 'envoyee',
        'priorite'         => 'haute',
        'notes'            => 'Super opportunité',
        'date_candidature' => '2026-05-22',
    ])->assertRedirect('/candidatures');

    $this->assertDatabaseHas('candidatures', [
        'entreprise' => 'Google',
        'user_id'    => $user->id,
    ]);
});

test('un utilisateur ne peut pas créer une candidature avec des données invalides', function () {
    $user = createUser();

    $this->actingAs($user)->post('/candidatures', [
        'entreprise'       => '',
        'poste'            => '',
        'statut'           => 'invalide',
        'priorite'         => '',
        'date_candidature' => '',
    ])->assertSessionHasErrors(['entreprise', 'poste', 'statut', 'priorite', 'date_candidature']);
});

test('la candidature est bien associée au user connecté', function () {
    $user = createUser();

    $this->actingAs($user)->post('/candidatures', [
        'entreprise'       => 'Stripe',
        'poste'            => 'Backend',
        'statut'           => 'envoyee',
        'priorite'         => 'moyenne',
        'date_candidature' => '2026-05-22',
    ]);

    $this->assertDatabaseHas('candidatures', [
        'entreprise' => 'Stripe',
        'user_id'    => $user->id,
    ]);
});

// ─── Modifier une candidature ──────────────────────────────────

test('un utilisateur peut modifier sa propre candidature', function () {
    $user        = createUser();
    $candidature = createCandidature($user);

    $this->actingAs($user)->put("/candidatures/{$candidature->id}", [
        'entreprise'       => 'Google Modifié',
        'poste'            => 'Senior Dev',
        'statut'           => 'entretien',
        'priorite'         => 'haute',
        'date_candidature' => '2026-05-22',
    ])->assertRedirect('/candidatures');

    $this->assertDatabaseHas('candidatures', [
        'id'         => $candidature->id,
        'entreprise' => 'Google Modifié',
    ]);
});

// ─── Policy — accès non autorisé ──────────────────────────────

test('un utilisateur ne peut pas voir la candidature d\'un autre', function () {
    $user1       = createUser();
    $user2       = createUser();
    $candidature = createCandidature($user2);

    $this->actingAs($user1)
         ->get("/candidatures/{$candidature->id}")
         ->assertStatus(403);
});

test('un utilisateur ne peut pas modifier la candidature d\'un autre', function () {
    $user1       = createUser();
    $user2       = createUser();
    $candidature = createCandidature($user2);

    $this->actingAs($user1)->put("/candidatures/{$candidature->id}", [
        'entreprise'       => 'Hack',
        'poste'            => 'Hack',
        'statut'           => 'envoyee',
        'priorite'         => 'faible',
        'date_candidature' => '2026-05-22',
    ])->assertStatus(403);
});

test('un utilisateur ne peut pas archiver la candidature d\'un autre', function () {
    $user1       = createUser();
    $user2       = createUser();
    $candidature = createCandidature($user2);

    $this->actingAs($user1)
         ->delete("/candidatures/{$candidature->id}/archive")
         ->assertStatus(403);
});

// ─── Archivage (Soft Delete) ───────────────────────────────────

test('un utilisateur peut archiver sa candidature', function () {
    $user        = createUser();
    $candidature = createCandidature($user);

    $this->actingAs($user)
         ->delete("/candidatures/{$candidature->id}/archive")
         ->assertRedirect('/candidatures');

    $this->assertSoftDeleted('candidatures', [
        'id' => $candidature->id,
    ]);
});

test('une candidature archivée n\'apparaît plus dans la liste principale', function () {
    $user        = createUser();
    $candidature = createCandidature($user, ['entreprise' => 'ArchivéeCorp']);

    $this->actingAs($user)
         ->delete("/candidatures/{$candidature->id}/archive");

    $this->actingAs($user)
         ->get('/candidatures')
         ->assertDontSee('ArchivéeCorp');
});

// ─── Restauration ─────────────────────────────────────────────

test('un utilisateur peut restaurer une candidature archivée', function () {
    $user        = createUser();
    $candidature = createCandidature($user);

    $candidature->delete();

    $this->actingAs($user)
         ->post("/candidatures/{$candidature->id}/restore")
         ->assertRedirect('/archives');

    $this->assertDatabaseHas('candidatures', [
        'id'         => $candidature->id,
        'deleted_at' => null,
    ]);
});

test('une candidature restaurée réapparaît dans la liste active', function () {
    $user        = createUser();
    $candidature = createCandidature($user, ['entreprise' => 'RestauréeCorp']);

    $candidature->delete();

    $this->actingAs($user)
         ->post("/candidatures/{$candidature->id}/restore");

    $this->actingAs($user)
         ->get('/candidatures')
         ->assertSee('RestauréeCorp');
});