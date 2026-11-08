@extends('layouts.app')
@section('title', 'Crédits clients')
@section('page-title', 'Suivi des crédits clients')
@section('breadcrumb')
    <li class="breadcrumb-item active">Crédits clients</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --bg:     #F8F9FB;
    --card:   #ffffff;
    --border: #E8ECF0;
    --text:   #141C26;
    --muted:  #7A8899;
    --blue:   #2563EB;
    --blue-l: #EEF4FF;
    --green:  #16A34A;
    --green-l:#E8F8EE;
    --amber:  #D97706;
    --amber-l:#FEF3C7;
    --red:    #DC2626;
    --red-l:  #FEF2F2;
    --sh:     0 2px 12px rgba(20,28,38,.07);
    --sh-md:  0 6px 24px rgba(20,28,38,.10);
    --r:      14px;
}

.cr-wrap { font-family: 'DM Sans', sans-serif; color: var(--text); }

/* ── KPI ── */
.cr-kpi { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
.cr-kpi-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r); padding: 20px 22px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: var(--sh); transition: box-shadow .2s;
}
.cr-kpi-card:hover { box-shadow: var(--sh-md); }
.cr-kpi-ico {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.cr-kpi-ico.blue  { background: var(--blue-l);  color: var(--blue); }
.cr-kpi-ico.green { background: var(--green-l); color: var(--green); }
.cr-kpi-ico.red   { background: var(--red-l);   color: var(--red); }
.cr-kpi-val { font-size: 1.35rem; font-weight: 800; line-height: 1; margin-bottom: 3px; }
.cr-kpi-lbl { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

/* ── Filtres ── */
.cr-filter-bar {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r); padding: 14px 18px;
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
    margin-bottom: 18px; box-shadow: var(--sh);
}
.cr-filter-bar .fi {
    flex: 1; min-width: 180px;
    display: flex; align-items: center;
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 10px; overflow: hidden; transition: border-color .15s;
}
.cr-filter-bar .fi:focus-within { border-color: var(--blue); }
.cr-filter-bar .fi i { width: 36px; text-align: center; color: var(--muted); font-size: 13px; flex-shrink: 0; }
.cr-filter-bar .fi input,
.cr-filter-bar .fi select {
    flex: 1; border: none; background: transparent;
    padding: 9px 10px 9px 0; font-size: 13px; color: var(--text);
    outline: none; font-family: 'DM Sans', sans-serif;
}
.cr-filter-bar .fi select { appearance: none; }
.cr-btn-filter {
    height: 40px; padding: 0 18px; border-radius: 10px; border: none;
    background: var(--blue); color: #fff; font-size: 13px; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif; display: inline-flex;
    align-items: center; gap: 6px; transition: opacity .15s; flex-shrink: 0;
}
.cr-btn-filter:hover { opacity: .88; }
.cr-btn-reset {
    height: 40px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--border); background: var(--card);
    color: var(--muted); font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    display: inline-flex; align-items: center; gap: 5px;
    text-decoration: none; flex-shrink: 0;
}
.cr-btn-reset:hover { background: var(--bg); color: var(--text); text-decoration: none; }

/* ── Accordion client ── */
.cr-client-block {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r); margin-bottom: 12px;
    box-shadow: var(--sh); overflow: hidden; transition: box-shadow .2s;
}
.cr-client-block:hover { box-shadow: var(--sh-md); }

.cr-client-summary {
    display: flex; flex-wrap: wrap; align-items: center;
    gap: 10px; padding: 16px 20px;
    cursor: pointer; list-style: none;
    transition: background .12s;
}
.cr-client-summary::-webkit-details-marker { display: none; }
.cr-client-block[open] .cr-client-summary { background: #FAFBFD; border-bottom: 1px solid var(--border); }

.cr-client-name {
    flex: 1 1 180px; font-size: 14px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; gap: 8px;
}
.cr-client-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--blue-l); color: var(--blue);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; flex-shrink: 0;
}

