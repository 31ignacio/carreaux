<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Facture;
use App\Models\Produit;
use App\Models\RemboursementClient;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $today = $now->toDateString();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now;
        $monthStartDate = $monthStart->toDateString();

        $caJourCaissier = Facture::ventes()->where('statut', 'payee')
            ->whereDate('created_at', $today)
            ->sum('total');

        $caSemaineTotal = Facture::ventes()->where('statut', 'payee')
            ->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
            ->sum('total');

        $caMois = Facture::ventes()->where('statut', 'payee')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total');

        $totalProduits = Produit::where('actif', true)->count();

        $topProduits = DB::table('facture_lignes')
            ->join('factures', 'facture_lignes.facture_id', '=', 'factures.id')
            ->join('produits', 'facture_lignes.produit_id', '=', 'produits.id')
            ->where('factures.nature', 'vente')
            ->where('factures.statut', 'payee')
            ->whereBetween('factures.created_at', [$monthStart, $monthEnd])
            ->select(
                'produits.libelle',
                DB::raw('SUM(facture_lignes.quantite) as total_vendu'),
                DB::raw('SUM(facture_lignes.sous_total) as total_ca')
            )
            ->groupBy('produits.id', 'produits.libelle')
            ->orderByDesc('total_vendu')
            ->limit(5)
            ->get();

        $chartStart = $now->copy()->subDays(7);
        $caParJour = Facture::ventes()->where('statut', 'payee')
            ->whereBetween('created_at', [$chartStart, $now])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $reglementsParFacture = DB::table('reglements')
            ->where('created_at', '<=', $monthEnd)
            ->select('facture_id')
            ->selectRaw('SUM(montant) as montant_regle')
            ->groupBy('facture_id');

        $creditsRestantsMois = DB::table('factures')
            ->leftJoinSub($reglementsParFacture, 'reglements_facture', function ($join) {
                $join->on('reglements_facture.facture_id', '=', 'factures.id');
            })
            ->where('factures.mode_paiement', 'credit')
            ->where('factures.nature', 'vente')
            ->where('factures.statut', '!=', 'annulee')
            ->whereBetween('factures.created_at', [$monthStart, $monthEnd])
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN factures.total - factures.montant_avance - COALESCE(reglements_facture.montant_regle, 0) > 0
                    THEN factures.total - factures.montant_avance - COALESCE(reglements_facture.montant_regle, 0)
                    ELSE 0 END), 0) as reste'
            )
            ->value('reste');

        $remboursementsMois = RemboursementClient::whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('montant');

        $depensesNettesMois = Depense::whereNull('annulee_at')
            ->whereBetween('date_depense', [$monthStartDate, $today])
            ->sum('montant');

        $mouvementsAvancesNetsMois = DB::table('mouvements_avances')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN type = 'depot' THEN montant WHEN type = 'utilisation' THEN -montant ELSE 0 END), 0) as net"
            )
            ->value('net');

        return view('dashboard.index', [
            'caJourCaissier' => $caJourCaissier,
            'caSemaineTotal' => $caSemaineTotal,
            'caMois' => $caMois,
            'totalProduits' => $totalProduits,
            'topProduits' => $topProduits,
            'caParJour' => $caParJour,
            'creditsRestantsMois' => $creditsRestantsMois,
            'remboursementsMois' => $remboursementsMois,
            'depensesNettesMois' => $depensesNettesMois,
            'mouvementsAvancesNetsMois' => $mouvementsAvancesNetsMois,
            'periodeMoisDebut' => $monthStart,
            'periodeMoisFin' => $monthEnd,
        ]);
    }
}
