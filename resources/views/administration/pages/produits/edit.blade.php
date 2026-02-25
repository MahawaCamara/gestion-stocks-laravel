@extends('administration.base')

@section('content')
<div class="container py-5">
    <!-- Titre et retour -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">🛠️ Modifier un produit</h2>
        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Formulaire -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('produits.update', $produit->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Désignation</label>
                        <input type="text" id="designation" name="designation" class="form-control" value="{{ $produit->designation }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="categorie_id" class="form-label">Catégorie</label>
                        <select name="categorie_id" id="categorie_id" class="form-select" required>
                            <option disabled>-- Choisir une catégorie --</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ $categorie->id == $produit->categorie_id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ $produit->description }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label for="prix_vente" class="form-label">Prix de vente (FCFA)</label>
                        <input type="number" id="prix_vente" name="prix_vente" class="form-control" value="{{ $produit->prix_vente }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="stock_actuel" class="form-label">Stock actuel</label>
                        <input type="number" id="stock_actuel" name="stock_actuel" class="form-control" value="{{ $produit->stock_actuel }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                        <input type="number" id="seuil_alerte" name="seuil_alerte" class="form-control" value="{{ $produit->seuil_alerte }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
