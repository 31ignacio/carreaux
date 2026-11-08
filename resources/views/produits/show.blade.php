@extends('layouts.app')

@section('title', $produit->libelle)
@section('page-title', 'Détail Produit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
    <li class="breadcrumb-item active">{{ $produit->libelle }}</li>
@endsection

@push('styles')
<style>
:root {
    --primary: #0f3460;
    --accent:  #e94560;
    --success: #10b981;
    --warn:    #f59e0b;
    --info:    #3b82f6;
    --dark:    #0f172a;
    --border:  #e2e8f0;
    --sh:      0 4px 24px rgba(15,52,96,.09);
    --r:       16px;
}

/* ══ HERO ══ */
.pd-hero {
    background: linear-gradient(140deg, #0f172a 0%, #1e3a5f 50%, #0f3460 100%);
    border-radius: var(--r);
    padding: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 48px rgba(15,52,96,.28);
    margin-bottom: 20px;
}
.pd-hero::before {
    content: ''; position: absolute;
    top: -80px; right: -80px;
    width: 280px; height: 280px; border-radius: 50%;
    background: radial-gradient(circle, rgba(233,69,96,.2), transparent 65%);
    pointer-events: none;
}
.pd-hero::after {
    content: ''; position: absolute;
    bottom: -60px; left: 20%;
    width: 200px; height: 200px; border-radius: 50%;
    background: radial-gradient(circle, rgba(59,130,246,.12), transparent 65%);
    pointer-events: none;
}

.pd-avatar {
    width: 80px; height: 80px;
    border-radius: 18px;
    background: rgba(255,255,255,.1);
    border: 2px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: rgba(255,255,255,.8);
    overflow: hidden; flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(0,0,0,.25);
}
.pd-avatar img { width: 100%; height: 100%; object-fit: cover; }

.pd-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.8);
    padding: 4px 10px; border-radius: 30px;
    font-size: 10px; font-weight: 600;
}