.cr-chip {
    font-size: 12px; font-weight: 600; padding: 5px 12px;
    border-radius: 20px; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 5px;
}
.cr-chip.blue  { background: var(--blue-l);  color: #1D4ED8; }
.cr-chip.green { background: var(--green-l); color: var(--green); }
.cr-chip.red   { background: var(--red-l);   color: var(--red); font-weight: 700; }
.cr-chip.neutral { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }

.cr-toggle-ico {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: var(--muted); flex-shrink: 0;
    transition: background .15s, transform .2s;
}
.cr-client-block[open] .cr-toggle-ico {
    background: var(--blue-l); color: var(--blue);
    transform: rotate(180deg);
}

.cr-btn-fiche {
    height: 32px; padding: 0 14px; border-radius: 8px;
    border: 1.5px solid var(--blue); background: var(--blue-l);
    color: var(--blue); font-size: 12px; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    flex-shrink: 0; transition: all .15s;
}
.cr-btn-fiche:hover { background: var(--blue); color: #fff; text-decoration: none; }

/* ── Table bons ── */
.cr-table-wrap { overflow-x: auto; padding: 0 0 4px; }
.cr-table {
    width: 100%; border-collapse: collapse;
    font-size: 12.5px; min-width: 700px;
}
.cr-table thead th {
    padding: 9px 16px; text-align: left;
    font-size: 10.5px; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px; font-weight: 600;
    background: var(--bg); border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.cr-table tbody tr { border-bottom: 1px solid #F1F4F7; }
.cr-table tbody tr:last-child { border-bottom: none; }
.cr-table tbody tr:hover td { background: #FAFBFD; }
.cr-table td { padding: 10px 16px; vertical-align: middle; }
.cr-table td.num { font-family: monospace; font-size: 12px; color: var(--muted); }
.cr-table td.amt { font-weight: 700; color: var(--text); }
.cr-table td.reste-red { font-weight: 700; color: var(--red); }
.cr-table td.reste-ok  { font-weight: 700; color: var(--green); }

.cr-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10.5px; font-weight: 700;
}
.cr-badge.soldee   { background: var(--green-l); color: var(--green); }
.cr-badge.partiel  { background: var(--amber-l); color: var(--amber); }
.cr-badge.impayee  { background: var(--red-l);   color: var(--red); }

.cr-link-detail {
    color: var(--blue); font-size: 12px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
}
.cr-link-detail:hover { text-decoration: underline; }

/* ── Empty ── */
.cr-empty { text-align: center; padding: 50px 20px; color: var(--muted); }
.cr-empty i { font-size: 36px; opacity: .25; display: block; margin-bottom: 12px; }

/* ── Responsive ── */
@media(max-width: 768px) {
    .cr-kpi { grid-template-columns: 1fr; }
    .cr-client-summary { gap: 8px; }
}
</style>
@endpush

@section('content')
<div class="cr-wrap">

    {{-- ── KPI ── --}}
    <div class="cr-kpi">
        <div class="cr-kpi-card">
            <div class="cr-kpi-ico blue"><i class="fas fa-file-invoice-dollar"></i></div>
            <div>
                <div class="cr-kpi-val">{{ number_format($totaux->initial, 0, ',', ' ') }} F</div>
                <div class="cr-kpi-lbl">Crédits initiaux</div>
            </div>
        </div>
        <div class="cr-kpi-card">
            <div class="cr-kpi-ico green"><i class="fas fa-hand-holding-usd"></i></div>
            <div>
                <div class="cr-kpi-val">{{ number_format($totaux->rembourse, 0, ',', ' ') }} F</div>
                <div class="cr-kpi-lbl">Total remboursé</div>
            </div>
        </div>
        <div class="cr-kpi-card">
            <div class="cr-kpi-ico red"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <div class="cr-kpi-val" style="color:var(--red)">{{ number_format($soldeGlobal, 0, ',', ' ') }} F</div>
                <div class="cr-kpi-lbl">Solde restant</div>
            </div>
        </div>
    </div>

    {{-- ── Filtres ── --}}
    <form method="GET" class="cr-filter-bar">
        <div class="fi" style="flex:2;min-width:220px">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Nom du client ou numéro du bon…"
                   value="{{ request('search') }}">
        </div>
        <div class="fi" style="flex:1;min-width:180px">
            <i class="fas fa-filter"></i>
            <select name="statut">
                <option value="">Tous les clients crédit</option>
                <option value="dette"  @selected(request('statut') === 'dette') >Avec dette en cours</option>
                <option value="a_jour" @selected(request('statut') === 'a_jour')>À jour</option>
            </select>
        </div>
        <div class="fi" style="min-width:155px">
            <i class="fas fa-calendar"></i>
            <input type="date" name="date_debut" aria-label="Date de début" value="{{ request('date_debut') }}">
        </div>
        <div class="fi" style="min-width:155px">
            <i class="fas fa-calendar"></i>
            <input type="date" name="date_fin" aria-label="Date de fin" value="{{ request('date_fin') }}">
        </div>
        <button type="submit" class="cr-btn-filter">
            <i class="fas fa-search"></i> Filtrer
        </button>
        @if(request()->hasAny(['search','statut','date_debut','date_fin']))
            <a href="{{ route('credits.index') }}" class="cr-btn-reset">
                <i class="fas fa-times"></i> Effacer
            </a>
        @endif
    </form>

    {{-- ── Liste clients ── --}}
    @forelse($clients as $client)
        <details class="cr-client-block" @if(request('search')) open @endif>
            <summary class="cr-client-summary">
                {{-- Avatar + Nom --}}
                <div class="cr-client-name">
                    <div class="cr-client-avatar">
                        {{ strtoupper(substr($client->nom_complet, 0, 1)) }}
                    </div>
                    {{ $client->nom_complet }}
                </div>

                {{-- Chips financiers --}}
                <span class="cr-chip blue">
                    <i class="fas fa-file-invoice" style="font-size:10px"></i>
                    Crédit : {{ number_format($client->credits_initiaux, 0, ',', ' ') }} F
                </span>
                <span class="cr-chip neutral">
                    <i class="fas fa-check" style="font-size:10px"></i>
                    Remb. : {{ number_format($client->remboursements_total, 0, ',', ' ') }} F
                </span>
                <span class="cr-chip {{ (float)$client->solde_restant > 0 ? 'red' : 'green' }}">
                    <i class="fas fa-{{ (float)$client->solde_restant > 0 ? 'exclamation-circle' : 'check-circle' }}"
                       style="font-size:10px"></i>
                    Reste : {{ number_format($client->solde_restant, 0, ',', ' ') }} F
                </span>

                {{-- Action --}}
                <a class="cr-btn-fiche" href="{{ route('clients.show', $client) }}"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-eye"></i> Fiche / encaisser
                </a>
                @if((float) $client->solde_restant > 0)
                    <a class="cr-btn-fiche" href="{{ route('clients.show', $client) }}?action=credit"
                       onclick="event.stopPropagation()">
                        <i class="fas fa-hand-holding-usd"></i> Rembourser
                    </a>
                @endif
                <a class="cr-btn-fiche" href="{{ route('clients.show', $client) }}?action=avance"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-wallet"></i> Ajouter avance
                </a>

                {{-- Toggle icône --}}
                <span class="cr-toggle-ico"><i class="fas fa-chevron-down"></i></span>
            </summary>

            {{-- Table bons --}}
            <div class="cr-table-wrap">
                <table class="cr-table">
                    <thead>
                        <tr>
                            <th>Bon</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Avance</th>
                            <th>Crédit initial</th>
                            <th>Remboursé</th>
                            <th>Reste dû</th>
                            <th>État</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->factures as $facture)
                            @php
                                $paye   = (float) ($facture->reglements_sum_montant ?? 0);
                                $avance = (float) $facture->montant_avance;
                                $reste  = max((float) $facture->total - $avance - $paye, 0);
                            @endphp
                            <tr>
                                <td class="num">{{ $facture->numero }}</td>
                                <td style="color:var(--muted);white-space:nowrap">
                                    {{ $facture->created_at->format('d/m/Y') }}
                                </td>
                                <td class="amt">{{ number_format($facture->total, 0, ',', ' ') }} F</td>
                                <td style="color:var(--muted)">{{ number_format($avance, 0, ',', ' ') }} F</td>
                                <td class="amt">{{ number_format(max((float)$facture->total - $avance, 0), 0, ',', ' ') }} F</td>
                                <td style="color:var(--green);font-weight:600">{{ number_format($paye, 0, ',', ' ') }} F</td>
                                <td class="{{ $reste > 0 ? 'reste-red' : 'reste-ok' }}">
                                    {{ number_format($reste, 0, ',', ' ') }} F
                                </td>
                                <td>
                                    @if($reste <= 0)
                                        <span class="cr-badge soldee"><i class="fas fa-check-circle" style="font-size:9px"></i> Soldée</span>
                                    @elseif($paye > 0)
                                        <span class="cr-badge partiel"><i class="fas fa-clock" style="font-size:9px"></i> Partielle</span>
                                    @else
                                        <span class="cr-badge impayee"><i class="fas fa-times-circle" style="font-size:9px"></i> Impayée</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="cr-link-detail" href="{{ route('bons-livraison.show', $facture) }}">
                                        <i class="fas fa-arrow-right" style="font-size:10px"></i> Détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @empty
        <div class="cr-empty">
            <i class="fas fa-inbox"></i>
            <p style="font-size:14px;font-weight:600;margin:0">Aucun client ou crédit ne correspond à ces filtres.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div style="margin-top:16px">
        {{ $clients->links() }}
    </div>

</div>
@endsection
