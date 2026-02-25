@extends('administration.base')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>➕ Ajouter un nouveau produit</h2>
        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <form action="{{ route('produits.store') }}" method="POST" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label for="designation" class="form-label">Désignation</label>
            <input type="text" name="designation" id="designation" class="form-control" placeholder="Nom du produit" required>
        </div>

        <div class="mb-3">
            <label for="categorie_id" class="form-label">Catégorie</label>
            <div class="input-group">
                <select name="categorie_id" id="categorie_id" class="form-select" required>
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control" placeholder="Description du produit">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="prix_vente" class="form-label">Prix de vente (GNF)</label>
                <input type="number" step="0.01" name="prix_vente" id="prix_vente" class="form-control" placeholder="Ex : 1000" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="stock_actuel" class="form-label">Stock actuel</label>
                <input type="number" name="stock_actuel" id="stock_actuel" class="form-control" placeholder="Quantité disponible" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                <input type="number" name="seuil_alerte" id="seuil_alerte" class="form-control" placeholder="Ex : 5" required>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Enregistrer le produit
            </button>
        </div>
    </form>
</div>

<!-- MODAL Catégorie -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="addCategoryModalLabel">Ajouter une nouvelle catégorie</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la catégorie</label>
                <input type="text" name="nom" id="nom" class="form-control" required placeholder="Ex : Légume">
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Ajouter
            </button>
        </div>
    </form>
  </div>
</div>
@endsection
