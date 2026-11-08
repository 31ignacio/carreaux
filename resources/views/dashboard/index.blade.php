@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
    <style>
        .dashboard {
            --dash-ink: #14263f;
            --dash-muted: #6b7a90;
            --dash-border: #e4ebf3;
            --dash-blue: #2d5a8e;
            --dash-blue-d: #173452;
            --dash-blue-bg: #e8f0f9;
            --dash-bg: #f4f7fb;
            --dash-shadow: 0 10px 30px rgba(23, 52, 82, .07);
            --dash-shadow-h: 0 18px 42px rgba(23, 52, 82, .14);

            color: var(--dash-ink);
        }

        .dashboard *,
        .dashboard *::before,
        .dashboard *::after {
            box-sizing: border-box;
        }

        /* ══ Apparition douce ══ */
        @keyframes dashRise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-greeting,
        .finance-card,
        .sales-card,
        .pd-card {
            animation: dashRise .5s ease both;
        }

        .finance-card:nth-child(2) { animation-delay: .06s; }
        .finance-card:nth-child(3) { animation-delay: .12s; }
        .finance-card:nth-child(4) { animation-delay: .18s; }

        /* ══ Bandeau d'accueil (compact) ══ */
        .dashboard-greeting {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
            padding: 14px 22px;
            color: #fff;
            border-radius: 14px;
            background: linear-gradient(125deg, #0f2740 0%, #173f66 55%, #2d6a9f 100%);
            box-shadow: 0 10px 26px rgba(23, 52, 82, .2);
        }

        .dashboard-greeting::before {
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

        .dashboard-greeting::after {
            content: '';
            position: absolute;
            left: -50px;
            bottom: -90px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(96, 165, 250, .2), transparent 70%);
            pointer-events: none;
        }

        .dashboard-greeting > div {
            position: relative;
            z-index: 1;
        }

        .dashboard-greeting h2,
        .dashboard-greeting h4 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.1rem, 2.2vw, 1.35rem);
            font-weight: 800;
            letter-spacing: -.01em;
            line-height: 1.25;
        }

        .dashboard-greeting p {
            margin: 2px 0 0;
            color: rgba(255, 255, 255, .72);
            font-size: .88rem;
            line-height: 1.4;
        }

        .dashboard-clock {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 14px;
            color: rgba(255, 255, 255, .9);
            font-size: .88rem;
            text-transform: capitalize;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            backdrop-filter: blur(8px);
        }

        .dashboard-clock #liveTime {
            padding-left: 10px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
            border-left: 1px solid rgba(255, 255, 255, .28);
        }

        /* ══ Titre de période ══ */
        .period-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 34px 0 16px;
        }

        .period-heading h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: var(--dash-ink);
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -.01em;
        }

        .period-heading h3 i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            margin: 0 !important;
            color: var(--dash-blue);
            background: var(--dash-blue-bg);
            border-radius: 11px;
            font-size: .95rem;
        }

        .period-heading span {
            padding: 7px 14px;
            color: var(--dash-muted);
            background: #fff;
            border: 1px solid var(--dash-border);
            border-radius: 999px;
            font-size: .88rem;
            font-weight: 600;
        }

        /* ══ Grilles ══ */
        .finance-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .finance-card,
        .pd-card,
        .sales-card {
            background: #fff;
            border: 1px solid var(--dash-border);
            border-radius: 18px;
            box-shadow: var(--dash-shadow);
        }

        /* ══ Cartes financières ══ */
        .finance-card {
            --tone: var(--dash-blue);
            --tone-bg: var(--dash-blue-bg);

            position: relative;
            overflow: hidden;
            min-width: 0;
            padding: 24px 22px 20px;
            transition: transform .22s, box-shadow .22s;
        }

        .finance-card::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 4px;
            background: var(--tone);
        }

        .finance-card::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: var(--tone-bg);
            opacity: .65;
            pointer-events: none;
        }

        .finance-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--dash-shadow-h);
        }

        .finance-card > * {
            position: relative;
            z-index: 1;
        }

        .finance-card.tone-amber  { --tone: #d97706; --tone-bg: #fef3c7; }
        .finance-card.tone-green  { --tone: #16a34a; --tone-bg: #dcfce7; }
        .finance-card.tone-red    { --tone: #e11d48; --tone-bg: #ffe4e6; }
        .finance-card.tone-violet { --tone: #7c3aed; --tone-bg: #ede9fe; }

        .finance-card-top {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
        }

        .finance-icon,
        .pd-card-ico {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 48px;
            height: 48px;
            color: var(--tone, var(--dash-blue));
            background: var(--tone-bg, var(--dash-blue-bg));
            border-radius: 14px;
            font-size: 1.15rem;
        }

        .finance-label {
            color: var(--dash-muted);
            font-size: .88rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .finance-value {
            overflow-wrap: anywhere;
            color: var(--dash-ink);
            font-size: clamp(1.7rem, 2.6vw, 2.15rem);
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .finance-unit {
            margin-top: 2px;
            color: var(--tone, var(--dash-muted));
            font-size: .85rem;
            font-weight: 800;
            letter-spacing: .06em;
        }

        .finance-note {
            min-height: 40px;
            margin-top: 10px;
            color: var(--dash-muted);
            font-size: .82rem;
            line-height: 1.5;
        }

        .finance-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 8px 15px;
            color: var(--tone, var(--dash-blue));
            background: var(--tone-bg, var(--dash-blue-bg));
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 800;
            text-decoration: none;
            transition: all .2s;
        }

        .finance-link i {
            transition: transform .2s;
        }

        .finance-link:hover {
            color: #fff;
            background: var(--tone, var(--dash-blue));
            text-decoration: none;
        }

        .finance-link:hover i {
            transform: translateX(4px);
        }

        .finance-link:focus-visible {
            outline: 2px solid var(--tone, var(--dash-blue));
            outline-offset: 3px;
        }

        /* ══ Cartes de ventes (dashboard.admin / simple) ══ */
        .sales-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .sales-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            color: var(--dash-ink);
            text-decoration: none;
            transition: transform .22s, box-shadow .22s, border-color .22s;
        }

        .sales-card:hover {
            transform: translateY(-4px);
            border-color: #c5d6e8;
            box-shadow: var(--dash-shadow-h);
            color: var(--dash-ink);
            text-decoration: none;
        }

        .sales-card .finance-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            font-size: 1.3rem;
        }

        .sales-grid > :nth-child(4n+1) .finance-icon { --tone: #2d5a8e; --tone-bg: #e8f0f9; }
        .sales-grid > :nth-child(4n+2) .finance-icon { --tone: #16a34a; --tone-bg: #dcfce7; }
        .sales-grid > :nth-child(4n+3) .finance-icon { --tone: #d97706; --tone-bg: #fef3c7; }
        .sales-grid > :nth-child(4n+4) .finance-icon { --tone: #7c3aed; --tone-bg: #ede9fe; }

        .sales-value {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.15;
        }

        .sales-label {
            margin-top: 3px;
            color: var(--dash-muted);
            font-size: .88rem;
            font-weight: 600;
        }

        /* ══ Panneaux (graphique + top produits) ══ */
        .dashboard-panels {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
            gap: 20px;
        }

        .pd-card {
            min-width: 0;
            overflow: hidden;
        }

        .pd-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--dash-border);
            background: linear-gradient(180deg, #fbfdff, #fff);
        }

        .pd-card-ico {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            font-size: 1rem;
        }

        .pd-card-title {
            margin: 0;
            color: var(--dash-ink);
            font-size: 1.02rem;
            font-weight: 800;
        }

        .chart-wrap {
            min-height: 280px;
            padding: 18px;
        }

        .top-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 22px;
            border-bottom: 1px solid #eef2f7;
            transition: background .18s;
        }

        .top-item:hover {
            background: #f8fbff;
        }

        .top-item:last-child {
            border-bottom: 0;
        }

        .top-rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 32px;
            height: 32px;
            color: var(--dash-blue);
            background: var(--dash-blue-bg);
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 800;
        }

        .top-item:nth-child(1) .top-rank { color: #fff; background: linear-gradient(135deg, #f59e0b, #d97706); }
        .top-item:nth-child(2) .top-rank { color: #fff; background: linear-gradient(135deg, #94a3b8, #64748b); }
        .top-item:nth-child(3) .top-rank { color: #fff; background: linear-gradient(135deg, #d6915a, #b0693a); }

        .top-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            color: var(--dash-ink);
            font-size: .98rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .top-qty {
            flex: 0 0 auto;
            padding: 4px 11px;
            color: var(--dash-muted);
            background: var(--dash-bg);
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
        }

        /* ══ Responsive ══ */
        @media (max-width: 1199px) {
            .finance-grid,
            .sales-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .dashboard-panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .dashboard-greeting {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
                padding: 14px 16px;
            }

            .dashboard-clock {
                width: 100%;
                justify-content: space-between;
            }

            .finance-grid,
            .sales-grid {
                grid-template-columns: 1fr;
            }

            .finance-card {
                padding: 20px 18px 18px;
            }

            .period-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .dashboard-greeting,
            .finance-card,
            .sales-card,
            .pd-card {
                animation: none;
            }

            .finance-card,
            .sales-card,
            .finance-link,
            .finance-link i {
                transition: none;
            }
        }

        /* Interactions discrètes pour garder le tableau de bord réactif. */
        .dashboard-greeting,
        .finance-card,
        .sales-card,
        .pd-card {
            animation-duration: .24s;
        }

        .finance-card,
        .sales-card {
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(23, 52, 82, .06);
            transition: transform .16s ease, box-shadow .16s ease;
        }

        .finance-card:hover,
        .sales-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 52, 82, .10);
        }

        @media (prefers-reduced-motion: reduce) {
            .dashboard-greeting,
            .finance-card,
            .sales-card,
            .pd-card { animation: none; }
            .finance-card,
            .sales-card { transition: none; }
        }

        .dashboard-greeting {
            color: #123047;
            background: linear-gradient(115deg, #e7f7f5, #eef4ff);
            border: 1px solid #d9ecea;
            box-shadow: 0 6px 18px rgba(23, 52, 82, .06);
        }

        .dashboard-greeting h2,
        .dashboard-greeting h4 { color: #123047; }

        .dashboard-greeting p { color: #526b7d; }

        .dashboard-clock {
            color: #31536b;
            background: rgba(255, 255, 255, .72);
            border-color: rgba(49, 83, 107, .12);
            backdrop-filter: none;
        }

        .dashboard-clock #liveTime {
            color: #123047;
            border-color: rgba(49, 83, 107, .18);
        }

        .period-heading { margin-top: 26px; }
    </style>
@endpush

@section('content')
    <main class="dashboard">
        <section class="dashboard-greeting" aria-label="Accueil">
            <div>
                <h4>Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h4>
                <p>Un aperçu clair de l’activité et des finances de votre entreprise.</p>
            </div>
            <div class="dashboard-clock">
                <div id="liveDate"></div>
                <div id="liveTime"></div>
            </div>
        </section>

        @if (auth()->user()->isAdmin())
            @include('dashboard.admin')
        @else
            @include('dashboard.simple')
        @endif

        @unless (auth()->user()->isMagasinier())
        <section aria-labelledby="financePeriodTitle">
            <div class="period-heading">
                <h3 id="financePeriodTitle"><i class="far fa-calendar-alt"></i> Situation financière du mois</h3>
                <span>Du {{ $periodeMoisDebut->format('d/m/Y') }} au {{ $periodeMoisFin->format('d/m/Y') }}</span>
            </div>

            <div class="finance-grid">
                <article class="finance-card tone-amber">
                    <div class="finance-card-top">
                        <span class="finance-icon"><i class="fas fa-handshake"></i></span>
                        <span class="finance-label">Reste dû sur les crédits créés ce mois</span>
                    </div>
                    <div class="finance-value">{{ number_format($creditsRestantsMois, 0, ',', ' ') }}</div>
                    <div class="finance-unit">FCFA</div>
                    <div class="finance-note">Solde de ces seuls bons après déduction de tous leurs règlements enregistrés.</div>
                    <a class="finance-link" href="{{ route('credits.index') }}">Suivre les crédits <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="finance-card tone-green">
                    <div class="finance-card-top">
                        <span class="finance-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <span class="finance-label">Remboursements reçus</span>
                    </div>
                    <div class="finance-value">{{ number_format($remboursementsMois, 0, ',', ' ') }}</div>
                    <div class="finance-unit">FCFA</div>
                    <div class="finance-note">Remboursements enregistrés ce mois.</div>
                    <a class="finance-link" href="{{ route('remboursements.index') }}">Voir l’historique <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="finance-card tone-red">
                    <div class="finance-card-top">
                        <span class="finance-icon"><i class="fas fa-receipt"></i></span>
                        <span class="finance-label">Dépenses nettes</span>
                    </div>
                    <div class="finance-value">{{ number_format($depensesNettesMois, 0, ',', ' ') }}</div>
                    <div class="finance-unit">FCFA</div>
                    <div class="finance-note">Dépenses datées ce mois, hors dépenses annulées.</div>
                    <a class="finance-link" href="{{ route('depenses.index') }}">Gérer les dépenses <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="finance-card tone-violet">
                    <div class="finance-card-top">
                        <span class="finance-icon"><i class="fas fa-wallet"></i></span>
                        <span class="finance-label">Mouvements nets d’avances</span>
                    </div>
                    <div class="finance-value">{{ number_format($mouvementsAvancesNetsMois, 0, ',', ' ') }}</div>
                    <div class="finance-unit">FCFA</div>
                    <div class="finance-note">Dépôts moins utilisations historisés ; les soldes antérieurs au suivi ne sont pas reconstituables.</div>
                    <a class="finance-link" href="{{ route('clients.index') }}">Voir les clients <i class="fas fa-arrow-right"></i></a>
                </article>
            </div>
        </section>
        @endunless
    </main>

    @push('scripts')
        <script>
            function updateClock() {
                const now = new Date();
                document.getElementById('liveDate').textContent = now.toLocaleDateString('fr-FR', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('liveTime').textContent = now.toLocaleTimeString('fr-FR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            updateClock();
            setInterval(updateClock, 30000);

            const chartElement = document.getElementById('caChart');
            if (chartElement) {
                const caData = @json($caParJour);
                const labels = caData.map(item => item.date);
                const values = caData.map(item => Number(item.total));
                const maxVal = values.length ? Math.max(...values) : 0;

                new Chart(chartElement, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'CA (FCFA)',
                            data: values,
                            backgroundColor: values.map(value =>
                                value === maxVal ? '#2d5a8e' : 'rgba(45, 90, 142, .45)'
                            ),
                            borderWidth: 0,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => ' ' + new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA'
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#6b7a90', font: { size: 12 } },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#edf1f5' },
                                border: { display: false },
                                ticks: {
                                    color: '#6b7a90',
                                    font: { size: 12 },
                                    callback: value => new Intl.NumberFormat('fr-FR', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value)
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
