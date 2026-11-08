<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RemboursementClientController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\AvanceClientController;
use App\Http\Controllers\BonCommandeController;



Route::prefix('test-errors')->group(function () {
    
    // Page 404 - Not Found
    Route::get('/404', function() {
        abort(404);
    })->name('test.404');
    
    // Page 403 - Forbidden
    Route::get('/403', function() {
        abort(403, 'Vous n\'avez pas les droits nécessaires.');
    })->name('test.403');
    
    // Page 500 - Server Error
    Route::get('/500', function() {
        abort(500);
    })->name('test.500');
    
    // Page 419 - Session Expired
    Route::get('/419', function() {
        abort(419);
    })->name('test.419');
    
    // Page 429 - Too Many Requests
    Route::get('/429', function() {
        abort(429);
    })->name('test.429');
    
    // Page 503 - Maintenance
    Route::get('/503', function() {
        abort(503);
    })->name('test.503');
    
    // Page 401 - Unauthorized
    Route::get('/401', function() {
        abort(401);
    })->name('test.401');
    
    // Test avec message personnalisé 403
    Route::get('/403-custom', function() {
        abort(403, 'Accès réservé aux administrateurs.');
    })->name('test.403.custom');
    
    // Test avec exception personnalisée
    Route::get('/exception', function() {
        throw new \Exception('Une erreur personnalisée est survenue !');
    })->name('test.exception');
    
    // Test 404 avec une URL inexistante
    Route::get('/page-inexistante', function() {
        // Cette route n'existe pas, Laravel renverra automatiquement une 404
        // Mais on peut aussi le faire manuellement
        abort(404);
    })->name('test.404.custom');
});

// Route qui existe pour tester la 404
Route::get('/test-existe', function() {
    return 'Cette page existe !';
})->name('test.existe');

// ✅ Redirection racine
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// ✅ Auth routes
Auth::routes(['register' => false]);

