<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Approvisionnement;
use Illuminate\Http\Request;
use App\Models\Produit;
class ApprovisionnementController extends Controller
{

    public function index(Request $request)
    {
        $produitsRupture = Produit::where('stock_actuel', '<', 10)->get();
    
        $query = Approvisionnement::with('produit')->latest();
    
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }
    
        if ($request->filled('date')) {
            $query->whereDate('date_approvisionnement', $request->date);
        }
    
        return view('administration.pages.approvisionnements.index', [
        'produitsList' => Produit::all(),
        'produitsRupture' => $produitsRupture,
        'approvisionnements' => $query->paginate(10)
       ]);
    }
    public function create()
    {
        
        $produits = Produit::all();
    
        return view('administration.pages.approvisionnements.create', compact('produits'));
    }

    public function exportPDF(Request $request)
    {   
        dd('OK', $request->all());
        $query = Approvisionnement::with('produit');
    
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }
    
        if ($request->filled('date')) {
            $query->whereDate('date_approvisionnement', $request->date);
        }
    
        $approvisionnements = $query->get();
    
        $pdf = Pdf::loadView('administration.pages.approvisionnements.pdf', compact('approvisionnements'));
    
        return $pdf->download('historique_approvisionnements.pdf');
    }


    
    public function update(Request $request, $id)
    {
       
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date' => 'required|date'
        ]);
    
        DB::transaction(function () use ($request) {
            $total = $request->quantite * $request->prix_unitaire;
    
            // 1. Créer l’approvisionnement
            Approvisionnement::create([
                'produit_id' => $request->produit_id,
                'quantite' => $request->quantite,
                'prix_unitaire' => $request->prix_unitaire,
                'total' => $total,
                'date' => $request->date,
            ]);
    
            // 2. Mettre à jour le stock du produit
            $produit = Produit::find($request->produit_id);
            $produit->stock_actuel += $request->quantite;
            $produit->save();
        });
    
        return back()->with('success', 'Approvisionnement effectué avec succès.');
    }

    public function show($id)
    {
        $approvisionnement = Approvisionnement::with('produit')->findOrFail($id);
    
        return view('administration.pages.approvisionnements.show', compact('approvisionnement'));
    }

    public function destroy(Approvisionnement $approvisionnement)
    {
        $approvisionnement->delete();
        return redirect()->route('approvisionnements.index')->with('success', 'Approvisionnement supprimé avec succès.');
    }


}
