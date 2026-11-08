@extends('layouts.app')

@section('title', 'Entrées de Stock')
@section('page-title', 'Entrées de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item active">Entrées de stock</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Syne:wght@700;800&display=swap');

:root {
    --dark:   #0f172a;
    --border: #e2e8f0;
    --sh:     0 4px 24px rgba(15,52,96,.09);
    --r:      16px;
}

/* ══ HERO ══ */
.e-hero {
    background: linear-gradient(140deg, #0f172a 0%, #065f46 60%, #047857 100%);
    border-radius: var(--r);
    padding: 22px 26px;
    margin-bottom: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 12px 40px rgba(4,120,87,.25);
}
.e-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.08),transparent 65%);
    pointer-events:none;
}
.e-hero-title { font-size:clamp(.95rem,3vw,1.3rem); font-weight:800; color:#fff; margin:0; }
.e-hero-sub   { font-size:11px; color:rgba(255,255,255,.55); margin-top:3px; }

.h-btn {
    display:inline-flex; align-items:center; gap:6px;
    border-radius:10px; font-size:12px; font-weight:700;
    padding:8px 14px; border:none; cursor:pointer;
    transition:transform .15s, box-shadow .15s;
    text-decoration:none !important; white-space:nowrap;
}
.h-btn:hover { transform:translateY(-2px); }
.h-btn-success {
    background:linear-gradient(135deg,#34d399,#10b981);
    color:#fff !important;
    box-shadow:0 4px 12px rgba(16,185,129,.3);
}
.h-btn-success:hover { box-shadow:0 6px 18px rgba(16,185,129,.4); }

.filter-active {
    display:inline-flex; align-items:center; gap:4px;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; padding:3px 10px; border-radius:20px;
    font-size:10px; font-weight:700;
}

/* ══ KPI ══ */
.kpi {
    border-radius:14px; padding:18px; box-shadow:var(--sh);
    transition:transform .2s, box-shadow .2s;
    position:relative; overflow:hidden; margin-bottom:14px;
}
.kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,.12); }
.kpi::after {
    content:''; position:absolute; bottom:-16px; right:-16px;
    width:65px; height:65px; border-radius:50%; background:rgba(255,255,255,.15);
}
.kpi-ico { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; margin-bottom:10px; }
.kpi-v   { font-size:1.5rem; font-weight:900; line-height:1; margin-bottom:3px; }
.kpi-l   { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; opacity:.65; }
.kpi-g   { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
.kpi-g .kpi-ico { background:rgba(16,185,129,.18); color:#10b981; }
.kpi-g .kpi-v, .kpi-g .kpi-l { color:#065f46; }
.kpi-b   { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
.kpi-b .kpi-ico { background:rgba(59,130,246,.18); color:#3b82f6; }
.kpi-b .kpi-v, .kpi-b .kpi-l { color:#1e3a8a; }
.kpi-o   { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.kpi-o .kpi-ico { background:rgba(245,158,11,.22); color:#d97706; }
.kpi-o .kpi-v, .kpi-o .kpi-l { color:#92400e; }
.kpi-p   { background:linear-gradient(135deg,#ede9fe,#ddd6fe); }
.kpi-p .kpi-ico { background:rgba(139,92,246,.18); color:#7c3aed; }
.kpi-p .kpi-v, .kpi-p .kpi-l { color:#4c1d95; }

/* ══ CARD ══ */
.pd-card { background:#fff; border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; margin-bottom:20px; }
.pd-card-head { padding:14px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.pd-card-title { display:flex; align-items:center; gap:9px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.7px; color:var(--dark); margin:0; }
.pd-card-ico   { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; flex-shrink:0; }
.filter-wrap   { padding:14px 20px; border-bottom:1px solid var(--border); background:#fafbff; }

/* ══ BADGES TABLE ══ */
.t-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:10px; font-weight:700; }
.t-in    { background:#d1fae5; color:#059669; }
.mq-in   { display:inline-flex; align-items:center; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:800; background:#d1fae5; color:#059669; }

/* ══ ALERTES PAGE ══ */
.e-alert { border-radius:12px; padding:11px 16px; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; margin-bottom:16px; }
.e-alert.success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
.e-alert.error   { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }

/* ══ MODAL OVERLAY ══ */
.ent-overlay {
    position:fixed; inset:0; z-index:99999;
    background:rgba(15,23,42,.65);
    backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center;
    opacity:0; pointer-events:none;
    transition:opacity .25s;
    padding:16px;
}
.ent-overlay.open { opacity:1; pointer-events:all; }

/* ══ MODAL BOX ══ */
.ent-modal {
    background:#fff;
    border-radius:22px;
    width:100%;
    max-width:720px;
    box-shadow:0 32px 80px rgba(11,15,26,.25);
    transform:translateY(24px) scale(.97);
    transition:transform .3s cubic-bezier(.34,1.56,.64,1);
    overflow:hidden;
    font-family:'DM Sans',sans-serif;
    max-height:90vh;
    display:flex;
    flex-direction:column;
}
.ent-overlay.open .ent-modal { transform:translateY(0) scale(1); }

/* Header modal */
.ent-modal-hd {
    background:linear-gradient(135deg,#065f46,#059669);
    padding:20px 28px 18px;
    display:flex; align-items:center; justify-content:space-between;
    position:relative; overflow:hidden;
    flex-shrink:0;
}
.ent-modal-hd::before {
    content:''; position:absolute; top:-30px; right:-30px;
    width:130px; height:130px; border-radius:50%;
    background:rgba(255,255,255,.07); pointer-events:none;
}
.ent-modal-title {
    font-family:'Syne',sans-serif;
    font-size:1.15rem; font-weight:800; color:#fff; margin:0;
    display:flex; align-items:center; gap:12px; position:relative; z-index:1;
}
.ent-modal-title-ico {
    width:38px; height:38px; border-radius:11px;
    background:rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:16px; color:#fff; flex-shrink:0;
}
.ent-modal-sub {
    font-size:11px; color:rgba(255,255,255,.55);
    margin:3px 0 0 50px; position:relative; z-index:1;
}
.ent-modal-close {
    width:34px; height:34px; border-radius:10px;
    border:1.5px solid rgba(255,255,255,.3);
    background:rgba(255,255,255,.1); color:rgba(255,255,255,.8);
    display:flex; align-items:center; justify-content:center;
    font-size:13px; cursor:pointer; transition:.15s;
    position:relative; z-index:1; flex-shrink:0;
}
.ent-modal-close:hover { background:rgba(255,255,255,.2); color:#fff; }

/* Body modal */
.ent-modal-body {
    padding:24px 28px;
    overflow-y:auto;
    flex:1;
}
.ent-modal-body::-webkit-scrollbar { width:4px; }
.ent-modal-body::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }

/* Stock info bar */
.stock-info-bar {
    background:linear-gradient(135deg,#f0fdf4,#dcfce7);
    border:1px solid #a7f3d0; border-radius:12px;
    padding:12px 16px; margin-bottom:20px;
    align-items:center; gap:12px;
    font-size:13px; color:#065f46; font-weight:600;
    display:none;
}
.stock-info-bar i { color:#059669; font-size:16px; }
.stock-val-big { font-size:1.15rem; font-weight:900; color:#059669; margin:0 4px; }

/* Champs formulaire */
.ent-field { margin-bottom:20px; }
.ent-field:last-child { margin-bottom:0; }
.ent-field label {
    display:flex; align-items:center; gap:6px;
    font-size:10px; font-weight:800;
    text-transform:uppercase; letter-spacing:.5px;
    color:#64748b; margin-bottom:8px;
    flex-wrap:wrap;
}
.ent-field label .req { color:#ef4444; }
.ent-field label .opt { font-size:9px; color:#94a3b8; font-weight:600; text-transform:none; letter-spacing:0; }

/* Badge "prix modifié" */
.prix-modif-badge {
    display:none;
    align-items:center; gap:4px;
    background:#fef3c7; border:1px solid #fde68a;
    color:#92400e; padding:2px 8px; border-radius:20px;
    font-size:9px; font-weight:700; text-transform:none; letter-spacing:0;
    white-space:nowrap;
}
.prix-modif-badge.visible { display:inline-flex; }

.ent-inp-wrap {
    display:flex; align-items:center;
    background:#f9fafb; border:1.5px solid #e5e7eb;
    border-radius:11px; overflow:hidden; transition:.2s;
}
.ent-inp-wrap:focus-within {
    border-color:#059669; background:#fff;
    box-shadow:0 0 0 3px rgba(5,150,105,.1);
}
.ent-ico {
    width:40px; display:flex; align-items:center; justify-content:center;
    color:#d1d5db; font-size:13px; flex-shrink:0;
}
.ent-inp-wrap:focus-within .ent-ico { color:#059669; }
.ent-inp {
    flex:1; border:none; background:transparent;
    padding:11px 10px 11px 0; font-size:13px; color:#111827;
    outline:none; font-family:'DM Sans',sans-serif;
}
.ent-inp::placeholder { color:#d1d5db; }
.ent-sfx {
    padding:0 14px; font-size:11px; font-weight:700; color:#94a3b8;
    white-space:nowrap; border-left:1px solid #f1f5f9;
}
.ent-sel {
    flex:1; border:none; background:transparent;
    padding:11px 6px 11px 0; font-size:13px; color:#111827;
    outline:none; font-family:'DM Sans',sans-serif;
    -webkit-appearance:none; -moz-appearance:none;
}

.ent-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
.ent-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; }
@media(max-width:560px) {
    .ent-grid-2, .ent-grid-3 { grid-template-columns:1fr; }
}

/* Calcul montant total */
.montant-preview {
    background:linear-gradient(135deg,#0f172a,#1e3a5f);
    border-radius:12px; padding:14px 20px;
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:20px;
}
.montant-preview .mp-lbl { font-size:11px; color:rgba(255,255,255,.5); font-weight:600; }
.montant-preview .mp-val { font-family:'Syne',sans-serif; font-size:1.4rem; font-weight:800; color:#34d399; }

/* Footer modal */
.ent-modal-ft {
    padding:16px 28px; border-top:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:flex-end;
    gap:10px; background:#fafbff; flex-shrink:0;
}
.btn-cancel-modal {
    padding:10px 22px; border-radius:11px;
    border:1.5px solid #e2e8f0; background:#f8fafc;
    color:#64748b; font-size:13px; font-weight:700;
    cursor:pointer; font-family:'DM Sans',sans-serif; transition:.15s;
}
.btn-cancel-modal:hover { background:#e2e8f0; color:#0f172a; }
.btn-save-modal {
    padding:11px 26px; border-radius:11px; border:none;
    background:linear-gradient(135deg,#059669,#10b981);
    color:#fff; font-size:13px; font-weight:700;
    cursor:pointer; box-shadow:0 4px 16px rgba(16,185,129,.3);
    font-family:'DM Sans',sans-serif; transition:.2s;
    display:inline-flex; align-items:center; gap:8px;
}
.btn-save-modal:hover:not(:disabled) { transform:translateY(-1px); box-shadow:0 6px 20px rgba(16,185,129,.4); }
.btn-save-modal:disabled { opacity:.6; cursor:not-allowed; }

/* ══ SELECT2 DANS LE MODAL ══ */
#modalEntree .select2-container { width:100% !important; }
#modalEntree .select2-container--default .select2-selection--single {
    height:44px !important;
    border:1.5px solid #e5e7eb !important;
    border-radius:11px !important;
    background:#f9fafb !important;
    display:flex !important;
    align-items:center !important;
    transition:.2s !important;
    outline:none !important;
}
#modalEntree .select2-container--default.select2-container--open .select2-selection--single,
#modalEntree .select2-container--default.select2-container--focus .select2-selection--single {
    border-color:#059669 !important;
    background:#fff !important;
    box-shadow:0 0 0 3px rgba(5,150,105,.1) !important;
}
#modalEntree .select2-selection__rendered {
    font-size:13px !important;
    font-weight:500 !important;
    color:#111827 !important;
    line-height:44px !important;
    padding-left:14px !important;
    padding-right:30px !important;
}
#modalEntree .select2-selection__placeholder { color:#d1d5db !important; font-weight:400 !important; }
#modalEntree .select2-selection__arrow { height:44px !important; right:12px !important; }
#modalEntree .select2-selection__clear { margin-top:10px !important; color:#94a3b8 !important; font-size:16px !important; }

/* Dropdown Select2 */
.select2-dropdown {
    border:1.5px solid #e2e8f0 !important;
    border-radius:14px !important;
    box-shadow:0 16px 48px rgba(11,15,26,.16) !important;
    overflow:hidden !important;
    font-family:'DM Sans',sans-serif !important;
}
.select2-search--dropdown { padding:10px !important; background:#fafbff !important; }
.select2-search--dropdown .select2-search__field {
    border:1.5px solid #e2e8f0 !important;
    border-radius:9px !important;
    padding:8px 12px !important;
    font-size:13px !important;
    outline:none !important;
    font-family:'DM Sans',sans-serif !important;
    width:100% !important;
    box-sizing:border-box !important;
}
.select2-search--dropdown .select2-search__field:focus {
    border-color:#059669 !important;
    box-shadow:0 0 0 3px rgba(5,150,105,.1) !important;
}
.select2-results__options { padding:4px !important; }
.select2-results__option {
    font-size:12.5px !important;
    font-weight:500 !important;
    padding:9px 14px !important;
    color:#374151 !important;
    border-radius:8px !important;
    margin:1px 0 !important;
    font-family:'DM Sans',sans-serif !important;
}
.select2-results__option--highlighted {
    background:#f0fdf4 !important;
    color:#059669 !important;
    font-weight:600 !important;
}
.select2-results__option[aria-selected="true"] {
    background:#ecfdf5 !important;
    color:#059669 !important;
    font-weight:700 !important;
}

/* ══ RESPONSIVE ══ */
@media(max-width:767px) {
    .e-hero { padding:16px; }
    .kpi    { padding:14px; margin-bottom:10px; }
    .kpi-v  { font-size:1.2rem; }
    .kpi-ico{ width:32px; height:32px; font-size:.8rem; margin-bottom:7px; }
    .hide-sm{ display:none !important; }
    .h-btn  { padding:7px 11px; font-size:11px; }
    .ent-modal-body { padding:16px 18px; }
    .ent-modal-hd, .ent-modal-ft { padding:16px 18px; }
    .ent-modal-sub { margin-left:0; margin-top:6px; }
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<div class="e-hero">
    <div class="d-flex align-items-center justify-content-between"
         style="position:relative;z-index:1;gap:12px">
        <div style="min-width:0">
            <p class="e-hero-sub mb-1">
                <i class="fas fa-arrow-circle-down mr-1"></i> Gestion des stocks
                @if(request()->hasAny(['search','date_debut','date_fin']))
                    <span class="filter-active ml-2"><i class="fas fa-filter"></i> Filtre actif</span>
                    @if(request('search'))
                        <span class="filter-active ml-1">"{{ request('search') }}"</span>
                    @endif
                    @if(request('date_debut'))
                        <span class="filter-active ml-1">Du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}</span>
                    @endif
                    @if(request('date_fin'))
                        <span class="filter-active ml-1">Au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}</span>
                    @endif
                @endif
            </p>
            <h4 class="e-hero-title">Entrées de Stock</h4>
        </div>
        @if(auth()->user()->isAdmin())
            <button type="button" class="h-btn h-btn-success" onclick="openModalEntree()">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline">Nouvelle entrée</span>
            </button>
        @endif
    </div>
</div>

{{-- ══ KPI ══ --}}
<div class="row mb-2">
    <div class="col-6 col-md-3">
        <div class="kpi kpi-g">
            <div class="kpi-ico"><i class="fas fa-arrow-circle-down"></i></div>
            <div class="kpi-v">{{ number_format($stats['total'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Nb entrées</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi kpi-b">
            <div class="kpi-ico"><i class="fas fa-boxes"></i></div>
            <div class="kpi-v">{{ number_format($stats['quantite'], 0, ',', ' ') }}</div>
            <div class="kpi-l">Qté totale</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi kpi-o">
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

{{-- ══ TABLE ══ --}}
<div class="pd-card">

    <div class="pd-card-head">
        <h6 class="pd-card-title">
            <span class="pd-card-ico" style="background:#10b981">
                <i class="fas fa-arrow-circle-down"></i>
            </span>
            Liste des entrées
            <span class="badge badge-success ml-1">{{ $stats['total'] }}</span>
        </h6>
        @if(request()->hasAny(['search','date_debut','date_fin']))
            <a href="{{ route('stock.entrees') }}"
               class="btn btn-sm btn-outline-secondary" style="border-radius:10px;font-size:11px">
                <i class="fas fa-times mr-1"></i> Effacer les filtres
            </a>
        @endif
    </div>

    {{-- Filtres --}}
    <div class="filter-wrap">
        <form method="GET" action="{{ route('stock.entrees') }}">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fas fa-search mr-1"></i> Recherche produit
                    </label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Nom du produit ou référence..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fas fa-calendar mr-1"></i> Du
                    </label>
                    <input type="date" name="date_debut" class="form-control form-control-sm"
                           value="{{ request('date_debut') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fas fa-calendar mr-1"></i> Au
                    </label>
                    <input type="date" name="date_fin" class="form-control form-control-sm"
                           value="{{ request('date_fin') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <label class="small d-block mb-1">&nbsp;</label>
                    <div class="d-flex" style="gap:6px">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search mr-1"></i>
                            <span class="d-none d-sm-inline">Filtrer</span>
                        </button>
                        @if(request()->hasAny(['search','date_debut','date_fin']))
                            <a href="{{ route('stock.entrees') }}" class="btn btn-secondary btn-sm flex-fill">
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
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700">#</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700">Produit</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700" class="hide-sm">Fournisseur</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700">Type</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700" class="text-right">Qté</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700" class="text-right d-none d-md-table-cell">Stock après</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700" class="d-none d-md-table-cell">Par</th>
                    <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700">Date</th>
                    @if(auth()->user()->isAdmin())
                        <th style="padding:11px 16px;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.5px;font-weight:700" class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mvt)
                    <tr>
                        <td style="padding:11px 16px;vertical-align:middle;color:#94a3b8;font-size:11px">
                            {{ $mouvements->firstItem() + $loop->index }}
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle">
                            @if($mvt->produit)
    <a href="{{ route('produits.show', $mvt->produit->id) }}"
       class="font-weight-bold" style="color:#0f3460">
        {{ $mvt->produit->libelle }}
    </a>
@else
    <span class="text-danger">Produit supprimé</span>
@endif
                            <div class="small text-muted">{{ $mvt->produit->reference ?? '' }}</div>
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle;color:#64748b" class="hide-sm">
                            {{ $mvt->fournisseur?->nom ?? '—' }}
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle">
                            <span class="t-badge t-in">
                                <i class="fas fa-arrow-down"></i> {{ $mvt->type_label }}
                            </span>
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle" class="text-right">
                            <span class="mq-in">+{{ number_format($mvt->quantite, 2, ',', ' ') }}</span>
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle;color:#374151;font-weight:700" class="text-right d-none d-md-table-cell">
                            {{ number_format($mvt->stock_apres, 2, ',', ' ') }}
                            <small class="text-muted">
    {{ $mvt->produit ? $mvt->produit->unite : '—' }}
</small>
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle;color:#64748b" class="d-none d-md-table-cell">
                            {{ $mvt->user?->prenom ?? '—' }}
                        </td>
                        <td style="padding:11px 16px;vertical-align:middle;color:#94a3b8;font-size:11px">
                            {{ $mvt->created_at->format('d/m/y H:i') }}
                        </td>
                        @if(auth()->user()->isAdmin())
                            <td style="padding:11px 16px;vertical-align:middle" class="text-center">
                                <form action="{{ route('stock.entrees.destroy', $mvt) }}"
                                      method="POST" id="del-{{ $mvt->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            style="background:#fef2f2;color:#dc2626;border:none;
                                                   border-radius:8px;padding:5px 10px;cursor:pointer;
                                                   transition:.15s"
                                            onmouseover="this.style.background='#fee2e2'"
                                            onmouseout="this.style.background='#fef2f2'"
                                            onclick="confirmDelete('del-{{ $mvt->id }}')"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-5" style="color:#94a3b8">
                            <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
                            <div style="font-weight:700;font-size:14px">Aucune entrée trouvée</div>
                            @if(request()->hasAny(['search','date_debut','date_fin']))
                                <div class="mt-2">
                                    <a href="{{ route('stock.entrees') }}"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:10px">
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
    <div style="padding:14px 20px;border-top:1px solid var(--border);background:#fafbff">
        @include('partials.pagination', ['paginator' => $mouvements])
    </div>

</div>


{{-- ══════════════════════════════════════════════
     MODAL NOUVELLE ENTRÉE DE STOCK
══════════════════════════════════════════════ --}}
@if(auth()->user()->isAdmin())
    <div class="ent-overlay" id="modalEntree" onclick="if(event.target===this) closeModalEntree()">
        <div class="ent-modal">

            {{-- ── Header ── --}}
            <div class="ent-modal-hd">
                <div style="position:relative;z-index:1">
                    <div class="ent-modal-title">
                        <div class="ent-modal-title-ico">
                            <i class="fas fa-arrow-circle-down"></i>
                        </div>
                        Nouvelle entrée de stock
                    </div>
                    <div class="ent-modal-sub">Renseignez les informations de l'entrée</div>
                </div>
                <button type="button" class="ent-modal-close" onclick="closeModalEntree()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- ── Formulaire ── --}}
            <form action="{{ route('stock.entrees.store') }}" method="POST" id="formEntree">
                @csrf

                <div class="ent-modal-body">

                    {{-- Produit avec Select2 --}}
                    <div class="ent-field">
                        <label>Produit <span class="req">*</span></label>
                        {{--
                            IMPORTANT : les data-* sont sur les <option>.
                            Select2 les lit via option.element quand on passe l'objet jQuery.
                            On stocke aussi les infos dans un objet JS (produitsData) pour
                            un accès fiable sans dépendre de Select2.
                        --}}
                        <select name="produit_id" id="modalProduit" required style="width:100%">
                            <option value="">— Rechercher un produit —</option>
                            @foreach($produits as $p)
                                <option value="{{ $p->id }}"
                                        data-stock="{{ $p->stock_actuel }}"
                                        data-unite="{{ $p->unite }}">
                                    {{ $p->libelle }}
                                    @if($p->reference)({{ $p->reference }})@endif
                                    — Stock: {{ number_format($p->stock_actuel, 2, ',', ' ') }} {{ $p->unite }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Info stock actuel --}}
                    <div class="stock-info-bar" id="stockInfoBar">
                        <i class="fas fa-info-circle"></i>
                        <div style="flex:1">
                            Stock actuel :
                            <span class="stock-val-big" id="stockActuelVal">0</span>
                            <span id="stockUnite"></span>
                        </div>
                        <div style="font-size:11px;color:#059669;font-weight:700">
                            <i class="fas fa-check-circle mr-1"></i>Disponible
                        </div>
                    </div>

                    {{-- Quantité + Prix --}}
                    <div class="ent-grid-2">

                        {{-- Quantité --}}
                        <div class="ent-field" style="margin-bottom:0">
                            <label>Quantité <span class="req">*</span></label>
                            <div class="ent-inp-wrap">
                                <div class="ent-ico"><i class="fas fa-sort-numeric-up"></i></div>
                                <input type="number" name="quantite" id="modalQte" class="ent-inp"
                                    placeholder="Ex: 50" step="0.01" min="0.01" required>
                                <span class="ent-sfx" id="modalUnite">unité</span>
                            </div>
                        </div>

                    </div>

                    {{-- Fournisseur + BL --}}
                    <div class="ent-grid-2">
                        <div class="ent-field" style="margin-bottom:0">
                            <label>Fournisseur <span class="opt">(opt.)</span></label>
                            <div class="ent-inp-wrap">
                                <div class="ent-ico"><i class="fas fa-truck"></i></div>
                                <select name="fournisseur_id" class="ent-sel">
                                    <option value="">— Aucun —</option>
                                    @foreach($fournisseurs as $f)
                                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="ent-field" style="margin-bottom:0">
                            <label>N° Bon <span class="opt">(opt.)</span></label>
                            <div class="ent-inp-wrap">
                                <div class="ent-ico"><i class="fas fa-file-alt"></i></div>
                                <input type="text" name="reference_doc" class="ent-inp"
                                    placeholder="BL-2024-001">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── Footer ── --}}
                <div class="ent-modal-ft">
                    <button type="button" class="btn-cancel-modal" onclick="closeModalEntree()">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn-save-modal" id="btnSaveEntree">
                        <i class="fas fa-arrow-circle-down"></i> Enregistrer l'entrée
                    </button>
                </div>

            </form>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════
//  INDEX JS DES PRODUITS
//  Stocké côté JS pour éviter de dépendre
//  de la façon dont Select2 expose les data-*
// ════════════════════════════════════════
var produitsData = {
    @foreach($produits as $p)
    "{{ $p->id }}": {
        stock : {{ (float) $p->stock_actuel }},
        unite : "{{ addslashes($p->unite ?? 'unité') }}"
    },
    @endforeach
};

// Prix original du produit sélectionné (pour détecter si modifié)

// ════════════════════════════════════════
//  MODAL — OUVRIR
// ════════════════════════════════════════
function openModalEntree() {
    // Reset état interne

    // Reset formulaire
    document.getElementById('formEntree').reset();

    // Masquer les blocs dynamiques
    document.getElementById('stockInfoBar').style.display   = 'none';
    document.getElementById('modalUnite').textContent = 'unité';

    // Ouvrir l'overlay
    document.getElementById('modalEntree').classList.add('open');

    // Init Select2 après l'animation d'ouverture
    setTimeout(function () {
        if (typeof $ === 'undefined' || !$.fn || !$.fn.select2) return;

        // Détruire si déjà initialisé
        if ($('#modalProduit').hasClass('select2-hidden-accessible')) {
            $('#modalProduit').select2('destroy');
        }

        $('#modalProduit').select2({
            placeholder    : '— Rechercher un produit —',
            allowClear     : true,
            width          : '100%',
            dropdownParent : $('#modalEntree'),
            language: {
                noResults : function () { return 'Aucun produit trouvé'; },
                searching : function () { return 'Recherche en cours...'; },
            }
        }).on('change', function () {
            onProduitChange(this.value);
        });
    }, 150);
}

// ════════════════════════════════════════
//  MODAL — FERMER
// ════════════════════════════════════════
function closeModalEntree() {
    if (typeof $ !== 'undefined' && $('#modalProduit').hasClass('select2-hidden-accessible')) {
        $('#modalProduit').select2('destroy');
    }
    document.getElementById('modalEntree').classList.remove('open');
}

// Fermer avec Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModalEntree();
});

// ════════════════════════════════════════
//  CHANGEMENT PRODUIT
//  Reçoit l'id (string) du produit sélectionné
// ════════════════════════════════════════
function onProduitChange(produitId) {
    // Réinitialiser si aucun produit
    if (!produitId) {
        document.getElementById('stockInfoBar').style.display = 'none';
        document.getElementById('modalUnite').textContent     = 'unité';
                return;
    }

    var data = produitsData[produitId];
    if (!data) return;

    // Afficher le stock actuel
    document.getElementById('stockActuelVal').textContent =
        data.stock.toLocaleString('fr-FR', { maximumFractionDigits: 2 });
    document.getElementById('stockUnite').textContent  = data.unite;
    document.getElementById('stockInfoBar').style.display = 'flex';

    document.getElementById('modalUnite').textContent     = data.unite;

}

// ════════════════════════════════════════
//  SAISIE DU PRIX
//  Affiche le badge si le prix est modifié
// ════════════════════════════════════════
document.getElementById('formEntree')?.addEventListener('submit', function () {
    var btn = document.getElementById('btnSaveEntree');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
});

// ════════════════════════════════════════
//  SUPPRESSION
// ════════════════════════════════════════
function confirmDelete(formId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title             : 'Supprimer cette entrée ?',
            text              : 'Le stock sera recalculé.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonText : 'Oui, supprimer',
            cancelButtonText  : 'Annuler',
            confirmButtonColor: '#dc2626',
            cancelButtonColor : '#64748b',
        }).then(function (r) {
            if (r.isConfirmed) document.getElementById(formId).submit();
        });
    } else {
        if (confirm('Supprimer cette entrée ?')) document.getElementById(formId).submit();
    }
}

// ════════════════════════════════════════
//  RÉOUVERTURE AUTO SI ERREURS VALIDATION
// ════════════════════════════════════════
@if($errors->any())
    window.addEventListener('load', function () { openModalEntree(); });
@endif
</script>
@endpush