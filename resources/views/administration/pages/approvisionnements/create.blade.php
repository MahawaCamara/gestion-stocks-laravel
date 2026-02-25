@extends('administration.base')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Titre + Bouton retour --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-primary">➕ Nouvel approvisionnement</h2>
                <a href="{{ route('approvisionnements.index') }}" class="btn btn-outline-secondary">
                    ← Retour à l’historique
                </a>
            </div>

            {{-- Formulaire --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('approvisionnements.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="produit_id" class="form-label fw-bold">🛒 Produit</label>
                            <select name="produit_id" class="form-select" required>
                                <option value="">-- Sélectionner un produit --</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->id }}">{{ $produit->designation }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label fw-bold">📦 Quantité</label>
                            <input type="number" name="quantite" class="form-control" min="1" required placeholder="Entrez la quantité à ajouter">
                        </div>
                        <div class="mb-3">
                            <label for="prix_unitaire" class="form-label fw-bold">Prix Unitaire</label>
                            <input type="number" name="prix_unitaire" step="0.01" class="form-control" required placeholder="Entrez le prix_unitaire">
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label fw-bold">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-2">
                            ✅ Valider l'approvisionnement
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
