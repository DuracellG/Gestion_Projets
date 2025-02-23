@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $projet->titre }}</h4>
                    <div>
                        @if($projet->createur_id == Auth::id() || $projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists())
                            <a href="{{ route('projets.edit', $projet) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        @endif
                        @if($projet->createur_id == Auth::id())
                            <form action="{{ route('projets.destroy', $projet) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p>{{ $projet->description ?: 'Aucune description' }}</p>
                            
                            <h5>Détails</h5>
                            <p><strong>Statut :</strong> 
                                <span class="badge badge-{{ $projet->statut == 'en_cours' ? 'primary' : ($projet->statut == 'termine' ? 'success' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $projet->statut)) }}
                                </span>
                            </p>
                            <p><strong>Date de début :</strong> {{ date('d/m/Y', strtotime($projet->date_debut)) }}</p>
                            <p><strong>Date de fin :</strong> {{ date('d/m/Y', strtotime($projet->date_fin)) }}</p>
                            <p><strong>Créé par :</strong> {{ $projet->createur->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Progression</h5>
                            <div class="text-center mb-2">{{ $progression }}%</div>
                            <div class="progress mb-4" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                     style="width: {{ $progression }}%" 
                                     aria-valuenow="{{ $progression }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $progression }}%
                                </div>
                            </div>
                            
                            <h5>Équipe</h5>
                            <ul class="list-group">
                                @foreach($membres as $membre)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $membre->name }}
                                        <span class="badge badge-{{ $membre->pivot->role == 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($membre->pivot->role) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            @if($projet->createur_id == Auth::id() || $projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists())
                                <button type="button" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#inviterMembreModal">
                                    <i class="fas fa-user-plus"></i> Inviter un membre
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tâches</h5>
                    <a href="{{ route('projets.taches.create', $projet) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle tâche
                    </a>
                </div>
                <div class="card-body">
                    @if($taches->isEmpty())
                        <p class="text-center">Aucune tâche pour ce projet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Assignée à</th>
                                        <th>Échéance</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taches as $tache)
                                        <tr>
                                            <td>{{ $tache->titre }}</td>
                                            <td>
                                                @if($tache->assignee)
                                                    {{ $tache->assignee->name }}
                                                @else
                                                    <span class="text-muted">Non assignée</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ date('d/m/Y', strtotime($tache->date_echeance)) }}
                                                @if(strtotime($tache->date_echeance) < time() && $tache->statut != 'termine')
                                                    <span class="badge badge-danger">En retard</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $tache->statut == 'a_faire' ? 'secondary' : 
                                                    ($tache->statut == 'en_cours' ? 'primary' : 
                                                    ($tache->statut == 'termine' ? 'success' : 'warning')) 
                                                }}">
                                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('taches.show', $tache) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('taches.edit', $tache) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($tache->createur_id == Auth::id() || $projet->createur_id == Auth::id() || $projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists())
                                                    <form action="{{ route('taches.destroy', $tache) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modele pour inviter un membre -->
<div class="modal fade" id="inviterMembreModal" tabindex="-1" role="dialog" aria-labelledby="inviterMembreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviterMembreModalLabel">Inviter un membre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('projets.inviter', $projet) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email de l'utilisateur</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="membre">Membre</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Inviter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection