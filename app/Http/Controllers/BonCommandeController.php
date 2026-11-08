<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\LivraisonCommande;
use App\Models\LivraisonCommandeLigne;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BonCommandeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $date = $request->validate(['date' => ['nullable', 'date_format:Y-m-d']])['date'] ?? today()->toDateString();
        $statsQuery = Facture::ventes()->where('statut', '!=', 'annulee');

        $query = Facture::with('client', 'user')
            ->withCount('lignes', 'livraisons')
            ->ventes()->where('statut', '!=', 'annulee')
            ->when($date, fn ($q) => $q->whereDate('created_at', $date))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('numero', 'like', "%{$search}%")
                        ->orWhere('client_nom', 'like', "%{$search}%")
                        ->orWhere('client_telephone', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($c) => $c->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('telephone', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('statut'), fn ($q) => $q->where('livraison_statut', $request->statut))
            ->latest('created_at')
            ->latest('id');
        if (!in_array($user->role, ['admin', 'magasinier', 'caissier'], true)) {
            $query->where('user_id', $user->id);
        }

        $perPageInput = $request->input('per_page', '50');
        if ($perPageInput !== 'all' && !in_array((int) $perPageInput, [20, 50, 100], true)) {
            $perPageInput = '50';
        }
        $perPage = $perPageInput === 'all'
            ? max((clone $query)->count(), 1)
            : (int) $perPageInput;
        $bons = $query->paginate($perPage)->withQueryString();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'attente' => (clone $statsQuery)->where('livraison_statut', 'attente')->count(),
            'partielle' => (clone $statsQuery)->where('livraison_statut', 'partielle')->count(),
            'livree' => (clone $statsQuery)->where('livraison_statut', 'livree')->count(),
        ];

        return view('bons-commandes.index', compact('bons', 'stats', 'perPageInput', 'date'));
    }

    public function show(Facture $bonCommande)
    {
        $bonCommande->load([
            'client', 'user',
            'lignes' => fn ($q) => $q->withSum('livraisons as quantite_livree', 'quantite')->orderBy('id'),
            'livraisons.user', 'livraisons.lignes',
        ]);

        $bonsRestants = $bonCommande->lignes->filter(fn ($ligne) =>
            (float) $ligne->quantite_commandee - (float) ($ligne->quantite_livree ?? 0) > 0.0001
        );
        $produits = Produit::whereIn('id', $bonsRestants->pluck('produit_id')->filter())->get()->keyBy('id');

        return view('bons-commandes.show', compact('bonCommande', 'produits', 'bonsRestants'));
    }

    public function deliver(Request $request, Facture $bonCommande)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'magasinier'], true), 403);
        $data = $request->validate([
            'tout_livre' => ['nullable', 'boolean'],
            'lignes_selectionnees' => ['nullable', 'array'],
            'lignes_selectionnees.*' => ['required', 'integer'],
            'quantites' => ['nullable', 'array'],
            'quantites.*' => ['nullable', 'numeric', 'decimal:0,2', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $bonCommande) {
            $bon = Facture::ventes()->where('statut', '!=', 'annulee')->whereKey($bonCommande->id)->lockForUpdate()->firstOrFail();
            if ($bon->livraison_statut === 'livree') {
                throw ValidationException::withMessages(['livraison' => 'Cette commande est déjà entièrement livrée.']);
            }

            $lignes = FactureLigne::where('facture_id', $bon->id)->orderBy('id')->lockForUpdate()->get();
            $selectionnees = collect($data['lignes_selectionnees'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
            if (!$selectionnees->diff($lignes->pluck('id'))->isEmpty()) {
                throw ValidationException::withMessages(['lignes_selectionnees' => 'Un article sélectionné ne correspond pas à cette commande.']);
            }
            $resteParLigne = [];
            foreach ($lignes as $ligne) {
                $livree = (float) $ligne->livraisons()->sum('quantite');
                $resteParLigne[$ligne->id] = max((float) $ligne->quantite - $livree, 0);
            }

            $complete = (bool) ($data['tout_livre'] ?? false);
            $aLivrer = [];
            foreach ($lignes as $ligne) {
                if (!$complete && !$selectionnees->contains((int) $ligne->id)) continue;
                $reste = $resteParLigne[$ligne->id];
                $qte = $complete ? $reste : (float) ($data['quantites'][$ligne->id] ?? 0);
                if ($qte <= 0) {
                    throw ValidationException::withMessages([
                        "quantites.{$ligne->id}" => "Saisissez une quantité pour {$ligne->libelle}, ou décochez cet article.",
                    ]);
                }
                if ($qte > $reste + 0.0001) {
                    throw ValidationException::withMessages([
                        "quantites.{$ligne->id}" => "La quantité dépasse le reste à livrer pour {$ligne->libelle}.",
                    ]);
                }
                $aLivrer[] = ['ligne' => $ligne, 'quantite' => $qte];
            }

            if (!$aLivrer) {
                throw ValidationException::withMessages(['livraison' => 'Saisissez au moins une quantité à livrer.']);
            }

            $livraison = LivraisonCommande::create([
                'bon_commande_id' => $bon->id,
                'user_id' => auth()->id(),
                'complete' => $complete,
                'livree_le' => now(),
            ]);

            foreach ($aLivrer as $item) {
                $ligne = $item['ligne'];
                $qte = $item['quantite'];
                LivraisonCommandeLigne::create([
                    'livraison_id' => $livraison->id,
                    'bon_commande_ligne_id' => $ligne->id,
                    'produit_id' => $ligne->produit_id,
                    'libelle' => $ligne->libelle,
                    'unite' => $ligne->unite,
                    'quantite' => $qte,
                ]);

            }

            $reste = FactureLigne::where('facture_id', $bon->id)->get()->contains(function ($ligne) {
                return (float) $ligne->quantite - (float) $ligne->livraisons()->sum('quantite') > 0.0001;
            });
            $bon->update(['livraison_statut' => $reste ? 'partielle' : 'livree']);
        });

        return redirect()->route('bons-commandes.show', $bonCommande)
            ->with('success', 'Livraison enregistrée. Le bon de livraison ne contient que les articles remis.');
    }

    public function remainingTicket(Facture $bonCommande)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'magasinier'], true), 403);
        $bonCommande->load([
            'client',
            'lignes' => fn ($q) => $q->withSum('livraisons as quantite_livree', 'quantite')->orderBy('id'),
            'livraisons',
        ]);
        $lignesRestantes = $bonCommande->lignes->filter(fn ($ligne) =>
            (float) $ligne->quantite_commandee - (float) ($ligne->quantite_livree ?? 0) > 0.0001
        );

        return Pdf::loadView('bons-commandes.reste-a-livrer', compact('bonCommande', 'lignesRestantes'))
            ->setPaper('A4', 'portrait')
            ->stream('reste-a-livrer-' . $bonCommande->reference . '.pdf');
    }

    public function deliverySlip(Facture $bonCommande, LivraisonCommande $livraison)
    {
        abort_unless($livraison->bon_commande_id === $bonCommande->id, 404);
        $livraison->load('user', 'lignes');
        $bonCommande->load('client');

        return Pdf::loadView('bons-commandes.bon-livraison', compact('bonCommande', 'livraison'))
            ->setPaper('A4', 'portrait')
            ->stream('bon-livraison-' . $bonCommande->reference . '-' . $livraison->id . '.pdf');
    }
}
