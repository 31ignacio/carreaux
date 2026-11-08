<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'statut' => ['nullable', 'in:dette,a_jour'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date'],
        ]);
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $request->validate(['date_fin' => ['after_or_equal:date_debut']]);
        }

        $reglements = DB::table('reglements')
            ->select('facture_id')
            ->selectRaw('COALESCE(SUM(montant), 0) as rembourse')
            ->groupBy('facture_id');

        $credits = DB::table('factures')
            ->leftJoinSub($reglements, 'paiements_credit', 'paiements_credit.facture_id', '=', 'factures.id')
            ->where('factures.mode_paiement', 'credit')
            ->where('factures.nature', 'vente')
            ->where('factures.statut', '!=', 'annulee');

        if (!empty($filters['date_debut'])) $credits->whereDate('factures.created_at', '>=', $filters['date_debut']);
        if (!empty($filters['date_fin'])) $credits->whereDate('factures.created_at', '<=', $filters['date_fin']);

        $creditStats = (clone $credits)
            ->select('factures.client_id')
            ->selectRaw('COALESCE(SUM(factures.total - factures.montant_avance), 0) as credits_initiaux')
            ->selectRaw('COALESCE(SUM(paiements_credit.rembourse), 0) as remboursements_total')
            ->selectRaw('COALESCE(SUM(factures.total - factures.montant_avance - COALESCE(paiements_credit.rembourse, 0)), 0) as solde_restant')
            ->selectRaw('MAX(factures.created_at) as derniere_dette')
            ->groupBy('factures.client_id');

        $clientsQuery = Client::query()
            ->joinSub($creditStats, 'credits_client', 'credits_client.client_id', '=', 'clients.id')
            ->select('clients.*', 'credits_client.credits_initiaux', 'credits_client.remboursements_total', 'credits_client.solde_restant', 'credits_client.derniere_dette')
            ->with(['factures' => function ($query) use ($filters) {
                $query->where('mode_paiement', 'credit')->where('statut', '!=', 'annulee')
                    ->when(!empty($filters['date_debut']), fn ($q) => $q->whereDate('created_at', '>=', $filters['date_debut']))
                    ->when(!empty($filters['date_fin']), fn ($q) => $q->whereDate('created_at', '<=', $filters['date_fin']))
                    ->withSum('reglements', 'montant')
                    ->orderByDesc('created_at')->orderByDesc('id');
            }]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $clientsQuery->where(fn ($query) => $query->where('clients.nom', 'like', "%{$search}%")
                ->orWhere('clients.prenom', 'like', "%{$search}%")
                ->orWhereHas('factures', function ($invoices) use ($search, $filters) {
                    $invoices->where('mode_paiement', 'credit')->where('statut', '!=', 'annulee')
                        ->where('numero', 'like', "%{$search}%")
                        ->when(!empty($filters['date_debut']), fn ($q) => $q->whereDate('created_at', '>=', $filters['date_debut']))
                        ->when(!empty($filters['date_fin']), fn ($q) => $q->whereDate('created_at', '<=', $filters['date_fin']));
                }));
        }

        if (($filters['statut'] ?? null) === 'dette') $clientsQuery->where('credits_client.solde_restant', '>', 0);
        if (($filters['statut'] ?? null) === 'a_jour') $clientsQuery->where('credits_client.solde_restant', '<=', 0);

        // Most recent credit debt first, then newest invoices first in each client table.
        $clients = $clientsQuery->orderByDesc('credits_client.derniere_dette')->paginate(20)->withQueryString();

        $totaux = (clone $credits)
            ->selectRaw('COALESCE(SUM(factures.total - factures.montant_avance), 0) as initial')
            ->selectRaw('COALESCE(SUM(paiements_credit.rembourse), 0) as rembourse')
            ->first();
        $soldeGlobal = max((float) $totaux->initial - (float) $totaux->rembourse, 0);

        return view('credits.index', compact('clients', 'totaux', 'soldeGlobal', 'filters'));
    }
}
