@extends('administration.base')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Historique des ventes</h1>
        <a href="{{ route('ventes.create') }}" class="btn btn-success">
            ➕ Nouvelle vente
        </a>
    </div>


    {{-- Formulaire de filtre --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="numero_facture" class="form-control" placeholder="Numéro de facture" value="{{ request('numero_facture') }}">
        </div>
        <div class="col-md-4">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-primary me-2">Rechercher</button>
            <a href="{{ route('ventes.index') }}" class="btn btn-secondary">Réinitialiser</a>
        </div>
    </form>

    {{-- Tableau des ventes --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Numéro Facture</th>
                    <th>Vendeur</th>
                    <th>Date</th>
                    <th>Montant Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                    <tr>
                        <td>{{ $vente->numero_facture }}</td>
                        <td>{{ $vente->user->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date)->format('d/m/Y') }}</td>
                        <td>{{ number_format($vente->montant_total, 2, ',', ' ') }} GNF</td>
                        <td>
                            <a href="{{ route('ventes.facture.pdf', $vente->id) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                                🧾 Imprimer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune vente trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $ventes->links() }}
    </div>

</div>
@endsection
