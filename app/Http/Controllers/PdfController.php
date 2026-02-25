<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Approvisionnement;

class PdfController extends Controller
{
    public function venteRecu($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $vente = Vente::with('produit')->findOrFail($id);
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'Recu de Vente',0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Ln(5);
        $pdf->Cell(0,10,'Produit : ' . $vente->produit->designation,0,1);
        $pdf->Cell(0,10,'Quantite : ' . $vente->quantite,0,1);
        $pdf->Cell(0,10,'Prix unitaire : ' . $vente->produit->prix_vente . ' GNF',0,1);
        $pdf->Cell(0,10,'Total : ' . ($vente->quantite * $vente->produit->prix_vente) . ' GNF',0,1);
        $pdf->Cell(0,10,'Date : ' . $vente->date_vente,0,1);

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="recu_vente_'.$id.'.pdf"');
    }

    public function approvisionnementRecu($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');
    
        $appro = Approvisionnement::with('produit')->findOrFail($id);
        $pdf = new \FPDF();
        $pdf->AddPage();
    
        // Encodage UTF-8 -> ISO pour FPDF
        function encode($text) {
            return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        }
    
        // Style
        $primaryColor = [13, 110, 253];
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0, 10, encode("📦 Reçu d'approvisionnement"), 0, 1, 'C');
    
        // Ligne
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(10, 20, 200, 20);
        $pdf->Ln(6);
    
        // Informations
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $lineHeight = 8;
    
        $fields = [
            "Référence Approvisionnement" => $appro->id,
            "Produit" => $appro->produit->designation,
            "Quantité" => $appro->quantite,
            "Prix Unitaire (GNF)" => number_format($appro->prix_unitaire, 2, ',', ' '),
            "Total (GNF)" => number_format($appro->total, 2, ',', ' '),
            "Date Approvisionnement" => \Carbon\Carbon::parse($appro->date)->format('d/m/Y'),
        ];
    
        foreach ($fields as $label => $value) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(60, $lineHeight, encode($label . ' :'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, $lineHeight, encode($value), 0, 1);
        }
    
        // Bloc encadré informatif
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetFillColor(235, 245, 255);
        $pdf->MultiCell(0, 7, encode("Ce reçu confirme que l'approvisionnement a été bien enregistré dans le système de gestion de stock."), 1, 'L', true);
    
        // On garde de l'espace contrôlé avant le footer
        $pdf->Ln(5);
    
        // FOOTER toujours sur la même page
        $pdf->SetY(-25); 
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell(0, 10, encode("© " . date('Y') . " Cabinet de gestion de stock — Reçu généré automatiquement."), 0, 0, 'C');
    
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="recu_appro_' . $id . '.pdf"');
    }

}
