{{-- resources/views/rapports/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapports & Statistiques')
@section('page-title', 'Rapports & Statistiques')

@section('breadcrumb')
    <li class="breadcrumb-item active">Rapports</li>
@endsection

@push('styles')
    <style>
        .rp-wrap {
            --rp-ink: #14263f;
            --rp-muted: #6b7a90;
            --rp-border: #e4ebf3;
            --rp-soft: #f4f7fb;
            --rp-shadow: 0 1px 2px rgba(20, 38, 63, .04), 0 8px 24px rgba(20, 38, 63, .06);
            --rp-shadow-h: 0 2px 4px rgba(20, 38, 63, .05), 0 18px 40px rgba(20, 38, 63, .13);

            color: var(--rp-ink);
            font-variant-numeric: tabular-nums;
        }

        .rp-wrap *,
        .rp-wrap *::before,
        .rp-wrap *::after {
            box-sizing: border-box;
        }

        /* ══ Animations ══ */
        @keyframes rpRise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rp-hero,
        .rp-section-lbl,
        .rp-card {
            animation: rpRise .45s ease both;
        }

        .rp-section-lbl { animation-delay: .06s; }
        .rp-card:nth-child(1) { animation-delay: .1s; }
        .rp-card:nth-child(2) { animation-delay: .16s; }
        .rp-card:nth-child(3) { animation-delay: .22s; }
        .rp-card:nth-child(4) { animation-delay: .28s; }

        /* ══ Bandeau compact ══ */
        .rp-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 26px;
            padding: 14px 22px;
            color: #fff;
            border-radius: 14px;
            background: linear-gradient(125deg, #0f2740 0%, #173f66 55%, #2d6a9f 100%);
            box-shadow: 0 10px 26px rgba(23, 52, 82, .2);
        }

        .rp-hero::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -110px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .1);
            box-shadow: 0 0 0 28px rgba(255, 255, 255, .03);
            pointer-events: none;
        }

        .rp-hero::after {
            content: '';
            position: absolute;
            left: 30%;
            bottom: -90px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(96, 165, 250, .2), transparent 70%);
            pointer-events: none;
        }

        .rp-hero-left,
        .rp-hero-right {
            position: relative;
            z-index: 1;
        }

        .rp-hero-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: #fff;
            font-size: clamp(1.1rem, 2.2vw, 1.35rem);
            font-weight: 800;
            letter-spacing: -.01em;
            line-height: 1.25;
        }

        .rp-hero-title i {
            opacity: .75;
        }

        .rp-hero-sub {
            margin: 2px 0 0;
            color: rgba(255, 255, 255, .72);
            font-size: .88rem;
            line-height: 1.4;
        }

        .rp-hero-right {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .rp-hero-stat {
            display: flex;
            align-items: baseline;
            gap: 9px;
            padding: 7px 14px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            backdrop-filter: blur(8px);
        }

        .rp-hero-stat-val {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .rp-hero-stat-lbl {
            color: rgba(255, 255, 255, .75);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* ══ Titre de section ══ */
        .rp-section-lbl {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 14px;
            color: var(--rp-muted);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .rp-section-lbl::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--rp-border);
        }

        /* ══ Grille de rapports ══ */
        .rp-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .rp-card {
            --tone: #2d5a8e;
            --tone-bg: #e8f0f9;
            --tone-d: #23497a;

            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--rp-border);
            border-radius: 18px;
            box-shadow: var(--rp-shadow);
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }

        .rp-card::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 4px;
            background: var(--tone);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s;
        }

        .rp-card:hover {
            transform: translateY(-5px);
            border-color: #d3dce8;
            box-shadow: var(--rp-shadow-h);
        }

        .rp-card:hover::before {
            transform: scaleX(1);
        }

        .rpc-ventes { --tone: #2563eb; --tone-bg: #e8f0ff; --tone-d: #1d4ed8; }
        .rpc-stock  { --tone: #d97706; --tone-bg: #fef3c7; --tone-d: #b45309; }
        .rpc-ca     { --tone: #059669; --tone-bg: #d1fae5; --tone-d: #047857; }
        .rpc-pdf    { --tone: #dc2626; --tone-bg: #fee2e2; --tone-d: #b91c1c; }

        .rp-card-top {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1;
            padding: 24px 24px 18px;
        }

        .rp-ico-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            margin-bottom: 18px;
            color: var(--tone);
            background: var(--tone-bg);
            border-radius: 16px;
            font-size: 1.45rem;
            transition: transform .3s, color .3s, background .3s;
        }

        .rp-card:hover .rp-ico-wrap {
            transform: scale(1.06) rotate(-4deg);
            color: #fff;
            background: var(--tone);
        }

        .rp-card-name {
            margin-bottom: 8px;
            color: var(--rp-ink);
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -.01em;
        }

        .rp-card-desc {
            flex: 1;
            color: var(--rp-muted);
            font-size: .93rem;
            line-height: 1.6;
        }

        .rp-badge-export {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            padding: 4px 11px;
            color: var(--tone-d);
            background: var(--tone-bg);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 800;
        }

        /* ══ Pied de carte + bouton ══ */
        .rp-card-foot {
            padding: 0 24px 24px;
        }

        .rp-card-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            padding: 12px 14px 12px 18px;
            color: #fff !important;
            background: var(--tone);
            border: 0;
            border-radius: 12px;
            box-shadow: 0 8px 18px color-mix(in srgb, var(--tone) 30%, transparent);
            font-size: .95rem;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, box-shadow .2s;
        }

        .rp-card-btn .btn-lbl {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .rp-card-btn .btn-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, .2);
            border-radius: 50%;
            font-size: .72rem;
            transition: transform .25s, background .25s;
        }

        .rp-card-btn:hover {
            background: var(--tone-d);
            box-shadow: 0 12px 24px color-mix(in srgb, var(--tone) 40%, transparent);
            text-decoration: none;
        }

        .rp-card-btn:hover .btn-arrow {
            background: rgba(255, 255, 255, .3);
            transform: translateX(3px);
        }

        .rp-card-btn:focus-visible {
            outline: 3px solid var(--tone-bg);
            outline-offset: 2px;
        }

        /* ══ Responsive ══ */
        @media (max-width: 992px) {
            .rp-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .rp-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px 16px;
            }

            .rp-hero-right {
                width: 100%;
            }

            .rp-hero-stat {
                flex: 1 1 auto;
                justify-content: space-between;
            }

            .rp-grid {
                grid-template-columns: 1fr;
            }

            .rp-card-top {
                padding: 20px 20px 16px;
            }

            .rp-card-foot {
                padding: 0 20px 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .rp-hero,
            .rp-section-lbl,
            .rp-card {
                animation: none;
            }

            .rp-card,
            .rp-card::before,
            .rp-ico-wrap,
            .rp-card-btn,
            .rp-card-btn .btn-arrow {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="rp-wrap">

        {{-- ── HERO ── --}}
        <div class="rp-hero">
            <div class="rp-hero-left">
                <h1 class="rp-hero-title">
                    <i class="fas fa-chart-bar"></i>
                    Rapports &amp; Statistiques
                </h1>
                <p class="rp-hero-sub">Analysez les performances, exportez les données</p>
            </div>
            <div class="rp-hero-right">
                <div class="rp-hero-stat">
                    <div class="rp-hero-stat-val">{{ \App\Models\Bon::whereDate('created_at', today())->count() }}</div>
                    <div class="rp-hero-stat-lbl">Ventes aujourd'hui</div>
                </div>
                <div class="rp-hero-stat">
                    <div class="rp-hero-stat-val">
                        {{ number_format(\App\Models\Bon::where('statut', 'payee')->whereDate('created_at', today())->sum('total') / 1000, 0) }}K
                    </div>
                    <div class="rp-hero-stat-lbl">CA du jour (FCFA)</div>
                </div>
            </div>
        </div>

        {{-- ── RAPPORTS ANALYTIQUES ── --}}
        <div class="rp-section-lbl">
            <i class="fas fa-chart-line" style="font-size:.7rem"></i> Rapports analytiques
        </div>

        <div class="rp-grid">

            {{-- Ventes --}}
            <div class="rp-card rpc-ventes">
                <div class="rp-card-top">
                    <div class="rp-ico-wrap"><i class="fas fa-chart-line"></i></div>
                    <div class="rp-card-name">Rapport des Ventes</div>
                    <div class="rp-card-desc">
                        Analyse du chiffre d'affaires, évolution journalière, top produits vendus et performances par
                        caissier.
                    </div>
                </div>
                <div class="rp-card-foot">
                    <a href="{{ route('rapports.ventes') }}" class="rp-card-btn">
                        <span class="btn-lbl"><i class="fas fa-eye"></i> Voir le rapport</span>
                        <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Stock --}}
            <div class="rp-card rpc-stock">
                <div class="rp-card-top">
                    <div class="rp-ico-wrap"><i class="fas fa-warehouse"></i></div>
                    <div class="rp-card-name">Rapport de Stock</div>
                    <div class="rp-card-desc">
                        État actuel des stocks, valorisation par catégorie, produits en rupture et alertes de
                        réapprovisionnement.
                    </div>
                </div>
                <div class="rp-card-foot">
                    <a href="{{ route('rapports.stock') }}" class="rp-card-btn">
                        <span class="btn-lbl"><i class="fas fa-eye"></i> Voir le rapport</span>
                        <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Financier --}}
            <div class="rp-card rpc-ca">
                <div class="rp-card-top">
                    <div class="rp-ico-wrap"><i class="fas fa-coins"></i></div>
                    <div class="rp-card-name">Rapport financier</div>
                    <div class="rp-card-desc">
                        Ventes, encaissements de remboursements et dépenses, filtrés par période et catégorie.
                    </div>
                </div>
                <div class="rp-card-foot">
                    <a href="{{ route('rapports.finances') }}" class="rp-card-btn">
                        <span class="btn-lbl"><i class="fas fa-eye"></i> Voir le rapport</span>
                        <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Export PDF Stock --}}
            <div class="rp-card rpc-pdf">
                <div class="rp-card-top">
                    <div class="rp-ico-wrap"><i class="fas fa-file-pdf"></i></div>
                    <div class="rp-card-name">Export Stock PDF</div>
                    <div class="rp-card-desc">
                        Générez et téléchargez instantanément l'état complet du stock au format PDF.
                    </div>
                    <span class="rp-badge-export">
                        <i class="fas fa-download" style="font-size:.7rem"></i> Téléchargement direct
                    </span>
                </div>
                <div class="rp-card-foot">
                    <a href="{{ route('rapports.export-pdf') }}?type=stock" class="rp-card-btn" target="_blank">
                        <span class="btn-lbl"><i class="fas fa-download"></i> Télécharger PDF</span>
                        <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection