<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport Sorties de Stock</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 11px;
        color: #1e293b;
        background: #fff;
        padding: 24px;
    }

    /* ── En-tête ── */
    .header-top {
        background: #0f172a;
        border-radius: 8px 8px 0 0;
        padding: 18px 22px 16px;
        margin-bottom: 0;
    }
    .header-top h1 {
        font-size: 17px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 4px;
        letter-spacing: -.2px;
    }
    .header-top p {
        font-size: 9.5px;
        color: rgba(255,255,255,.5);
        margin: 0;
    }
    .header-bottom {
        background: #7f1d1d;
        border-radius: 0 0 8px 8px;
        padding: 9px 22px;
        margin-bottom: 22px;
    }
    .header-pills { display: table; width: 100%; }
    .header-pill  { display: table-cell; font-size: 9.5px; font-weight: 700; color: rgba(255,255,255,.8); padding-right: 24px; white-space: nowrap; }
    .header-pill span { color: #fca5a5; font-weight: 900; }

    /* ── KPI ── */
    .kpi-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 22px; }
    .kpi-table td { width: 33%; border-radius: 8px; padding: 13px 16px; text-align: center; vertical-align: middle; }
    .kpi-v { font-size: 20px; font-weight: 900; line-height: 1; margin-bottom: 4px; }
    .kpi-l { font-size: 8.5px; text-transform: uppercase; letter-spacing: .6px; font-weight: 700; }
    .kpi-red   { background: #fee2e2; }
    .kpi-red   .kpi-v { color: #7f1d1d; } .kpi-red   .kpi-l { color: #9f1239; }
    .kpi-amber { background: #fef3c7; }
    .kpi-amber .kpi-v { color: #92400e; } .kpi-amber .kpi-l { color: #b45309; }
    .kpi-blue  { background: #dbeafe; }
    .kpi-blue  .kpi-v { color: #1e3a8a; } .kpi-blue  .kpi-l { color: #1d4ed8; }

    /* ── Titre section ── */
    .section-title {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: #0f172a;
        border-left: 3px solid #ef4444;
        padding-left: 10px;
        margin-bottom: 12px;
    }

    /* ── Table ── */
    .main-table { width: 100%; border-collapse: collapse; font-size: 10.5px; }

    .main-table thead tr { background: #0f172a; }
    .main-table thead th {
        padding: 10px 13px;
        color: #94a3b8;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        text-align: left;
        border: none;
    }
    .main-table thead th.r { text-align: right; }

    .main-table tbody tr { border-bottom: 1px solid #e2e8f0; }
    .main-table tbody tr:nth-child(even) { background: #f8fafc; }
    .main-table tbody td { padding: 9px 13px; color: #334155; border: none; vertical-align: middle; }
    .main-table tbody td.r     { text-align: right; }
    .main-table tbody td.bold  { font-weight: 800; color: #0f172a; }
    .main-table tbody td.red   { color: #991b1b; font-weight: 800; }
    .main-table tbody td.muted { color: #94a3b8; font-size: 9.5px; }

    /* Rang */
    .rank { display: inline-block; width: 20px; height: 20px; border-radius: 50%; text-align: center; line-height: 20px; font-size: 9px; font-weight: 800; }
    .rank-1 { background: #fbbf24; color: #78350f; }
    .rank-2 { background: #94a3b8; color: #fff; }
    .rank-3 { background: #d97706; color: #fff; }
    .rank-n { background: #f1f5f9; color: #64748b; }

    /* Ligne total */
    .tr-total td { background: #0f172a !important; color: #fff !important; font-weight: 900; padding: 11px 13px; border: none; }
    .tr-total td.r { text-align: right; color: #fca5a5 !important; }

    /* ── Footer ── */
    .footer { margin-top: 28px; padding-top: 10px; border-top: 1px solid #e2e8f0; display: table; width: 100%; }
    .footer-left  { display: table-cell; font-size: 8.5px; color: #94a3b8; text-align: left; }
    .footer-right { display: table-cell; font-size: 8.5px; color: #94a3b8; text-align: right; }
</style>
</head>
<body>

{{-- ══ EN-TÊTE ══ --}}
<div class="header-top">
    <h1>Rapport &mdash; Sorties de Stock</h1>
    <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
</div>
<div class="header-bottom">
    <div class="header-pills">
        <div class="header-pill">
            Période :
            @if($filtres['date_debut'] || $filtres['date_fin'])
                <span>
                    @if($filtres['date_debut']) Du {{ \Carbon\Carbon::parse($filtres['date_debut'])->format('d/m/Y') }} @endif
                    @if($filtres['date_fin'])   au {{ \Carbon\Carbon::parse($filtres['date_fin'])->format('d/m/Y') }} @endif
                </span>
            @else
                <span>Toutes périodes</span>
            @endif
        </div>
        @if($filtres['type'])
            <div class="header-pill">Type : <span>{{ $filtres['type'] }}</span></div>
        @endif
        @if($filtres['search'])
            <div class="header-pill">Recherche : <span>"{{ $filtres['search'] }}"</span></div>
        @endif
    </div>
</div>

{{-- ══ KPI ══ --}}
<table class="kpi-table">
    <tr>
        <td class="kpi-red">
            <div class="kpi-v">{{ number_format($stats['total'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Nb de mouvements</div>
        </td>
        <td class="kpi-amber">
            <div class="kpi-v">{{ number_format($stats['quantite'], 2, ',', ' ') }}</div>
            <div class="kpi-l">Quantité totale sortie</div>
        </td>
        <td class="kpi-blue">
            <div class="kpi-v">{{ number_format($stats['valeur'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Valeur totale (FCFA)</div>
        </td>
    </tr>
</table>

{{-- ══ CUMUL PAR PRODUIT ══ --}}
<div class="section-title">Cumul par produit</div>

<table class="main-table">
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Produit</th>
            <th>Référence</th>
            <th class="r">Qté totale</th>
            <th class="r">Unité</th>
            <th class="r">Valeur (FCFA)</th>
            <th class="r">Mouvements</th>
        </tr>
    </thead>
    <tbody>
        @php $totalQte = 0; $totalVal = 0; @endphp

        @foreach($cumul as $i => $ligne)
            @php $totalQte += $ligne['quantite_tot']; $totalVal += $ligne['valeur_tot']; @endphp
            <tr>
                <td>
                    <span class="rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                        {{ $i + 1 }}
                    </span>
                </td>
                <td class="bold">{{ $ligne['libelle'] }}</td>
                <td class="muted" style="font-family: monospace;">{{ $ligne['reference'] ?: '—' }}</td>
                <td class="r red">{{ number_format($ligne['quantite_tot'], 2, ',', ' ') }}</td>
                <td class="r muted">{{ $ligne['unite'] }}</td>
                <td class="r bold" style="color:#991b1b;">{{ number_format($ligne['valeur_tot'], 0, ',', ' ') }}</td>
                <td class="r muted">{{ $ligne['nb_mouvements'] }}</td>
            </tr>
        @endforeach

        <tr class="tr-total">
            <td colspan="3">TOTAL GÉNÉRAL</td>
            <td class="r">{{ number_format($totalQte, 2, ',', ' ') }}</td>
            <td></td>
            <td class="r">{{ number_format($totalVal, 0, ',', ' ') }} FCFA</td>
            <td class="r">{{ $stats['total'] }}</td>
        </tr>
    </tbody>
</table>

{{-- ══ FOOTER ══ --}}
<div class="footer">
    <div class="footer-left">Système de gestion de stock &mdash; Rapport confidentiel</div>
    <div class="footer-right">{{ now()->format('d/m/Y H:i') }}</div>
</div>

</body>
</html>