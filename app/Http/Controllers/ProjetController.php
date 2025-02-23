<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjetController extends Controller
{
    /**
     * Affiche la liste des projets
     */
    public function index()
    {
        $user = Auth::user();
        $projets = $user->projetsParticipes()
                       ->orWhereIn('id', $user->projetsCrees()->pluck('id'))
                       ->get();
        
        return view('projets.index', compact('projets'));
    }

    /**
     * Affiche le formulaire de création de projet
     */
    public function create()
    {
        return view('projets.create');
    }

    /**
     * Enregistre un nouveau projet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);
        
        $validated['createur_id'] = Auth::id();
        $validated['statut'] = 'en_cours';
        
        $projet = Projet::create($validated);
        
        // Ajouter automatiquement le créateur comme admin du projet
        $projet->membres()->attach(Auth::id(), ['role' => 'admin']);
        
        return redirect()->route('projets.show', $projet)
                         ->with('success', 'Projet créé avec succès!');
    }

    /**
     * Affiche un projet spécifique
     */
    public function show(Projet $projet)
    {
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce projet');
        }
        
        $membres = $projet->membres;
        $taches = $projet->taches()->with('assignee')->get();
        $progression = $projet->calculerProgression();
        
        return view('projets.show', compact('projet', 'membres', 'taches', 'progression'));
    }

    /**
     * Affiche le formulaire d'édition d'un projet
     */
    public function edit(Projet $projet)
    {
        // Vérifier si l'utilisateur a le droit de modifier ce projet
        if ($projet->createur_id != Auth::id() && 
            !$projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists()) {
            abort(403, 'Vous n\'avez pas le droit de modifier ce projet');
        }
        
        return view('projets.edit', compact('projet'));
    }

    /**
     * Met à jour un projet existant
     */
    public function update(Request $request, Projet $projet)
    {
        // Vérifier si l'utilisateur a le droit de modifier ce projet
        if ($projet->createur_id != Auth::id() && 
            !$projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists()) {
            abort(403, 'Vous n\'avez pas le droit de modifier ce projet');
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:en_cours,termine,en_attente',
        ]);
        
        $projet->update($validated);
        
        return redirect()->route('projets.show', $projet)
                         ->with('success', 'Projet mis à jour avec succès!');
    }

    /**
     * Supprime un projet
     */
    public function destroy(Projet $projet)
    {
        // Vérifier si l'utilisateur est le créateur du projet
        if ($projet->createur_id != Auth::id()) {
            abort(403, 'Seul le créateur peut supprimer ce projet');
        }
        
        $projet->delete();
        
        return redirect()->route('projets.index')
                         ->with('success', 'Projet supprimé avec succès!');
    }
    
    /**
     * Invite un utilisateur à rejoindre le projet
     */
    public function inviterMembre(Request $request, Projet $projet)
{
    // Vérifier si l'utilisateur a le droit d'inviter des membres
    if ($projet->createur_id != Auth::id() && 
        !$projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists()) {
        abort(403, 'Vous n\'avez pas le droit d\'inviter des membres');
    }

    // Validation de l'email et du rôle
    $validated = $request->validate([
        'email' => 'required|email|exists:users,email',
        'role' => 'required|in:admin,membre',
    ]);

    // Récupération de l'utilisateur
    $user = User::where('email', $validated['email'])->first();

    // Vérifier si l'utilisateur est déjà membre
    if ($projet->membres->contains($user->id)) {
        return redirect()->back()
                         ->with('error', 'Cet utilisateur est déjà membre du projet');
    }

    // Ajouter l'utilisateur au projet
    $projet->membres()->attach($user->id, ['role' => $validated['role']]);

    // Envoi de l'invitation par email
    Mail::to($user->email)->send(new InvitationProjet($projet, $user));

    return redirect()->back()->with('success', 'Utilisateur invité avec succès!');
}