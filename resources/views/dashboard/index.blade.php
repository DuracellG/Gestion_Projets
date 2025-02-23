@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Tableau de bord</h1>
            
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Mes projets</span>
                            <a href="{{ route('projets.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nouveau projet
                            </a>
                        </div>
                        <div class="card-body">
                            @if ($projets->isEmpty())
                                <p class="text-center">Vous n'avez pas encore de projets.</p>
                            @else
                                <div class="row">
                                    @foreach ($projets as $projet)
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $projet->titre }}</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                        <span class="badge badge-{{ $projet->statut == 'en_cours' ? 'primary' : ($projet->statut == 'termine' ? 'success' : 'warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $projet->statut)) }}
                                                        </span>
                                                    </h6>
                                                    <p class="card-text">{{ Str::limit($projet->description, 100) }}</p>
                                                    
                                                    <div class="progress mb-3">
                                                        <div class="progress-bar" role="progressbar" 
                                                             style="width: {{ $projet->calculerProgression() }}%" 
                                                             aria-valuenow="{{ $projet->calculerProgression() }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            {{ $projet->calculerProgression() }}%
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="card-text">
                                                        <small class="text-muted">
                                                            Du {{ date('d/m/Y', strtotime($projet->date_debut)) }} 
                                                            au {{ date('d/m/Y', strtotime($projet->date_fin)) }}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="{{ route('projets.show', $projet) }}" class="btn btn-sm btn-outline-primary">
                                                        Voir le projet
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Mes tâches assignées</div>
                        <div class="card-body">
                            @if ($tachesAssignees->isEmpty())
                                <p class="text-center">Vous n'avez pas de tâches assignées.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tâche</th>
                                                <th>Projet</th>
                                                <th>Échéance</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tachesAssignees as $tache)
                                                <tr>
                                                    <td>{{ $tache->titre }}</td>
                                                    <td>
                                                        <a href="{{ route('projets.show', $tache->projet) }}">
                                                            {{ $tache->projet->titre }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ date('d/m/Y', strtotime($tache->date_echeance)) }}
                                                        @if (strtotime($tache->date_echeance) < time() && $tache->statut != 'termine')
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
                                                            Voir
                                                        </a>
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
    </div>
</div>
@endsection