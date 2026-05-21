<?php

use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\EntretienController;
use Illuminate\Support\Facades\Route;

// ─── Redirection racine ───────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ─── Dashboard (requis par Breeze après login) ────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// ─── Routes protégées ────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Candidatures — CRUD principal
    Route::resource('candidatures', CandidatureController::class);

    // Archiver (soft delete)
    Route::delete('candidatures/{id}/archive', [CandidatureController::class, 'archive'])
        ->name('candidatures.archive');

    // Page archives
    Route::get('archives', [CandidatureController::class, 'archives'])
        ->name('candidatures.archives');

    // Restaurer une candidature archivée
    Route::post('candidatures/{id}/restore', [CandidatureController::class, 'restore'])
        ->name('candidatures.restore');

    // Entretiens (imbriqués sous candidatures)
    Route::post('candidatures/{candidature}/entretiens', [EntretienController::class, 'store'])
        ->name('entretiens.store');

    Route::get('candidatures/{candidature}/entretiens/{entretien}/edit', [EntretienController::class, 'edit'])
        ->name('entretiens.edit');

    Route::put('candidatures/{candidature}/entretiens/{entretien}', [EntretienController::class, 'update'])
        ->name('entretiens.update');

    Route::delete('candidatures/{candidature}/entretiens/{entretien}', [EntretienController::class, 'destroy'])
        ->name('entretiens.destroy');

      Route::get('entretiens', function () {
        $entretiens = \App\Models\Entretien::whereHas('candidature', function($q) {
            $q->where('user_id', auth()->id());
        })->with('candidature')->orderBy('date_heure')->get();
        return view('entretiens.index', compact('entretiens'));
    })->name('entretiens.index');

});

// ─── Auth (Breeze) ────────────────────────────────────────────
require __DIR__.'/auth.php';