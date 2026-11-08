{{-- resources/views/inventaires/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventaire — ' . $inventaire->titre)
@section('page-title', $inventaire->titre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inventaires.index') }}">Inventaires</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($inventaire->titre, 30) }}</li>
@endsection

@push('styles')
    <style>
        .inv-show { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

        /* ── HERO ── */
        .inv-show-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
            border-radius: 20px;
            padding: 26px 32px;
            margin-bottom: 22px;
            display: flex; align-items: flex-start; justify-content: space-between; gap: 20px;
            position: relative; overflow: hidden;
            box-shadow: 0 10px 40px rgba(15,23,42,.2);
        }
        .inv-show-hero::before {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 240px; height: 240px; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .inv-show-hero::after {
            content: ''; position: absolute; bottom: -30px; left: 35%;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(37,99,235,.1);
        }
        .hero-main { position: relative; z-index: 1; }
        .hero-breadcrumb-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 10px;
        }
        .hero-bc-link {
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,.5);
            text-decoration: none; transition: color .15s;
        }
        .hero-bc-link:hover { color: rgba(255,255,255,.8); text-decoration: none; }
        .hero-bc-sep { font-size: 11px; color: rgba(255,255,255,.3); }
        .hero-bc-cur { font-size: 11px; font-weight: 700; color: rgba(255,255,255,.7); }

        .hero-title-row { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
        .hero-ico {
            width: 50px; height: 50px; border-radius: 15px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; flex-shrink: 0;
        }
        .hero-title {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 800; color: #fff; margin: 0 0 4px; letter-spacing: -.3px;
        }
        .hero-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .hero-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 20px; padding: 4px 12px;
            font-size: 11px; font-weight: 600; color: rgba(255,255,255,.75);
        }
        .hero-chip i { font-size: 10px; }
        .hero-status { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 12px; }

        /* ── STATUS BADGE HERO ── */
        .hstat {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 20px;
            font-size: 11px; font-weight: 800; letter-spacing: .3px;
        }
        .hs-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .hstat-encours { background: rgba(251,191,36,.15); color: #fbbf24; border: 1px solid rgba(251,191,36,.25); }
        .hstat-cloture { background: rgba(52,211,153,.15); color: #34d399; border: 1px solid rgba(52,211,153,.25); }

        /* ── HERO ACTIONS ── */
        .hero-actions { position: relative; z-index: 1; display: flex; flex-direction: column; gap: 8px; align-items: flex-end; }
        .ha-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; border-radius: 11px;
            font-size: 12px; font-weight: 800; border: none;
            cursor: pointer; transition: all .2s; text-decoration: none;
            white-space: nowrap;
        }
        .ha-btn:hover { transform: translateY(-2px); text-decoration: none; }
        .hab-green {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; box-shadow: 0 4px 14px rgba(5,150,105,.35);
        }
        .hab-green:hover { box-shadow: 0 8px 22px rgba(5,150,105,.45); color: #fff; }
        .hab-red {
            background: rgba(220,38,38,.15);
            border: 1px solid rgba(220,38,38,.3);
            color: #fca5a5;
        }
        .hab-red:hover { background: rgba(220,38,38,.25); color: #fca5a5; }
        .hab-purple {
            background: rgba(147,51,234,.15);
            border: 1px solid rgba(147,51,234,.3);
            color: #d8b4fe;
        }
        .hab-purple:hover { background: rgba(147,51,234,.25); color: #d8b4fe; }

        /* ── KPI ROW ── */
        .inv-kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }
        .inv-kpi {
            background: #fff; border: 1px solid #e8edf5;
            border-radius: 16px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: 0 2px 12px rgba(11,15,26,.05);
            transition: transform .2s, box-shadow .2s;
        }
        .inv-kpi:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(11,15,26,.1); }
        .inv-kpi-ico {
            width: 46px; height: 46px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .ki-prod    { background: #eff6ff; color: #2563eb; }
        .ki-eco-pos { background: #ecfdf5; color: #059669; }
        .ki-eco-neg { background: #fef2f2; color: #dc2626; }
        .ki-eco-zer { background: #f8fafc; color: #94a3b8; }
        .ki-val     { background: #f5f3ff; color: #7c3aed; }
        .ki-date    { background: #fff7ed; color: #ea580c; }
        .inv-kpi-val { font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1; }
        .inv-kpi-val.neg { color: #dc2626; }
        .inv-kpi-val.pos { color: #059669; }
        .inv-kpi-val.zer { color: #94a3b8; }
        .inv-kpi-lbl { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }

        /* ── CARD ── */
        .inv-card {
            background: #fff; border: 1px solid #e8edf5;
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 2px 16px rgba(11,15,26,.05);
        }
        .inv-card-head {
            padding: 18px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        }
        .inv-card-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; font-weight: 800; color: #0f172a;
            text-transform: uppercase; letter-spacing: .3px;
        }
        .inv-card-ico {
            width: 30px; height: 30px; border-radius: 9px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: #fff;
        }
        .save-inline-btn {
            display: inline-flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; border: none; border-radius: 11px;
            padding: 9px 18px; font-size: 12px; font-weight: 800;
            cursor: pointer; transition: all .2s;
            box-shadow: 0 4px 14px rgba(5,150,105,.3);
        }
        .save-inline-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(5,150,105,.4); }

        /* ── TABLE ── */
        .inv-table { width: 100%; font-size: 12px; }
        .inv-table th {
            padding: 12px 18px;
            font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: .6px;
            color: #94a3b8; background: #f8fafc;
            border-bottom: 1px solid #e8edf5;
            white-space: nowrap;
        }
        .inv-table td {
            padding: 13px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #374151;
        }
        .inv-table tbody tr:last-child td { border-bottom: none; }
        .inv-table tbody tr { transition: background .1s; }
        .inv-table tbody tr:hover td { background: #fafbff; }

        /* ── PRODUIT CELL ── */
        .prod-cell { display: flex; align-items: center; gap: 10px; }
        .prod-ico {
            width: 34px; height: 34px; border-radius: 10px;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: #94a3b8; flex-shrink: 0;
        }
        .prod-name { font-size: 13px; font-weight: 700; color: #0f172a; }
        .prod-unite { font-size: 10px; color: #94a3b8; margin-top: 1px; }
        .cat-badge {
            display: inline-block; padding: 3px 10px; border-radius: 8px;
            font-size: 10px; font-weight: 700;
            background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;
        }

        /* ── STOCK CELLS ── */
        .stock-theo {
            font-size: 13px; font-weight: 700; color: #374151;
            display: flex; align-items: baseline; gap: 4px;
        }
        .stock-unite { font-size: 10px; color: #94a3b8; }

        /* ── INPUT PHYSIQUE ── */
        .stock-input {
            width: 110px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px; font-weight: 700; color: #0f172a;
            background: #f8fafc; outline: none;
            transition: all .2s;
            -moz-appearance: textfield;
        }
        .stock-input::-webkit-inner-spin-button,
        .stock-input::-webkit-outer-spin-button { -webkit-appearance: none; }
        .stock-input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .stock-input.changed {
            border-color: #10b981;
            background: #f0fdf4;
        }

        /* ── ÉCART CELLS ── */
        .ecart-val { font-size: 14px; font-weight: 900; }
        .ecart-val.pos { color: #059669; }
        .ecart-val.neg { color: #dc2626; }
        .ecart-val.zer { color: #94a3b8; }
        .ecart-sub { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        /* ── READONLY STOCK ── */
        .stock-ro { font-size: 13px; font-weight: 700; color: #374151; }

        /* ── TOTAUX ROW ── */
        .totaux-row td {
            background: #f8fafc !important;
            border-top: 2px solid #e2e8f0 !important;
            padding: 14px 18px !important;
        }
        .totaux-label {
            font-size: 12px; font-weight: 800;
            text-transform: uppercase; letter-spacing: .5px; color: #64748b;
        }
        .totaux-val { font-size: 15px; font-weight: 900; }

        /* ── FOOTER ── */
        .inv-card-footer {
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
            background: #fafbff;
        }
        .footer-info { font-size: 12px; color: #94a3b8; }
        .footer-ecart-total {
            display: flex; align-items: center; gap: 10px;
        }
        .footer-ecart-lbl { font-size: 12px; font-weight: 700; color: #64748b; }
        .footer-ecart-val { font-size: 18px; font-weight: 900; }

        /* ── RESPONSIVE ── */
        @media(max-width: 1024px) { .inv-kpi-row { grid-template-columns: repeat(2,1fr); } }
        @media(max-width: 768px) {
            .inv-show-hero { flex-direction: column; }
            .hero-actions { flex-direction: row; flex-wrap: wrap; align-items: flex-start; }
            .inv-kpi-row { grid-template-columns: 1fr 1fr; }
            .hide-sm { display: none !important; }
            .stock-input { width: 90px; }
        }

        /* ── ANIM ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        .inv-show-hero { animation: fadeUp .4s ease both; }
        .inv-kpi-row   { animation: fadeUp .4s .1s ease both; }
        .inv-card      { animation: fadeUp .4s .2s ease both; }
    </style>
@endpush

@section('content')
<div class="inv-show">

    {{-- ── HERO ── --}}
    <div class="inv-show-hero">
        <div class="hero-main">

            {{-- Breadcrumb mini --}}
            <div class="hero-breadcrumb-row">
                <a href="{{ route('inventaires.index') }}" class="hero-bc-link">
                    <i class="fas fa-clipboard-list" style="font-size:10px"></i> Inventaires
                </a>
                <span class="hero-bc-sep">/</span>
                <span class="hero-bc-cur">{{ Str::limit($inventaire->titre, 40) }}</span>
            </div>

            {{-- Titre --}}
            <div class="hero-title-row">
                <div class="hero-ico"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <h1 class="hero-title">{{ $inventaire->titre }}</h1>
                </div>
            </div>

            {{-- Meta chips --}}
            <div class="hero-meta">
                @if($inventaire->statut === 'en_cours')
                    <span class="hstat hstat-encours">
                        <span class="hs-dot"></span> En cours
                    </span>
                @else
                    <span class="hstat hstat-cloture">
                        <span class="hs-dot"></span> Clôturé
                    </span>
                @endif
                <span class="hero-chip">
                    <i class="fas fa-user"></i>
                    {{ $inventaire->user->nom_complet }}
                </span>
                <span class="hero-chip">
                    <i class="fas fa-calendar"></i>
                    {{ $inventaire->created_at->format('d/m/Y à H:i') }}
                </span>
                @if($inventaire->date_cloture)
                    <span class="hero-chip">
                        <i class="fas fa-lock"></i>
                        Clôturé le {{ \Carbon\Carbon::parse($inventaire->date_cloture)->format('d/m/Y') }}
                    </span>
                @endif
                <span class="hero-chip">
                    <i class="fas fa-boxes"></i>
                    {{ $inventaire->lignes->count() }} produits
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="hero-actions">
            @if($inventaire->statut === 'en_cours')
                <button form="formInventaire" class="ha-btn hab-green">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
                <form action="{{ route('inventaires.cloturer', $inventaire) }}"
                      method="POST"
                      onsubmit="return confirm('Clôturer définitivement cet inventaire ?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="ha-btn hab-red">
                        <i class="fas fa-lock"></i> Clôturer
                    </button>
                </form>
            @endif
            <a href="{{ route('inventaires.pdf', $inventaire) }}"
               class="ha-btn hab-purple" target="_blank">
                <i class="fas fa-file-pdf"></i> Exporter PDF
            </a>
            <a href="{{ route('inventaires.pdf_sans_theorique', $inventaire) }}"
                class="ha-btn hab-purple" target="_blank">
                <i class="fas fa-file-pdf"></i> PDF comptage
            </a>
        </div>
    </div>

    {{-- ── KPI ROW ── --}}
    @php
        $ecartTotal = $inventaire->total_ecart_valeur;
        $lignesPos  = $inventaire->lignes->where('ecart', '>', 0)->count();
        $lignesNeg  = $inventaire->lignes->where('ecart', '<', 0)->count();
    @endphp
    <div class="inv-kpi-row">
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-prod"><i class="fas fa-boxes"></i></div>
            <div>
                <div class="inv-kpi-val">{{ $inventaire->lignes->count() }}</div>
                <div class="inv-kpi-lbl">Produits comptés</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-eco-pos"><i class="fas fa-arrow-up"></i></div>
            <div>
                <div class="inv-kpi-val pos">{{ $lignesPos }}</div>
                <div class="inv-kpi-lbl">Excédents</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-eco-neg"><i class="fas fa-arrow-down"></i></div>
            <div>
                <div class="inv-kpi-val neg">{{ $lignesNeg }}</div>
                <div class="inv-kpi-lbl">Manquants</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico {{ $ecartTotal > 0 ? 'ki-eco-pos' : ($ecartTotal < 0 ? 'ki-eco-neg' : 'ki-eco-zer') }}">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div>
                <div class="inv-kpi-val {{ $ecartTotal > 0 ? 'pos' : ($ecartTotal < 0 ? 'neg' : 'zer') }}" id="kpi-ecart-total">
                    {{ ($ecartTotal >= 0 ? '+' : '') . number_format($ecartTotal, 0, ',', ' ') }}
                    <small style="font-size:.5em; font-weight:600">FCFA</small>
                </div>
                <div class="inv-kpi-lbl">Écart valeur total</div>
            </div>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="inv-card">

        <div class="inv-card-head">
            <h6 class="inv-card-title">
                <span class="inv-card-ico"><i class="fas fa-table"></i></span>
                Détail des lignes d'inventaire
            </h6>
            @if($inventaire->statut === 'en_cours')
                <button form="formInventaire" class="save-inline-btn">
                    <i class="fas fa-save"></i> Sauvegarder les quantités
                </button>
            @endif
        </div>

        <form id="formInventaire"
              action="{{ route('inventaires.update', $inventaire) }}"
              method="POST">
            @csrf @method('PUT')

            <div style="overflow-x:auto">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produit</th>
                            <th class="hide-sm">Catégorie</th>
                            <th style="text-align:right">Stock théorique</th>
                            <th>Stock physique</th>
                            <th style="text-align:right">Écart</th>
                            <th style="text-align:right">Valeur écart</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventaire->lignes as $i => $ligne)
                            <tr>
                                {{-- N° --}}
                                <td style="color:#94a3b8; font-size:11px; font-weight:700; width:40px">
                                    {{ $i + 1 }}
                                </td>

                                {{-- Produit --}}
                                <td>
                                    <div class="prod-cell">
                                        <div class="prod-ico">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <div class="prod-name">{{ $ligne->produit->libelle }}</div>
                                            <div class="prod-unite">{{ $ligne->produit->unite }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Catégorie --}}
                                <td class="hide-sm">
                                    <span class="cat-badge">
                                        {{ $ligne->produit->categorie->nom }}
                                    </span>
                                </td>

                                {{-- Stock théorique --}}
                                <td style="text-align:right">
                                    <div class="stock-theo" style="justify-content:flex-end">
                                        {{ number_format($ligne->stock_theorique, 2, ',', ' ') }}
                                        <span class="stock-unite">{{ $ligne->produit->unite }}</span>
                                    </div>
                                </td>

                                {{-- Stock physique --}}
                                <td>
                                    @if($inventaire->statut === 'en_cours')
                                        <input type="number"
                                               name="lignes[{{ $ligne->id }}][stock_physique]"
                                               class="stock-input"
                                               step="0.01" min="0"
                                               value="{{ $ligne->stock_physique }}"
                                               data-theorique="{{ $ligne->stock_theorique }}"
                                               data-prix="{{ $ligne->produit->prix_detail }}"
                                               data-ligne="{{ $ligne->id }}"
                                               placeholder="Saisir...">
                                    @else
                                        <span class="stock-ro">
                                            {{ number_format($ligne->stock_physique ?? 0, 2, ',', ' ') }}
                                            <small style="font-size:10px; color:#94a3b8">{{ $ligne->produit->unite }}</small>
                                        </span>
                                    @endif
                                </td>

                                {{-- Écart quantité --}}
                                <td style="text-align:right">
                                    <div class="ecart-qte-{{ $ligne->id }}">
                                        @php $e = $ligne->ecart; @endphp
                                        <div class="ecart-val {{ $e > 0 ? 'pos' : ($e < 0 ? 'neg' : 'zer') }}">
                                            {{ $e >= 0 ? '+' : '' }}{{ number_format($e, 2, ',', ' ') }}
                                        </div>
                                        <div class="ecart-sub">{{ $ligne->produit->unite }}</div>
                                    </div>
                                </td>

                                {{-- Valeur écart --}}
                                <td style="text-align:right">
                                    <div class="ecart-val-{{ $ligne->id }}">
                                        @php $ev = $ligne->ecart_valeur; @endphp
                                        <div class="ecart-val {{ $ev > 0 ? 'pos' : ($ev < 0 ? 'neg' : 'zer') }}">
                                            {{ $ev >= 0 ? '+' : '' }}{{ number_format($ev, 0, ',', ' ') }}
                                            <small style="font-size:.65em; font-weight:600">FCFA</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- Ligne totaux --}}
                    <tfoot>
                        <tr class="totaux-row">
                            <td colspan="5">
                                <span class="totaux-label">
                                    <i class="fas fa-sigma mr-1"></i>
                                    Total — {{ $inventaire->lignes->count() }} produits
                                </span>
                            </td>
                            <td style="text-align:right">
                                @php $totalQte = $inventaire->lignes->sum('ecart'); @endphp
                                <div class="totaux-val {{ $totalQte > 0 ? 'ecart-val pos' : ($totalQte < 0 ? 'ecart-val neg' : 'ecart-val zer') }}">
                                    {{ $totalQte >= 0 ? '+' : '' }}{{ number_format($totalQte, 2, ',', ' ') }}
                                </div>
                            </td>
                            <td style="text-align:right">
                                <div class="totaux-val {{ $ecartTotal > 0 ? 'ecart-val pos' : ($ecartTotal < 0 ? 'ecart-val neg' : 'ecart-val zer') }}" id="footer-ecart">
                                    {{ $ecartTotal >= 0 ? '+' : '' }}{{ number_format($ecartTotal, 0, ',', ' ') }}
                                    <small style="font-size:.55em; font-weight:600">FCFA</small>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>

        {{-- Footer --}}
        <div class="inv-card-footer">
            <span class="footer-info">
                <i class="fas fa-info-circle mr-1"></i>
                {{ $inventaire->statut === 'en_cours'
                    ? 'Saisissez les quantités physiques comptées puis sauvegardez.'
                    : 'Inventaire clôturé — lecture seule.' }}
            </span>
            <div class="footer-ecart-total">
                <span class="footer-ecart-lbl">Écart total :</span>
                <span class="footer-ecart-val {{ $ecartTotal > 0 ? 'ecart-val pos' : ($ecartTotal < 0 ? 'ecart-val neg' : 'ecart-val zer') }}">
                    {{ $ecartTotal >= 0 ? '+' : '' }}{{ number_format($ecartTotal, 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.stock-input').forEach(function(input) {
    input.addEventListener('input', function() {
        const theorique   = parseFloat(this.dataset.theorique) || 0;
        const prix        = parseFloat(this.dataset.prix) || 0;
        const physique    = parseFloat(this.value) || 0;
        const ligneId     = this.dataset.ligne;
        const ecart       = physique - theorique;
        const ecartValeur = ecart * prix;

        /* visuel input */
        this.classList.toggle('changed', this.value !== '');

        /* classe couleur */
        const cls = ecart > 0 ? 'pos' : (ecart < 0 ? 'neg' : 'zer');

        /* cellule écart qté */
        const unite = this.closest('tr').querySelector('.prod-unite').textContent.trim();
        document.querySelector('.ecart-qte-' + ligneId).innerHTML =
            `<div class="ecart-val ${cls}">${ecart >= 0 ? '+' : ''}${ecart.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2})}</div>`
            + `<div class="ecart-sub">${unite}</div>`;

        /* cellule valeur écart */
        const evCls = ecartValeur > 0 ? 'pos' : (ecartValeur < 0 ? 'neg' : 'zer');
        const evRnd = Math.round(ecartValeur);
        document.querySelector('.ecart-val-' + ligneId).innerHTML =
            `<div class="ecart-val ${evCls}">${evRnd >= 0 ? '+' : ''}${evRnd.toLocaleString('fr-FR')}<small style="font-size:.65em;font-weight:600"> FCFA</small></div>`;

        /* recalcul total global */
        recalcTotal();
    });
});

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.stock-input').forEach(function(inp) {
        const theorique = parseFloat(inp.dataset.theorique) || 0;
        const prix      = parseFloat(inp.dataset.prix) || 0;
        const physique  = parseFloat(inp.value) || 0;
        total += (physique - theorique) * prix;
    });
    const cls = total > 0 ? 'pos' : (total < 0 ? 'neg' : 'zer');
    const rnd = Math.round(total);
    const fmt = (rnd >= 0 ? '+' : '') + rnd.toLocaleString('fr-FR');

    const fe = document.getElementById('footer-ecart');
    if (fe) {
        fe.className = 'totaux-val ecart-val ' + cls;
        fe.innerHTML = fmt + '<small style="font-size:.55em;font-weight:600"> FCFA</small>';
    }
    const ke = document.getElementById('kpi-ecart-total');
    if (ke) {
        ke.className = 'inv-kpi-val ' + cls;
        ke.innerHTML = fmt + '<small style="font-size:.5em;font-weight:600"> FCFA</small>';
    }
}
</script>
@endpush