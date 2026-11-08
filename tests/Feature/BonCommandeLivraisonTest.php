<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\LivraisonCommande;
use App\Models\LivraisonCommandeLigne;
use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BonCommandeLivraisonTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private User $storekeeper;
    private Client $client;
    private Produit $productA;
    private Produit $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertFalse(Schema::hasTable('bons_commandes'));
        $this->assertFalse(Schema::hasTable('bon_commande_lignes'));

        $this->secretary = User::create([
            'nom' => 'SecrÃƒÂ©taire', 'prenom' => 'Test', 'email' => 'secretary@example.test',
            'password' => 'password', 'role' => 'secretaire', 'actif' => true,
        ]);
        $this->storekeeper = User::create([
            'nom' => 'Magasinier', 'prenom' => 'Test', 'email' => 'storekeeper@example.test',
            'password' => 'password', 'role' => 'magasinier', 'actif' => true,
        ]);
        $this->client = Client::create(['nom' => 'Client test', 'type' => 'particulier', 'actif' => true]);
        $category = Categorie::create(['nom' => 'Test']);

        $this->productA = Produit::create([
            'categorie_id' => $category->id, 'libelle' => 'Produit A', 'unite' => 'piÃƒÂ¨ce',
            'stock_actuel' => 10, 'stock_minimum' => 0, 'actif' => true,
        ]);
        $this->productB = Produit::create([
            'categorie_id' => $category->id, 'libelle' => 'Produit B', 'unite' => 'piÃƒÂ¨ce',
            'stock_actuel' => 10, 'stock_minimum' => 0, 'actif' => true,
        ]);
    }

    public function test_supervisor_can_record_partial_then_complete_delivery_for_a_cash_sale(): void
    {
        $this->productA->update(['stock_actuel' => 5]);
        $this->productB->update(['stock_actuel' => 7]);
        $bon = Facture::create([
            'user_id' => $this->secretary->id,
            'numero' => 'BL-TEST-LIVRAISON',
            'nature' => 'vente',
            'livraison_statut' => 'attente',
            'client_id' => $this->client->id,
            'client_nom' => $this->client->nom,
            'client_telephone' => $this->client->telephone,
            'statut' => 'payee',
            'mode_paiement' => 'espece',
            'sous_total' => 1500,
            'remise' => 0,
            'total' => 1500,
            'montant_recu' => 1500,
            'monnaie' => 0,
            'reste_a_payer' => 0,
        ]);
        $ligneA = FactureLigne::create([
            'facture_id' => $bon->id, 'produit_id' => $this->productA->id,
            'libelle' => $this->productA->libelle, 'unite' => $this->productA->unite,
            'quantite' => 5, 'prix_unitaire' => 100, 'sous_total' => 500,
        ]);
        $ligneB = FactureLigne::create([
            'facture_id' => $bon->id, 'produit_id' => $this->productB->id,
            'libelle' => $this->productB->libelle, 'unite' => $this->productB->unite,
            'quantite' => 3, 'prix_unitaire' => 100, 'sous_total' => 300,
        ]);

        $this->actingAs($this->supervisor())
            ->get(route('bons-commandes.show', $bon))
            ->assertOk()
            ->assertSee('En attente')
            ->assertSee('Partiellement livr')
            ->assertSee('Enti')
            ->assertSee('tout')
            ->assertSee('Livrer cet article');
        $this->get(route('bons-commandes.index', ['per_page' => 'all']))
            ->assertOk()->assertSee('En attente');

        $this->post(route('bons-commandes.livraisons.store', $bon), [
            'lignes_selectionnees' => [$ligneA->id],
            'quantites' => [$ligneA->id => '2'],
        ])->assertRedirect(route('bons-commandes.show', $bon));

        $bon->refresh();
        $this->assertSame('partielle', $bon->livraison_statut);
        $this->get(route('bons-commandes.index', ['per_page' => 'all']))
            ->assertOk()->assertSee('Partiellement livrée');
        $this->assertSame(5.0, (float) $this->productA->fresh()->stock_actuel);
        $this->assertSame(7.0, (float) $this->productB->fresh()->stock_actuel);
        $livraisonPartielle = LivraisonCommande::with('lignes')->where('bon_commande_id', $bon->id)->firstOrFail();
        $this->assertCount(1, $livraisonPartielle->lignes);
        $this->assertSame($this->productA->id, $livraisonPartielle->lignes->first()->produit_id);
        $bonPdfPartiel = view('bons-commandes.bon-livraison', [
            'bonCommande' => $bon->load('client'),
            'livraison' => $livraisonPartielle->load('user', 'lignes'),
        ])->render();
        $this->assertStringContainsString('Produit A', $bonPdfPartiel);
        $this->assertStringNotContainsString('Produit B', $bonPdfPartiel);
        $this->get(route('bons-commandes.show', $bon))->assertOk()
            ->assertSee('2,00')
            ->assertSee('3,00');

        $this->post(route('bons-commandes.livraisons.store', $bon), ['tout_livre' => '1'])
            ->assertRedirect(route('bons-commandes.show', $bon));
        $bon->refresh();
        $this->assertSame('livree', $bon->livraison_statut);
        $this->get(route('bons-commandes.index', ['per_page' => 'all']))
            ->assertOk()->assertSee('Entièrement livrée');
        $this->assertSame(2, LivraisonCommande::where('bon_commande_id', $bon->id)->count());
        $this->assertSame(0, MouvementStock::where('reference_doc', $bon->reference)->count());

        $this->actingAs($this->supervisor());
        $this->get(route('bons-commandes.show', $bon))->assertOk()
            ->assertSee('Imprimer le ticket du reste')
            ->assertSee('Historique des livraisons');
        $this->get(route('bons-commandes.livraisons.pdf', [$bon, $livraisonPartielle]))
            ->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->get(route('bons-commandes.reste', $bon))
            ->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    private function supervisor(): User
    {
        return User::firstOrCreate(['email' => 'supervisor@example.test'], [
            'nom' => 'Superviseur', 'prenom' => 'Test', 'password' => 'password',
            'role' => 'superviseur', 'actif' => true,
        ]);
    }
    public function test_only_supervisor_or_admin_can_update_delivery_state(): void
    {
        $bon = Facture::create([
            'user_id' => $this->secretary->id, 'numero' => 'BL-TEST-ROLE',
            'nature' => 'vente', 'livraison_statut' => 'attente',
            'client_id' => $this->client->id, 'client_nom' => $this->client->nom,
            'statut' => 'payee', 'mode_paiement' => 'espece', 'sous_total' => 100,
            'remise' => 0, 'total' => 100, 'montant_recu' => 100,
            'monnaie' => 0, 'reste_a_payer' => 0,
        ]);
        $ligne = FactureLigne::create([
            'facture_id' => $bon->id, 'produit_id' => $this->productA->id,
            'libelle' => $this->productA->libelle, 'unite' => $this->productA->unite,
            'quantite' => 1, 'prix_unitaire' => 100, 'sous_total' => 100,
        ]);

        $this->actingAs($this->storekeeper)
            ->post(route('bons-commandes.livraisons.store', $bon), [
                'lignes_selectionnees' => [$ligne->id], 'quantites' => [$ligne->id => '1'],
            ])->assertForbidden();

        $this->assertSame('attente', $bon->fresh()->livraison_statut);
        $this->assertSame(0, LivraisonCommande::where('bon_commande_id', $bon->id)->count());
    }
    public function test_bon_de_commande_page_also_lists_existing_sales_documents_as_bons(): void
    {
        foreach (range(1, 3) as $number) {
            $facture = Facture::create([
                'user_id' => $this->secretary->id,
                'numero' => 'BL-TEST-' . str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'client_id' => $this->client->id,
                'client_nom' => $this->client->nom,
                'client_telephone' => $this->client->telephone,
                'statut' => 'payee',
                'mode_paiement' => 'espece',
                'sous_total' => 1500,
                'remise' => 0,
                'total' => 1500,
                'montant_recu' => 1500,
                'monnaie' => 0,
                'reste_a_payer' => 0,
            ]);
            $ligne = FactureLigne::create([
                'facture_id' => $facture->id, 'produit_id' => $this->productA->id,
                'libelle' => $this->productA->libelle, 'unite' => $this->productA->unite,
                'quantite' => 1, 'prix_unitaire' => 1500, 'sous_total' => 1500,
            ]);
            $livraison = LivraisonCommande::create([
                'bon_commande_id' => $facture->id, 'user_id' => $this->secretary->id,
                'complete' => true, 'livree_le' => now(),
            ]);
            LivraisonCommandeLigne::create([
                'livraison_id' => $livraison->id, 'bon_commande_ligne_id' => $ligne->id,
                'produit_id' => $this->productA->id, 'libelle' => $this->productA->libelle,
                'unite' => $this->productA->unite, 'quantite' => 1,
            ]);
        }

        $this->actingAs($this->secretary)
            ->get(route('bons-commandes.index', ['per_page' => 'all']))
            ->assertOk()
            ->assertSee('Livraisons')
            ->assertSee('Livraisons')
            ->assertDontSee('Montant')
            ->assertDontSee('Paiement')
            ->assertDontSee('Bons enregistr')
            ->assertSee('BL-TEST-0001')
            ->assertSee('BL-TEST-0002')
            ->assertSee('BL-TEST-0003');

        $vente = Facture::where('numero', 'BL-TEST-0001')->firstOrFail();
        $this->get(route('bons-commandes.show', $vente))
            ->assertOk()
            ->assertSee('BL-TEST-0001')
            ->assertSee('Historique des livraisons')
            ->assertSee('Produit A');

        $supervisor = User::create([
            'nom' => 'Superviseur', 'prenom' => 'Test', 'email' => 'supervisor-list@example.test',
            'password' => 'password', 'role' => 'superviseur', 'actif' => true,
        ]);
        $this->actingAs($supervisor)
            ->get(route('bons-commandes.index', ['per_page' => 'all']))
            ->assertOk()
            ->assertSee('BL-TEST-0001')
            ->assertDontSee('Nouveau bon');
        $this->assertFalse(Route::has('bons-commandes.create'));
        $this->assertFalse(Route::has('bons-commandes.store'));
    }
}
