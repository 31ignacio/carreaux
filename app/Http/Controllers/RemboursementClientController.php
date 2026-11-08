<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use App\Models\Reglement;
use App\Models\RemboursementClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RemboursementClientController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $data = $request->validate([
            'reference' => ['required', 'uuid'],
            'montant' => ['required', 'numeric', 'gt:0'],
            'mode_paiement' => ['required', Rule::in(['espece', 'mobile_money', 'carte'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $remboursement = DB::transaction(function () use ($client, $data) {
                $lockedClient = Client::whereKey($client->id)->lockForUpdate()->firstOrFail();

                $existing = RemboursementClient::where('reference', $data['reference'])->first();
                if ($existing) {
                    abort_if($existing->client_id !== $lockedClient->id, 404);
                    return $existing;
                }

                $factures = Facture::ventes()->where('client_id', $lockedClient->id)
                    ->where('mode_paiement', 'credit')->where('statut', '!=', 'annulee')
                    ->orderBy('created_at')->orderBy('id')->lockForUpdate()->get();

                $dus = $factures->mapWithKeys(fn ($facture) => [
                    $facture->id => max((float) $facture->total - (float) $facture->montant_avance - (float) $facture->reglements()->sum('montant'), 0),
                ]);
                $solde = round($dus->sum(), 2);
                $montant = round((float) $data['montant'], 2);

                if ($solde <= 0 || $montant > $solde) {
                    throw ValidationException::withMessages(['montant' => 'Le versement dépasse le solde dû (' . number_format($solde, 0, ',', ' ') . ' FCFA).']);
                }

                $payment = RemboursementClient::create([
                    'reference' => $data['reference'], 'client_id' => $lockedClient->id,
                    'user_id' => auth()->id(), 'montant' => $montant,
                    'mode_paiement' => $data['mode_paiement'], 'notes' => $data['notes'] ?? null,
                ]);

                $aRepartir = $montant;
                foreach ($factures as $facture) {
                    $resteFacture = (float) $dus[$facture->id];
                    if ($resteFacture <= 0 || $aRepartir <= 0) continue;

                    $affecte = min($aRepartir, $resteFacture);
                    Reglement::create([
                        'facture_id' => $facture->id, 'user_id' => auth()->id(),
                        'montant' => $affecte, 'mode_paiement' => $data['mode_paiement'],
                        'notes' => 'Remboursement client ' . $payment->reference,
                    ]);
                    $nouveauReste = round($resteFacture - $affecte, 2);
                    $facture->update(['reste_a_payer' => $nouveauReste, 'statut' => $nouveauReste <= 0 ? 'payee' : 'en_cours']);
                    $aRepartir = round($aRepartir - $affecte, 2);
                }

                return $payment;
            }, 3);
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $existing = RemboursementClient::where('reference', $data['reference'])->first();
            if ($existing) {
                if ($request->expectsJson()) return $this->receipt($existing);

                return redirect()->route('remboursements.recus', $existing)->with('warning', 'Ce remboursement avait déjà été enregistré.');
            }
            throw $e;
        }

        if ($request->expectsJson()) return $this->receipt($remboursement);

        return redirect()->route('remboursements.recus', $remboursement)->with(
            $remboursement->wasRecentlyCreated ? 'success' : 'warning',
            $remboursement->wasRecentlyCreated ? 'Remboursement enregistré et réparti sur les factures ouvertes.' : 'Ce remboursement avait déjà été enregistré.'
        );
    }

    public function index(Request $request)
    {
        $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
        ]);

        $query = RemboursementClient::with(['client', 'user']);
        if ($request->filled('date_debut')) $query->whereDate('created_at', '>=', $request->date_debut);
        if ($request->filled('date_fin')) $query->whereDate('created_at', '<=', $request->date_fin);
        if ($request->filled('search')) $query->whereHas('client', fn ($q) => $q->where('nom', 'like', '%' . $request->search . '%')->orWhere('prenom', 'like', '%' . $request->search . '%'));
        if ($request->filled('client_id')) $query->where('client_id', $request->client_id);

        $remboursements = $query->latest()->paginate(25)->withQueryString();
        $total = (clone $query)->sum('montant');
        $nombre = (clone $query)->count();
        $clients = Client::query()
            ->whereHas('remboursements')
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom']);

        return view('remboursements.index', compact('remboursements', 'total', 'nombre', 'clients'));
    }

    public function receipt(RemboursementClient $remboursement)
    {
        $remboursement->load(['client', 'user']);
        $factures = Facture::ventes()->where('client_id', $remboursement->client_id)
            ->where('mode_paiement', 'credit')
            ->where('statut', '!=', 'annulee')
            ->withSum('reglements', 'montant')
            ->get(['id', 'total', 'montant_avance']);
        $resteArembourser = $factures->sum(fn ($facture) => max(
            (float) $facture->total - (float) $facture->montant_avance - (float) $facture->reglements_sum_montant,
            0
        ));

        return Pdf::loadView('remboursements.receipt', compact('remboursement', 'resteArembourser'))
            ->setPaper('a4')
            ->download('recu-remboursement-' . $remboursement->reference . '.pdf');
    }
}
