{{-- resources/views/rapports/pdf-stock.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Stock</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }

        .header {
            background: #1a1a2e;
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 9px;
            opacity: .7;
        }

        .stats {
            display: table;
            width: 100%;
            margin-bottom: 18px;
            border-spacing: 8px;
        }

        .stat {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            border-radius: 6px;
        }

        .stat .num {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }

        .stat .lbl {
            font-size: 9px;
            margin-top: 2px;
            display: block;
        }

        .s1 {
            background: #e8eaf6;
            color: #3949ab;
        }

        .s2 {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .s3 {
            background: #fff8e1;
            color: #f57f17;
        }

        .s4 {
            background: #ffebee;
            color: #c62828;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1a1a2e;
            color: white;
        }

        th {
            padding: 7px 8px;
            font-size: 9px;
            text-transform: uppercase;
            text-align: left;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .text-warning {
            color: #f39c12;
            font-weight: bold;
        }

        .text-success {
            color: #27ae60;
            font-weight: bold;
        }

        .text-muted {
            color: #aaa;
        }

        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: bold;
            color: white;
        }

        .pill-ok {
            background: #27ae60;
        }

        .pill-faible {
            background: #f39c12;
        }

        .pill-rupture {
            background: #e74c3c;
        }

        .pill-cat {
            background: #3498db;
        }

        tfoot td {
            background: #1a1a2e;
            color: white;
            font-weight: bold;
            padding: 7px 8px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>État des Stocks — Gamaco</h1>
        <p>Cotonou, Bénin &nbsp;|&nbsp; Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    {{-- STATS --}}
    @php
        $totalProduits = $produits->count();
        $produitsOk = $produits->filter(fn($p) => $p->statut_stock === 'normal')->count();
        $produitsFaibles = $produits->filter(fn($p) => $p->statut_stock === 'faible')->count();
        $produitsRupture = $produits->filter(fn($p) => $p->statut_stock === 'rupture')->count();
        $valeurTotale = $produits->sum(fn($p) => $p->stock_actuel * $p->prix_achat);
    @endphp

    <div class="stats">
        <div class="stat s1">
            <span class="num">{{ $totalProduits }}</span>
            <span class="lbl">Total produits</span>
        </div>
        <div class="stat s2">
            <span class="num">{{ $produitsOk }}</span>
            <span class="lbl">Stock normal</span>
        </div>
        <div class="stat s3">
            <span class="num">{{ $produitsFaibles }}</span>
            <span class="lbl">Stock faible</span>
        </div>
        <div class="stat s4">
            <span class="num">{{ $produitsRupture }}</span>
            <span class="lbl">Rupture</span>
        </div>
    </div>

    <p style="text-align:right; margin-bottom:12px; font-size:11px;">
        Valeur totale stock :
        <strong>{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</strong>
    </p>

    {{-- TABLEAU --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th class="text-right">Prix achat</th>
                <th class="text-right">Prix détail</th>
                <th class="text-right">Stock actuel</th>
                <th class="text-right">Stock min.</th>
                <th class="text-right">Valeur stock</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits->sortBy('libelle') as $i => $p)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><strong>{{ $p->libelle }}</strong></td>
                    <td><span class="pill pill-cat">{{ $p->categorie->nom }}</span></td>
                    <td>{{ $p->fournisseur?->nom ?? '—' }}</td>
                    <td class="text-right">{{ number_format($p->prix_achat, 0, ',', ' ') }} F</td>
                    <td class="text-right">{{ number_format($p->prix_detail, 0, ',', ' ') }} F</td>
                    <td class="text-right">
                        <span
                            class="{{ $p->statut_stock === 'rupture' ? 'text-danger' : ($p->statut_stock === 'faible' ? 'text-warning' : 'text-success') }}">
                            {{ number_format($p->stock_actuel, 2, ',', ' ') }} {{ $p->unite }}
                        </span>
                    </td>
                    <td class="text-right text-muted">
                        {{ number_format($p->stock_minimum, 2, ',', ' ') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($p->stock_actuel * $p->prix_achat, 0, ',', ' ') }} F
                    </td>
                    <td class="text-center">
                        @if ($p->statut_stock === 'rupture')
                            <span class="pill pill-rupture">Rupture</span>
                        @elseif($p->statut_stock === 'faible')
                            <span class="pill pill-faible">Faible</span>
                        @else
                            <span class="pill pill-ok">Normal</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">VALEUR TOTALE STOCK :</td>
                <td class="text-right">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Gamaco &copy; {{ date('Y') }} &nbsp;|&nbsp; Rapport généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>

</html>
