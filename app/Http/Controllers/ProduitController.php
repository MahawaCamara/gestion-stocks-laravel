<?php

namespace App\Http\Controllers;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Exports\ProduitsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produit::with('categorie');
    
        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }
    
        $produits = $query->orderBy('designation')->paginate(10); 
        // garde les filtres en pagination
        $produits->appends($request->all()); 
    
        return view('administration.pages.produits.liste', compact('produits'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('administration.pages.produits.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Vérifie si le produit existe déjà (même désignation et même catégorie)
        $exists = Produit::where('designation', $request->designation)
            ->where('categorie_id', $request->categorie_id)
            ->exists();
    
        if ($exists) {
            // Redirection avec un message d'erreur pour l'alerte
            return back()->with('error', 'Ce produit existe déjà dans cette catégorie.');
        }
    
        // Validation normale
        $request->validate([
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'prix_vente' => 'required|numeric|min:0',
            'stock_actuel' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
        ]);
    
        Produit::create($request->all());
    
        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

 
   
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
    public function edit(Produit $produit)
    {
         $categories = Categorie::all();
        return view('administration.pages.produits.edit', compact('produit', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {   
         $produit->update($request->all());
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
         $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }


    public function etatStock(Request $request)
    {
        $query = Produit::query();

        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }

        if ($request->has('stock_faible')) {
            $query->whereColumn('stock_actuel', '<', 'seuil_alerte');
        }

        $produits = $query->orderBy('stock_actuel', 'asc')->paginate(15)->withQueryString();

        return view('administration.pages.stocks.index', compact('produits'));
    }

    public function exportExcel(Request $request)
    {
        $query = Produit::query();

        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }

        if ($request->has('stock_faible')) {
            $query->whereColumn('stock_actuel', '<', 'seuil_alerte');
        }

        $produits = $query->orderBy('stock_actuel', 'asc')->get();

        return Excel::download(new ProduitsExport($produits), 'etat_stock.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Produit::query();

        if ($request->filled('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%');
        }

        if ($request->has('stock_faible')) {
            $query->whereColumn('stock_actuel', '<', 'seuil_alerte');
        }

        $produits = $query->orderBy('stock_actuel', 'asc')->get();

        $pdf = Pdf::loadView('administration.pages.stocks.pdf', compact('produits'));

        return $pdf->download('etat_stock.pdf');
    }
}
