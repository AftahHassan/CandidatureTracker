<?php

use App\Http\Controllers\CandidatureController;
use Illuminate\Support\Facades\Route;

// Page d'accueil → redirige vers la liste
Route::get('/', function () {
    return redirect()->route('candidatures.index');
});

// Toutes les routes candidatures protégées par auth
Route::middleware('auth')->group(function () {

    // CRUD principal
    Route::resource('candidatures', CandidatureController::class);

    // Archivage et restauration
    Route::delete('candidatures/{id}/archive', [CandidatureController::class, 'archive'])
        ->name('candidatures.archive');

    Route::post('candidatures/{id}/restore', [CandidatureController::class, 'restore'])
        ->name('candidatures.restore');

    // Page archives
    Route::get('archives', [CandidatureController::class, 'archives'])
        ->name('candidatures.archives');

});

require __DIR__.'/auth.php';