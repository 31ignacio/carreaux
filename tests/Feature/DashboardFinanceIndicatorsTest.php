<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Depense;
use App\Models\Facture;
use App\Models\MouvementAvance;
use App\Models\Reglement;
use App\Models\RemboursementClient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardFinanceIndicatorsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::parse('2026-10-16 12:00:00', config('app.timezone')));

        $this->user = User::create([
            'nom' => 'Test',
            'prenom' => 'Admin',
            'email' => 'dashboard@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $this->client = Client::create(['nom' => 'Client test', 'type' => 'particulier']);
        $this->actingAs($this->user);
    }

    public function test_dashboard_financial_indicators_use_current_month_movements_and_exclude_future_or_cancelled_records(): void
    {
        $currentCredit = $this->createCreditInvoice(10000, '2026-10-01 08:00:00');
        $this->createReglement($currentCredit, 400, '2026-10-05 09:00:00');
        $this->createReglement($currentCredit, 1600, '2026-10-16 11:00:00');
        $this->createReglement($currentCredit, 2000, '2026-10-16 13:00:00');

        $this->createCreditInvoice(9000, '2026-09-30 23:59:00');
        $this->createCreditInvoice(4000, '2026-10-10 10:00:00', 'annulee');
        $this->createCreditInvoice(7000, '2026-10-17 00:00:00');

        $this->createRefund(500, '2026-10-01 00:00:00');
        $this->createRefund(100, '2026-10-16 11:00:00');
        $this->createRefund(1000, '2026-10-16 13:00:00');
        $this->createRefund(3000, '2026-09-30 23:59:00');

        $this->createExpense(600, '2026-10-01', false);
        $this->createExpense(650, '2026-10-16', false);
        $this->createExpense(900, '2026-10-16', true);
        $this->createExpense(6000, '2026-09-30', false);
        $this->createExpense(3000, '2026-10-17', false);

        $this->createAdvanceMovement('depot', 1300, '2026-10-01 00:00:00');
        $this->createAdvanceMovement('utilisation', 500, '2026-10-16 11:00:00');
        $this->createAdvanceMovement('depot', 3000, '2026-10-16 13:00:00');
        $this->createAdvanceMovement('depot', 4000, '2026-09-30 23:59:00');
        $this->client->forceFill(['solde_avance' => 6000])->save();

        $response = $this->get(route('dashboard'))->assertOk();

        $response->assertSee('01/10/2026');
        $response->assertSee('16/10/2026');
        $response->assertSee('7 000');
        $response->assertSee('600');
        $response->assertSee('1 250');
        $response->assertSee('800');
        $response->assertDontSee('6 000');
        $response->assertDontSee('3 000');
        $response->assertSee('Mouvements nets d’avances');
        $response->assertSee('Gérer les dépenses');

        $response->assertDontSee('Dernières factures');
        $response->assertDontSee('Produits en alerte');
        $response->assertDontSee('Stock faible');
        $response->assertDontSee('Rupture de stock');
        $response->assertDontSee('Dettes restantes par client');
        $response->assertDontSee('Derniers remboursements');

        $this->user->forceFill(['role' => 'caissier'])->save();
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mouvements nets d’avances')
            ->assertSee('Caisse');
    }

    private function createCreditInvoice(float $amount, string $createdAt, string $status = 'en_cours'): Facture
    {
        $invoice = Facture::create([
            'user_id' => $this->user->id,
            'numero' => 'F-' . Str::uuid(),
            'client_id' => $this->client->id,
            'client_nom' => $this->client->nom,
            'statut' => $status,
            'mode_paiement' => 'credit',
            'sous_total' => $amount,
            'remise' => 0,
            'total' => $amount,
            'montant_recu' => 0,
            'montant_avance' => 1000,
            'monnaie' => 0,
            'reste_a_payer' => $amount - 1000,
        ]);
        $invoice->forceFill(['created_at' => $createdAt])->saveQuietly();

        return $invoice;
    }

    private function createReglement(Facture $invoice, float $amount, string $createdAt): void
    {
        $reglement = Reglement::create([
            'facture_id' => $invoice->id,
            'user_id' => $this->user->id,
            'montant' => $amount,
            'mode_paiement' => 'espece',
        ]);
        $reglement->forceFill(['created_at' => $createdAt])->saveQuietly();
    }

    private function createRefund(float $amount, string $createdAt): void
    {
        $refund = RemboursementClient::create([
            'reference' => (string) Str::uuid(),
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
            'montant' => $amount,
            'mode_paiement' => 'espece',
        ]);
        $refund->forceFill(['created_at' => $createdAt])->saveQuietly();
    }

    private function createExpense(float $amount, string $date, bool $cancelled): void
    {
        Depense::create([
            'designation' => 'Dépense de test',
            'categorie' => 'Test',
            'montant' => $amount,
            'date_depense' => $date,
            'mode_paiement' => 'espece',
            'user_id' => $this->user->id,
            'annulee_at' => $cancelled ? now() : null,
        ]);
    }

    private function createAdvanceMovement(string $type, float $amount, string $createdAt): void
    {
        $movement = MouvementAvance::create([
            'reference' => (string) Str::uuid(),
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
            'type' => $type,
            'montant' => $amount,
        ]);
        $movement->forceFill(['created_at' => $createdAt])->saveQuietly();
    }
}
