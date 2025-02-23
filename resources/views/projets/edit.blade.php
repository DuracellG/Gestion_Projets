<!-- resources/views/projets/edit.blade.php -->

@extends('layouts.app') <!-- Assurez-vous que ce fichier hérite d'un layout existant -->

@section('content')
<div class="container">
    <h1>Modifier le projet</h1>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de modification du projet -->
    <form action="{{ route('projets.update', $projet->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Méthode HTTP PUT pour la mise à jour -->

        <div class="form-group">
            <label for="titre">Titre du projet</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $projet->titre) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $projet->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut', $projet->date_debut) }}" required>
        </div>

        <div class="form-group">
            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin', $projet->date_fin) }}" required>
        </div>

        <!-- Champ pour le statut -->
        <div class="form-group">
            <label for="statut">Statut</label>
            <select name="statut" id="statut" class="form-control" required>
                <option value="en_cours" {{ old('statut', $projet->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine" {{ old('statut', $projet->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                <option value="en_attente" {{ old('statut', $projet->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection


