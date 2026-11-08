<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reste à livrer {{ $bonCommande->reference }}</title>
    <style>
        @page { size:A4 landscape; margin:11mm 13mm; }
        * { box-sizing:border-box; }
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
        .state { display:inline-block; padding:4px 9px; border-radius:12px; background:#f4f5fa; color:#1a1a2e; font-size:9px; font-weight:bold; }
        table.items { width:100%; margin-top:5px; border-collapse:collapse; }
        .items thead { display:table-header-group; }
        .items th { padding:9px 7px; border:1px solid #1a1a2e; color:#fff; background:#1a1a2e; text-align:left; font-size:10px; text-transform:uppercase; letter-spacing:.35px; }
        .items td { padding:9px 7px; border-bottom:1px solid #e2e5ec; font-size:10px; }
        .items tbody tr:nth-child(even) { background:#f7f8fb; }
        .center { text-align:center!important; }
        .right { text-align:right!important; white-space:nowrap; }
        .empty { padding:24px!important; color:#64748b; text-align:center; }
        .foot { margin-top:28px; padding-top:6px; border-top:1px solid #e2e5ec; color:#8790a0; text-align:center; font-size:8px; }
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
    $derniereLivraison = $bonCommande->livraisons->sortByDesc('livree_le')->first();
@endphp

<table class="brand"><tr>
    <td style="width:64px"><div class="brand-mark">G83</div></td>
    <td class="brand-name">GAMACO 83<small>Vente et distribution</small></td>
    <td class="brand-description">Vente de matériaux et fournitures<br>pour vos besoins professionnels et particuliers
        <span class="brand-date">ÉDITÉ LE : {{ now()->format('d/m/Y') }}</span>
    </td>
</tr></table>

<table class="info-grid"><tr>
    <td><div class="info-box">
        <div class="info-title">Récapitulatif du reste à livrer</div>
        <div class="info-value"><strong>Bon N° {{ $bonCommande->reference }}</strong></div>
        <div class="info-value">Commande du {{ $bonCommande->created_at->format('d/m/Y à H:i') }}</div>
    </div></td>
    <td><div class="info-box client">
        <div class="info-title">Client</div>
        <div class="info-value">Nom : <strong>{{ $clientNom }}</strong></div>
        <div class="info-value">Contact : {{ $telephone }}</div>
    </div></td>
</tr></table>

<div class="ref-line">
    <strong>Dernière livraison :</strong> {{ $derniereLivraison ? $derniereLivraison->livree_le->format('d/m/Y à H:i') : 'Aucune livraison' }}
    &nbsp;&nbsp; <strong>État :</strong> <span class="state">{{ $bonCommande->livraison_statut_label }}</span>
</div>

<table class="items">
    <thead><tr>
        <th class="center" style="width:6%">#</th>
        <th style="width:44%">Désignation</th>
        <th class="center" style="width:16%">Quantité commandée</th>
        <th class="center" style="width:16%">Déjà livrée</th>
        <th class="center" style="width:18%">Reste à récupérer</th>
    </tr></thead>
    <tbody>
        @forelse($lignesRestantes as $i => $ligne)
            @php
                $livree = (float)($ligne->quantite_livree ?? 0);
                $reste = max((float)$ligne->quantite_commandee - $livree, 0);
                $formatQte = fn ($qte) => rtrim(rtrim(number_format((float)$qte, 2, ',', ' '), '0'), ',');
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td><strong>{{ $ligne->libelle }}</strong></td>
                <td class="center">{{ $formatQte($ligne->quantite_commandee) }} {{ $ligne->unite }}</td>
                <td class="center">{{ $formatQte($livree) }} {{ $ligne->unite }}</td>
                <td class="center"><strong>{{ $formatQte($reste) }} {{ $ligne->unite }}</strong></td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">Aucun article ne reste à livrer. La commande est entièrement livrée.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="foot">Ticket de suivi du bon {{ $bonCommande->reference }} · Édité le {{ now()->format('d/m/Y à H:i') }} · GAMACO 83</div>
</body>
</html>
