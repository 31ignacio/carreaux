{{-- resources/views/rapports/ventes.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport des Ventes')
@section('page-title', '')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Ventes</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&family=Syne:wght@700;800&display=swap');

.rv-wrap { font-family: sans-serif; }

/* ══ PAGE HEADER ══ */
.rv-page-hd {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
}
.rv-page-hd-left h1 {
    font-family: sans-serif; font-size: 1.5rem;
    color: #0f172a; margin: 0 0 2px; font-weight: 800;
}
.rv-page-hd-left p { font-size: 12px; color: #94a3b8; margin: 0; font-weight: 500; }
.btn-export-pdf {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff !important; border: none; border-radius: 12px;
    padding: 11px 20px; font-size: 13px; font-weight: 700;
    text-decoration: none !important; transition: all .2s;
    box-shadow: 0 4px 16px rgba(220,38,38,.3); font-family: 'DM Sans', sans-serif;
}
.btn-export-pdf:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(220,38,38,.4); color: #fff !important; }

/* ══ FILTRES ══ */
.rv-filters {
    background: #fff; border: 1px solid #f1f5f9; border-radius: 16px;
    padding: 16px 20px; margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(11,15,26,.04);
    display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;
}
.flt-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 160px; }
.flt-group label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }
.flt-wrap {
    display: flex; align-items: center; background: #f8fafc;
    border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; transition: .2s;
}
.flt-wrap:focus-within { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.flt-ico { width: 34px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 12px; flex-shrink: 0; }
.flt-wrap:focus-within .flt-ico { color: #6366f1; }
.flt-inp { flex: 1; border: none; background: transparent; padding: 9px 10px 9px 0; font-size: 12.5px; color: #0f172a; outline: none; font-family: 'DM Sans', sans-serif; }
.btn-filtrer {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #6366f1, #818cf8);
    color: #fff; border: none; border-radius: 10px;
    padding: 10px 18px; font-size: 12px; font-weight: 700;
    cursor: pointer; transition: .2s; white-space: nowrap;
    font-family:  sans-serif;
    box-shadow: 0 4px 12px rgba(99,102,241,.25);
}
.btn-filtrer:hover { transform: translateY(-1px); }
.btn-reset {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f1f5f9; border: none; border-radius: 10px;
    padding: 10px 16px; font-size: 12px; font-weight: 700;
    color: #64748b !important; text-decoration: none !important;
    transition: .15s; white-space: nowrap;
}
.btn-reset:hover { background: #e2e8f0; }

/* ══ KPI CARDS ══ */
.rv-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
@media(max-width: 768px) { .rv-kpis { grid-template-columns: 1fr; } }

.kpi-card {
    background: #fff; border: 1px solid #f1f5f9; border-radius: 18px;
    padding: 20px 22px; position: relative; overflow: hidden;
    box-shadow: 0 2px 12px rgba(11,15,26,.05); transition: transform .2s;
}
.kpi-card:hover { transform: translateY(-3px); }
.kpi-card::before {
    content: ''; position: absolute; top: -30px; right: -30px;
    width: 100px; height: 100px; border-radius: 50%; opacity: .06;
}
.kpi-card.kpi-ca::before   { background: #10b981; }
.kpi-card.kpi-nb::before   { background: #6366f1; }
.kpi-card.kpi-rem::before  { background: #f59e0b; }

.kpi-ico {
    width: 44px; height: 44px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; margin-bottom: 14px;
}
.kpi-ca  .kpi-ico { background: #d1fae5; color: #059669; }
.kpi-nb  .kpi-ico { background: #e0e7ff; color: #4f46e5; }
.kpi-rem .kpi-ico { background: #fef3c7; color: #d97706; }

.kpi-val { font-family:  sans-serif; font-size: 1.7rem; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 4px; }
.kpi-val sup { font-size: 12px; font-weight: 700; color: #94a3b8; vertical-align: super; }
.kpi-label { font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 12px; }
.kpi-foot {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 8px;
    padding: 4px 10px; font-size: 11px; font-weight: 600; color: #94a3b8;
}
.kpi-ca  .kpi-foot { background: #f0fdf4; border-color: #bbf7d0; color: #059669; }
.kpi-nb  .kpi-foot { background: #eef2ff; border-color: #c7d2fe; color: #4f46e5; }
.kpi-rem .kpi-foot { background: #fffbeb; border-color: #fde68a; color: #d97706; }

/* ══ DEUX COLONNES ══ */
.rv-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; margin-bottom: 20px; }
@media(max-width: 900px) { .rv-row { grid-template-columns: 1fr; } }

/* ══ CARDS ══ */
.rv-card {
    background: #fff; border: 1px solid #f1f5f9; border-radius: 18px;
    overflow: hidden; box-shadow: 0 2px 12px rgba(11,15,26,.05);
}
.rv-card-hd {
    padding: 15px 20px; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    background: #fafbff;
}
.rv-card-title {
    font-size: 12px; font-weight: 800; color: #0f172a;
    text-transform: uppercase; letter-spacing: .5px;
    display: flex; align-items: center; gap: 8px; margin: 0;
}
.rv-card-title i { opacity: .4; }
.rv-badge {
    background: #0f172a; color: #fff; padding: 2px 9px;
    border-radius: 20px; font-size: 10px; font-weight: 800;
}
.rv-card-body { padding: 20px; }

/* ══ RÉSUMÉ TABLE ══ */
.resume-table { width: 100%; border-collapse: collapse; }
.resume-table td { padding: 10px 14px; border-bottom: 1px solid #f9fafb; font-size: 12px; vertical-align: middle; }
.resume-table tr:last-child td { border-bottom: none; }
.resume-table .lbl { color: #94a3b8; font-weight: 600; }
.resume-table .val { font-weight: 800; color: #0f172a; text-align: right; }
.resume-table .val-success { color: #059669; }
.resume-table .val-warning { color: #d97706; }
.resume-highlight td { background: #f0fdf4 !important; }

/* ══ MEILLEUR JOUR ══ */
.best-day-badge {
    display: inline-flex; flex-direction: column; align-items: flex-end;
    gap: 2px;
}
.best-day-date { font-size: 11px; font-weight: 800; color: #059669; }
.best-day-ca { font-size: 10px; color: #94a3b8; }

/* ══ TABLE FACTURES ══ */
.rv-table-hd {
    padding: 15px 20px; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    background: #fafbff; flex-wrap: wrap; gap: 10px;
}
.rv-table { width: 100%; border-collapse: collapse; }
.rv-table th {
    padding: 10px 16px; font-size: 9px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .5px; color: #94a3b8;
    background: #f8fafc; border-bottom: 1px solid #f1f5f9; white-space: nowrap;
}
.rv-table td { padding: 13px 16px; border-bottom: 1px solid #f9fafb; vertical-align: middle; font-size: 12.5px; }
.rv-table tr:last-child td { border-bottom: none; }
.rv-table tr:hover td { background: #fafbff; }
.rv-table tfoot td { padding: 12px 16px; font-size: 12px; background: #f8fafc; border-top: 2px solid #e2e8f0; font-weight: 800; color: #0f172a; }

.facture-num {
    font-weight: 800; color: #4f46e5;
    text-decoration: none !important; font-size: 12px;
}
.facture-num:hover { color: #6366f1; }

.paiement-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
    background: #f1f5f9; color: #475569;
}
.type-prix-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
}
.tp-detail { background: #d1fae5; color: #065f46; }
.tp-moyen  { background: #fef3c7; color: #92400e; }
.tp-gros   { background: #dbeafe; color: #1e40af; }

.montant-pos { font-weight: 800; color: #059669; }
.montant-neg { color: #d97706; font-weight: 700; }
.montant-muted { color: #cbd5e1; }

.caissier-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; color: #64748b;
}
.caissier-avatar {
    width: 24px; height: 24px; border-radius: 7px;
    background: #e0e7ff; color: #4f46e5;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 900; flex-shrink: 0;
}

.date-cell { font-size: 11px; color: #64748b; white-space: nowrap; }
.date-cell strong { display: block; font-size: 12px; color: #0f172a; font-weight: 700; }

.act-btn {
    width: 30px; height: 30px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px; text-decoration: none !important; transition: all .15s;
    color: #94a3b8; background: transparent; border: none; cursor: pointer;
}
.act-btn-blue:hover { background: #eff6ff; color: #2563eb; }
.act-btn-red:hover  { background: #fef2f2; color: #dc2626; }

.rv-empty { text-align: center; padding: 60px 20px; }
.rv-empty i { font-size: 40px; color: #e2e8f0; display: block; margin-bottom: 12px; }
.rv-empty p { font-size: 13px; font-weight: 700; color: #94a3b8; margin: 0; }

@keyframes rowIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.row-anim { animation: rowIn .22s ease both; }

@media(max-width:768px) {
    .rv-table th:nth-child(n+3):nth-child(-n+5),
    .rv-table td:nth-child(n+3):nth-child(-n+5) { display: none; }
}
</style>
@endpush

@section('content')
<div class="rv-wrap">

    {{-- ══ HEADER ══ --}}
    <div class="rv-page-hd">
        <div class="rv-page-hd-left">
            <h1><i class="fas fa-chart-line" style="opacity:.4;font-size:1.1rem;margin-right:8px"></i>Rapport des ventes</h1>
            <p>
                Période : {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                → {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                · {{ $nbVentes }} vente(s)
            </p>
        </div>
        <a href="{{ route('rapports.export-pdf') }}?type=ventes&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}"
           class="btn-export-pdf" target="_blank">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </a>
    </div>

    {{-- ══ FILTRES ══ --}}
    <div class="rv-filters">
        <form method="GET" style="display:contents">
            <div class="flt-group">
                <label><i class="fas fa-calendar-alt mr-1"></i> Date début</label>
                <div class="flt-wrap">
                    <div class="flt-ico"><i class="fas fa-calendar-alt"></i></div>
                    <input type="date" name="date_debut" class="flt-inp" value="{{ $dateDebut }}">
                </div>
            </div>
            <div class="flt-group">
                <label><i class="fas fa-calendar-check mr-1"></i> Date fin</label>
                <div class="flt-wrap">
                    <div class="flt-ico"><i class="fas fa-calendar-check"></i></div>
                    <input type="date" name="date_fin" class="flt-inp" value="{{ $dateFin }}">
                </div>
            </div>
            <button type="submit" class="btn-filtrer"><i class="fas fa-search"></i> Filtrer</button>
            <a href="{{ route('rapports.ventes') }}" class="btn-reset"><i class="fas fa-redo"></i> Ce mois</a>
        </form>
    </div>

    {{-- ══ KPIs ══ --}}
    <div class="rv-kpis">
        <div class="kpi-card kpi-ca">
            <div class="kpi-ico"><i class="fas fa-money-bill-wave"></i></div>
            <div class="kpi-val">{{ number_format($totalCA, 0, ',', ' ') }} <sup>FCFA</sup></div>
            <div class="kpi-label">Chiffre d'affaires</div>
            <div class="kpi-foot">
                <i class="fas fa-calendar-alt" style="font-size:9px"></i>
                {{ \Carbon\Carbon::parse($dateDebut)->format('d/m') }} – {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
            </div>
        </div>
        <div class="kpi-card kpi-nb">
            <div class="kpi-ico"><i class="fas fa-shopping-cart"></i></div>
            <div class="kpi-val">{{ $nbVentes }}</div>
            <div class="kpi-label">Ventes effectuées</div>
            <div class="kpi-foot">
                <i class="fas fa-receipt" style="font-size:9px"></i>
                Panier moy. : {{ $nbVentes > 0 ? number_format($totalCA / $nbVentes, 0, ',', ' ') : 0 }} FCFA
            </div>
        </div>
        <div class="kpi-card kpi-rem">
            <div class="kpi-ico"><i class="fas fa-percent"></i></div>
            <div class="kpi-val">{{ number_format($totalRemises, 0, ',', ' ') }} <sup>FCFA</sup></div>
            <div class="kpi-label">Total remises accordées</div>
            <div class="kpi-foot">
                <i class="fas fa-chart-pie" style="font-size:9px"></i>
                {{ $nbVentes > 0 && ($totalCA + $totalRemises) > 0 ? number_format(($totalRemises / ($totalCA + $totalRemises)) * 100, 1) : 0 }}% du CA brut
            </div>
        </div>
    </div>

    {{-- ══ GRAPHIQUE + RÉSUMÉ ══ --}}
    <div class="rv-row">
        {{-- Graphique --}}
        <div class="rv-card">
            <div class="rv-card-hd">
                <h3 class="rv-card-title"><i class="fas fa-chart-bar"></i> CA par jour</h3>
            </div>
            <div class="rv-card-body">
                @if($ventesParJour->count() > 0)
                    <canvas id="chartVentes" height="110"></canvas>
                @else
                    <div class="rv-empty">
                        <i class="fas fa-chart-bar"></i>
                        <p>Aucune donnée sur cette période</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Résumé --}}
        <div class="rv-card">
            <div class="rv-card-hd">
                <h3 class="rv-card-title"><i class="fas fa-info-circle"></i> Résumé période</h3>
            </div>
            <table class="resume-table">
                <tbody>
                    <tr>
                        <td class="lbl">Période</td>
                        <td class="val" style="font-size:11px">
                            {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                            → {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Nb jours</td>
                        <td class="val">
                            {{ \Carbon\Carbon::parse($dateDebut)->diffInDays(\Carbon\Carbon::parse($dateFin)) + 1 }} jours
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Nb ventes</td>
                        <td class="val">{{ $nbVentes }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">CA total</td>
                        <td class="val val-success">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="lbl">Panier moyen</td>
                        <td class="val">{{ $nbVentes > 0 ? number_format($totalCA / $nbVentes, 0, ',', ' ') : 0 }} FCFA</td>
                    </tr>
                    <tr>
                        <td class="lbl">Total remises</td>
                        <td class="val val-warning">- {{ number_format($totalRemises, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="resume-highlight">
                        <td class="lbl" style="color:#059669;font-weight:700"><i class="fas fa-trophy" style="font-size:10px;margin-right:4px"></i> Meilleur jour</td>
                        <td class="val">
                            @if($ventesParJour->count() > 0)
                                <div class="best-day-badge">
                                    <span class="best-day-date">
                                        {{ $ventesParJour->keys()->get($ventesParJour->values()->search($ventesParJour->max())) }}
                                    </span>
                                    <span class="best-day-ca">{{ number_format($ventesParJour->max(), 0, ',', ' ') }} FCFA</span>
                                </div>
                            @else
                                <span style="color:#cbd5e1">—</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ TABLE FACTURES ══ --}}
    <div class="rv-card">
        <div class="rv-table-hd">
            <h3 class="rv-card-title">
                <i class="fas fa-list"></i> Détail des bons
                <span class="rv-badge">{{ $nbVentes }}</span>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="rv-table">
                <thead>
                    <tr>
                        <th>N° Bon</th>
                        <th>Client</th>
                        <th>Paiement</th>
                        <th>Type prix</th>
                        <th style="text-align:right">Sous-total</th>
                        <th style="text-align:right">Remise</th>
                        <th style="text-align:right">Total</th>
                        <th>Caissier</th>
                        <th>Date</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $i => $facture)
                    <tr class="row-anim" style="animation-delay:{{ $i * 0.025 }}s">
                        <td>
                            <a href="{{ route('bons-livraison.show', $facture) }}" class="facture-num">
                                <i class="fas fa-receipt" style="font-size:9px;opacity:.5;margin-right:4px"></i>{{ $facture->numero }}
                            </a>
                        </td>
                        <td style="font-size:12px;font-weight:600;color:#374151">
                            {{ $facture->client->nom ?? 'Client Divers' }}
                        </td>
                        <td>
                            <span class="paiement-badge">
                                @if(str_contains(strtolower($facture->mode_paiement_label ?? ''), 'espèce') || str_contains(strtolower($facture->mode_paiement_label ?? ''), 'cash'))
                                    <i class="fas fa-money-bill-wave" style="font-size:8px"></i>
                                @elseif(str_contains(strtolower($facture->mode_paiement_label ?? ''), 'mobile') || str_contains(strtolower($facture->mode_paiement_label ?? ''), 'momo'))
                                    <i class="fas fa-mobile-alt" style="font-size:8px"></i>
                                @else
                                    <i class="fas fa-credit-card" style="font-size:8px"></i>
                                @endif
                                {{ $facture->mode_paiement_label }}
                            </span>
                        </td>
                        <td>
                            @php
                                $tp = strtolower($facture->type_prix_label ?? '');
                                $tpClass = str_contains($tp,'gros') && !str_contains($tp,'demi') ? 'tp-gros' : (str_contains($tp,'moyen') || str_contains($tp,'demi') ? 'tp-moyen' : 'tp-detail');
                            @endphp
                            <span class="type-prix-badge {{ $tpClass }}">{{ $facture->type_prix_label }}</span>
                        </td>
                        <td style="text-align:right;font-weight:700;color:#374151">
                            {{ number_format($facture->sous_total, 0, ',', ' ') }} F
                        </td>
                        <td style="text-align:right">
                            @if($facture->remise > 0)
                                <span class="montant-neg">-{{ number_format($facture->remise, 0, ',', ' ') }} F</span>
                            @else
                                <span class="montant-muted">—</span>
                            @endif
                        </td>
                        <td style="text-align:right">
                            <span class="montant-pos">{{ number_format($facture->total, 0, ',', ' ') }} F</span>
                        </td>
                        <td>
                            <div class="caissier-chip">
                                <div class="caissier-avatar">{{ strtoupper(substr($facture->user->prenom ?? '?', 0, 1)) }}</div>
                                {{ $facture->user->prenom ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <div class="date-cell">
                                <strong>{{ $facture->created_at->format('d/m/Y') }}</strong>
                                {{ $facture->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td style="text-align:center;white-space:nowrap">
                            <div style="display:inline-flex;align-items:center;gap:4px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:10px;padding:3px">
                                <a href="{{ route('bons-livraison.show', $facture) }}" class="act-btn act-btn-blue" title="Voir le bon de livraison"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('bons-livraison.pdf', $facture) }}" class="act-btn act-btn-red" title="Télécharger PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="rv-empty">
                                <i class="fas fa-inbox"></i>
                                <p>Aucune vente sur cette période</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($nbVentes > 0)
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;color:#94a3b8;font-size:10px;text-transform:uppercase;letter-spacing:.5px">Totaux</td>
                        <td style="text-align:right">{{ number_format($factures->sum('sous_total'), 0, ',', ' ') }} F</td>
                        <td style="text-align:right;color:#d97706">- {{ number_format($totalRemises, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;color:#059669">{{ number_format($totalCA, 0, ',', ' ') }} F</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    @if($ventesParJour->count() > 0)
    (function() {
        var ctx = document.getElementById('chartVentes').getContext('2d');
        var labels = {!! json_encode($ventesParJour->keys()) !!};
        var values = {!! json_encode($ventesParJour->values()) !!};
        var maxVal = Math.max.apply(null, values);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'CA (FCFA)',
                    data: values,
                    backgroundColor: values.map(function(v) {
                        return v === maxVal ? 'rgba(99,102,241,0.9)' : 'rgba(99,102,241,0.35)';
                    }),
                    borderColor: values.map(function(v) {
                        return v === maxVal ? '#4f46e5' : 'rgba(99,102,241,0.5)';
                    }),
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#94a3b8',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "sans-serif", size: 10, weight: '700' },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(241,245,249,1)', drawBorder: false },
                        ticks: {
                            font: { family: "sans-serif", size: 10 },
                            color: '#94a3b8',
                            callback: function(v) {
                                if (v >= 1000000) return (v/1000000).toFixed(1) + 'M';
                                if (v >= 1000) return (v/1000).toFixed(0) + 'k';
                                return v;
                            }
                        }
                    }
                }
            }
        });
    })();
    @endif
</script>
@endpush