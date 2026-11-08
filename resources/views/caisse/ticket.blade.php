<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Ticket {{ $facture->numero }}</title>
    <style>
        /* ══ RESET ══ */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: 72mm auto;
            margin: 0;
        }

        html,
        body {
            width: 72mm;
            height: auto;
            margin: 0 auto;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.35;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            text-align: left;
        }

        /* ══ CENTRAGE DU TICKET ══ */
        .ticket {
            width: 72mm;
            max-width: 72mm;
            margin: 0 auto;
            padding: 1mm 0;
            box-sizing: border-box;
        }

        /* ══ HEADER ══ */
        .ticket-top {
            text-align: left;
            padding: 6px 1mm 5px;
            border-bottom: 2px solid #000;
        }

        .brand-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            margin-bottom: 3px;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-shrink: 0;
        }

        .logo-wrap img {
            width: 16mm;
            max-height: 16mm;
            object-fit: contain;
            filter: none;
            -webkit-filter: none;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            line-height: 1.1;
        }

        .shop-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
            color: #000;
        }

        .shop-sub {
            font-size: 9px;
            font-weight: 700;
            line-height: 1.45;
            margin-top: 1px;
            text-align: center;
        }

        .shop-divider {
            border: none;
            border-top: 1px solid #000;
            margin: 3px auto 2px;
            width: 70%;
        }

        /* ══ CORPS ══ */
        .ticket-body {
            padding: 5px 1mm 0;
            font-size: 10px;
            font-weight: 700;
        }

        .ticket-meta {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .ticket-num {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 3px;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
        }

        .client-row {
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 1px;
        }

        /* Séparateurs */
        .sep {
            border: none;
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        .sep-solid {
            border: none;
            border-top: 2px solid #000;
            margin: 4px 0;
        }

        /* Colonnes en-tête */
        .col-hd {
            display: flex;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }

        .col-hd span:first-child {
            flex: 1;
        }

        .col-hd span:last-child {
            width: 72px;
            text-align: right;
        }

        /* Produits */
        .ligne {
            margin-bottom: 3px;
        }

        .ligne-nom {
            font-size: 11px;
            font-weight: 700;
        }

        .ligne-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            font-weight: 700;
        }

        .sous-total {
            font-weight: 700;
        }

        /* Remise */
        .remise-row {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            margin-top: 2px;
        }

        /* Total */
        .total-wrap {
            margin-top: 3px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
            font-size: 13px;
            font-weight: 700;
        }

        /* TVA */
        .tva-block {
            font-size: 9px;
            font-weight: 700;
            margin-top: 2px;
        }

        .tva-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-weight: 700;
        }

        /* Paiement */
        .paiement-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            margin-top: 3px;
        }

        .monnaie-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            margin-top: 2px;
        }

        /* Crédit */
        .credit-badge {
            border: 2px solid #000;
            padding: 4px 4px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            margin-top: 3px;
        }

        /* MECeF */
        .sep-mecef {
            font-size: 8px;
            font-weight: 700;
            text-align: center;
            margin: 5px 0 3px;
            letter-spacing: .03em;
        }

        .emecef-block {
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            margin-top: 2px;
        }

        .emecef-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 2px;
        }

        .emecef-code {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .03em;
            word-break: break-all;
            margin: 3px 0;
            border: 1px solid #000;
            padding: 3px;
        }

        .emecef-infos {
            font-size: 9px;
            font-weight: 700;
            text-align: left;
            margin: 3px 0;
        }

        .ei-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-weight: 700;
        }

        .qr-wrap {
            display: flex;
            justify-content: center;
            margin-top: 4px;
        }

        .qr-wrap img {
            width: 100px;
            height: 100px;
        }

        /* FOOTER */
        .ticket-footer {
            text-align: center;
            padding: 4px 1mm 3px;
            font-size: 10px;
            font-weight: 700;
        }

        .merci {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .footer-sub {
            font-size: 9px;
            font-weight: 700;
            margin-top: 2px;
        }

        .footer-line {
            border-top: 1px dotted #000;
            margin: 4px 8px;
        }

        /* ══ REPÈRE DE DÉCHIRURE : point discret au milieu ══ */
        .tear-guide {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1mm 0 0;
            padding-bottom: 1mm;
        }

        .tear-dot {
            width: 1.5mm;
            height: 1.5mm;
            border-radius: 50%;
            background: #000;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* PRINT */
        @media print {

            html,
            body {
                width: 72mm;
                height: auto;
                overflow: visible;
                margin: 0 auto;
            }

            .ticket {
                width: 72mm;
                max-width: 72mm;
                margin: 0 auto;
                border: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .logo-wrap img {
                filter: none !important;
                -webkit-filter: none !important;
            }
        }
    </style>
</head>

<body onload="window.print();" onafterprint="window.close();">
    <div class="ticket">

        {{-- HEADER --}}
        <div class="ticket-top">
            <div class="brand-row">
                {{-- <div class="logo-wrap">
            <img src="data:image/jpeg;base64,..." alt="La Madone Gamacorche">
            </div> --}}
                <div class="brand-text">
                    <div class="shop-name">GAMACO 83</div>
                </div>
            </div>
            <div class="shop-sub">
                Abomey-Calavi<br>
                Tél : 01 55 43 32 33<br>
                gamaco83@gmail.com
                IFU : 3202659254832 RCCM : RB/COT/26 B 43072
            </div>
        </div>

        {{-- CORPS --}}
        <div class="ticket-body">

            <div class="ticket-meta">
                <span>{{ $facture->created_at->format('d/m/Y') }}</span>
                <span>{{ $facture->created_at->format('H:i') }}</span>
            </div>

            <div class="ticket-num">N° {{ $facture->numero }}</div>

            <div class="client-row">
                Client :
                {{ $facture->client ? $facture->client->nom . ' ' . $facture->client->prenom : 'Client divers' }}
            </div>
            <div class="client-row">
                IFU : {{ $facture->client ? $facture->client->ifu : '-' }}
            </div>
            <div class="client-row">Code/caissier : {{ $facture->user->id }} / {{ $facture->user->prenom }}</div>

            <hr class="sep">

            <div class="col-hd">Article — quantité × prix unitaire = total ligne TTC</div>

            @foreach ($facture->lignes as $index => $ligne)
                <div class="ligne">
                    <div class="ligne-nom">
                        {{ $ligne->libelle }}(A-EX)
                    </div>
                    <div class="ligne-detail">
                        <span>{{ $ligne->quantite % 1 == 0 ? (float) $ligne->quantite : $ligne->quantite }} × {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</span>
                        <span class="sous-total">= {{ number_format($ligne->sous_total, 0, ',', ' ') }} F</span>
                    </div>
                </div>
            @endforeach

            @if ($facture->remise > 0)
                <hr class="sep">
                <div class="remise-row">
                    <span>Sous-total</span><span>{{ number_format($facture->sous_total, 0, ',', ' ') }} F</span>
                </div>
                <div class="remise-row"><span>Remise</span><span>- {{ number_format($facture->remise, 0, ',', ' ') }}
                        F</span></div>
            @endif

            <div class="total-wrap">
                <div class="total-final">
                    <span>TOTAL </span>
                    <span>{{ number_format($facture->total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="paiement-row">
                    <span>Mode de paiement</span>
                    <span>{{ $facture->mode_paiement_label }}</span>
                </div>
            </div>

            @if ($facture->client)
                <div class="remise-row">
                    <span>Avance disponible</span>
                    <span>{{ number_format((float) $facture->client->solde_avance, 0, ',', ' ') }} F</span>
                </div>
                <div class="remise-row">
                    <span>Crédit restant</span>
                    <span>{{ number_format((float) $facture->client->creances, 0, ',', ' ') }} F</span>
                </div>
            @endif

            @if ($facture->normalisee && $facture->emecef_code)
                <div class="sep-mecef">- - - - NORMALISATION FISCALE e-MECeF - - - -</div>
                <div class="emecef-block">
                    <div class="emecef-title">Code MECeF / DGI</div>
                    <div class="emecef-code">{{ $facture->emecef_code }}</div>
                    <div class="emecef-infos">
                        @if ($facture->emecef_nim)
                            <div class="ei-row"><span>MECeF NIM :</span><span>{{ $facture->emecef_nim }}</span></div>
                        @endif
                        @if ($facture->emecef_counters)
                            <div class="ei-row"><span>Compteurs :</span><span>{{ $facture->emecef_counters }}</span>
                            </div>
                        @endif
                        @if ($facture->emecef_datetime)
                            <div class="ei-row"><span>Heure :</span><span>{{ $facture->emecef_datetime }}</span></div>
                        @endif
                    </div>
                    @if ($facture->emecef_qr)
                        <div class="qr-wrap">
                            @php
                                $qr = $facture->emecef_qr;
                                $isImage =
                                    str_starts_with($qr, 'data:') ||
                                    str_starts_with($qr, 'http') ||
                                    base64_decode($qr, true) !== false;
                            @endphp

                            @if (str_starts_with($qr, 'data:') ||
                                    (base64_decode(preg_replace('/\s+/', '', $qr), true) !== false && !str_starts_with($qr, 'http')))
                                <img src="data:image/png;base64,{{ preg_replace('/\s+/', '', $qr) }}" alt="QR MECeF"
                                    style="width:100px;height:100px">
                            @else
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qr) }}"
                                    alt="QR MECeF" style="width:100px;height:100px">
                            @endif
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <hr class="sep-solid">

        <div class="ticket-footer">
            <div class="merci">Merci pour votre achat !</div>
        </div>

        {{-- Repère de déchirure : point au milieu --}}
        <div class="tear-guide" aria-hidden="true">
            <span class="tear-dot"></span>
        </div>

    </div>
</body>

</html>