{{-- resources/views/inventaires/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inventaire - {{ $inventaire->titre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 25px;
            background: #fff;
        }

        /* ── HEADER ── */
        .header {
            background: #1a1a2e;
            color: white;
            padding: 18px 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10px;
            opacity: .75;
        }

        .header .badge-statut {
            float: right;
            margin-top: -30px;
            background: #27ae60;
            color: white;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .header .badge-statut.en-cours {
            background: #f39c12;
        }

        /* ── INFO BOXES ── */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px 15px;
            vertical-align: top;
        }

        .info-cell h4 {
            font-size: 10px;
            text-transform: uppercase;
            color: #1a1a2e;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
            margin-bottom: 8px;
            letter-spacing: .5px;
        }

        .info-cell table {
            width: 100%;
        }

        .info-cell td {
            padding: 3px 0;
            font-size: 11px;
        }

        .info-cell td:first-child {
            color: #666;
            width: 45%;
        }

        .info-cell td:last-child {
            font-weight: bold;
            color: #333;
        }

        /* ── STATS ── */
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 8px;
        }

        .stat-cell {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 12px 8px;
            border-radius: 6px;
            vertical-align: middle;
        }

        .stat-cell .num {
            font-size: 22px;
            font-weight: bold;
            display: block;
        }

        .stat-cell .lbl {
            font-size: 10px;
            margin-top: 3px;
            display: block;
        }

        .stat-total {
            background: #e8eaf6;
            color: #3949ab;
        }

        .stat-ok {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .stat-manque {
            background: #ffebee;
            color: #c62828;
        }

        .stat-excede {
            background: #fff8e1;
            color: #f57f17;
        }

        /* ── ECART TOTAL ── */
        .ecart-total {
            text-align: right;
            margin-bottom: 12px;
            font-size: 12px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        /* ── TABLE ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 4px solid #1a1a2e;
            padding-left: 10px;
            margin-bottom: 10px;
        }

        table.main {
            width: 100%;
            border-collapse: collapse;
        }

        table.main thead tr {
            background: #1a1a2e;
            color: white;
        }

        table.main thead th {
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .3px;
            font-weight: bold;
        }

        table.main tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        table.main tbody tr:nth-child(even) {
            background: #fafafa;
        }

        table.main tbody td {
            padding: 7px 10px;
            font-size: 11px;
        }

        table.main tfoot td {
            padding: 8px 10px;
            background: #1a1a2e;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .pill {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }

        .pill-ok {
            background: #27ae60;
        }

        .pill-manque {
            background: #e74c3c;
        }

        .pill-excede {
            background: #f39c12;
        }

        .pill-cat {
            background: #3498db;
        }

        .text-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .text-success {
            color: #27ae60;
            font-weight: bold;
        }

        .text-muted {
            color: #aaa;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Rapport d'Inventaire — {{ $inventaire->titre }}</h1>
        <p>Gamaco &nbsp;|&nbsp; Cotonou, Bénin &nbsp;|&nbsp; Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <span class="badge-statut {{ $inventaire->statut === 'en_cours' ? 'en-cours' : '' }}">
            {{ $inventaire->statut_label }}
        </span>
    </div>

    {{-- INFOS --}}
    @php
        $lignes = $inventaire->lignes;
        $totalProduits = $lignes->count();
        $sansEcart = $lignes->filter(fn($l) => $l->ecart == 0)->count();
        $enManque = $lignes->filter(fn($l) => $l->ecart < 0)->count();
        $enExcedent = $lignes->filter(fn($l) => $l->ecart > 0)->count();
        $valeurEcartTotal = $lignes->sum('ecart_valeur');
    @endphp

    <div class="info-row">
        <div class="info-cell">
            <h4>Informations</h4>
            <table>
                <tr>
                    <td>Titre</td>
                    <td>{{ $inventaire->titre }}</td>
                </tr>
                <tr>
                    <td>Catégorie</td>
                    <td>
                        {{ $inventaire->categorie_filtre === 'toutes' || !$inventaire->categorie_filtre
                            ? 'Toutes les catégories'
                            : $inventaire->categorie?->nom ?? '-' }}
                    </td>
                </tr>
                @if ($inventaire->notes)
                    <tr>
                        <td>Notes</td>
                        <td>{{ $inventaire->notes }}</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="info-cell">
            <h4>Dates & Responsable</h4>
            <table>
                <tr>
                    <td>Créé par</td>
                    <td>{{ $inventaire->user->nom_complet }}</td>
                </tr>
                <tr>
                    <td>Date création</td>
                    <td>{{ $inventaire->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Date clôture</td>
                    <td>
                        {{ $inventaire->date_cloture ? \Carbon\Carbon::parse($inventaire->date_cloture)->format('d/m/Y H:i') : '—' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-cell stat-total">
            <span class="num">{{ $totalProduits }}</span>
            <span class="lbl">Produits total</span>
        </div>
        <div class="stat-cell stat-ok">
            <span class="num">{{ $sansEcart }}</span>
            <span class="lbl">Sans écart</span>
        </div>
        <div class="stat-cell stat-manque">
            <span class="num">{{ $enManque }}</span>
            <span class="lbl">En manque</span>
        </div>
        <div class="stat-cell stat-excede">
            <span class="num">{{ $enExcedent }}</span>
            <span class="lbl">En excédent</span>
        </div>
    </div>

    {{-- ECART TOTAL --}}
    <div class="ecart-total">
        Écart total valorisé :
        <strong
            class="{{ $valeurEcartTotal < 0 ? 'text-danger' : ($valeurEcartTotal > 0 ? 'text-success' : 'text-muted') }}">
            {{ $valeurEcartTotal >= 0 ? '+' : '' }}{{ number_format($valeurEcartTotal, 0, ',', ' ') }} FCFA
        </strong>
    </div>

    {{-- TABLEAU --}}
    <div class="section-title">Détail des produits</div>

    <table class="main">
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Catégorie</th>
                <th class="text-right">Théorique</th>
                <th class="text-right">Physique</th>
                <th class="text-right">Écart Qté</th>
                <th class="text-right">Écart Valeur</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lignes as $i => $ligne)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><strong>{{ $ligne->produit->libelle }}</strong></td>
                    <td>
                        {{ $ligne->produit->categorie->nom }}
                    </td>
                    <td class="text-right">
                        {{ number_format($ligne->stock_theorique, 2, ',', ' ') }}
                        <small class="text-muted">{{ $ligne->produit->unite }}</small>
                    </td>
                    <td class="text-right">
                        @if ($ligne->stock_physique !== null)
                            {{ number_format($ligne->stock_physique, 2, ',', ' ') }}
                            <small class="text-muted">{{ $ligne->produit->unite }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($ligne->ecart == 0)
                            <span class="text-muted">0</span>
                        @elseif($ligne->ecart < 0)
                            <span class="text-danger">{{ number_format($ligne->ecart, 2, ',', ' ') }}</span>
                        @else
                            <span class="text-success">+{{ number_format($ligne->ecart, 2, ',', ' ') }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($ligne->ecart_valeur == 0)
                            <span class="text-muted">0 FCFA</span>
                        @elseif($ligne->ecart_valeur < 0)
                            <span class="text-danger">{{ number_format($ligne->ecart_valeur, 0, ',', ' ') }}
                                FCFA</span>
                        @else
                            <span class="text-success">+{{ number_format($ligne->ecart_valeur, 0, ',', ' ') }}
                                FCFA</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($ligne->ecart == 0)
                            <span class="pill pill-ok">OK</span>
                        @elseif($ligne->ecart < 0)
                            <span class="pill pill-manque">Manque</span>
                        @else
                            <span class="pill pill-excede">Excédent</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td class="text-right">{{ number_format($lignes->sum('stock_theorique'), 2, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($lignes->sum('stock_physique'), 2, ',', ' ') }}</td>
                <td class="text-right">
                    {{ $lignes->sum('ecart') >= 0 ? '+' : '' }}{{ number_format($lignes->sum('ecart'), 2, ',', ' ') }}
                </td>
                <td class="text-right">
                    {{ $valeurEcartTotal >= 0 ? '+' : '' }}{{ number_format($valeurEcartTotal, 0, ',', ' ') }} FCFA
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- SIGNATURES --}}
    <table style="width:100%; margin-top:50px;">
        <tr>
            <td style="width:40%; text-align:center; border-top:1px solid #ccc; padding-top:8px;">
                <strong>Responsable inventaire</strong><br>
                <small class="text-muted">{{ $inventaire->user->nom_complet }}</small>
            </td>
            <td style="width:20%;"></td>
            <td style="width:40%; text-align:center; border-top:1px solid #ccc; padding-top:8px;">
                <strong>Validation direction</strong><br>
                <small class="text-muted">&nbsp;</small>
            </td>
        </tr>
    </table>

    <div class="footer">
        Gamaco &copy; {{ date('Y') }} &nbsp;|&nbsp; Rapport généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>

</html>
