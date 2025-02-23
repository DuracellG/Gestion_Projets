<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les projets créés par l'utilisateur
        $projetsCrees = $user->projetsCrees;
        
        // Récupérer les projets auxquels l'utilisateur participe
        $projetsParticipes = $user->projetsParticipes;
        
        // Fusionner les projets et éliminer les doublons
        $projets = $projetsCrees->merge($projetsParticipes)->unique('id');
        
        // Récupérer les tâches assignées à l'utilisateur
        $tachesAssignees = $user->tachesAssignees()
                               ->with('projet')
                               ->orderBy('date_echeance')
                               ->get();
        
        return view('dashboard.index', compact('projets', 'tachesAssignees'));
    }
}