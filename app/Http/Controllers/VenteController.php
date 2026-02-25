<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vente::with('user')->orderByDesc('date');

        if ($request->filled('numero_facture')) {
            $query->where('numero_facture', 'like', '%' . $request->numero_facture . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $ventes = $query->paginate(10);
        return view('administration.pages.ventes.index', compact('ventes'));
    }

    public function create(Request $request)
    {
        $query = Produit::query();

        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }

        $produits = $query->orderBy('designation')->paginate(5);

        return view('administration.pages.ventes.create', compact('produits'));
    }


    public function store(Request $request)
{
    $request->validate([
        'produits.*.id' => 'required|exists:produits,id',
        'produits.*.quantite' => 'required|integer|min:0',
    ]);

    $total = 0;
    $lignes = [];

    // Filtrer les produits avec une quantité > 0
    foreach ($request->produits as $item) {
        $quantite = (int) $item['quantite'];

        if ($quantite < 1) continue;

        $produit = Produit::findOrFail($item['id']);

        // Validation stock suffisant
        if ($produit->stock_actuel < $quantite) {
            return back()->with('error', "🚫 Stock insuffisant pour le produit : {$produit->designation}");
        }

        // Validation seuil d'alerte (on interdit la vente si stock après vente < seuil)
        if (($produit->stock_actuel - $quantite) < $produit->seuil_alerte) {
            return back()->with('error', "🚫 La vente du produit \"{$produit->designation}\" ferait passer le stock en dessous du seuil critique.");
        }

        $sous_total = $produit->prix_vente * $quantite;
        $total += $sous_total;

        $lignes[] = [
            'produit_id' => $produit->id,
            'quantite' => $quantite,
            'prix_unitaire' => $produit->prix_vente,
            'total' => $sous_total,
        ];
    }

    if (count($lignes) === 0) {
        return back()->with('error', 'Aucun produit avec une quantité valide n\'a été sélectionné.');
    }

    DB::beginTransaction();

    try {
        $vente = Vente::create([
            'numero_facture' => 'VF-' . date('YmdHis') . '-' . Str::random(4),
            'user_id' => auth()->id(),
            'montant_total' => $total,
            'date' => now()->toDateString(),
        ]);

        foreach ($lignes as $ligne) {
            $ligne['vente_id'] = $vente->id;
            LigneVente::create($ligne);

            // Mise à jour stock
            $produit = Produit::findOrFail($ligne['produit_id']);
            $produit->decrement('stock_actuel', $ligne['quantite']);
        }

        DB::commit();

        // Vérifie les seuils après mise à jour du stock
        $alertes = [];
        foreach ($lignes as $ligne) {
            $produit = Produit::find($ligne['produit_id']);
            if ($produit->stock_actuel <= $produit->seuil_alerte) {
                $alertes[] = "⚠️ Le stock du produit '{$produit->designation}' est en dessous du seuil d'alerte ! (Stock actuel : {$produit->stock_actuel})";
            }
        }

        return redirect()->route('ventes.index')->with([
            'success' => '✅ Vente enregistrée avec succès.',
            'alertes' => $alertes
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', '❌ Une erreur est survenue : ' . $e->getMessage());
    }
}

    public function ventesParEmploye()
    {
        $employes = User::with('ventes.produit')->get();
        return view('ventes.par-employe', compact('employes'));
    }


    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function exportPDF($id)
    {
        $vente = Vente::findOrFail($id);
        $pdf = new \Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Reçu de Vente', 0, 1, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Produit : {$vente->produit->nom}", 0, 1);
        $pdf->Cell(0, 10, "Quantité : {$vente->quantite}", 0, 1);
        $pdf->Cell(0, 10, "Total : {$vente->total} FCFA", 0, 1);
        $pdf->Output();
        exit;
    }
    public function recapitulatif()
    {
        $ventesJour = Vente::with('produit', 'user')
            ->whereDate('date', today())
            ->get();

        $totalJour = $ventesJour->sum('total');

        return view('ventes.recapitulatif', compact('ventesJour', 'totalJour'));
    }

    public function facturePdf($id)
    {
        $vente = Vente::with('ligneVentes.produit', 'user')->findOrFail($id);
        $pdf = Pdf::loadView('administration.pages.ventes.facture', compact('vente'));
        return $pdf->download('facture_' . $vente->numero_facture . '.pdf');
    }

    public function filtrer(Request $request)
    {
        $query = Vente::with('produit', 'user');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('employe')) {
            $query->where('user_id', $request->employe);
        }

        $ventes = $query->get();
        $employes = User::all();

        return view('ventes.filtre', compact('ventes', 'employes'));
    }
    public function recuDetailAjax($id)
    {
        $vente = Vente::with(['user', 'lignes.produit'])->findOrFail($id);
        return view('administration.pages.recus._recu_detail_modal', compact('vente'));
    }
}
