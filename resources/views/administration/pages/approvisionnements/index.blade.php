@extends('administration.base')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">📦 Historique des approvisionnements</h1>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('approvisionnements.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="produit_id" class="form-label">Filtrer par produit</label>
            <select name="produit_id" id="produit_id" class="form-select">
                <option value="">Tous les produits</option>
                @foreach ($produitsList as $prod)
                    <option value="{{ $prod->id }}" {{ request('produit_id') == $prod->id ? 'selected' : '' }}>
                        {{ $prod->designation }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="date" class="form-label">Filtrer par date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">🔍 Filtrer</button>
            <a href="{{ route('approvisionnements.index') }}" class="btn btn-secondary">🔄 Réinitialiser</a>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('approvisionnements.pdf') }}" class="btn btn-danger w-100">
                🧾 Export PDF
            </a>
        </div>
    </form>

    {{-- Bouton nouvel approvisionnement --}}
    <div class="mb-3">
        <a href="{{ route('approvisionnements.create') }}" class="btn btn-success">➕ Nouvel approvisionnement</a>
    </div>

    {{-- Tableau --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($approvisionnements as $appro)
                <tr class="{{ $appro->quantite < 10 ? 'table-danger' : 'table-success' }}">
                    <td>
                        {{ $appro->produit->designation }}
                        {!! $appro->quantite < 10 ? '<span class="text-danger ms-1">⚠️ Faible stock</span>' : '<span class="text-success ms-1">✔️</span>' !!}
                    </td>
                    <td>{{ $appro->quantite }}</td>
                    <td>{{ \Carbon\Carbon::parse($appro->date_approvisionnement)->format('d/m/Y H:i') }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('approvisionnements.show', $appro->id) }}" class="btn btn-info btn-sm">🔍 Voir</a>
                        
                        <form action="{{ route('approvisionnements.destroy', $appro->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                        </form>

                        <a href="{{ url('/recu/approvisionnement/' . $appro->id) }}" target="_blank" class="btn btn-info btn-sm">📄 PDF</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun approvisionnement trouvé.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $approvisionnements->links() }}
    </div>

</div>
@endsection
