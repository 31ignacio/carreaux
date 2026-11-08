<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('factures', 'nature')) {
            Schema::table('factures', fn (Blueprint $table) => $table->string('nature', 20)->default('vente'));
        }
        if (!Schema::hasColumn('factures', 'livraison_statut')) {
            Schema::table('factures', fn (Blueprint $table) => $table->string('livraison_statut', 20)->default('livree'));
        }
        if (!Schema::hasColumn('facture_lignes', 'unite')) {
            Schema::table('facture_lignes', fn (Blueprint $table) => $table->string('unite', 50)->nullable());
        }

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            Schema::table('facture_lignes', function (Blueprint $table) {
                $table->dropForeign(['produit_id']);
                $table->unsignedBigInteger('produit_id')->nullable()->change();
                $table->foreign('produit_id')->references('id')->on('produits')->nullOnDelete();
            });
        }

        if (!Schema::hasTable('bons_commandes') || !Schema::hasTable('bon_commande_lignes')) {
            return;
        }

        if ($driver === 'mysql') {
            Schema::table('livraison_commande_lignes', fn (Blueprint $table) => $table->dropForeign(['bon_commande_ligne_id']));
            Schema::table('livraisons_commande', fn (Blueprint $table) => $table->dropForeign(['bon_commande_id']));
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        $factureIds = [];
        $ligneIds = [];
        foreach (DB::table('bons_commandes')->orderBy('id')->get() as $commande) {
            $client = $commande->client_id
                ? DB::table('clients')->where('id', $commande->client_id)->first()
                : null;
            $numero = $commande->reference;
            if (DB::table('factures')->where('numero', $numero)->exists()) {
                $numero = 'BC-MIG-' . $commande->id;
            }

            $factureId = DB::table('factures')->insertGetId([
                'user_id' => $commande->user_id,
                'numero' => $numero,
                'operation_reference' => null,
                'client_id' => $commande->client_id,
                'client_nom' => $client ? trim(($client->prenom ?? '') . ' ' . ($client->nom ?? '')) : null,
                'client_telephone' => $client->telephone ?? null,
                'statut' => 'payee',
                'mode_paiement' => 'espece',
                'sous_total' => 0,
                'remise' => 0,
                'total' => 0,
                'montant_recu' => 0,
                'montant_avance' => 0,
                'monnaie' => 0,
                'reste_a_payer' => 0,
                'notes' => $commande->notes,
                'nature' => 'commande',
                'livraison_statut' => $commande->statut,
                'created_at' => $commande->created_at,
                'updated_at' => $commande->updated_at,
            ]);
            $factureIds[$commande->id] = $factureId;

            foreach (DB::table('bon_commande_lignes')->where('bon_commande_id', $commande->id)->orderBy('id')->get() as $ligne) {
                $ligneIds[$ligne->id] = DB::table('facture_lignes')->insertGetId([
                    'facture_id' => $factureId,
                    'produit_id' => $ligne->produit_id,
                    'libelle' => $ligne->libelle,
                    'unite' => $ligne->unite,
                    'quantite' => $ligne->quantite_commandee,
                    'prix_unitaire' => 0,
                    'sous_total' => 0,
                    'created_at' => $ligne->created_at,
                    'updated_at' => $ligne->updated_at,
                ]);
            }
        }

        foreach (DB::table('livraisons_commande')->orderBy('id')->get() as $livraison) {
            if (isset($factureIds[$livraison->bon_commande_id])) {
                DB::table('livraisons_commande')->where('id', $livraison->id)
                    ->update(['bon_commande_id' => $factureIds[$livraison->bon_commande_id]]);
            }
        }
        foreach (DB::table('livraison_commande_lignes')->orderBy('id')->get() as $ligneLivree) {
            if (isset($ligneIds[$ligneLivree->bon_commande_ligne_id])) {
                DB::table('livraison_commande_lignes')->where('id', $ligneLivree->id)
                    ->update(['bon_commande_ligne_id' => $ligneIds[$ligneLivree->bon_commande_ligne_id]]);
            }
        }

        if ($driver === 'sqlite') {
            $this->rebuildSqliteDeliveryTables();
            DB::statement('PRAGMA foreign_keys = ON');
        } elseif ($driver === 'mysql') {
            Schema::table('livraisons_commande', function (Blueprint $table) {
                $table->foreign('bon_commande_id')->references('id')->on('factures')->cascadeOnDelete();
            });
            Schema::table('livraison_commande_lignes', function (Blueprint $table) {
                $table->foreign('bon_commande_ligne_id')->references('id')->on('facture_lignes')->restrictOnDelete();
            });
        }

        Schema::dropIfExists('bon_commande_lignes');
        Schema::dropIfExists('bons_commandes');
    }

    private function rebuildSqliteDeliveryTables(): void
    {
        $livraisons = DB::table('livraisons_commande')->orderBy('id')->get();
        $lignes = DB::table('livraison_commande_lignes')->orderBy('id')->get();

        Schema::create('livraisons_commande_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_commande_id')->constrained('factures')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->boolean('complete')->default(false);
            $table->timestamp('livree_le');
            $table->timestamps();
            $table->index(['bon_commande_id', 'livree_le']);
        });
        foreach ($livraisons as $livraison) {
            DB::table('livraisons_commande_new')->insert((array) $livraison);
        }

        Schema::create('livraison_commande_lignes_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livraison_id')->constrained('livraisons_commande_new')->cascadeOnDelete();
            $table->foreignId('bon_commande_ligne_id')->constrained('facture_lignes')->restrictOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('libelle');
            $table->string('unite', 50)->nullable();
            $table->decimal('quantite', 12, 2);
            $table->timestamps();
        });
        foreach ($lignes as $ligne) {
            DB::table('livraison_commande_lignes_new')->insert((array) $ligne);
        }

        Schema::dropIfExists('livraison_commande_lignes');
        Schema::dropIfExists('livraisons_commande');
        Schema::rename('livraisons_commande_new', 'livraisons_commande');
        Schema::rename('livraison_commande_lignes_new', 'livraison_commande_lignes');
    }

    public function down(): void
    {
        throw new RuntimeException('Cette migration transfère des bons dans factures et ne peut pas être annulée automatiquement.');
    }
};
