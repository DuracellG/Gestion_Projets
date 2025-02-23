@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Créer une nouvelle tâche pour : {{ $projet->titre }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projets.taches.store', $projet) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="titre" class="col-md-4 col-form-label text-md-right">Titre</label>

                            <div class="col-md-6">
                                <input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') }}" required autofocus>

                                @error('titre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_echeance" class="col-md-4 col-form-label text-md-right">Date d'échéance</label>

                            <div class="col-md-6">
                                <input id="date_echeance" type="date" class="form-control @error('date_echeance') is-invalid @enderror" name="date_echeance" value="{{ old('date_echeance') }}" required>

                                @error('date_echeance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="assignee_id" class="col-md-4 col-form-label text-md-right">Assignée à</label>

                            <div class="col-md-6">
                                <select id="assignee_id" class="form-control @error('assignee_id') is-invalid @enderror" name="assignee_id">
                                    <option value="">Non assignée</option>
                                    @foreach($membres as $membre)
                                        <option value="{{ $membre->id }}" {{ old('assignee_id') == $membre->id ? 'selected' : '' }}>
                                            {{ $membre->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('assignee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="statut" class="col-md-4 col-form-label text-md-right">Statut</label>

                            <div class="col-md-6">
                                <select id="statut" class="form-control @error('statut') is-invalid @enderror" name="statut" required>
                                    <option value="a_faire" {{ old('statut') == 'a_faire' ? 'selected' : '' }}>À faire</option>
                                    <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                </select>

                                @error('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Créer la tâche
                                </button>
                                <a href="{{ route('projets.show', $projet) }}" class="btn btn-secondary">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection