<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\FichierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes (provided by laravel/ui)
Auth::routes();

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Home Route
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projets
    Route::resource('projets', ProjetController::class);
    Route::post('/projets',[ProjetController::class,'store'])->name('projets.store');
    Route::put('/projets/{projet}',[ProjetController::class,'update'])->name('projets.update');
    Route::post('/projets/{projet}/inviter', [ProjetController::class, 'inviterMembre'])->name('projets.inviter');
    
    // Tâches
    Route::resource('taches', TacheController::class)->except(['index', 'create', 'store']);
    Route::get('/projets/{projet}/taches/create', [TacheController::class, 'create'])->name('projets.taches.create');
    Route::post('/projets/{projet}/taches', [TacheController::class, 'store'])->name('projets.taches.store');
    Route::post('/taches/{tache}/upload', [TacheController::class, 'uploadFichier'])->name('taches.upload');
    
    // Fichiers
    Route::get('/fichiers/{id}/telecharger', [TacheController::class, 'telechargerFichier'])->name('fichiers.telecharger');
    Route::delete('/fichiers/{id}', [TacheController::class, 'supprimerFichier'])->name('fichiers.destroy');
});

// Redirect root to home
    Route::get('/', function () {
        return redirect()->route('home');
});