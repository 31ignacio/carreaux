<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Depense;
use App\Models\Categorie;
use App\Models\Facture;
use App\Models\MouvementAvance;
use App\Models\Produit;
use App\Models\RemboursementClient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GestionFinanciereTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create(['nom' => 'Test', 'prenom' => 'Agent', 'email' => 'agent@example.test', 'password' => 'password', 'role' => 'admin']);
        $this->client = Client::create(['nom' => 'Client', 'type' => 'particulier']);
        $this->actingAs($this->user);
    }

    private function factureCredit(float $montant): Facture
    {
        return Facture::create([
            'user_id' => $this->user->id, 'numero' => Facture::genererNumero(), 'client_id' => $this->client->id,
            'client_nom' => $this->client->nom, 'statut' => 'en_cours', 'mode_paiement' => 'credit',
            'sous_total' => $montant, 'remise' => 0, 'total' => $montant,
            'montant_recu' => 0, 'monnaie' => 0, 'reste_a_payer' => $montant,
        ]);
    }

    private function produitPourVente(): Produit
    {
        $categorie = Categorie::create(['nom' => 'Tests']);
        return Produit::create([
            'categorie_id' => $categorie->id, 'libelle' => 'Produit test', 'prix_detail' => 100,
            'stock_actuel' => 100, 'stock_minimum' => 0, 'actif' => true,
        ]);
    }

    private function vendreAvecAvance(Produit $produit, int $quantite, ?string $reference = null): void
    {
        $this->postJson(route('caisse.valider'), [
            'operation_reference' => $reference ?? (string) Str::uuid(),
            'mode_paiement' => 'espece', 'montant_recu' => 0,
            'client_id' => $this->client->id, 'client_nom' => $this->client->nom,
            'produits' => [['id' => $produit->id, 'quantite' => $quantite, 'prix_unitaire' => 100]],
        ])->assertOk()->assertJson(['success' => true]);
    }

    public function test_discounted_cash_sale_is_not_recorded_as_credit(): void
    {
        $product = $this->produitPourVente();

        $this->postJson(route('caisse.valider'), [
            'operation_reference' => (string) Str::uuid(),
            'mode_paiement' => 'espece',
            'montant_recu' => 1000,
            'remise' => 200,
            'produits' => [['id' => $product->id, 'quantite' => 10, 'prix_unitaire' => 100]],
        ])->assertOk()
            ->assertJsonPath('is_credit', false)
            ->assertJsonPath('monnaie', 200);

        $invoice = Facture::latest('id')->firstOrFail();
        $this->assertSame('vente', $invoice->nature);
        $this->assertSame('attente', $invoice->livraison_statut);
        $this->assertSame(0, $invoice->livraisons()->count());
        $this->assertSame(800.0, (float) $invoice->total);
        $this->assertSame('espece', $invoice->mode_paiement);
        $this->assertSame('payee', $invoice->statut);
        $this->assertSame(0.0, (float) $invoice->reste_a_payer);
        $this->assertSame(800.0, (float) $invoice->reglements()->sum('montant'));
        $this->assertSame(200.0, (float) $invoice->monnaie);
    }

    public function test_credit_sale_without_client_returns_json_validation_error(): void
    {
        $product = $this->produitPourVente();

        $this->postJson(route('caisse.valider'), [
            'operation_reference' => (string) Str::uuid(),
            'mode_paiement' => 'credit',
            'montant_recu' => 0,
            'produits' => [['id' => $product->id, 'quantite' => 1, 'prix_unitaire' => 100]],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('client_id');
    }

    public function test_customer_can_top_up_advance_and_use_it_across_multiple_invoices(): void
    {
        $reference = (string) Str::uuid();
        $payload = ['reference' => $reference, 'montant' => 2000, 'mode_paiement' => 'espece'];
        $this->post(route('clients.avances.store', $this->client), $payload)->assertRedirect();
        $this->post(route('clients.avances.store', $this->client), $payload)->assertRedirect();
        $this->assertSame(2000.0, (float) $this->client->fresh()->solde_avance);
        $this->assertSame(1, $this->client->mouvementsAvance()->where('type', 'depot')->count());
        $this->get(route('clients.show', $this->client))->assertOk()->assertSee('Avances')->assertSee($reference);

        $product = $this->produitPourVente();
        foreach ([5, 7, 8] as $quantity) $this->vendreAvecAvance($product, $quantity);

        $this->assertSame(3, $this->client->factures()->count());
        $this->assertSame(0.0, (float) $this->client->fresh()->solde_avance);
        $this->assertSame(0.0, (float) $this->client->factures()->sum('reste_a_payer'));
        $this->assertSame(2000.0, (float) $this->client->factures()->sum('montant_avance'));
        $this->assertSame(4, $this->client->mouvementsAvance()->count());
    }

    public function test_purchase_exceeding_advance_consumes_balance_and_creates_credit_once(): void
    {
        $this->post(route('clients.avances.store', $this->client), [
            'reference' => (string) Str::uuid(), 'montant' => 1000, 'mode_paiement' => 'mobile_money',
        ])->assertRedirect();

        $product = $this->produitPourVente();
        $this->vendreAvecAvance($product, 2);
        $ref = (string) Str::uuid();
        $this->vendreAvecAvance($product, 9, $ref);
        $this->vendreAvecAvance($product, 9, $ref);

        $invoice = $this->client->factures()->latest('id')->firstOrFail();
        $this->assertSame(0.0, (float) $this->client->fresh()->solde_avance);
        $this->assertSame(800.0, (float) $invoice->montant_avance);
        $this->assertSame(100.0, (float) $invoice->fresh()->reste_a_payer);
        $this->assertSame('credit', $invoice->mode_paiement);
        $this->assertSame(2, $this->client->factures()->count());
        $this->assertSame(3, $this->client->mouvementsAvance()->count());
        $this->patch(route('factures.annuler', $invoice))->assertSessionHas('error');
        $this->delete(route('factures.destroy', $invoice))->assertSessionHas('error');
        $this->assertSame('en_cours', $invoice->fresh()->statut);
    }

    public function test_customer_repayments_allocate_across_open_invoices_and_reach_zero(): void
    {
        $first = $this->factureCredit(4000);
        $second = $this->factureCredit(1000);

        foreach ([2000, 1000, 2000] as $index => $amount) {
            $this->post(route('clients.remboursements.store', $this->client), [
                'reference' => (string) Str::uuid(), 'montant' => $amount, 'mode_paiement' => 'espece',
            ])->assertRedirect();
        }

        $this->assertSame(3, $this->client->remboursements()->count());
        $this->assertSame(5000.0, (float) $this->client->remboursements()->sum('montant'));
        $this->assertSame(5000.0, (float) $first->reglements()->sum('montant') + (float) $second->reglements()->sum('montant'));
        $this->assertSame('payee', $first->fresh()->statut);
        $this->assertSame('payee', $second->fresh()->statut);
        $this->assertSame(0.0, (float) $first->fresh()->reste_a_payer + (float) $second->fresh()->reste_a_payer);
    }

    public function test_repayment_cannot_exceed_balance_and_duplicate_reference_is_idempotent(): void
    {
        $this->factureCredit(1000);
        $reference = (string) Str::uuid();
        $payload = ['reference' => $reference, 'montant' => 1001, 'mode_paiement' => 'carte'];
        $this->from('/clients/' . $this->client->id)->post(route('clients.remboursements.store', $this->client), $payload)->assertSessionHasErrors('montant');

        $payload['montant'] = 500;
        $this->post(route('clients.remboursements.store', $this->client), $payload)->assertRedirect();
        $this->post(route('clients.remboursements.store', $this->client), $payload)->assertRedirect();
        $this->assertSame(1, $this->client->remboursements()->count());
        $this->assertSame(500.0, (float) $this->client->remboursements()->sum('montant'));
    }

    public function test_client_repayment_submission_returns_pdf_for_modal_download(): void
    {
        $invoice = $this->factureCredit(1000);

        $response = $this->postJson(route('clients.remboursements.store', $this->client), [
            'reference' => (string) Str::uuid(), 'montant' => 400, 'mode_paiement' => 'espece',
        ])->assertOk()->assertHeader('content-type', 'application/pdf');

        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertSame(600.0, (float) $invoice->fresh()->reste_a_payer);
    }

    public function test_credit_period_filter_and_newest_debt_order(): void
    {
        $oldInvoice = $this->factureCredit(1000);
        $oldInvoice->forceFill(['created_at' => now()->parse('2026-05-10 09:00:00')])->saveQuietly();
        $latestInvoice = $this->factureCredit(500);
        $latestInvoice->forceFill(['created_at' => now()->parse('2026-05-20 09:00:00')])->saveQuietly();

        $otherClient = Client::create(['nom' => 'Client Plus Recent', 'type' => 'particulier']);
        $otherInvoice = Facture::create([
            'user_id' => $this->user->id, 'numero' => Facture::genererNumero(), 'client_id' => $otherClient->id,
            'client_nom' => $otherClient->nom, 'statut' => 'en_cours', 'mode_paiement' => 'credit',
            'sous_total' => 750, 'remise' => 0, 'total' => 750, 'montant_recu' => 0,
            'monnaie' => 0, 'reste_a_payer' => 750,
        ]);
        $otherInvoice->forceFill(['created_at' => now()->parse('2026-05-25 09:00:00')])->saveQuietly();

        $this->get(route('credits.index', ['date_debut' => '2026-05-15', 'date_fin' => '2026-05-31']))
            ->assertOk()
            ->assertSee($latestInvoice->numero)
            ->assertSee($otherInvoice->numero)
            ->assertDontSee($oldInvoice->numero)
            ->assertViewHas('clients', function ($clients) use ($otherClient) {
                return $clients->first()->id === $otherClient->id;
            });
    }

    public function test_a_credit_invoice_with_allocated_repayments_cannot_be_cancelled_or_deleted(): void
    {
        $invoice = $this->factureCredit(1000);
        $this->post(route('clients.remboursements.store', $this->client), [
            'reference' => (string) Str::uuid(), 'montant' => 200, 'mode_paiement' => 'espece',
        ])->assertRedirect();

        $this->patch(route('factures.annuler', $invoice))->assertSessionHas('error');
        $this->delete(route('factures.destroy', $invoice))->assertSessionHas('error');
        $this->assertDatabaseHas('factures', ['id' => $invoice->id, 'statut' => 'en_cours']);
    }

    public function test_refund_history_filters_by_client_and_receipt_downloads_as_pdf(): void
    {
        $otherClient = Client::create(['nom' => 'Autre', 'prenom' => 'Client', 'type' => 'particulier']);
        $refund = RemboursementClient::create([
            'reference' => (string) Str::uuid(), 'client_id' => $this->client->id,
            'user_id' => $this->user->id, 'montant' => 750, 'mode_paiement' => 'espece',
        ]);
        $otherRefund = RemboursementClient::create([
            'reference' => (string) Str::uuid(), 'client_id' => $otherClient->id,
            'user_id' => $this->user->id, 'montant' => 1250, 'mode_paiement' => 'carte',
        ]);

        $this->get(route('remboursements.index', ['client_id' => $this->client->id]))
            ->assertOk()
            ->assertSee(substr($refund->reference, 0, 8))
            ->assertDontSee(substr($otherRefund->reference, 0, 8))
            ->assertSee('750 FCFA');

        $response = $this->get(route('remboursements.recus', $refund))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString($refund->reference . '.pdf', $response->headers->get('content-disposition'));
    }

    public function test_expense_can_be_recorded_and_cancelled_with_audit_reason(): void
    {
        $this->post(route('depenses.store'), [
            'designation' => 'Transport', 'categorie' => 'Logistique', 'montant' => 2500,
            'date_depense' => today()->toDateString(), 'mode_paiement' => 'espece', 'beneficiaire' => 'Livreur',
        ])->assertRedirect();

        $expense = Depense::firstOrFail();
        $this->patch(route('depenses.annuler', $expense), ['motif_annulation' => 'Saisie erronée'])->assertRedirect();
        $this->assertNotNull($expense->fresh()->annulee_at);
        $this->assertSame('Saisie erronée', $expense->fresh()->motif_annulation);
        $this->assertSame(0.0, (float) Depense::whereNull('annulee_at')->sum('montant'));
    }

    public function test_expense_changes_do_not_create_a_modification_history(): void
    {
        $expense = Depense::create([
            'designation' => 'Ancienne désignation', 'categorie' => 'Bureau', 'montant' => 1000,
            'date_depense' => today(), 'mode_paiement' => 'espece', 'user_id' => $this->user->id,
        ]);
        $this->put(route('depenses.update', $expense), [
            'designation' => 'Nouvelle désignation', 'categorie' => 'Bureau', 'montant' => 1500,
            'date_depense' => today()->toDateString(), 'mode_paiement' => 'carte',
        ])->assertRedirect();

        $this->assertSame(0, $expense->historiques()->count());
        return;
        $this->assertSame('Ancienne désignation', $history->avant['designation']);
        $this->assertSame('Nouvelle désignation', $history->apres['designation']);
    }

    public function test_finance_pages_render_for_authenticated_users(): void
    {
        $this->get(route('clients.index'))->assertOk();
        $this->get(route('clients.show', $this->client))->assertOk();
        $this->get(route('credits.index'))->assertOk();
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('depenses.index'))->assertOk()->assertSee('modalCreateDepense')->assertSee('modalEditDepense')->assertDontSee('Historique des modifications');
        $this->get(route('remboursements.index'))->assertOk();
        $this->get(route('rapports.finances'))->assertOk();
    }
}
