<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;   // ← façade DomPDF
class StockController extends Controller
{
   public function entrees(Request $request)
    {
        $query = MouvementStock::with(['produit', 'fournisseur', 'user'])
            ->where('type', 'like', '%entree%')
            ->when($request->search, function($q, $v) {
                $q->whereHas('produit', fn($p) => $p->where('libelle', 'like', "%$v%")
                    ->orWhere('reference', 'like', "%$v%"));
            })
            ->when($request->date_debut, fn($q,$v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->date_fin,   fn($q,$v) => $q->whereDate('created_at', '<=', $v))
            ->latest();

        // ✅ Stats calculées sur le même query (avant pagination)
        $statsQuery = clone $query;
        $statsItems = $statsQuery->get();

        $stats = [
            'total'     => $statsItems->count(),
            'quantite'  => $statsItems->sum('quantite'),
            'valeur'    => $statsItems->sum(fn($m) => $m->quantite * $m->prix_unitaire),
            'jours'     => $statsItems->groupBy(fn($m) => $m->created_at->format('d/m/Y'))->count(),
        ];

        $mouvements = $query->paginate(20)->withQueryString();
        $produits     = Produit::where('actif', true)->orderBy('libelle')->get();
        $fournisseurs = Fournisseur::where('actif', true)->orderBy('nom')->get();

        return view('stock.entrees', compact('mouvements', 'stats','produits','fournisseurs'));
    }

public function storeEntree(Request $request)
{
    $request->validate([
        'produit_id'     => 'required|exists:produits,id',
        'fournisseur_id' => 'nullable|exists:fournisseurs,id',
        'quantite'       => 'required|numeric|min:0.01',
        'reference_doc'  => 'nullable|string|max:255',
    ]);
 
    $produit = Produit::findOrFail($request->produit_id);
 
    $produit->entreeStock(
        $request->quantite,
        0,
        auth()->id(),
        $request->fournisseur_id,
        $request->motif       ?? null,
        $request->reference_doc ?? null
    );
 
    return redirect()->route('stock.entrees')
        ->with('success', 'Entrée de stock enregistrée avec succès.');
}

    // public function sorties(Request $request)
    // {
    //     $query = MouvementStock::with(['produit', 'user'])
    //         ->where('type', 'not like', '%entree%')
    //         ->when($request->search, function($q, $v) {
    //             $q->whereHas('produit', fn($p) => $p->where('libelle', 'like', "%$v%")
    //                 ->orWhere('reference', 'like', "%$v%"))
    //             ->orWhere('motif', 'like', "%$v%");
    //         })
    //         ->when($request->type,       fn($q,$v) => $q->where('type', $v))
    //         ->when($request->date_debut, fn($q,$v) => $q->whereDate('created_at', '>=', $v))
    //         ->when($request->date_fin,   fn($q,$v) => $q->whereDate('created_at', '<=', $v))
    //         ->latest();

    //     // Stats sur le filtre complet (avant pagination)
    //     $statsItems = (clone $query)->get();
    //     $stats = [
    //         'total'    => $statsItems->count(),
    //         'quantite' => $statsItems->sum('quantite'),
    //         'valeur'   => $statsItems->sum(fn($m) => $m->quantite * $m->prix_unitaire),
    //         'jours'    => $statsItems->groupBy(fn($m) => $m->created_at->format('d/m/Y'))->count(),
    //     ];

    //     $mouvements = $query->paginate(20)->withQueryString();
        

    //     return view('stock.sorties', compact('mouvements', 'stats'));
    // }



// ────────────────────────────────────────────────────────────────────
//  Méthode principale : liste paginée + cumul + stats
// ────────────────────────────────────────────────────────────────────
public function sorties(Request $request)
{
    $query = MouvementStock::with(['produit', 'user'])
        ->where('type', 'not like', '%entree%')
        ->when($request->search, function ($q, $v) {
            $q->whereHas('produit', fn($p) => $p
                ->where('libelle',   'like', "%$v%")
                ->orWhere('reference', 'like', "%$v%"))
              ->orWhere('motif', 'like', "%$v%");
        })
        ->when($request->type,       fn($q, $v) => $q->where('type', $v))
        ->when($request->date_debut, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
        ->when($request->date_fin,   fn($q, $v) => $q->whereDate('created_at', '<=', $v))
        ->latest();

    // Stats globales sur la sélection filtrée (avant pagination)
    $statsItems = (clone $query)->get();

    $stats = [
        'total'    => $statsItems->count(),
        'quantite' => $statsItems->sum('quantite'),
        'valeur'   => $statsItems->sum(fn($m) => $m->quantite * $m->prix_unitaire),
        'jours'    => $statsItems->groupBy(fn($m) => $m->created_at->format('d/m/Y'))->count(),
    ];

    // ── Cumul par produit (affiché uniquement quand filtre actif) ──
    $cumul = null;
    $filtreActif = $request->hasAny(['search', 'type', 'date_debut', 'date_fin']);

    if ($filtreActif) {
        $cumul = $statsItems
            ->groupBy('produit_id')
            ->map(function ($lignes) {
                $first = $lignes->first();
                return [
                    'libelle'       => $first->produit?->libelle ?? 'Produit supprimé',
                    'reference'     => $first->produit?->reference ?? '',
                    'unite'         => $first->produit?->unite ?? '',
                    'quantite_tot'  => $lignes->sum('quantite'),
                    'valeur_tot'    => $lignes->sum(fn($m) => $m->quantite * $m->prix_unitaire),
                    'nb_mouvements' => $lignes->count(),
                ];
            })
            ->sortByDesc('valeur_tot')
            ->values();
    }

    $mouvements = $query->paginate(20)->withQueryString();

    return view('stock.sorties', compact('mouvements', 'stats', 'cumul', 'filtreActif'));
}

// ────────────────────────────────────────────────────────────────────
//  Méthode export PDF
// ────────────────────────────────────────────────────────────────────
public function sortiesPdf(Request $request)
{
    $query = MouvementStock::with(['produit', 'user'])
        ->where('type', 'not like', '%entree%')
        ->when($request->search,     fn($q, $v) => $q->whereHas('produit', fn($p) => $p->where('libelle', 'like', "%$v%")))
        ->when($request->type,       fn($q, $v) => $q->where('type', $v))
        ->when($request->date_debut, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
        ->when($request->date_fin,   fn($q, $v) => $q->whereDate('created_at', '<=', $v))
        ->latest();

    $mouvements = $query->get();

    // Cumul par produit
    $cumul = $mouvements
        ->groupBy('produit_id')
        ->map(function ($lignes) {
            $first = $lignes->first();
            return [
                'libelle'       => $first->produit?->libelle ?? 'Produit supprimé',
                'reference'     => $first->produit?->reference ?? '',
                'unite'         => $first->produit?->unite ?? '',
                'quantite_tot'  => $lignes->sum('quantite'),
                'valeur_tot'    => $lignes->sum(fn($m) => $m->quantite * $m->prix_unitaire),
                'nb_mouvements' => $lignes->count(),
            ];
        })
        ->sortByDesc('valeur_tot')
        ->values();

    $stats = [
        'total'    => $mouvements->count(),
        'quantite' => $mouvements->sum('quantite'),
        'valeur'   => $mouvements->sum(fn($m) => $m->quantite * $m->prix_unitaire),
    ];

    $filtres = [
        'date_debut' => $request->date_debut,
        'date_fin'   => $request->date_fin,
        'type'       => $request->type,
        'search'     => $request->search,
    ];

    $pdf = Pdf::loadView('stock.sorties_pdf', compact('cumul', 'mouvements', 'stats', 'filtres'))
              ->setPaper('a4', 'portrait');

    $filename = 'sorties_stock_' . now()->format('Ymd_Hi') . '.pdf';

    return $pdf->download($filename);
}

   public function storeSortie(Request $request)
{
    $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'quantite'   => 'required|numeric|min:0.01',
        'type'       => 'required|in:sortie,perte,retour,ajustement',
        'motif'      => 'nullable|string|max:255',
    ]);

    $produit = Produit::findOrFail($request->produit_id);

    if ((float) $request->quantite > (float) $produit->stock_actuel) {
        return response()->json([
            'error' => 'Quantité demandée (' . $request->quantite . ') supérieure au stock disponible ('
                     . $produit->stock_actuel . ' ' . $produit->unite . ').'
        ], 422);
    }

    $prix = (float) ($produit->prix_detail ?? $produit->prix_achat ?? 0);

    $produit->sortieStock(
        (float) $request->quantite,
        $prix,
        auth()->id(),
        $request->motif,
        null,
        $request->type
    );

    return response()->json(['success' => true]);
}


    // StockController
    public function destroyEntree(MouvementStock $mouvement)
    {
        if (!str_contains($mouvement->type, 'entree')) {
            return back()->with('error', 'Action non autorisée.');
        }

        try {
            $produit = $mouvement->produit;

            // ── Vérification stock suffisant ──
            if ($mouvement->quantite > $produit->stock_actuel) {
                return back()->with('error',
                    'Impossible de supprimer cette entrée : le stock actuel (' .
                    $produit->stock_actuel . ' ' . $produit->unite .
                    ') est inférieur à la quantité de l\'entrée (' .
                    $mouvement->quantite . ' ' . $produit->unite . ').'
                );
            }

            $produit->decrement('stock_actuel', $mouvement->quantite);
            $mouvement->delete();

            return back()->with('success',
                'Entrée supprimée et stock mis à jour (-' .
                $mouvement->quantite . ' ' . $produit->unite . ').'
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
