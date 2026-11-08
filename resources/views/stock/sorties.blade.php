@extends('layouts.app')

@section('title', 'Sorties de Stock')
@section('page-title', 'Sorties de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item active">Sorties de stock</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
:root {
    --dark:   #0f172a;
    --border: #e2e8f0;
    --sh:     0 4px 24px rgba(15,52,96,.09);
    --r:      16px;
}

/* ══ HERO ══ */
.s-hero {
    background: linear-gradient(140deg, #0f172a 0%, #7f1d1d 60%, #991b1b 100%);
    border-radius: var(--r);
    padding: 22px 26px;
    margin-bottom: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 12px 40px rgba(153,27,27,.28);
}
.s-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.07),transparent 65%);
    pointer-events:none;
}
.s-hero-title { font-size: clamp(.95rem,3vw,1.3rem); font-weight:800; color:#fff; margin:0; }
.s-hero-sub   { font-size:11px; color:rgba(255,255,255,.55); margin-top:3px; }

.h-btn {
    display:inline-flex; align-items:center; gap:6px;
    border-radius:10px; font-size:12px; font-weight:700;
    padding:8px 14px; border:none; cursor:pointer;
    transition:transform .15s, box-shadow .15s;
    text-decoration:none !important; white-space:nowrap;
}
.h-btn:hover { transform:translateY(-2px); text-decoration:none !important; }
.h-btn-danger {
    background:linear-gradient(135deg,#f87171,#ef4444);
    color:#fff !important;
    box-shadow:0 4px 12px rgba(239,68,68,.3);
}
.h-btn-danger:hover { box-shadow:0 6px 18px rgba(239,68,68,.4); }
.h-btn-pdf {
    background:linear-gradient(135deg,#f97316,#ea580c);
    color:#fff !important;
    box-shadow:0 4px 12px rgba(234,88,12,.3);
}
.h-btn-pdf:hover { box-shadow:0 6px 18px rgba(234,88,12,.4); }

.filter-active {
    display:inline-flex; align-items:center; gap:4px;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; padding:3px 10px; border-radius:20px;
    font-size:10px; font-weight:700;
}

/* ══ KPI ══ */
.kpi { border-radius:14px; padding:18px; box-shadow:var(--sh); transition:transform .2s, box-shadow .2s; position:relative; overflow:hidden; margin-bottom:14px; }
.kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,.12); }
.kpi::after { content:''; position:absolute; bottom:-16px; right:-16px; width:65px; height:65px; border-radius:50%; background:rgba(255,255,255,.15); }
.kpi-ico { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; margin-bottom:10px; }
.kpi-v   { font-size:1.5rem; font-weight:900; line-height:1; margin-bottom:3px; }
.kpi-l   { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; opacity:.65; }
.kpi-r { background:linear-gradient(135deg,#fee2e2,#fecaca); }
.kpi-r .kpi-ico { background:rgba(239,68,68,.18); color:#ef4444; }
.kpi-r .kpi-v, .kpi-r .kpi-l { color:#7f1d1d; }
.kpi-o { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.kpi-o .kpi-ico { background:rgba(245,158,11,.22); color:#d97706; }
.kpi-o .kpi-v, .kpi-o .kpi-l { color:#92400e; }
.kpi-b { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
.kpi-b .kpi-ico { background:rgba(59,130,246,.18); color:#3b82f6; }
.kpi-b .kpi-v, .kpi-b .kpi-l { color:#1e3a8a; }
.kpi-p { background:linear-gradient(135deg,#ede9fe,#ddd6fe); }
.kpi-p .kpi-ico { background:rgba(139,92,246,.18); color:#7c3aed; }
.kpi-p .kpi-v, .kpi-p .kpi-l { color:#4c1d95; }

/* ══ CARD ══ */
.pd-card { background:#fff; border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; margin-bottom:20px; }
.pd-card-head { padding:14px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.pd-card-title { display:flex; align-items:center; gap:9px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.7px; color:var(--dark); margin:0; }
.pd-card-ico { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; flex-shrink:0; }
.filter-wrap { padding:14px 20px; border-bottom:1px solid var(--border); background:#fafbff; }

/* ══ CUMUL ══ */
.cumul-card {
    background: linear-gradient(135deg,#fff7ed 0%,#fff 60%);
    border: 1px solid #fed7aa;
    border-radius: var(--r);
    box-shadow: var(--sh);
    overflow: hidden;
    margin-bottom: 20px;
}
.cumul-head {
    padding: 14px 20px;
    border-bottom: 1px solid #fed7aa;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    background: linear-gradient(90deg,#fff7ed,#fff);
}
.cumul-title { display:flex; align-items:center; gap:9px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.7px; color:#7c2d12; margin:0; }
.cumul-ico { width:28px; height:28px; border-radius:7px; background:linear-gradient(135deg,#f97316,#ea580c); display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; }

.cumul-table { width:100%; border-collapse:collapse; font-size:12px; }
.cumul-table thead tr { background: #7c2d12; }
.cumul-table thead th { padding:10px 16px; color:#fca5a5; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; border:none; }
.cumul-table thead th.r { text-align:right; }
.cumul-table tbody tr { border-bottom:1px solid #fde8d8; transition:background .1s; }
.cumul-table tbody tr:hover { background:#fff7f0; }
.cumul-table tbody tr:last-child { border-bottom:none; }
.cumul-table td { padding:10px 16px; vertical-align:middle; border:none; }
.cumul-table td.r { text-align:right; }
.cumul-table td.bold { font-weight:700; color:#1e293b; }
.cumul-table .tr-total td { background:#7c2d12; color:#fff; font-weight:900; padding:10px 16px; }
.cumul-table .tr-total td.r { text-align:right; color:#fca5a5; }
.cumul-rank { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; font-size:10px; font-weight:800; }
.rank-1 { background:#fbbf24; color:#78350f; }
.rank-2 { background:#94a3b8; color:#fff; }
.rank-3 { background:#d97706; color:#fff; }
.rank-n { background:#f1f5f9; color:#64748b; }

/* ══ BADGES ══ */
.t-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:10px; font-weight:700; }
.t-vente  { background:#fee2e2; color:#dc2626; }
.t-perte  { background:#fef3c7; color:#d97706; }
.t-retour { background:#ede9fe; color:#7c3aed; }
.t-adj    { background:#dbeafe; color:#2563eb; }
.t-sortie { background:#fee2e2; color:#dc2626; }
.mq-out   { display:inline-flex; align-items:center; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:800; background:#fee2e2; color:#dc2626; }
.stock-warn { color:#ef4444; font-weight:800; }

/* ══ MODAL ══ */
.modal-sortie .modal-content { border:none; border-radius:18px; overflow:hidden; box-shadow:0 24px 64px rgba(15,23,42,.22); }
.modal-sortie .modal-header { background:linear-gradient(135deg,#0f172a 0%,#7f1d1d 100%); border:none; padding:20px 24px; }
.modal-sortie .modal-title  { color:#fff; font-weight:800; font-size:15px; }
.modal-sortie .modal-header .close { color:rgba(255,255,255,.7); opacity:1; text-shadow:none; font-size:20px; }
.modal-sortie .modal-header .close:hover { color:#fff; }
.modal-sortie .modal-body   { padding:24px; }
.modal-sortie .modal-footer { border-top:1px solid #e2e8f0; padding:14px 24px; background:#f8fafc; }
.ms-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:#64748b; margin-bottom:6px; display:block; }
.ms-input { border-radius:10px !important; border:1.5px solid #e2e8f0 !important; font-size:13px !important; padding:10px 14px !important; transition:border-color .2s, box-shadow .2s !important; }
.ms-input:focus { border-color:#ef4444 !important; box-shadow:0 0 0 3px rgba(239,68,68,.12) !important; outline:none !important; }
.stock-pill { display:inline-flex; align-items:center; gap:5px; background:#f1f5f9; border-radius:8px; padding:5px 12px; font-size:12px; font-weight:700; color:#475569; transition:all .3s; }
.stock-pill.danger { background:#fee2e2; color:#dc2626; }
.stock-pill.ok     { background:#dcfce7; color:#16a34a; }
.btn-sortie-submit { background:linear-gradient(135deg,#ef4444,#b91c1c); border:none; border-radius:10px; color:#fff; font-weight:800; font-size:13px; padding:10px 22px; transition:transform .15s, box-shadow .15s; }
.btn-sortie-submit:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 20px rgba(239,68,68,.35); color:#fff; }
.btn-sortie-submit:disabled { opacity:.5; cursor:not-allowed; }
.ms-error { display:none; background:#fee2e2; color:#dc2626; border-radius:8px; padding:8px 12px; font-size:12px; font-weight:600; margin-top:12px; }

/* Select2 */
.select2-container--default .select2-selection--single { border-radius:10px !important; border:1.5px solid #e2e8f0 !important; height:42px !important; padding:5px 10px !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height:30px !important; font-size:13px !important; color:#0f172a; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:40px !important; }
.select2-container--default.select2-container--focus .select2-selection--single { border-color:#ef4444 !important; box-shadow:0 0 0 3px rgba(239,68,68,.12) !important; }
.select2-dropdown { border-radius:10px !important; border:1.5px solid #e2e8f0 !important; overflow:hidden; }
.select2-results__option--highlighted { background:#fee2e2 !important; color:#b91c1c !important; }
.select2-search__field { border-radius:8px !important; border:1.5px solid #e2e8f0 !important; padding:6px 10px !important; font-size:12px !important; }

@media(max-width:767px) {
    .s-hero { padding:16px; } .kpi { padding:14px; margin-bottom:10px; }
    .kpi-v { font-size:1.2rem; } .kpi-ico { width:32px; height:32px; font-size:.8rem; margin-bottom:7px; }
    .hide-sm { display:none !important; } .h-btn { padding:7px 11px; font-size:11px; }
}
@media(max-width:420px) { .kpi-v { font-size:1rem; } .kpi-l { font-size:9px; } }
</style>
@endpush

@section('content')

{{-- ════════════ HERO ════════════ --}}
<div class="s-hero">
    <div class="d-flex align-items-center justify-content-between"
         style="position:relative; z-index:1; gap:12px">
        <div style="min-width:0">
            <p class="s-hero-sub mb-1">
                <i class="fas fa-arrow-circle-up mr-1"></i> Gestion des stocks
                @if($filtreActif)
                    <span class="filter-active ml-2"><i class="fas fa-filter"></i> Filtre actif</span>
                    @if(request('search'))
                        <span class="filter-active ml-1">"{{ request('search') }}"</span>
                    @endif
                    @if(request('type'))
                        <span class="filter-active ml-1">{{ request('type') }}</span>
                    @endif
                    @if(request('date_debut'))
                        <span class="filter-active ml-1">Du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}</span>
                    @endif
                    @if(request('date_fin'))
                        <span class="filter-active ml-1">Au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}</span>
                    @endif
                @endif
            </p>
            <h4 class="s-hero-title">Sorties de Stock</h4>
        </div>
        <div class="d-flex align-items-center" style="gap:8px; flex-wrap:wrap;">
            {{-- Bouton PDF (visible uniquement si filtre actif) --}}
            @if($filtreActif)
                <a href="{{ route('stock.sorties.pdf', request()->query()) }}"
                   class="h-btn h-btn-pdf" target="_blank" title="Générer le rapport PDF">
                    <i class="fas fa-file-pdf"></i>
                    <span class="d-none d-sm-inline">Export PDF</span>
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <button type="button" class="h-btn h-btn-danger" data-toggle="modal" data-target="#modalSortie">
                    <i class="fas fa-plus"></i>
                    <span class="d-none d-sm-inline">Nouvelle sortie</span>
                </button>
            @endif
        </div>
    </div>
</div>

{{-- ════════════ KPI ════════════ --}}
<div class="row mb-2">
    <div class="col-6 col-md-3">
        <div class="kpi kpi-r">
            <div class="kpi-ico"><i class="fas fa-arrow-circle-up"></i></div>
            <div class="kpi-v">{{ number_format($stats['total'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Nb sorties</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi kpi-o">
            <div class="kpi-ico"><i class="fas fa-boxes"></i></div>
            <div class="kpi-v">{{ number_format($stats['quantite'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Qté totale</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi kpi-b">
            <div class="kpi-ico"><i class="fas fa-money-bill-wave"></i></div>
            <div class="kpi-v">
                @if($stats['valeur'] >= 1000000)
                    {{ number_format($stats['valeur'] / 1000000, 1, ',', ' ') }}M
                @else
                    {{ number_format($stats['valeur'] / 1000, 0, ',', ' ') }}K
                @endif
            </div>
            <div class="kpi-l">Valeur (FCFA)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi kpi-p">
            <div class="kpi-ico"><i class="fas fa-calendar-day"></i></div>
            <div class="kpi-v">{{ number_format($stats['jours'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Jours actifs</div>
        </div>
    </div>
</div>


{{-- ════════════ CUMUL PAR PRODUIT (affiché si filtre actif) ════════════ --}}
@if($filtreActif && $cumul && $cumul->count())
<div class="cumul-card">
    <div class="cumul-head">
        <h6 class="cumul-title">
            <span class="cumul-ico"><i class="fas fa-chart-bar"></i></span>
            Cumul par produit
            <span class="badge badge-warning ml-1" style="background:#f97316; color:#fff; border-radius:20px; font-size:10px; padding:3px 9px;">
                {{ $cumul->count() }} produit(s)
            </span>
        </h6>
        <a href="{{ route('stock.sorties.pdf', request()->query()) }}"
           class="h-btn h-btn-pdf" target="_blank" style="font-size:11px; padding:7px 14px;">
            <i class="fas fa-file-pdf"></i> Générer PDF
        </a>
    </div>

    <div class="table-responsive">
        <table class="cumul-table">
            <thead>
                <tr>
                    <th style="width:36px">#</th>
                    <th>Produit</th>
                    <th>Référence</th>
                    <th class="r">Qté totale</th>
                    <th class="r hide-sm">Unité</th>
                    <th class="r">Valeur FCFA</th>
                    <th class="r hide-sm">Mouvements</th>
                </tr>
            </thead>
            <tbody>
                @php $totQte = 0; $totVal = 0; @endphp
                @foreach($cumul as $i => $ligne)
                    @php $totQte += $ligne['quantite_tot']; $totVal += $ligne['valeur_tot']; @endphp
                    <tr>
                        <td>
                            <span class="cumul-rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="bold">{{ $ligne['libelle'] }}</td>
                        <td style="color:#64748b; font-size:11px; font-family:monospace;">
                            {{ $ligne['reference'] ?: '—' }}
                        </td>
                        <td class="r bold" style="color:#dc2626;">
                            {{ number_format($ligne['quantite_tot'], 2, ',', ' ') }}
                        </td>
                        <td class="r hide-sm" style="color:#64748b;">
                            {{ $ligne['unite'] }}
                        </td>
                        <td class="r bold" style="color:#991b1b;">
                            {{ number_format($ligne['valeur_tot'], 0, ',', ' ') }}
                        </td>
                        <td class="r hide-sm" style="color:#64748b;">
                            <span style="background:#f1f5f9; padding:2px 9px; border-radius:20px; font-size:10px; font-weight:700;">
                                {{ $ligne['nb_mouvements'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                <tr class="tr-total">
                    <td colspan="3">TOTAL</td>
                    <td class="r">{{ number_format($totQte, 2, ',', ' ') }}</td>
                    <td class="hide-sm"></td>
                    <td class="r">{{ number_format($totVal, 0, ',', ' ') }}</td>
                    <td class="r hide-sm">{{ $stats['total'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif


{{-- ════════════ TABLE MOUVEMENTS ════════════ --}}
<div class="pd-card">

    <div class="pd-card-head">
        <h6 class="pd-card-title">
            <span class="pd-card-ico" style="background:#ef4444">
                <i class="fas fa-arrow-circle-up"></i>
            </span>
            Liste des sorties
            <span class="badge badge-danger ml-1">{{ $stats['total'] }}</span>
        </h6>
        @if($filtreActif)
            <a href="{{ route('stock.sorties') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:10px; font-size:11px">
                <i class="fas fa-times mr-1"></i> Effacer les filtres
            </a>
        @endif
    </div>

    {{-- Filtres --}}
    <div class="filter-wrap">
        <form method="GET" action="{{ route('stock.sorties') }}">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-search mr-1"></i> Recherche</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Produit ou motif..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-filter mr-1"></i> Type</label>
                    <select name="type" class="form-control form-control-sm">
                        <option value="">Tous les types</option>
                        <option value="sortie_vente"  {{ request('type')=='sortie_vente'  ?'selected':'' }}>🛒 Vente</option>
                        <option value="sortie_perte"  {{ request('type')=='sortie_perte'  ?'selected':'' }}>⚠️ Perte</option>
                        <option value="sortie_retour" {{ request('type')=='sortie_retour' ?'selected':'' }}>↩️ Retour fournisseur</option>
                        <option value="ajustement"    {{ request('type')=='ajustement'    ?'selected':'' }}>🔧 Ajustement</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-calendar mr-1"></i> Du</label>
                    <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-calendar mr-1"></i> Au</label>
                    <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <label class="small d-block mb-1">&nbsp;</label>
                    <div class="d-flex" style="gap:6px">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search mr-1"></i>
                            <span class="d-none d-sm-inline">Filtrer</span>
                        </button>
                        @if($filtreActif)
                            <a href="{{ route('stock.sorties') }}" class="btn btn-secondary btn-sm flex-fill">
                                <i class="fas fa-times mr-1"></i>
                                <span class="d-none d-sm-inline">Reset</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:12px">
            <thead style="background:#f8fafc">
                <tr>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">#</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">Produit</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">Type</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="text-right">Qté</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="text-right hide-sm">Prix unit.</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="text-right hide-sm">Montant</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="text-right d-none d-md-table-cell">Stock après</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="d-none d-md-table-cell">Motif</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700" class="d-none d-md-table-cell">Par</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mvt)
                    @php
                        $stockBas  = $mvt->produit && $mvt->stock_apres <= $mvt->produit->stock_minimum;
                        $typeClass = match($mvt->type) {
                            'sortie_vente'  => 't-vente',
                            'sortie_perte'  => 't-perte',
                            'sortie_retour' => 't-retour',
                            'ajustement'    => 't-adj',
                            default         => 't-sortie',
                        };
                        $typeIcon  = match($mvt->type) {
                            'sortie_vente'  => 'shopping-cart',
                            'sortie_perte'  => 'exclamation-triangle',
                            'sortie_retour' => 'undo',
                            default         => 'sliders-h',
                        };
                    @endphp
                    <tr>
                        <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8; font-size:11px">
                            {{ $mouvements->firstItem() + $loop->index }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle">
                            @if($mvt->produit)
                                <a href="{{ route('produits.show', $mvt->produit->id) }}" class="font-weight-bold" style="color:#0f3460">
                                    {{ $mvt->produit->libelle }}
                                </a>
                            @else
                                <span class="text-muted">Produit supprimé</span>
                            @endif
                            <div class="small text-muted">{{ $mvt->produit->reference ?? '' }}</div>
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle">
                            <span class="t-badge {{ $typeClass }}">
                                <i class="fas fa-{{ $typeIcon }}"></i>
                                {{ $mvt->type_label }}
                            </span>
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle" class="text-right">
                            <span class="mq-out">-{{ number_format($mvt->quantite, 2, ',', ' ') }}</span>
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b" class="text-right hide-sm">
                            {{ number_format($mvt->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; font-weight:700; color:#374151" class="text-right hide-sm">
                            {{ number_format($mvt->quantite * $mvt->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle" class="text-right d-none d-md-table-cell">
                            <span class="{{ $stockBas ? 'stock-warn' : '' }}" style="font-weight:700">
                                {{ number_format($mvt->stock_apres, 2, ',', ' ') }}
                                <small class="text-muted">{{ $mvt->produit?->unite ?? '' }}</small>
                            </span>
                            @if($stockBas)
                                <i class="fas fa-exclamation-triangle text-danger ml-1" title="Stock sous le minimum"></i>
                            @endif
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b; font-size:11px" class="d-none d-md-table-cell">
                            {{ $mvt->motif ?? '—' }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b" class="d-none d-md-table-cell">
                            {{ $mvt->user?->prenom ?? '—' }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8; font-size:11px">
                            {{ $mvt->created_at->format('d/m/y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                            <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
                            Aucune sortie trouvée
                            @if($filtreActif)
                                <div class="mt-2">
                                    <a href="{{ route('stock.sorties') }}" class="btn btn-sm btn-outline-primary" style="border-radius:10px">
                                        <i class="fas fa-times mr-1"></i> Effacer les filtres
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:14px 20px; border-top:1px solid var(--border); background:#fafbff">
        @include('partials.pagination', ['paginator' => $mouvements])
    </div>

</div>

{{-- ════════════ MODAL NOUVELLE SORTIE ════════════ --}}
@if(auth()->user()->isAdmin())
<div class="modal fade modal-sortie" id="modalSortie" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:460px">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-arrow-circle-up mr-2"></i> Nouvelle sortie de stock
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="ms-label"><i class="fas fa-box mr-1"></i> Produit</label>
                    <select id="ms-produit" class="form-control" style="width:100%">
                        <option value="">Sélectionner un produit...</option>
                        @foreach(\App\Models\Produit::where('actif', true)->where('stock_actuel', '>', 0)->orderBy('libelle')->get() as $p)
                            <option value="{{ $p->id }}"
                                    data-stock="{{ $p->stock_actuel }}"
                                    data-unite="{{ $p->unite }}"
                                    data-min="{{ $p->stock_minimum }}">
                                {{ $p->libelle }}
                                @if($p->reference) · {{ $p->reference }}@endif
                                — {{ number_format($p->stock_actuel, 2, ',', ' ') }} {{ $p->unite }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="ms-stock-wrap" class="mb-3" style="display:none">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background:#f8fafc; border:1px solid #e2e8f0">
                        <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px">
                            <i class="fas fa-warehouse mr-1"></i> Stock disponible
                        </span>
                        <span class="stock-pill" id="ms-stock-pill">
                            <i class="fas fa-cubes"></i>
                            <span id="ms-stock-val">—</span>
                        </span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="ms-label"><i class="fas fa-tag mr-1"></i> Type de sortie</label>
                    <select id="ms-type" class="form-control ms-input">
                        <option value="sortie">🛒 Vente</option>
                        <option value="perte">⚠️ Perte / Casse</option>
                        <option value="retour">↩️ Retour fournisseur</option>
                        <option value="ajustement">🔧 Ajustement</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="ms-label"><i class="fas fa-sort-numeric-up mr-1"></i> Quantité à sortir</label>
                    <input type="number" id="ms-quantite" class="form-control ms-input"
                           placeholder="0" min="0.01" step="0.01" disabled>
                    <small id="ms-qte-hint" class="text-muted mt-1 d-block" style="font-size:11px"></small>
                </div>

                <div class="form-group mb-0">
                    <label class="ms-label"><i class="fas fa-comment mr-1"></i> Motif <span class="text-muted font-weight-normal">(optionnel)</span></label>
                    <input type="text" id="ms-motif" class="form-control ms-input" placeholder="Ex : Vendu au client X, casse...">
                </div>

                <div class="ms-error" id="ms-error"></div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-dismiss="modal"
                        style="border-radius:10px; font-size:13px; font-weight:700; padding:9px 18px">
                    <i class="fas fa-times mr-1"></i> Annuler
                </button>
                <button type="button" id="ms-submit" class="btn-sortie-submit" disabled>
                    <i class="fas fa-arrow-circle-up mr-2"></i> Enregistrer
                </button>
            </div>

        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    $('#ms-produit').select2({
        dropdownParent: $('#modalSortie'),
        placeholder: 'Rechercher un produit...',
        allowClear: true,
        language: {
            noResults: function() { return 'Aucun produit trouvé'; },
            searching: function() { return 'Recherche en cours...'; }
        }
    });

    $('#ms-produit').on('change', function () {
        const opt    = $(this).find(':selected');
        const hasVal = $(this).val();
        if (!hasVal) {
            $('#ms-stock-wrap').slideUp(150);
            $('#ms-quantite').val('').prop('disabled', true).attr('max', '').removeClass('is-invalid is-valid');
            $('#ms-qte-hint').text('');
            updateSubmit(); return;
        }
        const stock = parseFloat(opt.data('stock') || 0);
        const unite = opt.data('unite') || '';
        const min   = parseFloat(opt.data('min')   || 0);
        $('#ms-stock-wrap').slideDown(150);
        $('#ms-stock-val').text(stock.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ' + unite);
        $('#ms-stock-pill').removeClass('ok danger').addClass(stock <= min ? 'danger' : 'ok');
        $('#ms-quantite').prop('disabled', false).attr('max', stock).val('').removeClass('is-invalid is-valid');
        $('#ms-qte-hint').html('<i class="fas fa-info-circle mr-1"></i>Maximum autorisé : <strong>' + stock + ' ' + unite + '</strong>');
        $('#ms-error').hide();
        updateSubmit();
    });

    $('#ms-quantite').on('input', function () {
        const val   = parseFloat($(this).val());
        const opt   = $('#ms-produit').find(':selected');
        const stock = parseFloat(opt.data('stock') || 0);
        const unite = opt.data('unite') || '';
        if (!$(this).val() || isNaN(val) || val <= 0) { $(this).removeClass('is-invalid is-valid'); updateSubmit(); return; }
        if (val > stock) {
            $(this).removeClass('is-valid').addClass('is-invalid');
            $('#ms-qte-hint').html('<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>Dépasse le stock disponible (' + stock + ' ' + unite + ')</span>');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#ms-qte-hint').html('<i class="fas fa-info-circle mr-1"></i>Maximum autorisé : <strong>' + stock + ' ' + unite + '</strong>');
        }
        updateSubmit();
    });

    function updateSubmit() {
        const produit = $('#ms-produit').val();
        const qte     = parseFloat($('#ms-quantite').val());
        const stock   = parseFloat($('#ms-produit').find(':selected').data('stock') || 0);
        $('#ms-submit').prop('disabled', !(produit && !isNaN(qte) && qte > 0 && qte <= stock));
    }

    $('#modalSortie').on('hidden.bs.modal', function () {
        $('#ms-produit').val(null).trigger('change');
        $('#ms-quantite').val('').prop('disabled', true).removeClass('is-invalid is-valid');
        $('#ms-motif').val('');
        $('#ms-type').val('sortie_vente');
        $('#ms-stock-wrap').hide();
        $('#ms-error').hide();
        $('#ms-submit').prop('disabled', true).html('<i class="fas fa-arrow-circle-up mr-2"></i> Enregistrer');
    });

    $('#ms-submit').on('click', function () {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...');
        $('#ms-error').hide();
        $.ajax({
            url: '{{ route("stock.sorties.store") }}',
            method: 'POST',
            data: {
                _token:     $('meta[name="csrf-token"]').attr('content'),
                produit_id: $('#ms-produit').val(),
                quantite:   $('#ms-quantite').val(),
                type:       $('#ms-type').val(),
                motif:      $('#ms-motif').val(),
            },
            success: function () {
                $('#modalSortie').modal('hide');
                $('body').append('<div id="toast-ok" style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#16a34a;color:#fff;padding:12px 20px;border-radius:12px;font-weight:700;font-size:13px;box-shadow:0 8px 24px rgba(0,0,0,.15)"><i class="fas fa-check-circle mr-2"></i>Sortie enregistrée !</div>');
                setTimeout(function() { window.location.reload(); }, 800);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Une erreur est survenue.';
                $('#ms-error').html('<i class="fas fa-exclamation-circle mr-1"></i>' + msg).show();
                btn.prop('disabled', false).html('<i class="fas fa-arrow-circle-up mr-2"></i> Enregistrer');
            }
        });
    });
});
</script>
@endpush