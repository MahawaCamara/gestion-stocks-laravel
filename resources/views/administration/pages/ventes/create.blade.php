@extends('administration.base')

@section('content')
<div class="container my-5" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">🛒 Nouvelle vente</h2>
            <small class="text-muted">Sélectionnez les produits et quantités à vendre</small>
        </div>
        <a href="{{ route('ventes.index') }}" class="btn btn-outline-dark">← Retour à la liste</a>
    </div>

    <form method="GET" action="{{ route('ventes.create') }}" class="mb-4" id="venteForm">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher un produit..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
            <a href="{{ route('ventes.create') }}" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
    </form>

    <form action="{{ route('ventes.store') }}" method="POST" id="form-vente">
        @csrf

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th class="text-end">Prix Unitaire</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $index => $produit)
                        @php $rupture = $produit->stock_actuel <= $produit->seuil_alerte; @endphp
                        <tr class="{{ $rupture ? 'table-warning' : '' }}">
                            <td>
                                <strong>{{ $produit->designation }}</strong>
                                <input type="hidden" name="produits[{{ $index }}][id]" value="{{ $produit->id }}">
                            </td>
                            <td class="text-end">{{ number_format($produit->prix_vente, 2, ',', ' ') }}</td>
                            <td class="text-center">{{ $produit->stock_actuel }}</td>
                            <td
                                data-seuil-alerte="{{ $produit->seuil_alerte }}"
                            >
                                @if($rupture)
                                    <input type="number" class="form-control text-center" value="0" readonly>
                                    <input type="hidden" name="produits[{{ $index }}][quantite]" value="0">
                                    <small class="text-danger d-block">⚠ Stock critique</small>
                                @else
                                    <input type="number"
                                        name="produits[{{ $index }}][quantite]"
                                        class="form-control text-center quantite"
                                        data-prix="{{ $produit->prix_vente }}"
                                        data-stock="{{ $produit->stock_actuel }}"
                                        data-seuil-alerte="{{ $produit->seuil_alerte }}"
                                        value="0"
                                        min="0">
                                @endif
                            </td>
                            <td class="text-end sous-total">0,00</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun produit disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h4>Total : <span id="montant-total">0,00</span> GNF</h4>
            <button type="submit" class="btn btn-success" id="btn-submit" disabled>Valider</button>
        </div>
    </form>

    <div class="d-flex justify-content-center mt-4">
        {{ $produits->appends(request()->query())->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const inputs = document.querySelectorAll('.quantite');
    const totalDisplay = document.getElementById('montant-total');
    const btnSubmit = document.getElementById('btn-submit');

    function calculerTotaux() {
        let total = 0;
        let active = false;

        inputs.forEach(input => {
            const quantite = parseInt(input.value) || 0;
            const prix = parseFloat(input.dataset.prix);
            const ligne = input.closest('tr');
            const sousTotal = quantite * prix;
            ligne.querySelector('.sous-total').textContent = sousTotal.toLocaleString('fr-FR', { minimumFractionDigits: 2 });

            total += sousTotal;
            if (quantite > 0) active = true;
        });

        totalDisplay.textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 2 });
        btnSubmit.disabled = !active;
    }

    inputs.forEach(input => {
        input.addEventListener('input', calculerTotaux);
    });

    document.getElementById('form-vente').addEventListener('submit', function (e) {
        e.preventDefault();

        let erreurs = [];

        inputs.forEach(input => {
            const stock = parseInt(input.dataset.stock);
            const quantite = parseInt(input.value);
            const nomProduit = input.closest('tr').querySelector('strong').textContent;
            const seuilAlerte = parseInt(input.dataset.seuilAlerte);

            if (quantite > stock) {
                erreurs.push(`Le stock est insuffisant pour "${nomProduit}".`);
            }

            if ((stock - quantite) < seuilAlerte) {
                erreurs.push(`La vente de "${nomProduit}" ferait passer le stock en dessous du seuil critique.`);
            }
        });

        if (erreurs.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Vente non autorisée',
                html: erreurs.join('<br>'),
                confirmButtonText: 'Corriger'
            });
        } else {
            this.submit();
        }
    });

    calculerTotaux();
</script>
@endsection
