{{-- resources/views/factures/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Bon ' . $facture->numero)
@section('page-title', '')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('bons-livraison.index') }}">Bons</a></li>
    <li class="breadcrumb-item active">{{ $facture->numero }}</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800;9..40,900&family=Syne:wght@700;800&display=swap');

.fct-wrap { font-family:'DM Sans',sans-serif; max-width:1100px; margin:0 auto; }

/* ══ HERO ══ */
.fct-hero {
    border-radius:20px; padding:24px 30px; margin-bottom:20px;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:16px;
    position:relative; overflow:hidden;
    box-shadow:0 10px 36px rgba(15,23,42,.18);
}
.fct-hero.statut-payee   { background:linear-gradient(140deg,#0f172a 0%,#1e3a5f 55%,#065f46 100%); }
.fct-hero.statut-credit  { background:linear-gradient(140deg,#0f172a 0%,#1e3a5f 55%,#92400e 100%); }
.fct-hero.statut-annulee { background:linear-gradient(140deg,#0f172a 0%,#374151 100%); }
.fct-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; border-radius:50%;
    background:rgba(255,255,255,.05); pointer-events:none;
}

.fct-hero-left { position:relative; z-index:1; }
.fct-hero-num  { font-family:,sans-serif; font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 4px; letter-spacing:-.3px; }
.fct-hero-meta { font-size:12px; color:rgba(255,255,255,.5); display:flex; align-items:center; gap:14px; flex-wrap:wrap; }
.fct-hero-meta span { display:inline-flex; align-items:center; gap:5px; }

.fct-statut {
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 14px; border-radius:20px; font-size:11px; font-weight:800;
}
.fs-payee   { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
.fs-credit  { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
.fs-annulee { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }

.fct-hero-right { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }
.btn-fct {
    display:inline-flex; align-items:center; gap:7px;
    border-radius:11px; padding:9px 18px; font-size:12px; font-weight:700;
    cursor:pointer; transition:all .2s; text-decoration:none; border:none;
    font-family:'DM Sans',sans-serif;
}
.btn-fct:hover { transform:translateY(-2px); text-decoration:none; }
.btn-fct-pdf    { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:#fff; }
.btn-fct-pdf:hover { background:rgba(255,255,255,.25); color:#fff; }
.btn-fct-back   { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.7); }
.btn-fct-back:hover { background:rgba(255,255,255,.15); color:#fff; }
.btn-fct-print  { background:linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 4px 14px rgba(5,150,105,.35); }

/* ══ GRID INFOS ══ */
.fct-grid {
    display:grid; grid-template-columns:1fr 1fr;
    gap:14px; margin-bottom:16px;
}
@media(max-width:600px) { .fct-grid { grid-template-columns:1fr; } }

.fct-card {
    background:#fff; border:1px solid #e8edf5;
    border-radius:16px; overflow:hidden;
    box-shadow:0 2px 12px rgba(11,15,26,.05);
}
.fct-card-hd {
    padding:12px 18px; border-bottom:1px solid #f1f5f9;
    background:#fafbff; display:flex; align-items:center; gap:9px;
}
.fct-card-ico {
    width:26px; height:26px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; color:#fff; flex-shrink:0;
}
.fct-card-hd h6 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin:0; }
.fct-card-bd { padding:16px 18px; }

.fct-info-row {
    display:flex; align-items:flex-start;
    justify-content:space-between; gap:8px;
    padding:7px 0; border-bottom:1px solid #f8fafc;
    font-size:12px;
}
.fct-info-row:last-child { border-bottom:none; padding-bottom:0; }
.fct-info-row:first-child { padding-top:0; }
.fct-info-lbl { color:#94a3b8; font-weight:600; flex-shrink:0; }
.fct-info-val { font-weight:700; color:#0f172a; text-align:right; }

/* ══ TABLE LIGNES ══ */
.fct-table-card {
    background:#fff; border:1px solid #e8edf5;
    border-radius:16px; overflow:hidden;
    box-shadow:0 2px 12px rgba(11,15,26,.05);
    margin-bottom:16px;
}
.fct-table-hd {
    padding:12px 18px; border-bottom:1px solid #f1f5f9;
    background:#fafbff; display:flex; align-items:center; gap:9px;
}
.fct-table-hd h6 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin:0; }
.nb-badge { background:#0f172a; color:#fff; padding:2px 9px; border-radius:20px; font-size:10px; font-weight:800; margin-left:4px; }

.fct-table { width:100%; border-collapse:collapse; font-size:12px; }
.fct-table th {
    padding:10px 16px; font-size:9.5px; font-weight:800;
    text-transform:uppercase; letter-spacing:.5px;
    color:#94a3b8; background:#f8fafc;
    border-bottom:1px solid #f1f5f9; white-space:nowrap;
}
.fct-table td { padding:13px 16px; border-bottom:1px solid #f8fafc; vertical-align:middle; }
.fct-table tbody tr:last-child td { border-bottom:none; }
.fct-table tbody tr:hover td { background:#fafbff; }

.prod-num    { font-size:11px; font-weight:800; color:#cbd5e1; }
.prod-name   { font-size:13px; font-weight:700; color:#0f172a; }
.prod-pal    { font-size:10px; color:#94a3b8; margin-top:2px; }
.prix-cell   { font-size:12px; font-weight:700; color:#374151; text-align:right; }
.qte-cell    { font-size:13px; font-weight:800; color:#0f172a; text-align:center; }
.st-cell     { font-size:13px; font-weight:900; color:#059669; text-align:right; }

/* ══ TOTAUX ══ */
.fct-totaux {
    background:#fff; border:1px solid #e8edf5;
    border-radius:16px; overflow:hidden;
    box-shadow:0 2px 12px rgba(11,15,26,.05);
}
.tot-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 20px; border-bottom:1px solid #f8fafc;
    font-size:13px;
}
.tot-row:last-child { border-bottom:none; }
.tot-lbl { color:#64748b; font-weight:600; }
.tot-val { font-weight:800; color:#0f172a; }

.tot-total {
    background:linear-gradient(135deg,#059669,#10b981);
    padding:16px 20px;
}
.tot-total .tot-lbl { color:rgba(255,255,255,.75); font-size:11px; text-transform:uppercase; letter-spacing:.5px; font-weight:800; }
.tot-total .tot-val { font-family:,sans-serif; font-size:1.6rem; font-weight:800; color:#fff; }

.tot-monnaie .tot-val { color:#2563eb; }
.tot-remise  .tot-val { color:#dc2626; }
.tot-recu    .tot-val { color:#374151; }

/* ══ CREDIT BANNER ══ */
.credit-banner {
    background:#fffbeb; border:1px solid #fde68a;
    border-radius:14px; padding:14px 18px;
    display:flex; align-items:center; gap:12px;
    margin-top:16px; font-size:12px; color:#92400e;
}
.credit-banner-ico {
    width:36px; height:36px; border-radius:10px;
    background:#fef3c7; display:flex; align-items:center; justify-content:center;
    font-size:16px; color:#d97706; flex-shrink:0;
}
.credit-banner strong { display:block; font-size:13px; font-weight:800; color:#78350f; margin-bottom:2px; }

/* ══ ANIMATIONS ══ */
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.fct-hero       { animation:fadeUp .3s ease both; }
.fct-grid       { animation:fadeUp .3s .08s ease both; }
.fct-table-card { animation:fadeUp .3s .14s ease both; }
.fct-totaux     { animation:fadeUp .3s .2s ease both; }
</style>
@endpush

@section('content')
<div class="fct-wrap">

    {{-- ══ HERO ══ --}}
    @php
        $statutClass = match($facture->statut) {
            'payee'   => 'statut-payee',
            'en_cours'=> 'statut-credit',
            default   => 'statut-annulee',
        };
        $statutBadge = match($facture->statut) {
            'payee'   => 'fs-payee',
            'en_cours'=> 'fs-credit',
            default   => 'fs-annulee',
        };
    @endphp
    <div class="fct-hero {{ $statutClass }}">
        <div class="fct-hero-left">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:8px">
                <h1 class="fct-hero-num">{{ $facture->numero }}</h1>
                <span class="fct-statut {{ $statutBadge }}">
                    <i class="fas {{ $facture->statut === 'payee' ? 'fa-check-circle' : ($facture->statut === 'en_cours' ? 'fa-clock' : 'fa-times-circle') }}" style="font-size:9px"></i>
                    {{ $facture->statut_label }}
                </span>
            </div>
            <div class="fct-hero-meta">
                <span><i class="fas fa-calendar-alt"></i> {{ $facture->created_at->format('d/m/Y à H:i') }}</span>
                <span><i class="fas fa-user"></i> {{ $facture->user->nom_complet ?? "_" }}</span>
                <span><i class="fas fa-{{ $facture->mode_paiement === 'espece' ? 'money-bill-wave' : ($facture->mode_paiement === 'mobile_money' ? 'mobile-alt' : 'credit-card') }}"></i> {{ $facture->mode_paiement_label }}</span>
            </div>
        </div>
        <div class="fct-hero-right">
            <a href="{{ route('bons-livraison.pdf', $facture) }}" target="_blank" class="btn-fct btn-fct-pdf">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('caisse.ticket', $facture) }}" target="_blank" class="btn-fct btn-fct-print">
                <i class="fas fa-print"></i> Ticket
            </a>
            <a href="{{ route('bons-livraison.index') }}" class="btn-fct btn-fct-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>


    {{-- ══ INFOS CLIENT + PAIEMENT ══ --}}
    <div class="fct-grid">

        {{-- Client --}}
        <div class="fct-card">
            <div class="fct-card-hd">
                <span class="fct-card-ico" style="background:linear-gradient(135deg,#2563eb,#3b82f6)">
                    <i class="fas fa-user"></i>
                </span>
                <h6>Client</h6>
            </div>
            <div class="fct-card-bd">
                @if($facture->client)
                    <div class="fct-info-row">
                        <span class="fct-info-lbl">Nom</span>
                        <span class="fct-info-val">{{ $facture->client ? $facture->client->nom . ' ' . $facture->client->prenom : 'Client Divers' }}</span>
                    </div>
                    <div class="fct-info-row">
                        <span class="fct-info-lbl">Téléphone</span>
                        <span class="fct-info-val">{{ $facture->client->telephone ?? '—' }}</span>
                    </div>
                @else
                    <div class="fct-info-row">
                        <span class="fct-info-lbl">Client</span>
                        <span class="fct-info-val" style="color:#94a3b8">Client divers</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Détails vente --}}
        <div class="fct-card">
            <div class="fct-card-hd">
                <span class="fct-card-ico" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="fas fa-receipt"></i>
                </span>
                <h6>Détails de la vente</h6>
            </div>
            <div class="fct-card-bd">
                <div class="fct-info-row">
                    <span class="fct-info-lbl">Date</span>
                    <span class="fct-info-val">{{ $facture->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="fct-info-row">
                    <span class="fct-info-lbl">Caissier</span>
                    <span class="fct-info-val">{{ $facture->user->nom_complet ?? "_" }}</span>
                </div>
                <div class="fct-info-row">
                    <span class="fct-info-lbl">Mode paiement</span>
                    <span class="fct-info-val">{{ $facture->mode_paiement_label }}</span>
                </div>
                <div class="fct-info-row">
                    <span class="fct-info-lbl">Nb articles</span>
                    <span class="fct-info-val">{{ $facture->lignes->count() }} ligne(s)</span>
                </div>
            </div>
        </div>

    </div>


    {{-- ══ LIGNES ══ --}}
    <div class="fct-table-card">
        <div class="fct-table-hd">
            <span class="fct-card-ico" style="background:linear-gradient(135deg,#475569,#64748b)">
                <i class="fas fa-list"></i>
            </span>
            <h6>
                Articles
                <span class="nb-badge">{{ $facture->lignes->count() }}</span>
            </h6>
        </div>
        <div style="overflow-x:auto">
            <table class="fct-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Produit</th>
                        <th style="text-align:right">Prix unit.</th>
                        <th style="text-align:center">Qté</th>
                        <th style="text-align:right">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facture->lignes as $i => $ligne)
                    <tr>
                        <td><span class="prod-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div class="prod-name">{{ $ligne->libelle }}</div>
                        </td>
                        <td class="prix-cell">
                            {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="qte-cell">
                            {{ number_format($ligne->quantite, 2, ',', ' ') }}
                        </td>
                        <td class="st-cell">
                            {{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- ══ TOTAUX ══ --}}
    <div class="fct-totaux">

        <div class="tot-row">
            <span class="tot-lbl">Sous-total</span>
            <span class="tot-val">{{ number_format($facture->sous_total, 0, ',', ' ') }} FCFA</span>
        </div>

        @if($facture->remise > 0)
        <div class="tot-row tot-remise">
            <span class="tot-lbl"><i class="fas fa-percent mr-1" style="font-size:10px"></i> Remise</span>
            <span class="tot-val">− {{ number_format($facture->remise, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif

        <div class="tot-row tot-total">
            <span class="tot-lbl">Total à payer</span>
            <span class="tot-val">{{ number_format($facture->total, 0, ',', ' ') }} FCFA</span>
        </div>

        @if($facture->statut !== 'en_cours')
        <div class="tot-row tot-recu">
            <span class="tot-lbl">Montant reçu</span>
            <span class="tot-val">{{ number_format($facture->montant_recu, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="tot-row tot-monnaie">
            <span class="tot-lbl"><i class="fas fa-exchange-alt mr-1" style="font-size:10px"></i> Monnaie rendue</span>
            <span class="tot-val">{{ number_format($facture->monnaie, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif

    </div>

    {{-- ══ BANNIÈRE CRÉDIT ══ --}}
    @if($facture->statut === 'en_cours')
    <div class="credit-banner">
        <div class="credit-banner-ico"><i class="fas fa-clock"></i></div>
        <div>
            <strong>Vente à crédit — Paiement en attente</strong>
            Reste à encaisser :
            <strong style="font-size:15px;color:#d97706">
                {{ number_format($facture->reste_a_payer, 0, ',', ' ') }} FCFA
            </strong>
        </div>
    </div>
    @endif

</div>
@endsection