<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de livraison {{ $bonCommande->reference }}</title>
    <style>
        @page { size: A4 landscape; margin: 11mm 13mm; }
        * { box-sizing: border-box; }
        body { margin:0; color:#263247; font-family:DejaVu Sans,Arial,sans-serif; font-size:11px; }
        .brand { width:100%; border-collapse:collapse; border-bottom:2px solid #1a1a2e; }
        .brand td { padding:0 0 11px; vertical-align:middle; }
        .brand-mark { width:64px; height:58px; line-height:58px; border-radius:9px; background:#1a1a2e; color:#fff; text-align:center; font-size:20px; font-weight:bold; }
        .brand-name { width:42%; padding-left:10px!important; color:#1a1a2e; font-size:19px; line-height:1.05; font-weight:bold; }
        .brand-name small { display:block; margin-top:5px; color:#6b7280; font-size:9px; font-weight:normal; }
        .brand-description { color:#465166; text-align:right; font-size:12px; line-height:1.5; }
        .brand-date { display:block; margin-top:5px; color:#1a1a2e; font-size:10px; }
        .info-grid { width:100%; margin:0 -9px; border-collapse:separate; border-spacing:9px 12px; }
        .info-grid td { width:50%; vertical-align:top; }
        .info-box { min-height:76px; padding:10px 12px; border:1px solid #d9deea; border-left:4px solid #1a1a2e; background:#f4f5fa; }
        .info-box.client { border-left-color:#69738a; background:#f8f9fb; }
        .info-title { margin-bottom:7px; color:#1a1a2e; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:.5px; }
        .info-value { margin:3px 0; font-size:11px; }
        .ref-line { padding:0 3px 10px; color:#354158; font-size:11px; }
        .ref-line strong { color:#1a1a2e; }
        table.items { width:100%; margin-top:5px; border-collapse:collapse; }
        .items thead { display:table-header-group; }
        .items th { padding:9px 7px; border:1px solid #1a1a2e; color:#fff; background:#1a1a2e; text-align:left; font-size:10px; text-transform:uppercase; letter-spacing:.35px; }
        .items td { padding:9px 7px; border-bottom:1px solid #e2e5ec; font-size:10px; }
        .items tbody tr:nth-child(even) { background:#f7f8fb; }
        .center { text-align:center!important; }
        .right { text-align:right!important; white-space:nowrap; }
        .signatures { width:100%; margin-top:48px; border-collapse:collapse; }
        .signatures td { width:50%; padding:0 8px; vertical-align:top; }
        .signature-label { display:inline-block; min-width:155px; padding-bottom:4px; border-bottom:1px solid #6b7280; color:#1a1a2e; font-size:10px; font-weight:bold; }
        .signature-right { text-align:right; }
        .foot { margin-top:20px; padding-top:6px; border-top:1px solid #e2e5ec; color:#8790a0; text-align:center; font-size:8px; }
        tr { page-break-inside:avoid; }
    </style>
</head>
<body>
@php
    $client = $bonCommande->client;
    $clientNom = $client
        ? trim(($client->nom ?? '') . ' ' . ($client->prenom ?? ''))
        : ($bonCommande->client_nom ?: 'Client divers');
    $telephone = $client?->telephone ?? $bonCommande->client_telephone ?? 'Non renseigné';
@endphp

<table class="brand"><tr>
    <td style="width:64px"><div class="brand-mark">G83</div></td>
    <td class="brand-name">GAMACO 83<small>Vente et distribution</small></td>
    <td class="brand-description">Vente de matériaux et fournitures<br>pour vos besoins professionnels et particuliers
        <span class="brand-date">DATE : {{ $livraison->livree_le->format('d/m/Y') }}</span>
    </td>
</tr></table>

<table class="info-grid"><tr>
    <td><div class="info-box">
        <div class="info-title">Bon de livraison</div>
        <div class="info-value"><strong>N° {{ $bonCommande->reference }}-{{ str_pad((string)$livraison->id, 3, '0', STR_PAD_LEFT) }}</strong></div>
        <div class="info-value">Commande : {{ $bonCommande->reference }}</div>
    </div></td>
    <td><div class="info-box client">
        <div class="info-title">Client</div>
        <div class="info-value">Nom : <strong>{{ $clientNom }}</strong></div>
        <div class="info-value">Contact : {{ $telephone }}</div>
    </div></td>
</tr></table>

<div class="ref-line"><strong>Date de commande :</strong> {{ $bonCommande->created_at->format('d/m/Y à H:i') }}
    &nbsp;&nbsp; <strong>Date de livraison :</strong> {{ $livraison->livree_le->format('d/m/Y à H:i') }}
    &nbsp;&nbsp; <strong>Préparé par :</strong> {{ $livraison->user->nom_complet ?? '—' }}
</div>

<table class="items">
    <thead><tr>
        <th class="center" style="width:6%">#</th>
        <th style="width:59%">Désignation</th>
        <th class="center" style="width:17%">Quantité remise</th>
        <th class="center" style="width:18%">UGS</th>
    </tr></thead>
    <tbody>
        @forelse($livraison->lignes as $i => $ligne)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td><strong>{{ $ligne->libelle }}</strong></td>
                <td class="center">{{ rtrim(rtrim(number_format((float)$ligne->quantite, 2, ',', ' '), '0'), ',') }}</td>
                <td class="center">{{ $ligne->unite ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="center">Aucun article enregistré pour cette livraison.</td></tr>
        @endforelse
    </tbody>
</table>

<table class="signatures"><tr>
    <td><span class="signature-label">Le livreur</span></td>
    <td class="signature-right"><span class="signature-label">Le réceptionniste</span></td>
</tr></table>

<div class="foot">Ce bon présente uniquement les articles remis lors de cette livraison · GAMACO 83</div>
</body>
</html>
