<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>État des stocks</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #333; color: white; }
        .stock-faible { background-color: #f8d7da; }
    </style>
</head>
<body>
    <h2>📦 État des stocks</h2>
    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Stock Actuel</th>
                <th>Seuil d'Alerte</th>
                <th>Dernière Mise à Jour</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
            <tr class="{{ $produit->stock_actuel < $produit->seuil_alerte ? 'stock-faible' : '' }}">
                <td>{{ $produit->designation }}</td>
                <td>{{ $produit->stock_actuel }}</td>
                <td>{{ $produit->seuil_alerte }}</td>
                <td>{{ $produit->updated_at->format('d/m/Y H:i') }}</td>
                <td>{{ $produit->stock_actuel < $produit->seuil_alerte ? 'Stock Faible' : 'OK' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
