@extends('layouts.app')

@section('title', 'Historique - ' . $produit->libelle)
@section('page-title', 'Historique des mouvements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
    <li class="breadcrumb-item">
        <a href="{{ route('produits.show', $produit) }}">{{ $produit->libelle }}</a>
    </li>
    <li class="breadcrumb-item active">Historique</li>
@endsection

@push('styles')
<style>
:root {
    --dark:   #0f172a;
    --border: #e2e8f0;
    --sh:     0 4px 24px rgba(15,52,96,.09);
    --r:      16px;
}

/* ══ HERO BANNER ══ */
.hist-hero {
    background: linear-gradient(140deg, #0f172a 0%, #1e3a5f 50%, #0f3460 100%);
    border-radius: var(--r);
    padding: 22px 26px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(15,52,96,.25);
}
.hist-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; border-radius:50%;
    background:radial-gradient(circle,rgba(233,69,96,.18),transparent 65%);
    pointer-events:none;
}
.hist-hero-title {
    font-size: clamp(.95rem,3vw,1.3rem);
    font-weight: 800; color: #fff; margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.hist-hero-sub { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 3px; }

.h-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border-radius: 10px; font-size: 12px; font-weight: 700;
    padding: 8px 14px; border: none; cursor: pointer;
    transition: transform .15s, box-shadow .15s;
    text-decoration: none !important; white-space: nowrap;
}
.h-btn:hover { transform: translateY(-2px); text-decoration: none !important; }
.h-btn-light {
    background: rgba(255,255,255,.14);
    color: #fff !important;
    border: 1px solid rgba(255,255,255,.2) !important;
}
.h-btn-light:hover { background: rgba(255,255,255,.22); }
.h-btn-success {
    background: linear-gradient(135deg,#10b981,#059669);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(16,185,129,.3);
}
.h-btn-success:hover { box-shadow: 0 6px 18px rgba(16,185,129,.4); }

/* ══ KPI ══ */
.kpi {
    border-radius: 14px; padding: 18px;
    box-shadow: var(--sh);
    transition: transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
    margin-bottom: 14px;
}
.kpi:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }
.kpi::after {
    content:''; position:absolute; bottom:-16px; right:-16px;
    width:65px; height:65px; border-radius:50%;
    background:rgba(255,255,255,.15);
}
.kpi-ico {
    width:38px; height:38px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:.9rem; margin-bottom:10px;
}
.kpi-v { font-size:1.5rem; font-weight:900; line-height:1; margin-bottom:3px; }
.kpi-l { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; opacity:.65; }

.kpi-g  { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
.kpi-g  .kpi-ico { background:rgba(16,185,129,.18); color:#10b981; }
.kpi-g  .kpi-v   { color:#065f46; } .kpi-g .kpi-l { color:#065f46; }

.kpi-b  { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
.kpi-b  .kpi-ico { background:rgba(59,130,246,.18); color:#3b82f6; }
.kpi-b  .kpi-v   { color:#1e3a8a; } .kpi-b .kpi-l { color:#1e3a8a; }

.kpi-r  { background:linear-gradient(135deg,#fee2e2,#fecaca); }
.kpi-r  .kpi-ico { background:rgba(233,69,96,.18); color:#e94560; }
.kpi-r  .kpi-v   { color:#7f1d1d; } .kpi-r .kpi-l { color:#7f1d1d; }

.kpi-p  { background:linear-gradient(135deg,#ede9fe,#ddd6fe); }
.kpi-p  .kpi-ico { background:rgba(139,92,246,.18); color:#7c3aed; }
.kpi-p  .kpi-v   { color:#4c1d95; } .kpi-p .kpi-l { color:#4c1d95; }

.kpi-o  { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.kpi-o  .kpi-ico { background:rgba(245,158,11,.22); color:#d97706; }
.kpi-o  .kpi-v   { color:#92400e; } .kpi-o .kpi-l { color:#92400e; }

/* ══ CARD ══ */
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
    flex-wrap: wrap; gap: 10px;
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

/* ══ FILTRES ══ */
.filter-wrap {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    background: #fafbff;
}

/* ══ TYPE BADGE ══ */
.t-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
}
.t-in  { background: #d1fae5; color: #059669; }
.t-out { background: #fee2e2; color: #dc2626; }
.t-adj { background: #ede9fe; color: #7c3aed; }

.mq {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 800;
}
.mq-in  { background: #d1fae5; color: #059669; }
.mq-out { background: #fee2e2; color: #dc2626; }

/* ══ RESPONSIVE ══ */
@media (max-width: 767px) {
    .hist-hero  { padding: 16px; }
    .kpi        { padding: 14px; margin-bottom: 10px; }
    .kpi-v      { font-size: 1.2rem; }
    .kpi-ico    { width: 32px; height: 32px; font-size: .8rem; margin-bottom: 7px; }
    .hide-sm    { display: none !important; }
    .h-btn      { padding: 7px 11px; font-size: 11px; }
}
@media (max-width: 420px) {
    .kpi-v { font-size: 1rem; }
    .kpi-l { font-size: 9px; }
}
</style>
@endpush

@section('content')

{{-- ════════════ HERO BANNER ════════════ --}}
<div class="hist-hero">
    <div class="d-flex align-items-center justify-content-between"
         style="position:relative; z-index:1; gap:12px">

        <div style="min-width:0">
            <p class="hist-hero-sub mb-1">
                <i class="fas fa-history mr-1"></i> Historique des mouvements
            </p>
            <h4 class="hist-hero-title">{{ $produit->libelle }}</h4>
        </div>

        <div class="d-flex flex-wrap" style="gap:8px; flex-shrink:0">
            <a href="{{ route('produits.show', $produit) }}" class="h-btn h-btn-light" title="Voir plus de détails">
                <i class="fas fa-eye"></i>
                <span class="d-none d-sm-inline">Fiche produit</span>
            </a>
            <a href="{{ route('stock.entrees') }}?produit={{ $produit->id }}"
               class="h-btn h-btn-success" title="Entrée Stock">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline">Entrée stock</span>
            </a>
        </div>
    </div>
</div>

{{-- ════════════ KPI ════════════ --}}
<div class="row mb-2">
    <div class="col-6 col-sm-6 col-md-3">
        <div class="kpi {{ $produit->isRupture() ? 'kpi-r' : ($produit->isStockFaible() ? 'kpi-o' : 'kpi-g') }}">
            <div class="kpi-ico"><i class="fas fa-cubes"></i></div>
            <div class="kpi-v">{{ number_format($produit->stock_actuel, 0, ',', ' ') }}</div>
            <div class="kpi-l">Stock actuel · {{ $produit->unite }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3">
        <div class="kpi kpi-b">
            <div class="kpi-ico"><i class="fas fa-arrow-circle-down"></i></div>
            {{-- ✅ Variable du controller --}}
            <div class="kpi-v">{{ number_format($totalEntrees, 0, ',', ' ') }}</div>
            <div class="kpi-l">Total entrées</div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3">
        <div class="kpi kpi-r">
            <div class="kpi-ico"><i class="fas fa-arrow-circle-up"></i></div>
            {{-- ✅ Variable du controller --}}
            <div class="kpi-v">{{ number_format($totalSorties, 0, ',', ' ') }}</div>
            <div class="kpi-l">Total sorties</div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-md-3">
        <div class="kpi kpi-p">
            <div class="kpi-ico"><i class="fas fa-list"></i></div>
            {{-- ✅ Variable du controller --}}
            <div class="kpi-v">{{ number_format($totalMouvements, 0, ',', ' ') }}</div>
            <div class="kpi-l">Nb mouvements</div>
        </div>
    </div>
</div>

{{-- ════════════ TABLE ════════════ --}}
<div class="pd-card">

    {{-- Header --}}
    <div class="pd-card-head">
        <h6 class="pd-card-title">
            <span class="pd-card-ico" style="background:#0f172a">
                <i class="fas fa-exchange-alt"></i>
            </span>
            Mouvements de stock
        </h6>
    </div>

    {{-- Filtres --}}
    <div class="filter-wrap">
        <form method="GET">
            <div class="row">

                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fas fa-filter mr-1"></i> Type
                    </label>
                    <select name="type" class="form-control form-control-sm"
                            onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        <option value="entree_achat"  {{ request('type')=='entree_achat'  ?'selected':'' }}>📥 Entrée achat</option>
                        <option value="entree_retour" {{ request('type')=='entree_retour' ?'selected':'' }}>↩️ Entrée retour</option>
                        <option value="sortie_vente"  {{ request('type')=='sortie_vente'  ?'selected':'' }}>🛒 Sortie vente</option>
                        <option value="sortie_perte"  {{ request('type')=='sortie_perte'  ?'selected':'' }}>⚠️ Sortie perte</option>
                        <option value="ajustement"    {{ request('type')=='ajustement'    ?'selected':'' }}>🔧 Ajustement</option>
                    </select>
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

                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <label class="small d-block mb-1">&nbsp;</label>
                    <div class="d-flex" style="gap:6px">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search mr-1"></i>
                            <span class="d-none d-sm-inline">Filtrer</span>
                        </button>
                        @if(request()->hasAny(['type','date_debut','date_fin']))
                            <a href="{{ route('produits.historique', $produit) }}"
                               class="btn btn-secondary btn-sm flex-fill">
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
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                        Type
                    </th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="text-right">Quantité</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="text-right hide-sm">Prix unit.</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="text-right hide-sm">Montant</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="text-right d-none d-md-table-cell">Avant</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="text-right d-none d-sm-table-cell">Après</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="d-none d-lg-table-cell">Motif</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                        class="d-none d-md-table-cell">Par</th>
                    <th style="padding:11px 16px; color:#64748b; font-size:10px;
                               text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mvt)
                    @php
                        $in  = str_contains($mvt->type, 'entree');
                        $adj = str_contains($mvt->type, 'ajustement');
                    @endphp
                    <tr>
                        <td style="padding:11px 16px; vertical-align:middle">
                            <span class="t-badge {{ $adj ? 't-adj' : ($in ? 't-in' : 't-out') }}">
                                <i class="fas fa-{{ $adj ? 'sliders-h' : ($in ? 'arrow-down' : 'arrow-up') }}"></i>
                                {{ $mvt->type_label }}
                            </span>
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle" class="text-right">
                            <span class="mq {{ $in ? 'mq-in' : 'mq-out' }}">
                                {{ $in ? '+' : '-' }}{{ number_format($mvt->quantite, 2, ',', ' ') }}
                            </span>
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b"
                            class="text-right hide-sm">
                            {{ number_format($mvt->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; font-weight:700; color:#374151"
                            class="text-right hide-sm">
                            {{ number_format($mvt->quantite * $mvt->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8"
                            class="text-right d-none d-md-table-cell">
                            {{ number_format($mvt->stock_avant, 2, ',', ' ') }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; font-weight:700; color:#374151"
                            class="text-right d-none d-sm-table-cell">
                            {{ number_format($mvt->stock_apres, 2, ',', ' ') }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b; font-size:11px"
                            class="d-none d-lg-table-cell">
                            {{ $mvt->motif ?? '—' }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#64748b"
                            class="d-none d-md-table-cell">
                            {{ $mvt->user?->prenom ?? '—' }}
                        </td>
                        <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8; font-size:11px">
                            {{ $mvt->created_at->format('d/m/y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                            <i class="fas fa-history fa-2x d-block mb-2" style="opacity:.4"></i>
                            Aucun mouvement trouvé
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

@endsection