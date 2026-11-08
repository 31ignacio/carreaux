<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmecefService
{
    private string $invoiceUrl;
    private string $infoUrl;
    private string $token;
    private string $ifu;
    private string $nim;

    public function __construct()
    {
        $this->token      = config('emecef.token');
        $this->ifu        = config('emecef.ifu');
        $this->nim        = config('emecef.nim');
        $this->invoiceUrl = config('emecef.invoice_url');
        $this->infoUrl    = config('emecef.info_url');
    }

    // ─────────────────────────────────────────
    //  1. VÉRIFIER LE STATUT DE L'API
    // ─────────────────────────────────────────
    public function status(): array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->get($this->invoiceUrl . '/');

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('eMECeF status error: ' . $e->getMessage());
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────
    //  2. SOUMETTRE UNE FACTURE (POST)
    // ─────────────────────────────────────────
    public function soumettre(array $payload): array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(15)
                ->post($this->invoiceUrl . '/', $payload);

            $data = $response->json();

            if ($response->failed() || isset($data['errorCode'])) {
                return [
                    'success'   => false,
                    'errorCode' => $data['errorCode'] ?? $response->status(),
                    'errorDesc' => $data['errorDesc'] ?? 'Erreur API e-MECeF',
                ];
            }

            return ['success' => true, 'data' => $data];

        } catch (\Exception $e) {
            Log::error('eMECeF soumettre error: ' . $e->getMessage());
            return ['success' => false, 'errorDesc' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────
    //  3. CONFIRMER UNE FACTURE (PUT)
    // ─────────────────────────────────────────
    public function confirmer(string $uid): array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(15)
                ->put("{$this->invoiceUrl}/{$uid}/confirm");

            $data = $response->json();

            if ($response->failed() || isset($data['errorCode'])) {
                return [
                    'success'   => false,
                    'errorCode' => $data['errorCode'] ?? $response->status(),
                    'errorDesc' => $data['errorDesc'] ?? 'Erreur confirmation e-MECeF',
                ];
            }

            return ['success' => true, 'data' => $data];

        } catch (\Exception $e) {
            Log::error('eMECeF confirmer error: ' . $e->getMessage());
            return ['success' => false, 'errorDesc' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────
    //  4. ANNULER UNE FACTURE (PUT)
    // ─────────────────────────────────────────
    public function annuler(string $uid): array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(15)
                ->put("{$this->invoiceUrl}/{$uid}/annuler");

            return ['success' => $response->successful(), 'data' => $response->json()];

        } catch (\Exception $e) {
            Log::error('eMECeF annuler error: ' . $e->getMessage());
            return ['success' => false, 'errorDesc' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────
    //  CONSTRUIRE LE PAYLOAD DEPUIS UNE FACTURE
    // ─────────────────────────────────────────
    public function buildPayload(\App\Models\Facture $facture): array
    {
        // Mapping mode paiement → type e-MECeF
        $paymentMap = [
            'espece'       => 'ESPECES',
            'carte'        => 'CARTEBANCAIRE',
            'mobile_money' => 'MOBILEMONEY',
            'credit'       => 'CREDIT',
            'virement'     => 'VIREMENT',
            'cheque'       => 'CHEQUES',
        ];

        $paymentType = $paymentMap[$facture->mode_paiement] ?? 'ESPECES';

        // Articles
        // $items = $facture->lignes->map(function ($ligne) {
        //     return [
        //         'name'      => $ligne->libelle,
        //         'price'     => (int) round($ligne->prix_unitaire),
        //         'quantity'  => (float) $ligne->quantite,
        //         // 'taxGroup'  => $ligne->produit->tax_group ?? 'B', // champ sur le produit
        //         'taxGroup'  => 'A' ?? 'B', // champ sur le produit
        //     ];
        // })->toArray();

        $items = $facture->lignes->map(function ($ligne, $index) {
    return [
        'name'      => $ligne->libelle,
        'price'     => (int) round($ligne->prix_unitaire),
        'quantity'  => (float) $ligne->quantite,
        'taxGroup'  => $index === 0 ? 'A' : 'A',
    ];
})->toArray();

        $payload = [
            'ifu'      => $this->ifu,
            'type'     => 'FV',
            'items'    => $items,
            'operator' => [
                'id'   => (string) $facture->user_id,
                'name' => $facture->user->prenom ?? $facture->user->nom_complet,
            ],
            'payment'  => [
                [
                    'name'   => $paymentType,
                    'amount' => (int) round($facture->total),
                ]
            ],
        ];

        // Client (optionnel)
        $clientName  = $facture->client ? trim($facture->client->nom . ' ' . $facture->client->prenom) : trim($facture->client_nom ?? '');
        $clientPhone = $facture->client ? ($facture->client->telephone ?? '') : ($facture->client_telephone ?? '');
        $clientIfu   = $facture->client ? ($facture->client->ifu ?? '') : '';

        if ($clientName !== '') {
            $payload['client'] = [
                'name'    => $clientName,
                'contact' => $clientPhone,
                'ifu'     => $clientIfu,
            ];
        }

        return $payload;
    }

    // ─────────────────────────────────────────
    //  FLUX COMPLET : SOUMETTRE + CONFIRMER
    // ─────────────────────────────────────────
    public function normaliser(\App\Models\Facture $facture): array
    {
        // 1. Construire le payload
        $payload = $this->buildPayload($facture);

        // 2. Soumettre la facture
        $submit = $this->soumettre($payload);
        if (!$submit['success']) {
            return $submit;
        }

        $uid = $submit['data']['uid'];

        // 3. Confirmer immédiatement
        $confirm = $this->confirmer($uid);
        if (!$confirm['success']) {
            // Tentative d'annulation propre
            $this->annuler($uid);
            return $confirm;
        }

        return [
            'success'      => true,
            'uid'          => $uid,
            'codeMECeFDGI' => $confirm['data']['codeMECeFDGI'],
            'qrCode'       => $confirm['data']['qrCode'],
            'dateTime'     => $confirm['data']['dateTime'],
            'counters'     => $confirm['data']['counters'],
            'nim'          => $confirm['data']['nim'],
        ];
    }

    // ─────────────────────────────────────────
//  CONSTRUIRE LE PAYLOAD D'UNE FACTURE D'AVOIR (FA)
// ─────────────────────────────────────────
public function buildAvoirPayload(\App\Models\Facture $facture): array
{
    // codeMECeFDGI sans tirets = 24 caractères (ex: "X537E4DBAJUUHHXNFWISFEKJ")
    $reference = str_replace('-', '', $facture->emecef_code);

    if (strlen($reference) !== 24) {
        throw new \Exception("codeMECeFDGI invalide pour la FA : '{$reference}' (" . strlen($reference) . " car.)");
    }

    $paymentMap = [
        'espece'       => 'ESPECES',
        'carte'        => 'CARTEBANCAIRE',
        'mobile_money' => 'MOBILEMONEY',
        'credit'       => 'CREDIT',
        'virement'     => 'VIREMENT',
        'cheque'       => 'CHEQUES',
    ];

    $paymentType = $paymentMap[$facture->mode_paiement] ?? 'ESPECES';

    $items = $facture->lignes->values()->map(function ($ligne, $index) {
        return [
            'name'      => $ligne->libelle,
            'price'     => (int) round($ligne->prix_unitaire),
            'quantity'  => (float) $ligne->quantite,
            'taxGroup'  => $index === 0 ? 'A' : 'A',
        ];
    })->toArray();

    $payload = [
        'ifu'       => $this->ifu,
        'type'      => 'FA',                    // ← Facture d'Avoir
        'reference' => $reference,              // ← 24 car. obligatoires
        'items'     => $items,
        'operator'  => [
            'id'   => (string) $facture->user_id,
            'name' => $facture->user->prenom ?? $facture->user->nom_complet,
        ],
        'payment'   => [
            [
                'name'   => $paymentType,
                'amount' => (int) round($facture->total),
            ]
        ],
    ];

    $clientName  = $facture->client ? trim($facture->client->nom . ' ' . $facture->client->prenom) : trim($facture->client_nom ?? '');
    $clientPhone = $facture->client ? ($facture->client->telephone ?? '') : ($facture->client_telephone ?? '');
    $clientIfu   = $facture->client ? ($facture->client->ifu ?? '') : '';

    if ($clientName !== '') {
        $payload['client'] = [
            'name'    => $clientName,
            'contact' => $clientPhone,
            'ifu'     => $clientIfu,
        ];
    }

    return $payload;
}

// ─────────────────────────────────────────
//  FLUX COMPLET : ÉMETTRE UNE FA (avoir = annulation)
// ─────────────────────────────────────────
public function normaliserAvoir(\App\Models\Facture $facture): array
{
    $payload = $this->buildAvoirPayload($facture);

    $submit = $this->soumettre($payload);
    if (!$submit['success']) {
        return $submit;
    }

    $uid = $submit['data']['uid'];

    $confirm = $this->confirmer($uid);
    if (!$confirm['success']) {
        $this->annuler($uid);   // annule la FA en attente si confirm échoue
        return $confirm;
    }

    return [
        'success'      => true,
        'uid'          => $uid,
        'codeMECeFDGI' => $confirm['data']['codeMECeFDGI'],
        'qrCode'       => $confirm['data']['qrCode'],
        'dateTime'     => $confirm['data']['dateTime'],
        'counters'     => $confirm['data']['counters'],
        'nim'          => $confirm['data']['nim'],
    ];
}
}