// ✅ Routes protégées
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produits
    
    Route::post('produits/import', [ProduitController::class, 'import'])->name('produits.import');
    Route::get('produits/{produit}/historique', [ProduitController::class, 'historique'])->name('produits.historique');
    Route::delete('produits/bulk-destroy', [ProduitController::class, 'destroyBulk'])->name('produits.bulk-destroy');
    Route::resource('produits', ProduitController::class);
    // Catégories
    Route::resource('categories', CategorieController::class);

    // Stock
   Route::middleware(['role:admin,magasinier,caissier'])->prefix('stock')->name('stock.')->group(function () {
    Route::get('entrees',        [StockController::class, 'entrees'])->name('entrees');
    // Route::get('entrees/create', [StockController::class, 'createEntree'])->name('entrees.create');
    Route::post('entrees',       [StockController::class, 'storeEntree'])->name('entrees.store');
    Route::get('sorties',        [StockController::class, 'sorties'])->name('sorties');
    Route::post('sorties',       [StockController::class, 'storeSortie'])->name('sorties.store');  // ✅
    Route::delete('entrees/{mouvement}', [StockController::class, 'destroyEntree'])->name('entrees.destroy');
     Route::get('/stock/sorties/pdf', [StockController::class, 'sortiesPdf'])->name('sorties.pdf');
Route::get('/stock/sorties',     [StockController::class, 'sorties'])->name('sorties');
});

    // client
    Route::resource('clients', ClientController::class);
    Route::get('credits', [CreditController::class, 'index'])->name('credits.index');
    Route::patch('clients/{client}/toggle-actif', [ClientController::class, 'toggleActif'])->name('clients.toggleActif');
    Route::get('clients-search', [ClientController::class, 'search'])->name('clients.search');
    Route::post('clients/{client}/remboursements', [RemboursementClientController::class, 'store'])->name('clients.remboursements.store');
    Route::post('clients/{client}/avances', [AvanceClientController::class, 'store'])->name('clients.avances.store');
    Route::middleware(['role:admin,magasinier,caissier'])->group(function () {
        Route::get('bons-commandes', [BonCommandeController::class, 'index'])->name('bons-commandes.index');
        Route::get('bons-commandes/{bonCommande}', [BonCommandeController::class, 'show'])->name('bons-commandes.show');
        Route::get('bons-commandes/{bonCommande}/livraisons/{livraison}/imprimer', [BonCommandeController::class, 'deliverySlip'])->name('bons-commandes.livraisons.pdf');
    });
    Route::middleware(['role:admin,magasinier'])->group(function () {
        Route::post('bons-commandes/{bonCommande}/livraisons', [BonCommandeController::class, 'deliver'])->name('bons-commandes.livraisons.store');
        Route::get('bons-commandes/{bonCommande}/reste-a-livrer', [BonCommandeController::class, 'remainingTicket'])->name('bons-commandes.reste');
    });
    Route::get('remboursements', [RemboursementClientController::class, 'index'])->name('remboursements.index');
    Route::get('remboursements/{remboursement}/recu', [RemboursementClientController::class, 'receipt'])->name('remboursements.recus');

    Route::get('depenses', [DepenseController::class, 'index'])->name('depenses.index');
    Route::post('depenses', [DepenseController::class, 'store'])->name('depenses.store');
    Route::get('depenses/{depense}/justificatif', [DepenseController::class, 'justificatif'])->name('depenses.justificatif');
    Route::middleware(['role:admin,superviseur'])->group(function () {
        Route::get('depenses/{depense}/modifier', [DepenseController::class, 'edit'])->name('depenses.edit');
        Route::put('depenses/{depense}', [DepenseController::class, 'update'])->name('depenses.update');
        Route::patch('depenses/{depense}/annuler', [DepenseController::class, 'cancel'])->name('depenses.annuler');
    });
 

    // Caisse
    Route::get('caisse',           [CaisseController::class, 'index'])->name('caisse.index');
    Route::post('caisse/valider',  [CaisseController::class, 'valider'])->name('caisse.valider');
    Route::get('caisse/recherche', [CaisseController::class, 'recherche'])->name('caisse.recherche');
    Route::get('caisse/ticket/{id}', [CaisseController::class, 'ticket'])->name('caisse.ticket');

    // Factures
    Route::resource('factures', FactureController::class)->only(['index', 'show', 'destroy']);
    Route::get('factures/{facture}/pdf',      [FactureController::class, 'pdf'])->name('factures.pdf');
    Route::patch('factures/{facture}/annuler',[FactureController::class, 'annuler'])->name('factures.annuler');
    // Nouvelle terminologie : les documents de vente sont des bons de livraison.
    Route::middleware(['role:admin,magasinier,caissier'])->group(function () {
    Route::get('bons-livraison', [FactureController::class, 'index'])->name('bons-livraison.index');
    Route::get('bons-livraison/{facture}', [FactureController::class, 'show'])->name('bons-livraison.show');
    });
    Route::delete('bons-livraison/{facture}', [FactureController::class, 'destroy'])->name('bons-livraison.destroy');
    Route::get('bons-livraison/{facture}/pdf', [FactureController::class, 'pdf'])->name('bons-livraison.pdf');
    Route::patch('bons-livraison/{facture}/annuler', [FactureController::class, 'annuler'])->name('bons-livraison.annuler');
    // Factures crédit
    // Route::get('factures/credits/credits',           [FactureController::class, 'credits'])->name('factures.credits');
    // Route::post('factures/{facture}/regler', [FactureController::class, 'regler'])->name('factures.regler');
    // Fournisseurs
    Route::resource('fournisseurs', FournisseurController::class);

    // Inventaires
    Route::resource('inventaires', InventaireController::class);
    Route::patch('inventaires/{inventaire}/cloturer', [InventaireController::class, 'cloturer'])->name('inventaires.cloturer');
    Route::get('inventaires/{inventaire}/pdf', [InventaireController::class, 'pdf'])->name('inventaires.pdf');
    Route::get('/inventaires/{inventaire}/pdf-comptage',[InventaireController::class, 'pdfSansTheorique'])->name('inventaires.pdf_sans_theorique');

    // Alertes
    Route::get('alertes',                    [AlerteController::class, 'index'])->name('alertes.index');
    Route::patch('alertes/{alerte}/traiter', [AlerteController::class, 'traiter'])->name('alertes.traiter');
    Route::patch('alertes/traiter-tout',     [AlerteController::class, 'traiterTout'])->name('alertes.traiter-tout');

    // Rapports
    Route::get('rapports',             [RapportController::class, 'index'])->name('rapports.index');
    Route::get('rapports/ventes',      [RapportController::class, 'ventes'])->name('rapports.ventes');
    Route::get('rapports/stock',       [RapportController::class, 'stock'])->name('rapports.stock');
    Route::get('rapports/finances', [RapportController::class, 'finances'])->name('rapports.finances');
    Route::get('/export-pdf', [RapportController::class, 'exportPdf'])->name('rapports.export-pdf');

    Route::get('rapports/export-excel', [RapportController::class, 'exportExcel'])->name('rapports.export-excel');

    // Users (admin seulement)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        
    });
});
