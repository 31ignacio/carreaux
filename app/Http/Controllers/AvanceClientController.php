<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\MouvementAvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AvanceClientController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $data = $request->validate([
            'reference' => ['required', 'uuid'],
            'montant' => ['required', 'numeric', 'gt:0'],
            'mode_paiement' => ['required', Rule::in(['espece', 'mobile_money', 'carte'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $mouvement = DB::transaction(function () use ($client, $data) {
            $client = Client::whereKey($client->id)->lockForUpdate()->firstOrFail();
            $existing = MouvementAvance::where('reference', $data['reference'])->first();
            if ($existing) {
                if ($existing->client_id !== $client->id || $existing->type !== 'depot') abort(404);
                return $existing;
            }

            $mouvement = MouvementAvance::create([
                'reference' => $data['reference'], 'client_id' => $client->id,
                'user_id' => auth()->id(), 'type' => 'depot', 'montant' => round((float) $data['montant'], 2),
                'mode_paiement' => $data['mode_paiement'], 'notes' => $data['notes'] ?? null,
            ]);
            $client->increment('solde_avance', $mouvement->montant);

            return $mouvement;
        }, 3);

        $isNew = $mouvement->wasRecentlyCreated;
        return redirect()->route('clients.show', $client)->with(
            $isNew ? 'success' : 'warning',
            $isNew ? 'Avance client enregistrée.' : 'Ce dépôt avait déjà été enregistré.'
        );
    }
}