.pd-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 30px;
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .4px;
}
.pd-pill .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.pill-ok  { background: rgba(16,185,129,.2); color: #10b981; border: 1px solid rgba(16,185,129,.3); }
.pill-ok  .dot { background: #10b981; box-shadow: 0 0 5px #10b981; }
.pill-wn  { background: rgba(245,158,11,.2); color: #f59e0b; border: 1px solid rgba(245,158,11,.3); }
.pill-wn  .dot { background: #f59e0b; box-shadow: 0 0 5px #f59e0b; }
.pill-er  { background: rgba(233,69,96,.2);  color: #e94560; border: 1px solid rgba(233,69,96,.3); }
.pill-er  .dot { background: #e94560; box-shadow: 0 0 5px #e94560; }

/* ══ HERO BUTTONS ══ */
.hero-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    border-radius: 11px;
    font-size: 12px; font-weight: 700;
    padding: 9px 14px;
    border: none;
    cursor: pointer;
    transition: transform .18s, box-shadow .18s;
    text-decoration: none !important;
    white-space: nowrap;
}
.hero-btn:hover { transform: translateY(-2px); text-decoration: none !important; }
.hero-btn-edit {
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: #fff !important;
    box-shadow: 0 4px 14px rgba(239,68,68,.35);
}
.hero-btn-edit:hover { box-shadow: 0 8px 22px rgba(239,68,68,.45); }
.hero-btn-hist {
    background: rgba(255,255,255,.14);
    color: #fff !important;
    border: 1px solid rgba(255,255,255,.2) !important;
}
.hero-btn-hist:hover { background: rgba(255,255,255,.22); }
.hero-btn-back {
    background: rgba(255,255,255,.07);
    color: rgba(255,255,255,.6) !important;
    border: 1px solid rgba(255,255,255,.1) !important;
}
.hero-btn-back:hover { background: rgba(255,255,255,.12); color: rgba(255,255,255,.8) !important; }

/* ══ KPI ══ */
.kpi {
    border-radius: 14px;
    padding: 18px;
    box-shadow: var(--sh);
    transition: transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
    margin-bottom: 14px;
}
.kpi:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }
.kpi::after {
    content: ''; position: absolute;
    bottom: -18px; right: -18px;
    width: 70px; height: 70px; border-radius: 50%;
    background: rgba(255,255,255,.15);
}
.kpi-ico {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; margin-bottom: 10px;
}
.kpi-v { font-size: 1.6rem; font-weight: 900; line-height: 1; margin-bottom: 3px; }
.kpi-l { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; opacity: .65; }

.kpi-g { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.kpi-g .kpi-ico { background: rgba(16,185,129,.18); color: #10b981; }
.kpi-g .kpi-v   { color: #065f46; }
.kpi-g .kpi-l   { color: #065f46; }

.kpi-o { background: linear-gradient(135deg, #fef3c7, #fde68a); }
.kpi-o .kpi-ico { background: rgba(245,158,11,.22); color: #d97706; }
.kpi-o .kpi-v   { color: #92400e; }
.kpi-o .kpi-l   { color: #92400e; }

.kpi-b { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
.kpi-b .kpi-ico { background: rgba(59,130,246,.18); color: #3b82f6; }
.kpi-b .kpi-v   { color: #1e3a8a; }
.kpi-b .kpi-l   { color: #1e3a8a; }

.kpi-r { background: linear-gradient(135deg, #fee2e2, #fecaca); }
.kpi-r .kpi-ico { background: rgba(233,69,96,.18); color: #e94560; }
.kpi-r .kpi-v   { color: #7f1d1d; }
.kpi-r .kpi-l   { color: #7f1d1d; }

/* ══ CARDS ══ */
.pd-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh);
    overflow: hidden;
    margin-bottom: 20px;
}
.pd-card-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.pd-card-title {
    display: flex; align-items: center; gap: 9px;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .7px;
    color: var(--dark); margin: 0;
}
.pd-card-ico {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; color: #fff; flex-shrink: 0;
}

/* ══ INFO ROWS ══ */
.ir {
    display: flex; align-items: flex-start;
    padding: 11px 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background .12s;
}
.ir:last-child { border-bottom: none; }
.ir:hover { background: #fafbff; }
.ir-k {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    color: #94a3b8; width: 38%; flex-shrink: 0; padding-top: 2px;
}
.ir-v { font-size: 13px; font-weight: 600; color: #1e293b; flex: 1; }

/* ══ PRIX ══ */
.px-row {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background .12s;
}
.px-row:last-child { border-bottom: none; }
.px-row:hover { background: #fafbff; }
.px-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.px-lbl  { flex: 1; font-size: 13px; font-weight: 600; color: #374151; }
.px-amt  { font-size: 14px; font-weight: 800; }
.px-pct  { font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 20px; min-width: 54px; text-align: center; }
.px-mg   { font-size: 11px; color: #94a3b8; min-width: 90px; text-align: right; }

/* ══ MOUVEMENTS ══ */
.mt-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
}
.mt-in  { background: #d1fae5; color: #059669; }
.mt-out { background: #fee2e2; color: #dc2626; }
.mq {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 800;
}
.mq-in  { background: #d1fae5; color: #059669; }
.mq-out { background: #fee2e2; color: #dc2626; }

/* ══ RESPONSIVE ══ */
@media (max-width: 991px) {
    .px-mg { display: none; }
    .kpi-v { font-size: 1.3rem; }
}
@media (max-width: 767px) {
    .pd-hero   { padding: 16px; }
    .pd-avatar { width: 60px; height: 60px; font-size: 1.5rem; border-radius: 13px; }
    .kpi       { margin-bottom: 10px; padding: 14px 16px; }
    .kpi-ico   { width: 36px; height: 36px; margin-bottom: 8px; }
    .kpi-v     { font-size: 1.4rem; }
    .px-pct    { display: none; }
    .px-amt    { font-size: 13px; }
    .ir-k      { width: 42%; }
    .ir-v      { font-size: 12px; }
}
@media (max-width: 420px) {
    .pd-avatar { width: 50px; height: 50px; font-size: 1.2rem; border-radius: 11px; }
    .kpi-v     { font-size: 1.2rem; }
    .hero-btn  { padding: 8px 10px; font-size: 11px; }
}
</style>
@endpush

@section('content')

{{-- ════════════ HERO ════════════ --}}
<div class="pd-hero">
    <div style="position:relative; z-index:1">

        {{-- Avatar + Infos + Boutons sur la même ligne --}}
        <div class="d-flex align-items-start" style="gap:14px">

            {{-- Avatar --}}
            <div class="pd-avatar">
                @if($produit->image)
                    <img src="{{ asset('storage/'.$produit->image) }}" alt="{{ $produit->libelle }}">
                @else
                    <i class="fas fa-box"></i>
                @endif
            </div>

            {{-- Nom + Chips + Statuts --}}
            <div style="flex:1; min-width:0">
                <h2 class="text-white font-weight-bold mb-2"
                    style="font-size:clamp(1rem,4vw,1.5rem); line-height:1.2;
                           white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                    {{ $produit->libelle }}
                </h2>

                <div class="d-flex flex-wrap mb-2" style="gap:5px">
                    @if($produit->reference)
                        <span class="pd-chip"><i class="fas fa-barcode"></i> {{ $produit->reference }}</span>
                    @endif
                    <span class="pd-chip"><i class="fas fa-layer-group"></i> {{ $produit->categorie->nom }}</span>
                    <span class="pd-chip d-none d-sm-inline-flex">
                        <i class="fas fa-weight"></i> {{ $produit->unite }}
                    </span>
                    @if($produit->fournisseur)
                        <span class="pd-chip d-none d-md-inline-flex">
                            <i class="fas fa-truck"></i> {{ $produit->fournisseur->nom }}
                        </span>
                    @endif
                </div>

                <div class="d-flex flex-wrap" style="gap:6px">
                    @if($produit->statut_stock == 'normal')
                        <span class="pd-pill pill-ok"><span class="dot"></span> Normal</span>
                    @elseif($produit->statut_stock == 'faible')
                        <span class="pd-pill pill-wn"><span class="dot"></span> Stock faible</span>
                    @else
                        <span class="pd-pill pill-er"><span class="dot"></span> Rupture</span>
                    @endif
                    @if($produit->actif)
                        <span class="pd-pill pill-ok"><span class="dot"></span> Actif</span>
                    @else
                        <span class="pd-pill pill-er"><span class="dot"></span> Inactif</span>
                    @endif
                </div>
            </div>

            {{-- ✅ Boutons toujours à DROITE --}}
            <div class="d-flex flex-column" style="gap:8px; flex-shrink:0">
              
                <a href="{{ route('produits.historique', $produit) }}"
                   class="hero-btn hero-btn-hist" title="Voir l'historique du produit">
                    <i class="fas fa-history"></i>
                    <span class="d-none d-sm-inline">Historique</span>
                </a>
                <a href="{{ route('produits.index') }}"
                   class="hero-btn hero-btn-back" title="Revenir en arrière">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Retour</span>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ════════════ KPI STOCK ════════════ --}}
<div class="row mb-2">
    <div class="col-12 col-sm-4">
        <div class="kpi {{ $produit->isRupture() ? 'kpi-r' : ($produit->isStockFaible() ? 'kpi-o' : 'kpi-g') }}">
            <div class="kpi-ico"><i class="fas fa-cubes"></i></div>
            <div class="kpi-v">{{ number_format($produit->stock_actuel, 0, ',', ' ') }}</div>
            <div class="kpi-l">Stock actuel · {{ $produit->unite }}</div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="kpi kpi-o">
            <div class="kpi-ico"><i class="fas fa-arrow-down"></i></div>
            <div class="kpi-v">{{ number_format($produit->stock_minimum, 0, ',', ' ') }}</div>
            <div class="kpi-l">Stock minimum · {{ $produit->unite }}</div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="kpi kpi-b">
            <div class="kpi-ico"><i class="fas fa-arrow-up"></i></div>
            <div class="kpi-v">{{ number_format($produit->stock_maximum ?? 0, 0, ',', ' ') }}</div>
            <div class="kpi-l">Stock maximum · {{ $produit->unite }}</div>
        </div>
    </div>
</div>

{{-- ════════════ CONTENU ════════════ --}}
<div class="row">

    {{-- ════ GAUCHE : Infos ════ --}}
    <div class="col-12 col-lg-4">
        <div class="pd-card">
            <div class="pd-card-head">
                <h6 class="pd-card-title">
                    <span class="pd-card-ico" style="background:#6366f1">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    Informations
                </h6>
            </div>
            <div class="ir">
                <span class="ir-k">Référence</span>
                <span class="ir-v">{{ $produit->reference ?? '—' }}</span>
            </div>
            <div class="ir">
                <span class="ir-k">Code barre</span>
                <span class="ir-v">{{ $produit->code_barre ?? '—' }}</span>
            </div>
            <div class="ir">
                <span class="ir-k">Catégorie</span>
                <span class="ir-v">
                    <span class="badge badge-info px-2 py-1 rounded-pill" style="font-size:11px">
                        {{ $produit->categorie->nom }}
                    </span>
                </span>
            </div>
            <div class="ir">
                <span class="ir-k">Fournisseur</span>
                <span class="ir-v">{{ $produit->fournisseur?->nom ?? '—' }}</span>
            </div>
            <div class="ir">
                <span class="ir-k">Unité</span>
                <span class="ir-v">{{ $produit->unite }}</span>
            </div>
            @if($produit->description)
            <div class="ir">
                <span class="ir-k">Description</span>
                <span class="ir-v" style="font-weight:400; color:#64748b; font-size:12px; line-height:1.5">
                    {{ $produit->description }}
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- ════ DROITE : Mouvements ════ --}}
    <div class="col-12 col-lg-8">

        {{-- Derniers mouvements --}}
        <div class="pd-card">
            <div class="pd-card-head">
                <h6 class="pd-card-title">
                    <span class="pd-card-ico" style="background:#0f172a">
                        <i class="fas fa-exchange-alt"></i>
                    </span>
                    Derniers mouvements
                </h6>
                <a href="{{ route('produits.historique', $produit) }}"
                   class="btn btn-sm btn-outline-secondary"
                   style="border-radius:10px; font-size:11px; font-weight:700">
                    <i class="fas fa-history mr-1"></i> Voir tout
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:12px">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="padding:11px 20px; color:#64748b; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                                Type
                            </th>
                            <th class="text-right"
                                style="padding:11px 20px; color:#64748b; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                                Qté
                            </th>
                            <th class="text-right d-none d-sm-table-cell"
                                style="padding:11px 20px; color:#64748b; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                                Stock après
                            </th>
                            <th class="d-none d-md-table-cell"
                                style="padding:11px 20px; color:#64748b; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                                Par
                            </th>
                            <th style="padding:11px 20px; color:#64748b; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produit->mouvementsStock()->latest()->take(5)->get() as $mvt)
                            @php $in = str_contains($mvt->type, 'entree'); @endphp
                            <tr>
                                <td style="padding:11px 20px; vertical-align:middle">
                                    <span class="mt-badge {{ $in ? 'mt-in' : 'mt-out' }}">
                                        <i class="fas fa-{{ $in ? 'arrow-down' : 'arrow-up' }}"></i>
                                        {{ $mvt->type_label }}
                                    </span>
                                </td>
                                <td class="text-right" style="padding:11px 20px; vertical-align:middle">
                                    <span class="mq {{ $in ? 'mq-in' : 'mq-out' }}">
                                        {{ $in ? '+' : '-' }}{{ number_format($mvt->quantite, 2, ',', ' ') }}
                                    </span>
                                </td>
                                <td class="text-right d-none d-sm-table-cell"
                                    style="padding:11px 20px; vertical-align:middle; font-weight:700; color:#374151">
                                    {{ number_format($mvt->stock_apres, 2, ',', ' ') }}
                                </td>
                                <td class="d-none d-md-table-cell"
                                    style="padding:11px 20px; vertical-align:middle; color:#64748b">
                                    {{ $mvt->user?->prenom ?? '—' }}
                                </td>
                                <td style="padding:11px 20px; vertical-align:middle;
                                           color:#94a3b8; font-size:11px">
                                    {{ $mvt->created_at->format('d/m/y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color:#94a3b8">
                                    <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
                                    Aucun mouvement enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection