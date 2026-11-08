<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu de remboursement {{ $remboursement->reference }}</title>
    <style>
        @page { margin: 36px; }
        body { margin:0; color:#172033; font:13px DejaVu Sans,Arial,sans-serif; }
        .receipt { border:1px solid #dce3ed; border-radius:12px; overflow:hidden; }
        .header { padding:28px 32px; color:#fff; background:#1e1b4b; }
        .brand { margin-bottom:26px; color:#c7d2fe; font-size:10px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; }
        h1 { margin:0 0 6px; color:#fff; font-size:23px; }
        .subtitle { color:#c7d2fe; font-size:11px; }
        .reference { float:right; margin-top:-2px; color:#c7d2fe; font-size:10px; text-align:right; }
        .reference strong { display:block; margin-top:5px; color:#fff; font-size:11px; }
        .content { padding:28px 32px 24px; }
        .amount-box { margin-bottom:24px; padding:18px 20px; border:1px solid #bbf7d0; border-radius:9px; background:#f0fdf4; }
        .amount-label { margin-bottom:7px; color:#166534; font-size:10px; font-weight:bold; letter-spacing:.8px; text-transform:uppercase; }
        .amount { color:#15803d; font-size:25px; font-weight:bold; }
        .balance-box { margin:0 0 20px; padding:14px 18px; border:1px solid #dbeafe; border-radius:9px; background:#eff6ff; }
        .balance-label { margin-bottom:6px; color:#1e40af; font-size:10px; font-weight:bold; letter-spacing:.7px; text-transform:uppercase; }
        .balance { color:#1d4ed8; font-size:19px; font-weight:bold; }
        .balance-note { margin-top:4px; color:#64748b; font-size:9px; }
        .details { width:100%; border-collapse:collapse; }
        .details td { padding:12px 0; border-bottom:1px solid #edf0f5; vertical-align:top; }
        .details tr:last-child td { border-bottom:0; }
        .label { width:36%; color:#758195; font-size:10px; font-weight:bold; letter-spacing:.5px; text-transform:uppercase; }
        .value { color:#202b3d; font-size:12px; font-weight:bold; }
        .note { margin-top:8px; padding:12px 14px; border-radius:7px; background:#f8fafc; }
        .note-label { margin-bottom:5px; color:#758195; font-size:9px; font-weight:bold; letter-spacing:.5px; text-transform:uppercase; }
        .footer { margin-top:24px; padding-top:14px; border-top:1px solid #edf0f5; color:#758195; font-size:10px; text-align:center; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="reference">RÉFÉRENCE<strong>{{ $remboursement->reference }}</strong></div>
            <div class="brand">Gamaco · Gestion financière</div>
            <h1>Reçu de remboursement</h1>
            <div class="subtitle">Confirmation de versement client</div>
        </div>
        <div class="content">
            <div class="amount-box">
                <div class="amount-label">Montant reçu</div>
                <div class="amount">{{ number_format($remboursement->montant, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="balance-box">
                <div class="balance-label">Reste à rembourser</div>
                <div class="balance">{{ number_format($resteArembourser, 0, ',', ' ') }} FCFA</div>
                <div class="balance-note">Solde restant sur les bons à crédit de ce client.</div>
            </div>
            <table class="details">
                <tr>
                    <td class="label">Client</td>
                    <td class="value">{{ $remboursement->client->nom_complet }}</td>
                </tr>
                @if($remboursement->client->telephone)
                    <tr>
                        <td class="label">Téléphone</td>
                        <td class="value">{{ $remboursement->client->telephone }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">Date et heure</td>
                    <td class="value">{{ $remboursement->created_at->format('d/m/Y à H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Mode de paiement</td>
                    <td class="value">{{ $remboursement->mode_paiement_label }}</td>
                </tr>
                <tr>
                    <td class="label">Enregistré par</td>
                    <td class="value">{{ $remboursement->user->nom_complet }}</td>
                </tr>
            </table>
            @if($remboursement->notes)
                <div class="note">
                    <div class="note-label">Note</div>
                    {{ $remboursement->notes }}
                </div>
            @endif
            <div class="footer">Merci pour votre règlement. Ce reçu confirme l’enregistrement de votre versement.</div>
        </div>
    </div>
</body>
</html>
