@extends('layouts.auth')

@section('content')
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Inscription</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nom -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <!-- Rôle -->
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="employe">Employé</option>
                    </select>
                </div>

                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <!-- Bouton -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">Déjà inscrit ?</a>
                    <button type="submit" class="btn btn-primary">S’inscrire</button>
                </div>
            </form>
        </div>
    </div>
@endsection
