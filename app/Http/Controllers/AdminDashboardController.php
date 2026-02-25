<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\User;
use App\Models\Vente;
use App\Models\Approvisionnement;
class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $produits_count = Produit::count();
        $ventes_count = Vente::count();
        $total_approvisionnements = Approvisionnement::sum('quantite');
        $employes_count = User::where('role', 'employe')->count();
    
        // Produits en stock faible (quantité < seuil_alerte)
        $produits_faibles = Produit::whereColumn('stock_actuel', '<', 'seuil_alerte')->get();
    
      // Labels pour les 7 derniers jours
        $labelsSemaine = [];
        $ventesSemaine = [];
        $approsSemaine = [];

        foreach (range(6, 0) as $i) {
            $day = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelsSemaine[] = Carbon::parse($day)->format('d/m');
            $ventesSemaine[] = Vente::whereDate('created_at', $day)->count();
            $approsSemaine[] = Approvisionnement::whereDate('created_at', $day)->sum('quantite');
        }
    
        // Labels pour les 6 derniers mois
        $labelsMois = [];
        $ventesMois = [];
        $approsMois = [];
    
        foreach (range(5, 0) as $i) {
            $mois = Carbon::now()->subMonths($i);
            $labelsMois[] = $mois->format('M Y');
            $ventesMois[] = Vente::whereMonth('created_at', $mois->month)
                                ->whereYear('created_at', $mois->year)
                                ->count();
            $approsMois[] = Approvisionnement::whereMonth('created_at', $mois->month)
                                ->whereYear('created_at', $mois->year)
                                ->sum('quantite');
        }
    
        // Labels pour les 5 dernières années
        $labelsAnnee = [];
        $ventesAnnee = [];
        $approsAnnee = [];
    
        foreach (range(4, 0) as $i) {
            $year = Carbon::now()->subYears($i)->year;
            $labelsAnnee[] = $year;
            $ventesAnnee[] = Vente::whereYear('created_at', $year)->count();
            $approsAnnee[] = Approvisionnement::whereYear('created_at', $year)->sum('quantite');
        }

        return view('administration.pages.dashboard', compact(
            'produits_count',
            'ventes_count',
            'total_approvisionnements',
            'employes_count',
            'produits_faibles',
            'labelsSemaine', 'ventesSemaine', 'approsSemaine',
            'labelsMois', 'ventesMois', 'approsMois',
            'labelsAnnee', 'ventesAnnee', 'approsAnnee'
        ));
    }
}