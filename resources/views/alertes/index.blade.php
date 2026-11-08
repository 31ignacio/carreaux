{{-- resources/views/alertes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Alertes Stock')
@section('page-title', 'Alertes de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item active">Alertes</li>
@endsection

@push('styles')
<style>
/* ══ BASE ══ */
.al-wrap { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

/* ══ HERO ══ */
.al-hero {
    background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ef4444 100%);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    position: relative; overflow: hidden;
    box-shadow: 0 10px 40px rgba(220,38,38,.25);
}
.al-hero::before {
    content: '';
    position: absolute; top: -60px; right: -60px;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.al-hero::after {
    content: '';
    position: absolute; bottom: -40px; left: 30%;
    width: 160px; height: 160px; border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.al-hero-left { position: relative; z-index: 1; }
.al-hero-ico {
    width: 52px; height: 52px; border-radius: 16px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff;
    margin-bottom: 14px;
}
.al-hero-title {
    font-size: clamp(1.2rem, 2.5vw, 1.5rem);
    font-weight: 800; color: #fff; margin: 0 0 4px;
    letter-spacing: -.3px;
}
.al-hero-sub { font-size: 13px; color: rgba(255,255,255,.65); margin: 0; }
.al-hero-right { position: relative; z-index: 1; text-align: right; }
.al-hero-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: #dc2626;
    border: none; border-radius: 12px;
    padding: 11px 20px;
    font-size: 13px; font-weight: 800;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
    text-decoration: none;
}
.al-hero-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    color: #b91c1c; text-decoration: none;
}
.al-hero-btn i { font-size: 13px; }

/* ══ KPI ROW ══ */
.al-kpi-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.al-kpi {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 16px;
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: 0 2px 12px rgba(11,15,26,.05);
    transition: transform .2s, box-shadow .2s;
}
.al-kpi:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(11,15,26,.1); }
.al-kpi-ico {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.ki-total   { background: #fee2e2; color: #dc2626; }
.ki-rupture { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.ki-faible  { background: #fffbeb; color: #d97706; }
.al-kpi-val { font-size: 26px; font-weight: 900; color: #0f172a; line-height: 1; }
.al-kpi-lbl { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* ══ CARD ══ */
.al-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(11,15,26,.05);
}
.al-card-head {
    padding: 18px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
}
.al-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 800;
    color: #0f172a; letter-spacing: -.1px;
    text-transform: uppercase;
}
.al-card-ico {
    width: 30px; height: 30px; border-radius: 9px;
    background: linear-gradient(135deg, #7f1d1d, #dc2626);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: #fff;
}
.al-count-badge {
    display: inline-flex; align-items: center;
    background: #fee2e2; color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 20px; padding: 3px 12px;
    font-size: 12px; font-weight: 800;
}

/* ══ FILTER ══ */
.al-filter-wrap { display: flex; align-items: center; gap: 8px; }
.al-filter-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
.al-filter-select {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 7px 12px;
    font-size: 12px; font-weight: 600;
    color: #374151; background: #f8fafc;
    cursor: pointer; outline: none;
    transition: border-color .2s;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px;
}
.al-filter-select:focus { border-color: #2563eb; background-color: #fff; }

/* ══ TABLE ══ */
.al-table { width: 100%; font-size: 12px; }
.al-table th {
    padding: 12px 18px;
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px;
    color: #94a3b8; background: #f8fafc;
    border-bottom: 1px solid #e8edf5;
    white-space: nowrap;
}
.al-table td {
    padding: 14px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
}
.al-table tbody tr { transition: background .1s; }
.al-table tbody tr:last-child td { border-bottom: none; }
.al-table tbody tr:hover td { background: #fafbff; }

/* ══ PRODUIT CELL ══ */
.prod-cell { display: flex; align-items: center; gap: 10px; }
.prod-cell-ico {
    width: 34px; height: 34px; border-radius: 10px;
    background: #f1f5f9;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: #94a3b8; flex-shrink: 0;
}
.prod-cell-name { font-weight: 700; color: #0f172a; font-size: 13px; }
.prod-cell-sku  { font-size: 10px; color: #94a3b8; margin-top: 1px; }

/* ══ STOCK CELL ══ */
.stock-val { font-size: 14px; font-weight: 900; }
.sv-red    { color: #dc2626; }
.sv-amber  { color: #d97706; }
.stock-min { font-size: 10px; color: #94a3b8; margin-top: 2px; }

/* ══ BADGES ══ */
.type-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px;
    font-size: 10px; font-weight: 800; letter-spacing: .3px;
}
.tb-rupture { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.tb-faible  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.tb-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

.cat-badge {
    display: inline-block;
    padding: 3px 10px; border-radius: 8px;
    font-size: 10px; font-weight: 700;
    background: #eff6ff; color: #2563eb;
    border: 1px solid #bfdbfe;
}

/* ══ DATE CELL ══ */
.date-main { font-size: 12px; font-weight: 600; color: #374151; }
.date-time { font-size: 10px; color: #94a3b8; margin-top: 1px; }

/* ══ ACTIONS ══ */
.action-group { display: flex; align-items: center; gap: 6px; }
.act-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 13px; border-radius: 9px;
    font-size: 11px; font-weight: 700;
    border: none; cursor: pointer;
    transition: all .15s; text-decoration: none;
    white-space: nowrap;
}
.act-btn:hover { transform: translateY(-1px); text-decoration: none; }
.ab-green { background: #d1fae5; color: #059669; }
.ab-green:hover { background: #a7f3d0; color: #047857; }
.ab-gray  { background: #f1f5f9; color: #475569; }
.ab-gray:hover  { background: #e2e8f0; color: #1e293b; }

/* ══ EMPTY ══ */
.al-empty {
    text-align: center; padding: 60px 20px;
}
.al-empty-ring {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    font-size: 32px; color: #059669;
    box-shadow: 0 8px 24px rgba(5,150,105,.2);
}
.al-empty-title { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
.al-empty-sub   { font-size: 13px; color: #94a3b8; }

/* ══ PAGINATION ══ */
.al-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.al-footer-info { font-size: 12px; color: #94a3b8; }

/* ══ RESPONSIVE ══ */
@media(max-width: 768px) {
    .al-kpi-row { grid-template-columns: 1fr; }
    .al-hero { flex-direction: column; align-items: flex-start; gap: 16px; }
    .al-hero-right { width: 100%; }
    .al-hero-btn { width: 100%; justify-content: center; }
    .hide-sm { display: none !important; }
}

/* ══ PULSE ══ */
.pulse-dot {
    display: inline-block; width: 8px; height: 8px; border-radius: 50%;
    background: #ef4444; position: relative; margin-right: 3px;
}
.pulse-dot::after {
    content: ''; position: absolute; inset: -3px; border-radius: 50%;
    background: rgba(239,68,68,.3);
    animation: pd 1.5s ease-out infinite;
}
@keyframes pd { 0%{transform:scale(.8);opacity:1} 100%{transform:scale(2);opacity:0} }

/* ══ ANIM ══ */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
.al-hero   { animation: fadeUp .4s ease both; }
.al-kpi-row { animation: fadeUp .4s .1s ease both; }
.al-card   { animation: fadeUp .4s .2s ease both; }
</style>
@endpush

@section('content')
<div class="al-wrap">

    {{-- ── HERO ── --}}
    <div class="al-hero">
        <div class="al-hero-left">
            <div class="al-hero-ico">
                <i class="fas fa-bell"></i>
            </div>
            <h1 class="al-hero-title">
                @if($alertes->total() > 0)
                    <span class="pulse-dot"></span>
                @endif
                Alertes de stock
            </h1>
            <p class="al-hero-sub">
                {{ $alertes->total() > 0
                    ? $alertes->total() . ' alerte(s) en attente de traitement'
                    : 'Tous les stocks sont à niveau — aucune alerte active' }}
            </p>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="al-hero-right">
            <form action="{{ route('alertes.traiter-tout') }}" method="POST"
                  onsubmit="return confirm('Marquer toutes les alertes comme traitées ?')">
                @csrf @method('PATCH')
                <button type="submit" class="al-hero-btn">
                    <i class="fas fa-check-double"></i>
                    Tout marquer traité
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- ── KPI ROW ── --}}
    <div class="al-kpi-row">
        <div class="al-kpi">
            <div class="al-kpi-ico ki-total">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <div class="al-kpi-val">{{ $alertes->total() }}</div>
                <div class="al-kpi-lbl">Total alertes</div>
            </div>
        </div>
        <div class="al-kpi">
            <div class="al-kpi-ico ki-rupture">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <div class="al-kpi-val">{{ $alertes->where('type','rupture')->count() }}</div>
                <div class="al-kpi-lbl">Ruptures</div>
            </div>
        </div>
        <div class="al-kpi">
            <div class="al-kpi-ico ki-faible">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="al-kpi-val">{{ $alertes->where('type','stock_faible')->count() }}</div>
                <div class="al-kpi-lbl">Stock faible</div>
            </div>
        </div>
    </div>

    {{-- ── CARD ── --}}
    <div class="al-card">

        {{-- Head --}}
        <div class="al-card-head">
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <h6 class="al-card-title">
                    <span class="al-card-ico"><i class="fas fa-bell"></i></span>
                    Alertes non traitées
                </h6>
                <span class="al-count-badge">
                    {{ $alertes->total() }} alerte(s)
                </span>
            </div>

            {{-- Filtre --}}
            <div class="al-filter-wrap">
                <span class="al-filter-label">Filtrer</span>
                <form method="GET" id="filterForm">
                    <select name="type" class="al-filter-select"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">Tous les types</option>
                        <option value="rupture"      {{ request('type') == 'rupture'      ? 'selected' : '' }}>
                            Rupture de stock
                        </option>
                        <option value="stock_faible" {{ request('type') == 'stock_faible' ? 'selected' : '' }}>
                            Stock faible
                        </option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto">
            <table class="al-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="hide-sm">Catégorie</th>
                        <th>Type</th>
                        <th>Stock actuel</th>
                        <th class="hide-sm">Stock min.</th>
                        <th class="hide-sm">Date</th>
                        @if(auth()->user()->isAdmin())
                        <th style="text-align:right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($alertes as $alerte)
                        @php $isRupture = $alerte->type === 'rupture'; @endphp
                        <tr>
                            {{-- Produit --}}
                            <td>
                                <div class="prod-cell">
                                    <div class="prod-cell-ico">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="prod-cell-name">
                                            {{ $alerte->produit->libelle }}
                                        </div>
                                        <div class="prod-cell-sku">
                                            Réf. {{ $alerte->produit->code ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Catégorie --}}
                            <td class="hide-sm">
                                <span class="cat-badge">
                                    {{ $alerte->produit->categorie->nom }}
                                </span>
                            </td>

                            {{-- Type alerte --}}
                            <td>
                                <span class="type-badge {{ $isRupture ? 'tb-rupture' : 'tb-faible' }}">
                                    <span class="tb-dot"></span>
                                    {{ $isRupture ? 'Rupture' : 'Stock faible' }}
                                </span>
                            </td>

                            {{-- Stock actuel --}}
                            <td>
                                <div class="stock-val {{ $isRupture ? 'sv-red' : 'sv-amber' }}">
                                    {{ number_format($alerte->stock_au_moment, 0, ',', ' ') }}
                                    <small style="font-size:.65em; font-weight:600">
                                        {{ $alerte->produit->unite }}
                                    </small>
                                </div>
                                <div class="stock-min">
                                    Au moment de l'alerte
                                </div>
                            </td>

                            {{-- Stock minimum --}}
                            <td class="hide-sm">
                                <span style="font-weight:700; color:#374151">
                                    {{ number_format($alerte->produit->stock_minimum, 0, ',', ' ') }}
                                    {{ $alerte->produit->unite }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="hide-sm">
                                <div class="date-main">
                                    {{ $alerte->created_at->format('d/m/Y') }}
                                </div>
                                <div class="date-time">
                                    {{ $alerte->created_at->format('H:i') }} ·
                                    {{ $alerte->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            @if(auth()->user()->isAdmin())
                            <td>
                                <div class="action-group" style="justify-content:flex-end">
                                    <a href="{{ route('stock.entrees') }}"
                                       class="act-btn ab-green" title="Faire une entrée stock">
                                        <i class="fas fa-plus"></i>
                                        <span class="hide-sm">Entrée</span>
                                    </a>
                                    <form action="{{ route('alertes.traiter', $alerte) }}"
                                          method="POST" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn ab-gray"
                                                title="Marquer comme traité">
                                            <i class="fas fa-check"></i>
                                            <span class="hide-sm">Traiter</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="al-empty">
                                    <div class="al-empty-ring">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="al-empty-title">Aucune alerte active !</div>
                                    <div class="al-empty-sub">
                                        Tous vos stocks sont à niveau. Continuez comme ça !
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer pagination --}}
        @if($alertes->hasPages())
            <div class="al-footer">
                <span class="al-footer-info">
                    Affichage {{ $alertes->firstItem() }}–{{ $alertes->lastItem() }}
                    sur {{ $alertes->total() }} alerte(s)
                </span>
                <div>
                    {{ $alertes->withQueryString()->links() }}
                </div>
            </div>
        @endif

    </div>
</div>
@endsection