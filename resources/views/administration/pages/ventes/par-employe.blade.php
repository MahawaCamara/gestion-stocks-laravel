@extends('administration.base')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📊 Ventes par employé</h2>

    @foreach ($employes as $employe)
        <div class="card mb-3">
            <div class="card-header fw-bold">
                👤 {{ $employe->name }} — {{ $employe->ventes->count() }} vente(s)
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($employe->ventes as $vente)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $vente->produit->designation }} — {{ $vente->quantite }} x {{ $vente->prix_unitaire }}€</span>
                            <span class="text-muted">{{ $vente->date->format('d/m/Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>
@endsection
