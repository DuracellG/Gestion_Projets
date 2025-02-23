<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class TacheController extends Controller
{
    
    /**
     * Affiche le formulaire de création de tâche
     */
    public function create(Projet $projet)
    {
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce projet');
        }
        
        $membres = $projet->membres;
        
        return view('taches.create', compact('projet', 'membres'));
    }

    /**
     * Enregistre une nouvelle tâche
     */
    public function store(Request $request, Projet $projet)
    {
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce projet');
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_echeance' => 'required|date',
            'assignee_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:a_faire,en_cours,termine,suspendu',
        ]);
        
        $validated['projet_id'] = $projet->id;
        $validated['createur_id'] = Auth::id();
        
        $tache = Tache::create($validated);
        
        // TODO: Envoyer une notification par email si la tâche est assignée
        
        return redirect()->route('projets.show', $projet)
                         ->with('success', 'Tâche créée avec succès!');
    }

    /**
     * Affiche une tâche spécifique
     */
    public function show(Tache $tache)
    {
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette tâche');
        }
        
        $fichiers = $tache->fichiers;
        
        return view('taches.show', compact('tache', 'projet', 'fichiers'));
    }

    /**
     * Affiche le formulaire d'édition d'une tâche
     */
    public function edit(Tache $tache)
    {
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette tâche');
        }
        
        $membres = $projet->membres;
        
        return view('taches.edit', compact('tache', 'projet', 'membres'));
    }

    /**
     * Met à jour une tâche existante
     */
    public function update(Request $request, Tache $tache)
    {
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette tâche');
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_echeance' => 'required|date',
            'assignee_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:a_faire,en_cours,termine,suspendu',
        ]);
        
        $tache->update($validated);
        
        return redirect()->route('taches.show', $tache)
                         ->with('success', 'Tâche mise à jour avec succès!');
    }

    /**
     * Supprime une tâche
     */
    public function destroy(Tache $tache)
    {
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur est le créateur de la tâche ou admin du projet
        if ($tache->createur_id != Auth::id() && 
            !$projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists() &&
            $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas le droit de supprimer cette tâche');
        }
        
        // Supprimer les fichiers associés
        foreach ($tache->fichiers as $fichier) {
            Storage::delete($fichier->chemin);
            $fichier->delete();
        }
        
        $tache->delete();
        
        return redirect()->route('projets.show', $projet)
                         ->with('success', 'Tâche supprimée avec succès!');
    }
    
    /**
     * Télécharge un fichier joint à une tâche
     */
    public function uploadFichier(Request $request, Tache $tache)
    {
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette tâche');
        }
        
        $request->validate([
            'fichier' => 'required|file|max:10240', // 10MB maximum
        ]);
        
        $file = $request->file('fichier');
        $path = $file->store('fichiers_taches');
        
        $tache->fichiers()->create([
            'nom' => $file->getClientOriginalName(),
            'chemin' => $path,
            'type_mime' => $file->getMimeType(),
            'taille' => $file->getSize(),
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->back()
                         ->with('success', 'Fichier téléchargé avec succès!');
    }
    
    /**
     * Télécharge un fichier joint à une tâche
     */
    public function telechargerFichier($id)
    {
        $fichier = Fichier::findOrFail($id);
        $tache = $fichier->tache;
        $projet = $tache->projet;
        
        // Vérifier si l'utilisateur a accès à ce projet
        if (!$projet->membres->contains(Auth::id()) && $projet->createur_id != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce fichier');
        }
        
        return Storage::download($fichier->chemin, $fichier->nom);
    }
}