@extends('administration.base')

@section('content')
<div class="container mt-5 mb-4">
    <h2 class="mb-4 text-center">Créer un Employé</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulaire avec enctype pour upload fichier -->
    <form method="POST" action="{{ route('employes.store') }}" enctype="multipart/form-data" id="formEmploye">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo (optionnelle)</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
        </div>

        <input type="hidden" name="role" value="employe">

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Créer</button>

            <!-- Bouton annuler : vide les champs du formulaire -->
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('formEmploye').reset();">Annuler</button>

            <!-- Bouton retour vers la liste -->
            <a href="{{ route('employes.index') }}" class="btn btn-link">Retour à la liste des employés</a>
        </div>
    </form>
</div>
@endsection
