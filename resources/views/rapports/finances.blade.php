@extends('layouts.app')
@section('title', 'Rapport financier')
@section('page-title', 'Rapport financier')

@push('styles')
    <style>
        .rf {
            --rf-ink: #14263f;
            --rf-muted: #6b7a90;
            --rf-border: #e4ebf3;
            --rf-soft: #f4f7fb;
            --rf-blue: #2d5a8e;
            --rf-blue-bg: #e8f0f9;
            --rf-shadow: 0 1px 2px rgba(20, 38, 63, .04), 0 8px 24px rgba(20, 38, 63, .06);
            --rf-shadow-h: 0 2px 4px rgba(20, 38, 63, .05), 0 16px 36px rgba(20, 38, 63, .12);

            color: var(--rf-ink);
            font-variant-numeric: tabular-nums;
        }

        .rf *,
        .rf *::before,
        .rf *::after {
            box-sizing: border-box;
        }

        @keyframes rfRise {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rf-head,
        .rf-filters,
        .rf-kpi,
        .rf-card {
            animation: rfRise .45s ease both;
        }

        .rf-kpi:nth-child(2) { animation-delay: .06s; }
        .rf-kpi:nth-child(3) { animation-delay: .12s; }

        /* ══ En-tête ══ */
        .rf-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .rf-head h2 {
            display: flex;
            align-items: center;
            gap: 11px;
            margin: 0;
            color: var(--rf-ink);
            font-size: clamp(1.25rem, 2.6vw, 1.6rem);
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .rf-head h2 i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--rf-blue);
            background: var(--rf-blue-bg);
            border-radius: 12px;
            font-size: 1rem;
        }

        .rf-period {
            padding: 7px 14px;
            color: var(--rf-muted);
            background: #fff;
            border: 1px solid var(--rf-border);
            border-radius: 999px;
            font-size: .88rem;
            font-weight: 600;
        }

        /* ══ Filtres ══ */
        .rf-filters {
            margin-bottom: 18px;
            padding: 16px 18px;
            background: #fff;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            box-shadow: var(--rf-shadow);
        }

        .rf-form {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 14px;
            margin: 0;
        }

        .rf-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }

        .rf-field label {
            margin: 0;
            color: var(--rf-muted);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .rf-field .form-control {
            height: 44px;
            min-width: 170px;
            margin: 0 !important;
            color: var(--rf-ink);
            background: var(--rf-soft);
            border: 1.5px solid var(--rf-border);
            border-radius: 11px;
            font-size: .98rem;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .rf-field .form-control:focus {
            background: #fff;
            border-color: var(--rf-blue);
            box-shadow: 0 0 0 4px rgba(45, 90, 142, .13);
        }

        .rf-submit {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            height: 44px;
            padding: 0 24px;
            border: 0;
            border-radius: 11px;
            font-size: .98rem;
            font-weight: 800;
            background: var(--rf-blue);
            box-shadow: 0 8px 18px rgba(45, 90, 142, .28);
            transition: transform .2s, box-shadow .2s, background .2s;
        }

        .rf-submit:hover {
            background: #23497a;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(45, 90, 142, .36);
        }

        /* ══ Cartes chiffres clés ══ */
        .rf-kpis {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .rf-kpi {
            --tone: var(--rf-blue);
            --tone-bg: var(--rf-blue-bg);

            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            padding: 20px 22px;
            background: #fff;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            box-shadow: var(--rf-shadow);
            transition: transform .22s, box-shadow .22s;
        }

        .rf-kpi::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--tone);
        }

        .rf-kpi:hover {
            transform: translateY(-4px);
            box-shadow: var(--rf-shadow-h);
        }

        .rf-kpi.tone-blue  { --tone: #2d5a8e; --tone-bg: #e8f0f9; }
        .rf-kpi.tone-green { --tone: #16a34a; --tone-bg: #dcfce7; }
        .rf-kpi.tone-red   { --tone: #e11d48; --tone-bg: #ffe4e6; }

        .rf-kpi-ico {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 54px;
            height: 54px;
            color: var(--tone);
            background: var(--tone-bg);
            border-radius: 16px;
            font-size: 1.3rem;
        }

        .rf-kpi-body {
            min-width: 0;
        }

        .rf-kpi-label {
            margin: 0 0 4px;
            color: var(--rf-muted);
            font-size: .9rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .rf-kpi-value {
            overflow-wrap: anywhere;
            color: var(--rf-ink);
            font-size: clamp(1.5rem, 2.4vw, 2rem);
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .rf-kpi-unit {
            margin-left: 6px;
            color: var(--tone);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .06em;
        }

        /* ══ Cartes tableaux ══ */
        .rf-cols {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .rf-card {
            min-width: 0;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            box-shadow: var(--rf-shadow);
        }

        .rf-cols + .rf-card {
            margin-top: 0;
        }

        .rf-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--rf-border);
        }

        .rf-card-ico {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 36px;
            height: 36px;
            color: var(--tone, var(--rf-blue));
            background: var(--tone-bg, var(--rf-blue-bg));
            border-radius: 11px;
            font-size: .92rem;
        }

        .rf-card-head h3 {
            margin: 0;
            color: var(--rf-ink);
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -.01em;
        }

        .rf-table-wrap {
            overflow-x: auto;
        }

        .rf-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            font-size: .95rem;
        }

        .rf-table thead th {
            padding: 12px 20px;
            color: var(--rf-muted);
            background: var(--rf-soft);
            border: 0;
            border-bottom: 1px solid var(--rf-border);
            font-size: .74rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .rf-table tbody td {
            padding: 14px 20px;
            color: var(--rf-ink);
            border: 0;
            border-bottom: 1px solid #eef2f7;
            vertical-align: middle;
        }

        .rf-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .rf-table tbody tr {
            transition: background .15s;
        }

        .rf-table tbody tr:hover {
            background: #f8fbff;
        }

        .rf-table .num {
            text-align: right;
            white-space: nowrap;
            font-weight: 800;
        }

        .rf-table th.num {
            text-align: right;
        }

        .rf-table .muted {
            color: var(--rf-muted);
            white-space: nowrap;
        }

        .rf-table tr.is-cancelled td {
            opacity: .55;
        }

        .rf-table tr.is-cancelled .rf-amount {
            text-decoration: line-through;
        }

        /* Part de catégorie */
        .rf-cat {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 120px;
        }

        .rf-cat-name {
            font-weight: 700;
        }

        .rf-bar {
            height: 6px;
            overflow: hidden;
            background: var(--rf-soft);
            border-radius: 999px;
        }

        .rf-bar span {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, #fb7185, #e11d48);
            border-radius: 999px;
        }

        /* Badges */
        .rf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .rf-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            background: currentColor;
            border-radius: 50%;
        }

        .rf-badge.ok  { color: #15803d; background: #dcfce7; }
        .rf-badge.off { color: #64748b; background: #eef2f7; }

        .rf-pill {
            display: inline-block;
            padding: 3px 10px;
            color: var(--rf-blue);
            background: var(--rf-blue-bg);
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        /* Messages vides */
        .rf-empty {
            padding: 34px 20px !important;
            text-align: center;
            color: var(--rf-muted) !important;
        }

        .rf-empty i {
            display: block;
            margin-bottom: 8px;
            color: #b9c6d6;
            font-size: 1.6rem;
        }

        /* ══ Responsive ══ */
        @media (max-width: 991px) {
            .rf-cols {
                grid-template-columns: 1fr;
            }

            .rf-kpis {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .rf-form {
                flex-direction: column;
                align-items: stretch;
            }

            .rf-field .form-control {
                width: 100%;
                min-width: 0;
            }

            .rf-submit {
                justify-content: center;
                width: 100%;
            }

            .rf-table thead th,
            .rf-table tbody td {
                padding: 12px 14px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .rf-head,
            .rf-filters,
            .rf-kpi,
            .rf-card {
                animation: none;
            }

            .rf-kpi,
            .rf-submit {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="rf">

        {{-- ══ En-tête ══ --}}
        <div class="rf-head">
            <h2><i class="fas fa-chart-line"></i> Rapport financier</h2>
            <span class="rf-period"><i class="far fa-calendar-alt mr-1"></i>
                Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
            </span>
        </div>

        {{-- ══ Filtres ══ --}}
        <div class="rf-filters">
            <form method="GET" class="rf-form">
                <div class="rf-field">
                    <label for="rf-debut">Du</label>
                    <input id="rf-debut" class="form-control" type="date" name="date_debut"
                        value="{{ $dateDebut }}" required>
                </div>

                <div class="rf-field">
                    <label for="rf-fin">Au</label>
                    <input id="rf-fin" class="form-control" type="date" name="date_fin"
                        value="{{ $dateFin }}" required>
                </div>

                <div class="rf-field">
                    <label for="rf-cat">Catégorie</label>
                    <select id="rf-cat" class="form-control" name="categorie">
                        <option value="">Toutes catégories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($categorie === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary rf-submit">
                    <i class="fas fa-filter"></i> Appliquer
                </button>
            </form>
        </div>

        {{-- ══ Chiffres clés ══ --}}
        <div class="rf-kpis">
            <div class="rf-kpi tone-blue">
                <span class="rf-kpi-ico"><i class="fas fa-file-invoice-dollar"></i></span>
                <div class="rf-kpi-body">
                    <p class="rf-kpi-label">Ventes facturées</p>
                    <div>
                        <span class="rf-kpi-value">{{ number_format($ventes, 0, ',', ' ') }}</span>
                        <span class="rf-kpi-unit">FCFA</span>
                    </div>
                </div>
            </div>

            <div class="rf-kpi tone-green">
                <span class="rf-kpi-ico"><i class="fas fa-money-bill-wave"></i></span>
                <div class="rf-kpi-body">
                    <p class="rf-kpi-label">Remboursements encaissés</p>
                    <div>
                        <span class="rf-kpi-value">{{ number_format($totalRemboursements, 0, ',', ' ') }}</span>
                        <span class="rf-kpi-unit">FCFA</span>
                    </div>
                </div>
            </div>

            <div class="rf-kpi tone-red">
                <span class="rf-kpi-ico"><i class="fas fa-receipt"></i></span>
                <div class="rf-kpi-body">
                    <p class="rf-kpi-label">Dépenses actives</p>
                    <div>
                        <span class="rf-kpi-value">{{ number_format($totalDepenses, 0, ',', ' ') }}</span>
                        <span class="rf-kpi-unit">FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ Catégories + Remboursements ══ --}}
        <div class="rf-cols">
            <div class="rf-card" style="--tone:#e11d48;--tone-bg:#ffe4e6">
                <div class="rf-card-head">
                    <span class="rf-card-ico"><i class="fas fa-tags"></i></span>
                    <h3>Dépenses par catégorie</h3>
                </div>
                <div class="rf-table-wrap">
                    <table class="rf-table">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th class="num">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depensesParCategorie as $ligne)
                                <tr>
                                    <td>
                                        <div class="rf-cat">
                                            <span class="rf-cat-name">{{ $ligne->categorie }}</span>
                                            <div class="rf-bar">
                                                <span
                                                    style="width: {{ $totalDepenses > 0 ? min(100, round(($ligne->total / $totalDepenses) * 100)) : 0 }}%"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="num">{{ number_format($ligne->total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="rf-empty">
                                        <i class="far fa-folder-open"></i>
                                        Aucune dépense.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rf-card" style="--tone:#16a34a;--tone-bg:#dcfce7">
                <div class="rf-card-head">
                    <span class="rf-card-ico"><i class="fas fa-hand-holding-usd"></i></span>
                    <h3>Remboursements reçus</h3>
                </div>
                <div class="rf-table-wrap">
                    <table class="rf-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Mode</th>
                                <th class="num">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($remboursements as $r)
                                <tr>
                                    <td class="muted">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $r->client->nom_complet }}</td>
                                    <td><span class="rf-pill">{{ $r->mode_paiement_label }}</span></td>
                                    <td class="num">{{ number_format($r->montant, 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="rf-empty">
                                        <i class="far fa-folder-open"></i>
                                        Aucun encaissement.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ Dépenses de la période ══ --}}
        <div class="rf-card" style="--tone:#2d5a8e;--tone-bg:#e8f0f9">
            <div class="rf-card-head">
                <span class="rf-card-ico"><i class="fas fa-list-ul"></i></span>
                <h3>Dépenses de la période</h3>
            </div>
            <div class="rf-table-wrap">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Désignation</th>
                            <th>Catégorie</th>
                            <th class="num">Montant</th>
                            <th>État</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depenses as $d)
                            <tr class="{{ $d->annulee_at ? 'is-cancelled' : '' }}">
                                <td class="muted">{{ $d->date_depense->format('d/m/Y') }}</td>
                                <td>{{ $d->designation }}</td>
                                <td><span class="rf-pill">{{ $d->categorie }}</span></td>
                                <td class="num"><span class="rf-amount">{{ number_format($d->montant, 0, ',', ' ') }} FCFA</span></td>
                                <td>
                                    @if ($d->annulee_at)
                                        <span class="rf-badge off">Annulée</span>
                                    @else
                                        <span class="rf-badge ok">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="rf-empty">
                                    <i class="far fa-folder-open"></i>
                                    Aucune dépense.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection