@extends('administration.base')

@section('content')
<div class="container mt-5" style="max-width: 1000px;">
    <h2 class="mb-4">📦 État des stocks</h2>

    <form method="GET" action="{{ route('stocks.index') }}" class="row g-3 mb-4 align-items-center">
        <div class="col-auto">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher un produit...">
        </div>
        <div class="col-auto form-check">
            <input type="checkbox" name="stock_faible" id="stock_faible" class="form-check-input" {{ request()->has('stock_faible') ? 'checked' : '' }}>
            <label for="stock_faible" class="form-check-label">Afficher seulement stocks faibles</label>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('stocks.exportExcel', request()->query()) }}" class="btn btn-success me-2" title="Exporter en Excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('stocks.exportPdf', request()->query()) }}" class="btn btn-danger" title="Exporter en PDF" target="_blank">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </form>

    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Désignation</th>
                <th>Stock Actuel</th>
                <th>Seuil d'Alerte</th>
                <th>Dernière Mise à Jour</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produits as $produit)
            <tr @if($produit->stock_actuel < $produit->seuil_alerte) class="table-warning" @endif>
                <td>{{ $produit->designation }}</td>
                <td>{{ $produit->stock_actuel }}</td>
                <td>{{ $produit->seuil_alerte }}</td>
                <td>{{ $produit->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($produit->stock_actuel < $produit->seuil_alerte)
                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i> Stock Faible</span>
                    @else
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> OK</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Aucun produit trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $produits->links() }}
    </div>
</div>
@endsection
