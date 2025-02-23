@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un nouveau projet</h1>

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

    <!-- Formulaire de création de projet -->
    <form action="{{ route('projets.store') }}" method="POST">
        @csrf <!-- Protection contre les attaques CSRF -->

        <div class="form-group">
            <label for="titre">Titre du projet</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
        </div>

        <div class="form-group">
            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection

