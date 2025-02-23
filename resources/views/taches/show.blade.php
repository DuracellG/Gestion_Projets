@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $tache->titre }}</h4>
                    <div>
                        <a href="{{ route('projets.show', $projet) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Retour au projet
                        </a>
                        <a href="{{ route('taches.edit', $tache) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @if($tache->createur_id == Auth::id() || $projet->createur_id == Auth::id() || $projet->membres()->where('user_id', Auth::id())->where('role', 'admin')->exists())
                            <form action="{{ route('taches.destroy', $tache) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche?')">
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
                            <p>{{ $tache->description ?: 'Aucune description' }}</p>
                            
                            <h5>Détails</h5>
                            <p><strong>Projet :</strong> <a href="{{ route('projets.show', $projet) }}">{{ $projet->titre }}</a></p>
                            <p><strong>Statut :</strong> 
                                <span class="badge badge-{{ 
                                    $tache->statut == 'a_faire' ? 'secondary' : 
                                    ($tache->statut == 'en_cours' ? 'primary' : 
                                    ($tache->statut == 'termine' ? 'success' : 'warning')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                </span>
                            </p>
                            <p><strong>Date d'échéance :</strong> 
                                {{ date('d/m/Y', strtotime($tache->date_echeance)) }}
                                @if(strtotime($tache->date_echeance) < time() && $tache->statut != 'termine')
                                    <span class="badge badge-danger">En retard</span>
                                @endif
                            </p>
                            <p><strong>Assignée à :</strong> 
                                @if($tache->assignee)
                                    {{ $tache->assignee->name }}
                                @else
                                    <span class="text-muted">Non assignée</span>
                                @endif
                            </p>
                            <p><strong>Créée par :</strong> {{ $tache->createur->name }}</p>
                            <p><strong>Créée le :</strong> {{ date('d/m/Y à H:i', strtotime($tache->created_at)) }}</p>
                            @if($tache->updated_at != $tache->created_at)
                                <p><strong>Dernière mise à jour :</strong> {{ date('d/m/Y à H:i', strtotime($tache->updated_at)) }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($tache->statut != 'termine' && $tache->assignee_id == Auth::id())
                                <form action="{{ route('taches.update', $tache) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="titre" value="{{ $tache->titre }}">
                                    <input type="hidden" name="description" value="{{ $tache->description }}">
                                    <input type="hidden" name="date_echeance" value="{{ $tache->date_echeance }}">
                                    <input type="hidden" name="assignee_id" value="{{ $tache->assignee_id }}">
                                    <input type="hidden" name="statut" value="termine">
                                    <button type="submit" class="btn btn-success btn-block mb-4">
                                        <i class="fas fa-check"></i> Marquer comme terminée
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Fichiers joints</h5>
                    <form action="{{ route('taches.upload', $tache) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                        @csrf
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fichier" name="fichier" required>
                                <label class="custom-file-label" for="fichier">Choisir un fichier</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Télécharger</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if($fichiers->isEmpty())
                        <p class="text-center">Aucun fichier joint à cette tâche.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Taille</th>
                                        <th>Téléchargé par</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fichiers as $fichier)
                                        <tr>
                                            <td>{{ $fichier->nom }}</td>
                                            <td>{{ $fichier->type_mime }}</td>
                                            <td>{{ number_format($fichier->taille / 1024, 2) }} KB</td>
                                            <td>{{ $fichier->utilisateur->name }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($fichier->created_at)) }}</td>
                                            <td>
                                                <a href="{{ route('fichiers.telecharger', $fichier->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Télécharger
                                                </a>
                                                @if($fichier->user_id == Auth::id() || $tache->createur_id == Auth::id() || $projet->createur_id == Auth::id())
                                                    <form action="{{ route('fichiers.destroy', $fichier->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier?')">
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
@endsection

@section('scripts')
<script>
    // Script pour afficher le nom du fichier dans le label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endsection