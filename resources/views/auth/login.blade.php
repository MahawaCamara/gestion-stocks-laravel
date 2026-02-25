@extends('layouts.auth')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <!-- Icône utilisateur -->
     <div class="text-center mb-3">
    <div 
        class="bg-success rounded-circle d-inline-block overflow-hidden" 
        style="width: 100px; height: 100px;"
    >
        <img 
            src="{{ asset('assets/img/default.png') }}" 
            alt="navbar brand" 
            class="w-100 h-100 object-fit-cover"
        />
    </div>
</div>


        <div class="card-header bg-success text-white text-center rounded">
            <h4 class="mb-0">Connexion</h4>
        </div>

        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

               <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <!-- Se souvenir de moi -->
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>

                <!-- Bouton -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
