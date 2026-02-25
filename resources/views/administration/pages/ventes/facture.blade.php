<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Facture N° {{ $vente->numero_facture }}</h2>
    <p>Date : {{ $vente->date->format('d/m/Y') }}</p>
    <p>Vendeur : {{ $vente->user->name }}</p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vente->ligneVentes as $ligne)
                <tr>
                    <td>{{ $ligne->produit->designation }}</td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} GNF</td>
                    <td>{{ number_format($ligne->total, 2, ',', ' ') }} GNF</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="text-align: right;">Total : {{ number_format($vente->montant_total, 2, ',', ' ') }} GNF</h3>
</body>
</html>
