<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de livraison {{ $facture->numero }}</title>
    <style>
        @page { size: A4 landscape; margin: 11mm 13mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #263247; font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; }
        .brand { width: 100%; border-collapse: collapse; border-bottom: 2px solid #1a1a2e; }
        .brand td { padding: 0 0 11px; vertical-align: middle; }
        .brand-mark { width: 64px; height: 58px; line-height: 58px; border-radius: 9px; background: #1a1a2e; color: #fff; text-align: center; font-weight: bold; font-size: 20px; }
        .brand-name { padding-left: 10px !important; width: 42%; color: #1a1a2e; font-size: 19px; line-height: 1.05; font-weight: bold; }
        .brand-name small { display: block; margin-top: 5px; color: #6b7280; font-size: 9px; font-weight: normal; }
        .brand-description { color: #465166; text-align: right; font-size: 12px; line-height: 1.5; }
        .brand-date { display: block; margin-top: 5px; color: #1a1a2e; font-size: 10px; }
        .info-grid { width: 100%; border-collapse: separate; border-spacing: 9px 12px; margin: 0 -9px; }
        .info-grid td { width: 50%; vertical-align: top; }
        .info-box { min-height: 74px; padding: 10px 12px; border: 1px solid #d9deea; border-left: 4px solid #1a1a2e; background: #f4f5fa; }
        .info-box.client { border-left-color: #69738a; background: #f8f9fb; }
        .info-title { margin-bottom: 7px; color: #1a1a2e; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; }
        .info-value { margin: 3px 0; font-size: 11px; }
        .order-reference { padding: 0 3px 10px; color: #354158; font-size: 11px; }
        .order-reference strong { color: #1a1a2e; }
        table.products { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .products thead { display: table-header-group; }
        .products th { padding: 9px 7px; color: #fff; background: #1a1a2e; border: 1px solid #1a1a2e; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .35px; }
        .products td { padding: 8px 7px; border-bottom: 1px solid #e2e5ec; font-size: 10px; }
        .products tbody tr:nth-child(even) { background: #f7f8fb; }
        .center { text-align: center !important; }
        .right { text-align: right !important; white-space: nowrap; }
        .totals { width: 100%; margin-top: 8px; border-collapse: collapse; }
        .totals td { padding: 6px 8px; }
        .totals-label { width: 78%; text-align: right; color: #465166; }
        .total-row td { border-top: 1px solid #cfd4df; color: #1a1a2e; font-size: 13px; font-weight: bold; }
        .signatures { width: 100%; margin-top: 42px; border-collapse: collapse; }
        .signatures td { width: 50%; padding: 0 8px; vertical-align: top; }
        .signature-label { display: inline-block; min-width: 150px; padding-bottom: 4px; border-bottom: 1px solid #6b7280; color: #1a1a2e; font-size: 10px; font-weight: bold; }
        .signature-right { text-align: right; }
        .foot { margin-top: 19px; padding-top: 6px; border-top: 1px solid #e2e5ec; color: #8790a0; text-align: center; font-size: 8px; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $client = $facture->client;
    $clientNom = $client
        ? trim(($client->nom ?? '') . ' ' . ($client->prenom ?? ''))
        : ($facture->client_nom ?: 'Client divers');
    $clientTelephone = $client->telephone ?? $facture->client_telephone ?? 'Non renseigné';
    $commande = $facture->operation_reference ?: 'N/S';
@endphp

<table class="brand">
    <tr>
        <td style="width:64px"><div class="brand-mark">G83</div></td>
        <td class="brand-name">GAMACO 83<small>Vente et distribution</small></td>
        <td class="brand-description">
            Vente de matériaux et fournitures<br>pour vos besoins professionnels et particuliers
            <span class="brand-date">DATE : {{ optional($facture->created_at)->format('d/m/Y') }}</span>
        </td>
    </tr>
</table>

<table class="info-grid">
    <tr>
        <td><div class="info-box">
            <div class="info-title">Bordereau de livraison</div>
            <div class="info-value"><strong>N° {{ $facture->numero }}</strong></div>
        </div></td>
        <td><div class="info-box client">
            <div class="info-title">Client</div>
            <div class="info-value">Nom : <strong>{{ $clientNom }}</strong></div>
            <div class="info-value">Contact : {{ $clientTelephone }}</div>
        </div></td>
    </tr>
</table>

<div class="order-reference"><strong>Commande :</strong> {{ $commande }}</div>

<table class="products">
    <thead>
        <tr>
            <th class="center" style="width:5%">#</th>
            <th style="width:39%">Désignation</th>
            <th class="center" style="width:13%">Quantité</th>
            <th class="center" style="width:13%">UGS</th>
            <th class="right" style="width:14%">P. U.</th>
            <th class="right" style="width:16%">Montant</th>
        </tr>
    </thead>
    <tbody>
        @foreach($facture->lignes as $i => $ligne)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td><strong>{{ $ligne->libelle }}</strong></td>
                <td class="center">{{ rtrim(rtrim(number_format((float)$ligne->quantite, 2, ',', ' '), '0'), ',') }}</td>
                <td class="center">{{ $ligne->unite ?? $ligne->produit?->unite ?? '—' }}</td>
                <td class="right">{{ number_format((float)$ligne->prix_unitaire, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((float)$ligne->sous_total, 0, ',', ' ') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="totals">
    @if((float)$facture->remise > 0)
        <tr><td class="totals-label">Sous-total</td><td class="right">{{ number_format((float)$facture->sous_total, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="totals-label">Remise</td><td class="right">- {{ number_format((float)$facture->remise, 0, ',', ' ') }} FCFA</td></tr>
    @endif
    <tr class="total-row"><td class="totals-label">TOTAL</td><td class="right">{{ number_format((float)$facture->total, 0, ',', ' ') }} FCFA</td></tr>
</table>

<table class="signatures">
    <tr>
        <td><span class="signature-label">Le livreur</span></td>
        <td class="signature-right"><span class="signature-label">Le réceptionniste</span></td>
    </tr>
</table>

<div class="foot">Bon de livraison {{ $facture->numero }} · GAMACO 83</div>
</body>
</html>
