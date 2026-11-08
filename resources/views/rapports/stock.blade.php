{{-- resources/views/rapports/stock.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport de Stock')
@section('page-title', 'Rapport de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
    <li class="breadcrumb-item active">Stock</li>
@endsection

@push('styles')
<style>
.rs-wrap { font-family:sans-serif; }

/* ── HERO ── */
.rs-hero {
    background: linear-gradient(135deg, #0f172a 0%, #92400e 55%, #78350f 100%);
    border-radius: 20px;
    padding: 26px 32px;
    margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 10px 40px rgba(120,53,15,.3);
}
.rs-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:240px; height:240px; border-radius:50%;
    background:rgba(255,255,255,.06); pointer-events:none;
}
.rs-hero::after {
    content:''; position:absolute; bottom:-40px; left:35%;
    width:180px; height:180px; border-radius:50%;
    background:rgba(217,119,6,.12); pointer-events:none;
}
.rs-hero-left { position:relative; z-index:1; }
.rs-hero-title { font-size:clamp(1rem,2.5vw,1.4rem); font-weight:800; color:#fff; margin:0 0 6px; }
.rs-hero-sub   { font-size:12px; color:rgba(255,255,255,.5); margin:0; }
.rs-hero-right {
    position:relative; z-index:1;
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
}
.rs-export-btn {
    display:inline-flex; align-items:center; gap:7px;
    background:linear-gradient(135deg,#991b1b,#dc2626);
    color:#fff !important; border-radius:12px;
    padding:10px 20px; font-size:12px; font-weight:800;
    text-decoration:none; border:none; cursor:pointer;
    transition:all .2s; box-shadow:0 4px 14px rgba(220,38,38,.35);
}
.rs-export-btn:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(220,38,38,.45); text-decoration:none; }
.rs-back-btn {
    display:inline-flex; align-items:center; gap:7px;
    background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.18);
    color:rgba(255,255,255,.8) !important; border-radius:12px;
    padding:10px 18px; font-size:12px; font-weight:700;
    text-decoration:none; transition:all .2s;
}
.rs-back-btn:hover { background:rgba(255,255,255,.18); color:#fff !important; text-decoration:none; }

/* ── KPI ── */
.rs-kpi-row { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.rs-kpi {
    background:#fff; border:1px solid #e8edf5;
    border-radius:18px; padding:20px;
    box-shadow:0 2px 16px rgba(11,15,26,.05);
    position:relative; overflow:hidden;
    transition:transform .2s, box-shadow .2s;
}
.rs-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,.1); }
.rs-kpi::after {
    content:''; position:absolute; bottom:-20px; right:-20px;
    width:80px; height:80px; border-radius:50%;
    background:rgba(0,0,0,.03);
}
.rs-kpi-ico {
    width:44px; height:44px; border-radius:13px;
    display:flex; align-items:center; justify-content:center;
    font-size:16px; margin-bottom:12px;
}
.rs-kpi-val { font-size:1.5rem; font-weight:900; line-height:1; margin-bottom:4px; }
.rs-kpi-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; opacity:.65; }

.kpi-amber { }
.kpi-amber .rs-kpi-ico { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#d97706; }
.kpi-amber .rs-kpi-val { color:#92400e; }
.kpi-amber .rs-kpi-lbl { color:#92400e; }

.kpi-red { }
.kpi-red .rs-kpi-ico { background:linear-gradient(135deg,#fee2e2,#fecaca); color:#dc2626; }
.kpi-red .rs-kpi-val { color:#7f1d1d; }
.kpi-red .rs-kpi-lbl { color:#7f1d1d; }

.kpi-orange { }
.kpi-orange .rs-kpi-ico { background:linear-gradient(135deg,#ffedd5,#fed7aa); color:#ea580c; }
.kpi-orange .rs-kpi-val { color:#7c2d12; }
.kpi-orange .rs-kpi-lbl { color:#7c2d12; }

.kpi-green { }
.kpi-green .rs-kpi-ico { background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#059669; }
.kpi-green .rs-kpi-val { color:#065f46; }
.kpi-green .rs-kpi-lbl { color:#065f46; }

/* ── CARD TABLE ── */
.rs-card {
    background:#fff; border:1px solid #e8edf5;
    border-radius:20px; overflow:hidden;
    box-shadow:0 2px 16px rgba(11,15,26,.05);
    margin-bottom:20px;
}
.rs-card-head {
    padding:16px 22px; border-bottom:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:10px;
}
.rs-card-title {
    display:flex; align-items:center; gap:10px;
    font-size:11px; font-weight:800;
    text-transform:uppercase; letter-spacing:.6px; color:#0f172a; margin:0;
}
.rs-card-ico {
    width:30px; height:30px; border-radius:9px;
    background:linear-gradient(135deg,#92400e,#d97706);
    display:flex; align-items:center; justify-content:center;
    font-size:12px; color:#fff;
}

/* ── FILTRES ── */
.rs-filter-bar {
    padding:14px 22px; border-bottom:1px solid #f1f5f9; background:#fafbff;
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
}
.rs-search-wrap {
    display:flex; align-items:center;
    background:#f1f5f9; border:1.5px solid #e2e8f0;
    border-radius:11px; overflow:hidden;
    transition:all .2s; flex:1; min-width:180px; max-width:300px;
}
.rs-search-wrap:focus-within { border-color:#d97706; background:#fff; box-shadow:0 0 0 3px rgba(217,119,6,.1); }
.rs-search-ico { width:38px; display:flex; align-items:center; justify-content:center; color:#c4cdd8; font-size:12px; }
.rs-search-wrap:focus-within .rs-search-ico { color:#d97706; }
.rs-search-inp { flex:1; border:none; background:transparent; padding:9px 10px 9px 0; font-size:12px; color:#0f172a; outline:none; }
.rs-filter-sel {
    background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:11px;
    padding:9px 14px; font-size:12px; font-weight:600; color:#374151;
    outline:none; cursor:pointer; transition:border-color .2s;
    -webkit-appearance:none; padding-right:28px;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 10px center;
}
.rs-filter-sel:focus { border-color:#d97706; }
.rs-filter-count {
    margin-left:auto;
    font-size:11px; font-weight:700; color:#94a3b8;
    white-space:nowrap;
}

/* ── TABLE ── */
.rs-table { width:100%; font-size:12px; border-collapse:collapse; }
.rs-table thead tr { background:#f8fafc; }
.rs-table th {
    padding:11px 16px; font-size:10px; font-weight:700;
    text-transform:uppercase; letter-spacing:.5px; color:#64748b;
    white-space:nowrap; border-bottom:1px solid #f1f5f9;
}
.rs-table td {
    padding:11px 16px; vertical-align:middle;
    border-bottom:1px solid #f8fafc;
}
.rs-table tr:last-child td { border-bottom:none; }
.rs-table tr:hover td   { background:#fafbff; }

/* Badges statut */
.s-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:20px;
    font-size:10px; font-weight:700;
}
.s-dot { width:5px; height:5px; border-radius:50%; background:currentColor; }
.sb-ok  { background:#d1fae5; color:#059669; }
.sb-low { background:#fef3c7; color:#d97706; }
.sb-out { background:#fee2e2; color:#dc2626; }

/* Stock bar */
.stock-bar-wrap { display:flex; align-items:center; gap:8px; }
.stock-bar-bg {
    flex:1; height:5px; border-radius:10px; background:#f1f5f9;
    min-width:50px; overflow:hidden;
}
.stock-bar-fill { height:100%; border-radius:10px; transition:width .4s; }
.stock-val { font-size:12px; font-weight:800; white-space:nowrap; }

/* ── CAT BADGE ── */
.cat-badge {
    display:inline-flex; align-items:center;
    background:#f0f4ff; color:#4f46e5;
    padding:3px 9px; border-radius:20px;
    font-size:10px; font-weight:700;
}

/* ── RESPONSIVE ── */
@media(max-width:992px) { .rs-kpi-row { grid-template-columns:repeat(2,1fr); } }
@media(max-width:600px)  {
    .rs-hero { flex-direction:column; align-items:flex-start; }
    .rs-kpi-row { grid-template-columns:1fr 1fr; }
    .hide-sm { display:none !important; }
}

/* ── ANIM ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.rs-hero    { animation:fadeUp .4s ease both; }
.rs-kpi-row { animation:fadeUp .4s .1s ease both; }
.rs-card    { animation:fadeUp .4s .2s ease both; }
</style>
@endpush

@section('content')

@php
    $produitsNormaux = $produits->filter(fn($p) => $p->statut_stock === 'normal')->count();
    $valeurFormatted = $valeurTotaleStock >= 1000000
        ? number_format($valeurTotaleStock / 1000000, 1, ',', ' ') . 'M'
        : number_format($valeurTotaleStock / 1000, 0, ',', ' ') . 'K';
@endphp

<div class="rs-wrap">

    {{-- ── HERO ── --}}
    <div class="rs-hero">
        <div class="rs-hero-left">
            <h1 class="rs-hero-title">
                <i class="fas fa-warehouse mr-2" style="opacity:.7"></i>
                Rapport de Stock
            </h1>
            <p class="rs-hero-sub">
                État au {{ now()->format('d/m/Y à H:i') }} &nbsp;·&nbsp;
                {{ $produits->count() }} produits
            </p>
        </div>
        <div class="rs-hero-right">
            <a href="{{ route('rapports.index') }}" class="rs-back-btn">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('rapports.export-pdf') }}?type=stock"
               target="_blank" class="rs-export-btn">
                <i class="fas fa-file-pdf"></i> Exporter PDF
            </a>
        </div>
    </div>

    {{-- ── KPI ── --}}
    <div class="rs-kpi-row">
        <div class="rs-kpi kpi-amber">
            <div class="rs-kpi-ico"><i class="fas fa-coins"></i></div>
            <div class="rs-kpi-val">{{ $valeurFormatted }}</div>
            <div class="rs-kpi-lbl">Valeur totale (FCFA)</div>
        </div>
        <div class="rs-kpi kpi-red">
            <div class="rs-kpi-ico"><i class="fas fa-times-circle"></i></div>
            <div class="rs-kpi-val">{{ $produitsRupture }}</div>
            <div class="rs-kpi-lbl">Produits en rupture</div>
        </div>
        <div class="rs-kpi kpi-orange">
            <div class="rs-kpi-ico"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="rs-kpi-val">{{ $produitsFaibles }}</div>
            <div class="rs-kpi-lbl">Stocks faibles</div>
        </div>
        <div class="rs-kpi kpi-green">
            <div class="rs-kpi-ico"><i class="fas fa-check-circle"></i></div>
            <div class="rs-kpi-val">{{ $produitsNormaux }}</div>
            <div class="rs-kpi-lbl">Stocks normaux</div>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="rs-card">

        <div class="rs-card-head">
            <h6 class="rs-card-title">
                <span class="rs-card-ico"><i class="fas fa-warehouse"></i></span>
                État des stocks
                <span class="badge badge-secondary ml-1" style="font-size:10px; border-radius:20px">
                    {{ $produits->count() }}
                </span>
            </h6>
        </div>

        {{-- Filtres ── --}}
        <div class="rs-filter-bar">
            <div class="rs-search-wrap">
                <div class="rs-search-ico"><i class="fas fa-search"></i></div>
                <input type="text" id="rsSearch" class="rs-search-inp"
                       placeholder="Rechercher un produit...">
            </div>
            <select id="rsStatut" class="rs-filter-sel">
                <option value="">Tous les statuts</option>
                <option value="normal">✅ Normal</option>
                <option value="faible">⚠️ Stock faible</option>
                <option value="rupture">❌ Rupture</option>
            </select>
            <select id="rsCategorie" class="rs-filter-sel">
                <option value="">Toutes catégories</option>
                @foreach($produits->pluck('categorie.nom')->unique()->sort() as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <span class="rs-filter-count" id="rsCount">
                {{ $produits->count() }} produit(s)
            </span>
        </div>

        {{-- Table ── --}}
        <div class="table-responsive">
            <table class="rs-table" id="tableStock">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produit</th>
                        <th class="hide-sm">Catégorie</th>
                        <th class="hide-sm">Fournisseur</th>
                        <th class="text-right">Prix achat</th>
                        <th class="text-right hide-sm">Prix détail</th>
                        <th>Stock actuel</th>
                        <th class="text-right hide-sm">Stock min.</th>
                        <th class="text-right">Valeur stock</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody id="rsBody">
                    @foreach($produits as $i => $p)
                        @php
                            $statut = $p->statut_stock;
                            $pct = $p->stock_minimum > 0
                                ? min(100, round(($p->stock_actuel / max($p->stock_minimum * 2, 1)) * 100))
                                : ($p->stock_actuel > 0 ? 100 : 0);
                            $barColor = match($statut) {
                                'rupture' => '#ef4444',
                                'faible'  => '#f59e0b',
                                default   => '#10b981',
                            };
                            $valeurStock = $p->stock_actuel * $p->prix_achat;
                        @endphp
                        <tr data-statut="{{ $statut }}" data-categorie="{{ $p->categorie->nom }}">
                            <td style="color:#94a3b8; font-size:11px">{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ route('produits.show', $p) }}"
                                   style="font-weight:700; color:#0f172a; text-decoration:none">
                                    {{ $p->libelle }}
                                </a>
                                @if($p->reference)
                                    <div style="font-size:10px; color:#94a3b8">{{ $p->reference }}</div>
                                @endif
                            </td>
                            <td class="hide-sm">
                                <span class="cat-badge">{{ $p->categorie->nom }}</span>
                            </td>
                            <td style="color:#64748b" class="hide-sm">
                                {{ $p->fournisseur?->nom ?? '—' }}
                            </td>
                            <td class="text-right" style="color:#64748b; font-weight:600">
                                {{ number_format($p->prix_achat, 0, ',', ' ') }}
                            </td>
                            <td class="text-right hide-sm" style="color:#374151; font-weight:700">
                                {{ number_format($p->prix_detail, 0, ',', ' ') }}
                            </td>
                            <td style="min-width:130px">
                                <div class="stock-bar-wrap">
                                    <div class="stock-bar-bg">
                                        <div class="stock-bar-fill"
                                             style="width:{{ $pct }}%; background:{{ $barColor }}">
                                        </div>
                                    </div>
                                    <span class="stock-val" style="color:{{ $barColor }}">
                                        {{ number_format($p->stock_actuel, 0, ',', ' ') }}
                                        <small style="font-size:9px; font-weight:600; opacity:.7">{{ $p->unite }}</small>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right hide-sm" style="color:#94a3b8; font-weight:600">
                                {{ number_format($p->stock_minimum, 0, ',', ' ') }}
                            </td>
                            <td class="text-right" style="font-weight:800; color:#374151">
                                @if($valeurStock >= 1000000)
                                    {{ number_format($valeurStock / 1000000, 1, ',', ' ') }}M
                                @elseif($valeurStock >= 1000)
                                    {{ number_format($valeurStock / 1000, 0, ',', ' ') }}K
                                @else
                                    {{ number_format($valeurStock, 0, ',', ' ') }}
                                @endif
                                <small style="color:#94a3b8; font-weight:400; font-size:10px">FCFA</small>
                            </td>
                            <td>
                                @if($statut === 'normal')
                                    <span class="s-badge sb-ok">
                                        <span class="s-dot"></span> Normal
                                    </span>
                                @elseif($statut === 'faible')
                                    <span class="s-badge sb-low">
                                        <span class="s-dot"></span> Faible
                                    </span>
                                @else
                                    <span class="s-badge sb-out">
                                        <span class="s-dot"></span> Rupture
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pied de table ── --}}
        <div style="padding:14px 22px; border-top:1px solid #f1f5f9; background:#fafbff;
                    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <span style="font-size:11px; color:#94a3b8" id="rsFooterCount">
                Affichage de {{ $produits->count() }} produit(s)
            </span>
            <div style="display:flex; gap:16px">
                <span style="font-size:12px; font-weight:700; color:#059669">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ $produitsNormaux }} normaux
                </span>
                <span style="font-size:12px; font-weight:700; color:#d97706">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ $produitsFaibles }} faibles
                </span>
                <span style="font-size:12px; font-weight:700; color:#ef4444">
                    <i class="fas fa-times-circle mr-1"></i>
                    {{ $produitsRupture }} ruptures
                </span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const rows      = document.querySelectorAll('#rsBody tr');
        const search    = document.getElementById('rsSearch');
        const selStatut = document.getElementById('rsStatut');
        const selCat    = document.getElementById('rsCategorie');
        const count     = document.getElementById('rsCount');
        const footer    = document.getElementById('rsFooterCount');

        function filter() {
            const q   = search.value.toLowerCase().trim();
            const st  = selStatut.value;
            const cat = selCat.value;
            let n = 0;

            rows.forEach(row => {
                const text   = row.textContent.toLowerCase();
                const statut = row.dataset.statut;
                const catRow = row.dataset.categorie;

                const ok = (!q  || text.includes(q))
                        && (!st  || statut === st)
                        && (!cat || catRow === cat);

                row.style.display = ok ? '' : 'none';
                if (ok) n++;
            });

            count.textContent  = n + ' produit(s)';
            footer.textContent = 'Affichage de ' + n + ' produit(s)';
        }

        search.addEventListener('input',    filter);
        selStatut.addEventListener('change', filter);
        selCat.addEventListener('change',    filter);
    })();
</script>
@endpush