<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminDashboardController,
    AdminEmployeController,
    ApprovisionnementController,
    CategorieController,
    DashboardController,
    EmployeDashboardController,
    PdfController,
    ProduitController,
    ProfileController,
    UserController,
    VenteController
};

// Redirection vers login par défaut
Route::get('/', fn() => redirect()->route('login'));

// Redirection post-authentification selon le rôle
Route::get('/redirect', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('redirect');


// ========== AUTHENTIFICATION ========== //
require __DIR__ . '/auth.php';


// ========== ADMIN ========= //
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});


// ========== EMPLOYÉ ========= //
Route::middleware(['auth', 'employe'])->prefix('employe')->name('employe.')->group(function () {
    Route::get('/dashboard', [EmployeDashboardController::class, 'index'])->name('dashboard');
});


// ========== PROFIL UTILISATEUR ========= //
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ========== GESTION DES RESSOURCES ========= //
Route::middleware(['auth'])->group(function () {
    Route::resource('produits', ProduitController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('approvisionnements', ApprovisionnementController::class);
    Route::resource('ventes', VenteController::class);
    // Gestion des employés
    Route::get('/employes', [AdminEmployeController::class, 'index'])->name('employes.index');
    Route::get('/employes/create', [AdminEmployeController::class, 'create'])->name('employes.create');
    Route::post('/employes', [AdminEmployeController::class, 'store'])->name('employes.store');
    Route::delete('/employes/{employe}', [AdminEmployeController::class, 'destroy'])->name('employes.destroy');
    Route::post('/employes/{user}/toggle', [AdminEmployeController::class, 'toggleStatus'])->name('employes.toggle');
});

Route::get('/categories/list', [CategorieController::class, 'list'])->name('categories.list');

// ========== PDF REÇUS ========= //
Route::get('/recu/vente/{id}', [PdfController::class, 'venteRecu']);
Route::get('/recu/approvisionnement/{id}', [PdfController::class, 'approvisionnementRecu']);


// ========== PRODUIT (par categorie) ========= //
Route::resource('categories', CategorieController::class)->only(['store']);
Route::get('/approvisionnements/pdf', [ApprovisionnementController::class, 'exportPDF'])->name('approvisionnements.pdf');
Route::get('/ventes/facture/{vente}', [VenteController::class, 'facture'])->name('ventes.facture');
Route::get('/ventes/filtrer', [VenteController::class, 'filtrer'])->name('ventes.filtrer');
Route::get('ventes/{id}/facture-pdf', [VenteController::class, 'facturePdf'])->name('ventes.facture.pdf');

// Route pour voir l'etat de stock
Route::get('/stocks', [ProduitController::class, 'etatStock'])->name('stocks.index');
Route::get('/stocks/export-excel', [ProduitController::class, 'exportExcel'])->name('stocks.exportExcel');
Route::get('/stocks/export-pdf', [ProduitController::class, 'exportPdf'])->name('stocks.exportPdf');


// // Liste des ventes
// Route::get('/recus/reçus', [VenteController::class, 'recus'])->name('ventes.recus');
// // Détail d'une vente
// Route::get('/recus/{id}/ajax-detail', [VenteController::class, 'recuDetailAjax'])->name('ventes.recu.detail.ajax');
// // Export PDF d'une vente
// Route::get('/recus/{id}/reçu-pdf', [VenteController::class, 'recuPdf'])->name('ventes.recu.pdf');
