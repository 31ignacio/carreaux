{{-- resources/views/rapports/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des ventes</title>
    <style>
        @page {
            margin: 15px 15px 20px 15px;
            size: landscape;
            margin-header: 0;
            margin-footer: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', 'Helvetica', sans-serif;
            font-size: 10px;
            color: #1a202c;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* ── EN-TÊTE ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #d4a843;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: middle;
            padding-bottom: 8px;
        }

        .header-logo {
            font-size: 16px;
            font-weight: 900;
            color: #0a1628;
            letter-spacing: 1px;
        }

        .header-logo span {
            color: #d4a843;
        }

        .header-divider {
            display: inline-block;
            width: 1px;
            height: 20px;
            background: #e2e8f0;
            margin: 0 12px;
            vertical-align: middle;
        }

        .header-title {
            font-size: 14px;
            font-weight: 800;
            color: #0a1628;
            vertical-align: middle;
        }

        .header-right {
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }

        .header-right strong {
            display: block;
            color: #0a1628;
            font-size: 10px;
        }

        /* ── INFOS PÉRIODE ── */
        .info-bar {
            background: #f8fafc;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 12px;
            border-left: 3px solid #d4a843;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 0 16px 0 0;
            vertical-align: baseline;
            white-space: nowrap;
        }

        .info-table td:last-child {
            padding-right: 0;
        }

        .info-label {
            color: #94a3b8;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-right: 4px;
        }

        .info-value {
            font-weight: 700;
            color: #0a1628;
            font-size: 10px;
        }

        .info-value.green {
            color: #059669;
        }

        .info-value.gold {
            color: #d97706;
        }

        /* ── KPI ── */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 12px;
            margin-left: -8px;
            margin-right: -8px;
            width: calc(100% + 16px);
        }

        .kpi-cell {
            width: 25%;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: center;
            vertical-align: middle;
        }

        .kpi-number {
            font-size: 18px;
            font-weight: 900;
            color: #0a1628;
            line-height: 1.2;
            white-space: nowrap;
        }

        .kpi-number.green {
            color: #059669;
        }

        .kpi-number.gold {
            color: #d97706;
        }

        .kpi-number.blue {
            color: #4f46e5;
        }

        .kpi-label {
            font-size: 7px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
            white-space: nowrap;
        }

        /* ── TABLE PRINCIPALE ── */
        .table-wrap {
            margin-top: 4px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        table.data thead th {
            background: #f1f5f9;
            color: #475569;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 800;
            padding: 5px 6px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        table.data tbody td {
            padding: 4px 6px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        table.data tbody tr:last-child td {
            border-bottom: none;
        }

        table.data .text-right {
            text-align: right;
        }

        table.data .text-center {
            text-align: center;
        }

        table.data .font-bold {
            font-weight: 700;
        }

        table.data .color-green {
            color: #059669;
        }

        table.data .color-gold {
            color: #d97706;
        }

        table.data .color-muted {
            color: #94a3b8;
        }

        table.data .color-blue {
            color: #4f46e5;
        }

        table.data tfoot td {
            padding: 5px 6px;
            border-top: 2px solid #e2e8f0;
            font-weight: 800;
            background: #f8fafc;
            font-size: 9px;
        }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-paiement {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-detail {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-moyen {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-gros {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ── TRONCATURE ── */
        .text-ellipsis {
            max-width: 80px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        /* ── PIED ── */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 7px;
            color: #94a3b8;
        }

        .footer-table .page-info {
            text-align: right;
            font-weight: 600;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            color: #94a3b8;
            padding: 20px 0;
            font-size: 10px;
        }

        .empty-state .icon {
            font-size: 24px;
            display: block;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>

    {{-- ══ EN-TÊTE ══ --}}
    <table class="header-table">
        <tr>
            <td style="width:55%">
                <span class="header-logo">GamacoRCHE <span>LA MADONE</span></span>
                <span class="header-divider"></span>
                <span class="header-title">Rapport des ventes</span>
            </td>
            <td class="header-right" style="width:45%">
                <strong>{{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} →
                    {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</strong>
                Généré le {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    {{-- ══ BARRE D'INFOS ══ --}}
    <div class="info-bar">
        <table class="info-table">
            <tr>
                <td>
                    <span class="info-label">📋 Période</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} →
                        {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</span>
                </td>
                <td>
                    <span class="info-label">📊 Jours</span>
                    <span
                        class="info-value">{{ \Carbon\Carbon::parse($dateDebut)->diffInDays(\Carbon\Carbon::parse($dateFin)) + 1 }}</span>
                </td>
                <td>
                    <span class="info-label">🧾 Ventes</span>
                    <span class="info-value">{{ $nbVentes }}</span>
                </td>
                <td>
                    <span class="info-label">💰 CA</span>
                    <span class="info-value green">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</span>
                </td>
                <td>
                    <span class="info-label">📉 Remises</span>
                    <span class="info-value gold">{{ number_format($totalRemises, 0, ',', ' ') }} FCFA</span>
                </td>
                <td>
                    <span class="info-label">🛒 Panier</span>
                    <span class="info-value">{{ $nbVentes > 0 ? number_format($totalCA / $nbVentes, 0, ',', ' ') : 0 }}
                        FCFA</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══ KPI ── --}}
    <table class="kpi-table">
        <tr>
            <td class="kpi-cell">
                <div class="kpi-number green">{{ number_format($totalCA, 0, ',', ' ') }}</div>
                <div class="kpi-label">Chiffre d'affaires (FCFA)</div>
            </td>
            <td class="kpi-cell">
                <div class="kpi-number blue">{{ $nbVentes }}</div>
                <div class="kpi-label">Nombre de ventes</div>
            </td>
            <td class="kpi-cell">
                <div class="kpi-number gold">{{ number_format($totalRemises, 0, ',', ' ') }}</div>
                <div class="kpi-label">Remises (FCFA)</div>
            </td>
            <td class="kpi-cell">
                <div class="kpi-number">{{ $nbVentes > 0 ? number_format($totalCA / $nbVentes, 0, ',', ' ') : 0 }}
                </div>
                <div class="kpi-label">Panier moyen (FCFA)</div>
            </td>
        </tr>
    </table>

    {{-- ══ TABLEAU DES VENTES ══ --}}
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th style="width:10%">N° Bon</th>
                    <th style="width:14%">Client</th>
                    <th style="width:11%">Paiement</th>
                    <th style="width:12%;text-align:right">Sous-total</th>
                    <th style="width:10%;text-align:right">Remise</th>
                    <th style="width:12%;text-align:right">Total</th>
                    <th style="width:11%">Caissier</th>
                    <th style="width:11%">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $facture)
                    <tr>
                        <td><span class="color-blue font-bold">{{ $facture->numero }}</span></td>
                        <td>
                            <span class="text-ellipsis" title="{{ $facture->client->nom ?? 'Client Divers' }}">
                                {{ $facture->client->nom ?? 'Client Divers' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-paiement">
                                {{ $facture->mode_paiement_label }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($facture->sous_total, 0, ',', ' ') }}</td>
                        <td class="text-right">
                            @if ($facture->remise > 0)
                                <span class="color-gold">-{{ number_format($facture->remise, 0, ',', ' ') }}</span>
                            @else
                                <span class="color-muted">—</span>
                            @endif
                        </td>
                        <td class="text-right font-bold color-green">{{ number_format($facture->total, 0, ',', ' ') }}
                        </td>
                        <td>{{ $facture->user->prenom ?? '—' }}</td>
                        <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <span class="icon">📭</span>
                                Aucune vente sur cette période
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($nbVentes > 0)
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"
                            style="color:#94a3b8;font-size:8px;text-transform:uppercase;letter-spacing:0.3px;">TOTAUX
                        </td>
                        <td class="text-right font-bold">{{ number_format($factures->sum('sous_total'), 0, ',', ' ') }}
                        </td>
                        <td class="text-right font-bold color-gold">- {{ number_format($totalRemises, 0, ',', ' ') }}
                        </td>
                        <td class="text-right font-bold color-green" style="font-size:11px;">
                            {{ number_format($totalCA, 0, ',', ' ') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    {{-- ══ PIED DE PAGE ══ --}}
    <table class="footer-table">
        <tr>
            <td style="width:50%">GamacoRCHE LA MADONE · Rapport généré automatiquement</td>
            <td class="page-info" style="width:50%">Page 1/1</td>
        </tr>
    </table>

</body>

</html>
