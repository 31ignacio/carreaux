<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('factures')
            ->where('nature', 'vente')
            ->where('statut', '!=', 'annulee')
            ->orderBy('id')
            ->chunkById(100, function ($factures) {
                foreach ($factures as $facture) {
                    if (DB::table('livraisons_commande')->where('bon_commande_id', $facture->id)->exists()) {
                        continue;
                    }

                    $lignes = DB::table('facture_lignes')->where('facture_id', $facture->id)->orderBy('id')->get();
                    if ($lignes->isEmpty()) {
                        continue;
                    }

                    $livraisonId = DB::table('livraisons_commande')->insertGetId([
                        'bon_commande_id' => $facture->id,
                        'user_id' => $facture->user_id,
                        'complete' => true,
                        'livree_le' => $facture->created_at,
                        'created_at' => $facture->created_at,
                        'updated_at' => $facture->created_at,
                    ]);

                    foreach ($lignes as $ligne) {
                        $produit = $ligne->produit_id
                            ? DB::table('produits')->where('id', $ligne->produit_id)->first()
                            : null;

                        DB::table('livraison_commande_lignes')->insert([
                            'livraison_id' => $livraisonId,
                            'bon_commande_ligne_id' => $ligne->id,
                            'produit_id' => $ligne->produit_id,
                            'libelle' => $ligne->libelle,
                            'unite' => $ligne->unite ?? $produit->unite ?? null,
                            'quantite' => $ligne->quantite,
                            'created_at' => $facture->created_at,
                            'updated_at' => $facture->created_at,
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        throw new RuntimeException('Les bons de livraison historiques créés à partir des ventes ne peuvent pas être supprimés automatiquement.');
    }
};
