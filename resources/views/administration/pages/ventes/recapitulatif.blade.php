@extends('administration.base')

@section('content')
<div class="container">
    <h2>🗓️ Ventes du {{ now()->format('d/m/Y') }}</h2>
    <table class="table table-striped mt-3">
        <thead><tr><th>Produit</th><th>Quantité</th><th>Total</th><th>Employé</th></tr></thead>
        <tbody>
            @foreach($ventesJour as $vente)
                <tr>
                    <td>{{ $vente->produit->nom }}</td>
                    <td>{{ $vente->quantite }}</td>
                    <td>{{ number_format($vente->total, 2) }} €</td>
                    <td>{{ $vente->user->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4 class="mt-3">💰 Total : {{ number_format($totalJour, 2) }} €</h4>
</div>
@endsection
