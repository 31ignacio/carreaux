<?php

namespace App\Http\Controllers;


use App\Models\Facture;
use App\Models\Produit;
use App\Models\MouvementStock;
use App\Models\Depense;
use App\Models\RemboursementClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    public function index()
    {
        return view('rapports.index');
    }

    public function ventes(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->get('date_fin', now()->format('Y-m-d'));

        $factures = Facture::ventes()->with(['lignes', 'user'])
            ->where('statut', 'payee')
            ->whereDate('created_at', '>=', $dateDebut)
            ->whereDate('created_at', '<=', $dateFin)
            ->get();

        $totalCA      = $factures->sum('total');
        $totalRemises = $factures->sum('remise');
        $nbVentes     = $factures->count();

        $ventesParJour = $factures->groupBy(fn($f) => $f->created_at->format('d/m/Y'))
            ->map(fn($group) => $group->sum('total'));

        return view('rapports.ventes', compact(
            'factures', 'totalCA', 'totalRemises',
            'nbVentes', 'ventesParJour', 'dateDebut', 'dateFin'
        ));
    }
    

    public function stock(Request $request)
    {
        $produits = Produit::with(['categorie', 'fournisseur'])
            ->where('actif', true)
            ->orderBy('libelle')
            ->get();

        $valeurTotaleStock = $produits->sum(fn($p) => $p->stock_actuel * $p->prix_achat);
        $produitsRupture   = $produits->where('stock_actuel', '<=', 0)->count();
        $produitsFaibles   = $produits->filter(fn($p) => $p->isStockFaible())->count();

        return view('rapports.stock', compact(
            'produits', 'valeurTotaleStock',
            'produitsRupture', 'produitsFaibles'
        ));
    }

    public function finances(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->toDateString());
        $dateFin = $request->get('date_fin', now()->toDateString());
        $categorie = $request->get('categorie');
        $depensesQuery = Depense::with('user')->whereBetween('date_depense', [$dateDebut, $dateFin]);
        if ($categorie) $depensesQuery->where('categorie', $categorie);
        $depenses = (clone $depensesQuery)->latest('date_depense')->get();
        $totalDepenses = (clone $depensesQuery)->whereNull('annulee_at')->sum('montant');
        $depensesParCategorie = (clone $depensesQuery)->whereNull('annulee_at')->select('categorie')->selectRaw('SUM(montant) as total')->groupBy('categorie')->orderByDesc('total')->get();
        $remboursements = RemboursementClient::with(['client', 'user'])->whereDate('created_at', '>=', $dateDebut)->whereDate('created_at', '<=', $dateFin)->latest()->get();
        $totalRemboursements = $remboursements->sum('montant');
        $ventes = Facture::ventes()->where('statut', '!=', 'annulee')->whereDate('created_at', '>=', $dateDebut)->whereDate('created_at', '<=', $dateFin)->sum('total');
        $categories = Depense::select('categorie')->distinct()->orderBy('categorie')->pluck('categorie');
        return view('rapports.finances', compact('dateDebut', 'dateFin', 'categorie', 'depenses', 'totalDepenses', 'depensesParCategorie', 'remboursements', 'totalRemboursements', 'ventes', 'categories'));
    }

     /**
     * Export PDF du rapport des ventes
     */
  public function exportPdf(Request $request)
{
    try {
        set_time_limit(500);
        @ini_set('memory_limit', '2048M'); // teste avec 2G si ton serveur le permet

        $dateDebut = $request->get('date_debut', now()->startOfMonth()->toDateString());
        $dateFin   = $request->get('date_fin', now()->toDateString());

        $factures = Facture::ventes()->select([
                'id', 'numero', 'client_id', 'user_id',
                'sous_total', 'remise', 'total',
                'mode_paiement', 'statut', 'created_at',
            ])
            ->with(['client:id,nom', 'user:id,prenom'])
            ->whereDate('created_at', '>=', $dateDebut)
            ->whereDate('created_at', '<=', $dateFin)
            ->where('statut', 'payee')
            ->orderBy('created_at')
            ->get();

        $nbVentes     = $factures->count();
        $totalCA      = $factures->sum('total');
        $totalRemises = $factures->sum('remise');

        $pdf = Pdf::loadView('rapports.export-pdf', compact(
            'factures', 'nbVentes', 'totalCA', 'totalRemises', 'dateDebut', 'dateFin'
        ));

        $pdf->setOptions([
            'isRemoteEnabled'      => false,
            'isHtml5ParserEnabled' => true,
            'defaultPaperSize'     => 'a4',
            'dpi'                  => 96,
        ]);
        $pdf->setPaper('a4', 'landscape');

        // Écrit sur disque au lieu de streamer directement
        $filename = 'rapport-ventes-' . $dateDebut . '-au-' . $dateFin . '-' . time() . '.pdf';
        $path = storage_path('app/public/rapports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $pdf->output());

        // Retourne le fichier en téléchargement, puis le supprime après envoi
        return response()->download($path, $filename)->deleteFileAfterSend(true);

    } catch (\Throwable $e) {
        \Log::error('PDF Export Error', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);
        return response('Erreur PDF : ' . $e->getMessage(), 500);
    }
}

}
