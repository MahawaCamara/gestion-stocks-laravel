<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PDF - Historique des approvisionnements</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #343a40;
            background-color: #fff;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        p {
            margin: 4px 0;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #f1f8ff;
            color: #0d6efd;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>
<body>

    <h2>📦 Reçu d'approvisionnement</h2>

    <div class="info">
        <p><strong>Date du rapport :</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

        @if(request('produit_id'))
            <p><strong>Filtré par produit :</strong> {{ $produitsList->where('id', request('produit_id'))->first()->nom ?? 'N/A' }}</p>
        @endif

        @if(request('date'))
            <p><strong>Filtré par date :</strong> {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire (GNF)</th>
                <th>Total (GNF)</th>
                <th>Date d’approvisionnement</th>
            </tr>
        </thead>
        <tbody>
        @php $grandTotal = 0; @endphp
        @forelse ($approvisionnements as $appro)
            @php $grandTotal += $appro->total; @endphp
            <tr>
                <td>{{ $appro->produit->designation }}</td>
                <td>{{ $appro->quantite }}</td>
                <td>{{ number_format($appro->prix_unitaire, 2, ',', ' ') }}</td>
                <td>{{ number_format($appro->total, 2, ',', ' ') }}</td>
                <td>{{ \Carbon\Carbon::parse($appro->date)->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center">Aucun approvisionnement trouvé.</td>
            </tr>
        @endforelse
        </tbody>

        @if($approvisionnements->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td class="fw-bold text-end">Total général :</td>
                <td class="fw-bold">{{ number_format($grandTotal, 2, ',', ' ') }} GNF</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Cabinet de gestion de stock — Reçu généré automatiquement
    </div>
</body>
</html>
