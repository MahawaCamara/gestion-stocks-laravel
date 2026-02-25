<?php

namespace App\Exports;

use App\Models\Produit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProduitsExport implements FromCollection, WithHeadings
{
    protected $produits;

    public function __construct($produits)
    {
        $this->produits = $produits;
    }

    public function collection()
    {
        return $this->produits->map(function($produit) {
            return [
                'ID' => $produit->id,
                'Désignation' => $produit->designation,
                'Prix Vente' => $produit->prix_vente,
                'Stock Actuel' => $produit->stock_actuel,
                'Seuil Alerte' => $produit->seuil_alerte,
                'Dernière mise à jour' => $produit->updated_at->format('d/m/Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Désignation',
            'Prix Vente (GNF)',
            'Stock Actuel',
            'Seuil Alerte',
            'Dernière mise à jour',
        ];
    }
}
