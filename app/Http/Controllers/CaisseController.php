<?php
// app/Http/Controllers/CaisseController.php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\Reglement;
use App\Models\Client;
use App\Models\MouvementAvance;
use App\Models\Categorie; // ← AJOUTER CETTE LIGNE

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\EmecefService;

class CaisseController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('nom')->get(['id', 'nom','prenom', 'telephone', 'solde_avance']);

        return view('caisse.index',compact('clients'));
    }

    /**
     * Recherche de produits pour la caisse (AJAX).
     * Retourne les produits disponibles pour la saisie de facture.
     */

    public function recherche(Request $request)
    {
        try {
            $search = trim((string) $request->get('q', ''));
            $role = $request->get('role', '');
            $user = auth()->user();

            // Utiliser le rôle de l'utilisateur si non passé en paramètre
            if (empty($role)) {
                $role = $user->role;
            }

            // Récupérer la catégorie "CATEGORIE DU HAUT"
            $categorieHaut = Categorie::where('nom', 'CATEGORIE DU HAUT')->first();

            // Démarrer la requête
            $produits = Produit::where('actif', true)
                ->where('stock_actuel', '>', 0);

            // ── FILTRAGE SELON LE RÔLE ──
            if ($role === 'caissier') {
                // Caissier : EXCLURE les produits de la catégorie "CATEGORIE DU HAUT"
                if ($categorieHaut) {
                    $produits->where(function($query) use ($categorieHaut) {
                        $query->where('categorie_id', '!=', $categorieHaut->id)
                            ->orWhereNull('categorie_id');
                    });
                }
                // Si la catégorie n'existe pas, on retourne tous les produits
                
            } elseif ($role === 'caissierHaut') {
                // Caissier Haut : UNIQUEMENT les produits de la catégorie "CATEGORIE DU HAUT"
                if ($categorieHaut) {
                    $produits->where('categorie_id', '=', $categorieHaut->id);
                } else {
                    // Si la catégorie n'existe pas, retourner vide
                    return response()->json([]);
                }
            }
            // Admin et Superviseur : pas de filtre (on garde tous les produits)

            // ── RECHERCHE ──
            if (!empty($search)) {
                $produits->where(function ($query) use ($search) {
                    $query->where('libelle', 'like', "%{$search}%")
                        ->orWhere('code_barre', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%");
                });
            }

            $produits = $produits
                ->orderByRaw('CASE WHEN code_barre = ? THEN 0 ELSE 1 END', [$search])
                ->select(
                    'id',
                    'libelle',
                    'stock_actuel',
                    'unite',
                    'reference',
                    'code_barre',
                    'categorie_id'
                )
                ->limit(10)
                ->get();

            // Retourner les informations du produit utiles à la caisse
            $produits = $produits->map(function ($p) {
                return [
                    'id'           => $p->id,
                    'libelle'      => $p->libelle,
                    'reference'    => $p->reference,
                    'code_barre'   => $p->code_barre,
                    'stock_actuel' => (float) $p->stock_actuel,
                    'unite'        => $p->unite,
                    'categorie_id' => $p->categorie_id,
                ];
            });

            return response()->json($produits);

        } catch (\Exception $e) {
            \Log::error('Erreur recherche caisse: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Enregistrement de la facture.
     * Le prix est saisi sur chaque ligne de la facture.
    */
    // public function valider(Request $request)
    // {
        //     $request->validate([
        //         'mode_paiement'       => 'required|in:espece,carte,mobile_money,credit',
        //         'montant_recu'        => 'required|numeric|min:0',
        //         'client_nom'          => 'nullable|string|max:255',
        //         'client_telephone'    => 'nullable|string|max:20',
        //         'client_id'           => 'nullable|exists:clients,id',
        //         'remise'              => 'nullable|numeric|min:0',
        //         'produits'            => 'required|array|min:1',
        //         'produits.*.id'       => 'required|exists:produits,id',
        //         'produits.*.quantite' => 'required|numeric|min:0.01',
        //     ]);

        //     // ══ VÉRIFICATION STOCK — AVANT la transaction ══
        //     $erreurStock = [];

        //     foreach ($request->produits as $item) {
        //         $produit = Produit::findOrFail($item['id']);
        //         $qte     = (float) $item['quantite'];

        //         if ($produit->stock_actuel <= 0) {
        //             $erreurStock[] = "\"{$produit->libelle}\" est en rupture de stock.";
        //         } elseif ($produit->stock_actuel < $qte) {
        //             $erreurStock[] = sprintf(
        //                 '"%s" : stock insuffisant (dispo : %s %s · demandé : %s %s)',
        //                 $produit->libelle,
        //                 number_format($produit->stock_actuel, 2, ',', ' '),
        //                 $produit->unite,
        //                 number_format($qte, 2, ',', ' '),
        //                 $produit->unite
        //             );
        //         }
        //     }

        //     if (!empty($erreurStock)) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Stock insuffisant pour ' . count($erreurStock) . ' produit(s).',
        //             'erreurs' => $erreurStock,
        //         ], 422);
        //     }
        //     // ══ FIN VÉRIFICATION STOCK ══

        //     DB::beginTransaction();


        //     try {
        //         $sousTotal = 0;
        //         $lignes    = [];

        //         foreach ($request->produits as $item) {
        //             $produit = Produit::findOrFail($item['id']);
        //             $qte     = (float) $item['quantite'];

        //             $prixUnitaire = $produit->getPrixParQuantite($qte);
        //             $sousLigne    = $prixUnitaire * $qte;
        //             $sousTotal   += $sousLigne;

        //             $lignes[] = [
        //                 'produit'       => $produit,
        //                 'quantite'      => $qte,
        //                 'prix_unitaire' => $prixUnitaire,
        //                 'sous_total'    => $sousLigne,
        //             ];
        //         }

        //         $remise   = (float) ($request->remise ?? 0);
        //         $total    = $sousTotal - $remise;
        //         $isCredit = $request->mode_paiement === 'credit';
        //         $monnaie  = $isCredit ? 0 : max($request->montant_recu - $total, 0);
        //         $clientId = $request->input('client_id');

        //         // ── Créer la facture ──
        //         $facture = Facture::create([
        //             'user_id'          => auth()->id(),
        //             'numero'           => Facture::genererNumero(),
        //             'client_id'        => $clientId,
        //             'client_telephone' => $request->client_telephone,
        //             'statut'           => $isCredit ? 'en_cours' : 'payee',
        //             'mode_paiement'    => $request->mode_paiement,
        //             'sous_total'       => $sousTotal,
        //             'remise'           => $remise,
        //             'total'            => $total,
        //             'montant_recu'     => $isCredit ? 0 : $request->montant_recu,
        //             'monnaie'          => $monnaie,
        //             'reste_a_payer'    => $isCredit ? $total : 0,
        //         ]);

        //         $factureId = $facture->id; // ← garder l'id avant commit

        //         // ── Créer les lignes + déduire le stock ──
        //         foreach ($lignes as $ligne) {
        //             FactureLigne::create([
        //                 'facture_id'    => $facture->id,
        //                 'produit_id'    => $ligne['produit']->id,
        //                 'libelle'       => $ligne['produit']->libelle,
        //                 'quantite'      => $ligne['quantite'],
        //                 'prix_unitaire' => $ligne['prix_unitaire'],
        //                 'sous_total'    => $ligne['sous_total'],
        //             ]);

        //             $ligne['produit']->sortieStock(
        //                 $ligne['quantite'],
        //                 $ligne['prix_unitaire'],
        //                 auth()->id(),
        //                 'Vente facture ' . $facture->numero,
        //                 $facture->id
        //             );
        //         }

        //         // ── Règlement ──
        //         if (!$isCredit) {
        //             Reglement::create([
        //                 'facture_id'    => $facture->id,
        //                 'user_id'       => auth()->id(),
        //                 'montant'       => min($request->montant_recu, $total),
        //                 'mode_paiement' => $request->mode_paiement,
        //                 'notes'         => 'Paiement caisse',
        //             ]);
        //         }

        //         DB::commit();

        //     } catch (\Exception $e) {
        //         DB::rollBack();
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Erreur : ' . $e->getMessage(),
        //         ], 500);
        //     }

        //     // ════════════════════════════════════════════════════
        //     // Normalisation e-MECeF — HORS transaction DB
        //     // On recharge depuis la BD pour avoir toutes les relations
        //     // ════════════════════════════════════════════════════
        //     $facture = Facture::with(['lignes.produit', 'client', 'user'])
        //                     ->findOrFail($factureId);

        //     $normalisee   = false;
        //     $emecef       = null;
        //     $emecefErreur = null;

        //     try {
        //         $service = new EmecefService();
        //         $result  = $service->normaliser($facture);

        //         if ($result['success']) {

        //             // UPDATE direct avec query builder — contourne tout cache Eloquent
        //             DB::table('factures')
        //             ->where('id', $factureId)
        //             ->update([
        //                 'emecef_uid'      => $result['uid'],
        //                 'emecef_code'     => $result['codeMECeFDGI'],
        //                 'emecef_qr'       => $result['qrCode'],
        //                 'emecef_datetime' => $result['dateTime'],
        //                 'emecef_counters' => $result['counters'],
        //                 'emecef_nim'      => $result['nim'],
        //                 'normalisee'      => true,
        //                 'normalisee_at'   => now(),
        //             ]);

        //             $normalisee = true;
        //             $emecef     = $result;

        //             // Log::info('eMECeF ✓ facture ' . $facture->numero . ' — code: ' . $result['codeMECeFDGI']);

        //         } else {
        //             $emecefErreur = $result['errorDesc'] ?? 'Erreur inconnue e-MECeF';
        //             // Log::warning('eMECeF ✗ facture ' . $facture->numero . ' : ' . $emecefErreur);
        //         }

        //     } catch (\Exception $e) {
        //         $emecefErreur = $e->getMessage();
        //         // Log::error('eMECeF exception facture ' . $facture->numero . ' : ' . $e->getMessage());
        //     }

        //     return response()->json([
        //         'success'       => true,
        //         'facture_id'    => $factureId,
        //         'numero'        => $facture->numero,
        //         'total'         => $total,
        //         'monnaie'       => $monnaie,
        //         'is_credit'     => $isCredit,
        //         'normalisee'    => $normalisee,
        //         'emecef_code'   => $emecef['codeMECeFDGI'] ?? null,
        //         'emecef_qr'     => $emecef['qrCode']       ?? null,
        //         'emecef_erreur' => $emecefErreur,
        //         'message'       => $isCredit
        //             ? 'Vente à crédit enregistrée — ' . number_format($total, 0, ',', ' ') . ' FCFA à encaisser.'
        //             : 'Vente enregistrée.' . ($normalisee ? ' ✓ Facture normalisée.' : ''),
        //     ]);
    // }

    public function valider(Request $request)
    {
        $request->validate([
            'mode_paiement'       => 'required|in:espece,carte,mobile_money,credit',
            'montant_recu'        => 'required|numeric|min:0',
            'client_nom'          => 'nullable|string|max:255',
            'client_telephone'    => 'nullable|string|max:20',
            'client_id'           => 'nullable|required_if:mode_paiement,credit|exists:clients,id',
            'operation_reference' => 'required|uuid',
            'remise'              => 'nullable|numeric|min:0',
            'produits'            => 'required|array|min:1',
            'produits.*.id'       => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|numeric|min:0.01',
            'produits.*.prix_unitaire' => 'required|numeric|gt:0',
        ]);

        $existingInvoice = Facture::ventes()->where('operation_reference', $request->operation_reference)->first();
        if ($existingInvoice) return $this->reponseVenteDejaEnregistree($existingInvoice);

        // ══ VÉRIFICATION STOCK — AVANT la transaction ══
        $erreurStock = [];

        foreach ($request->produits as $item) {
            $produit = Produit::findOrFail($item['id']);
            $qte     = (float) $item['quantite'];

            if ($produit->stock_actuel <= 0) {
                $erreurStock[] = "\"{$produit->libelle}\" est en rupture de stock.";
            } elseif ($produit->stock_actuel < $qte) {
                $erreurStock[] = sprintf(
                    '"%s" : stock insuffisant (dispo : %s %s · demandé : %s %s)',
                    $produit->libelle,
                    number_format($produit->stock_actuel, 2, ',', ' '),
                    $produit->unite,
                    number_format($qte, 2, ',', ' '),
                    $produit->unite
                );
            }
        }

        if (!empty($erreurStock)) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant pour ' . count($erreurStock) . ' produit(s).',
                'erreurs' => $erreurStock,
            ], 422);
        }
        // ══ FIN VÉRIFICATION STOCK ══

        DB::beginTransaction();


        try {
            $clientId = $request->input('client_id');
            $client = $clientId ? Client::whereKey($clientId)->lockForUpdate()->firstOrFail() : null;
            $productIds = collect($request->produits)->pluck('id')->unique()->sort()->values();
            $lockedProducts = Produit::whereIn('id', $productIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $requestedQuantities = collect($request->produits)->groupBy('id')->map(fn ($items) => $items->sum(fn ($item) => (float) $item['quantite']));
            foreach ($requestedQuantities as $productId => $quantity) {
                $product = $lockedProducts->get($productId);
                if (!$product || (float) $product->stock_actuel < $quantity) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Le stock a changé. Rechargez la caisse avant de valider.', 'erreurs' => ['Stock insuffisant pour un ou plusieurs produits.']], 422);
                }
            }

            $sousTotal = 0;
            $lignes    = [];

            foreach ($request->produits as $item) {
                $produit = $lockedProducts->get($item['id']);
                $qte     = (float) ($item['quantite'] ?? 0);
                $prixUnitaire = isset($item['prix_unitaire']) && $item['prix_unitaire'] !== ''
                    ? (float) $item['prix_unitaire']
                    : 0;

                $sousLigne  = round($prixUnitaire * $qte, 2);
                $sousTotal += $sousLigne;

                $lignes[] = [
                    'produit'       => $produit,
                    'quantite'      => $qte,
                    'prix_unitaire' => $prixUnitaire,
                    'sous_total'    => $sousLigne,
                ];
            }

            $remise      = (float) ($request->remise ?? 0);
            $montantRecu = (float) ($request->montant_recu ?? 0);
            $total       = max(round($sousTotal - $remise, 2), 0);
            $montantAvance = $client ? min((float) $client->solde_avance, $total) : 0;
            $resteApresAvance = round(max($total - $montantAvance, 0), 2);
            $montantRecuFacture = $request->mode_paiement === 'credit'
                ? 0
                : min($montantRecu, $resteApresAvance);
            $resteApresPaiement = round(max($resteApresAvance - $montantRecuFacture, 0), 2);
            $isCredit = $resteApresPaiement > 0;
            $modeFacture = $isCredit ? 'credit' : ($montantAvance > 0 && $montantRecuFacture <= 0 ? 'espece' : $request->mode_paiement);
            $monnaie = $request->mode_paiement === 'credit' ? 0 : max($montantRecu - $montantRecuFacture, 0);
            $clientNom   = $request->input('client_nom');
            $clientTelephone = $request->input('client_telephone');

            // ── Créer la facture ──
            $facture = Facture::create([
                'user_id'          => auth()->id(),
                'numero'           => Facture::genererNumero(),
                'operation_reference' => $request->operation_reference,
                'client_id'        => $clientId,
                'client_nom'       => $clientNom,
                'client_telephone' => $clientTelephone,
                'statut'           => $isCredit ? 'en_cours' : 'payee',
                'livraison_statut' => 'attente',
                'mode_paiement'    => $modeFacture,
                'sous_total'       => $sousTotal,
                'remise'           => $remise,
                'total'            => $total,
                'montant_recu'     => $montantRecuFacture,
                'montant_avance'   => $montantAvance,
                'monnaie'          => $monnaie,
                'reste_a_payer'    => $resteApresPaiement,
            ]);

            $factureId = $facture->id; // ← garder l'id avant commit

            // ── Créer les lignes + déduire le stock ──
            foreach ($lignes as $ligne) {
                FactureLigne::create([
                    'facture_id'    => $facture->id,
                    'produit_id'    => $ligne['produit']->id,
                    'libelle'       => $ligne['produit']->libelle,
                    'unite'         => $ligne['produit']->unite,
                    'quantite'      => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'sous_total'    => $ligne['sous_total'],
                ]);

                $ligne['produit']->sortieStock(
                    $ligne['quantite'],
                    $ligne['prix_unitaire'],
                    auth()->id(),
                    'Vente facture ' . $facture->numero,
                    $facture->id
                );
            }

            // ── Règlement ──
            if ($montantAvance > 0) {
                $client->decrement('solde_avance', $montantAvance);
                MouvementAvance::create([
                    'reference' => $request->operation_reference,
                    'client_id' => $client->id,
                    'facture_id' => $facture->id,
                    'user_id' => auth()->id(),
                    'type' => 'utilisation',
                    'montant' => $montantAvance,
                    'notes' => 'Utilisation automatique sur facture ' . $facture->numero,
                ]);
            }

            if ($montantRecuFacture > 0) {
                Reglement::create([
                    'facture_id'    => $facture->id,
                    'user_id'       => auth()->id(),
                    'montant'       => $montantRecuFacture,
                    'mode_paiement' => $request->mode_paiement,
                    'notes'         => 'Paiement caisse',
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $existingInvoice = Facture::ventes()->where('operation_reference', $request->operation_reference)->first();
            if ($existingInvoice) return $this->reponseVenteDejaEnregistree($existingInvoice);
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }

        // ════════════════════════════════════════════════════
        // Normalisation e-MECeF — HORS transaction DB
        // On recharge depuis la BD pour avoir toutes les relations
        // ════════════════════════════════════════════════════
        $facture = Facture::ventes()->with(['lignes.produit', 'client', 'user'])->findOrFail($factureId);

        $normalisee   = false;
        $emecef       = null;
        $emecefErreur = null;

        // try {
        //     $service = new EmecefService();
        //     $result  = $service->normaliser($facture);

        //     if ($result['success']) {

        //         // UPDATE direct avec query builder — contourne tout cache Eloquent
        //         DB::table('factures')
        //         ->where('id', $factureId)
        //         ->update([
        //             'emecef_uid'      => $result['uid'],
        //             'emecef_code'     => $result['codeMECeFDGI'],
        //             'emecef_qr'       => $result['qrCode'],
        //             'emecef_datetime' => $result['dateTime'],
        //             'emecef_counters' => $result['counters'],
        //             'emecef_nim'      => $result['nim'],
        //             'normalisee'      => true,
        //             'normalisee_at'   => now(),
        //         ]);

        //         $normalisee = true;
        //         $emecef     = $result;

        //         // Log::info('eMECeF ✓ facture ' . $facture->numero . ' — code: ' . $result['codeMECeFDGI']);

        //     } else {
        //         $emecefErreur = $result['errorDesc'] ?? 'Erreur inconnue e-MECeF';
        //         // Log::warning('eMECeF ✗ facture ' . $facture->numero . ' : ' . $emecefErreur);
        //     }

        // } catch (\Exception $e) {
        //     $emecefErreur = $e->getMessage();
        //     // Log::error('eMECeF exception facture ' . $facture->numero . ' : ' . $e->getMessage());
        // }

        return response()->json([
            'success'       => true,
            'facture_id'    => $factureId,
            'numero'        => $facture->numero,
            'total'         => $total,
            'monnaie'       => $monnaie,
            'avance_utilisee' => $montantAvance,
            'solde_avance' => $client ? (float) $client->fresh()->solde_avance : 0,
            'reste_a_payer' => $resteApresPaiement,
            'is_credit'     => $isCredit,
            'normalisee'    => $normalisee,
            'emecef_code'   => $emecef['codeMECeFDGI'] ?? null,
            'emecef_qr'     => $emecef['qrCode']       ?? null,
            'emecef_erreur' => $emecefErreur,
            'message'       => $isCredit
                ? 'Vente à crédit enregistrée — ' . number_format($resteApresPaiement, 0, ',', ' ') . ' FCFA à encaisser.'
                : 'Vente enregistrée.' . ($normalisee ? ' ✓ Facture normalisée.' : ''),
        ]);
    }

    
    private function reponseVenteDejaEnregistree(Facture $facture)
    {
        $facture->loadMissing('client');
        return response()->json([
            'success' => true,
            'facture_id' => $facture->id,
            'numero' => $facture->numero,
            'total' => (float) $facture->total,
            'monnaie' => (float) $facture->monnaie,
            'avance_utilisee' => (float) $facture->montant_avance,
            'solde_avance' => (float) ($facture->client?->solde_avance ?? 0),
            'reste_a_payer' => (float) $facture->reste_a_payer,
            'is_credit' => (float) $facture->reste_a_payer > 0,
            'message' => 'Cette vente a déjà été enregistrée.',
        ]);
    }

    public function ticket($id)
    {
        
        $facture = Facture::ventes()->with('lignes.produit')->findOrFail($id);

        return view('caisse.ticket', compact('facture'));
    }
}
