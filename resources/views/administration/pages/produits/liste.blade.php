@extends('administration.base')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">🧾 Liste des Produits</h2>
        <a href="{{ route('produits.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un produit
        </a>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" action="{{ route('produits.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher un produit..." value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </div>
        @if(request('search'))
            <div class="col-auto">
                <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times-circle"></i> Réinitialiser
                </a>
            </div>
        @endif
    </form>

    <!-- Tableau des produits -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Désignation</th>
                    <th>Catégorie</th>
                    <th>Prix (FCFA)</th>
                    <th>Stock</th>
                    <th>Seuil d'Alerte</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                    <tr class="{{ $produit->stock_actuel <= $produit->seuil_alerte ? 'table-danger' : '' }}">
                        <td>{{ $produit->designation }}</td>
                        <td>{{ $produit->categorie->nom ?? 'N/A' }}</td>
                        <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }}</td>
                        <td>{{ $produit->stock_actuel }}</td>
                        <td>{{ $produit->seuil_alerte }}</td>
                        <td>
                            <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $produits->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
