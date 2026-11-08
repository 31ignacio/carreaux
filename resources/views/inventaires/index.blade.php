{{-- resources/views/inventaires/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventaires')
@section('page-title', 'Gestion des Inventaires')

@section('breadcrumb')
    <li class="breadcrumb-item active">Inventaires</li>
@endsection

@push('styles')
    <style>
        .inv-wrap { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

        /* ── HERO ── */
        .inv-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 22px;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            position: relative; overflow: hidden;
            box-shadow: 0 10px 40px rgba(15,23,42,.2);
        }
        .inv-hero::before {
            content: '';
            position: absolute; top: -50px; right: -50px;
            width: 240px; height: 240px; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .inv-hero::after {
            content: '';
            position: absolute; bottom: -30px; left: 35%;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(37,99,235,.1);
        }
        .inv-hero-left { position: relative; z-index: 1; display: flex; align-items: center; gap: 18px; }
        .inv-hero-ico {
            width: 54px; height: 54px; border-radius: 16px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff; flex-shrink: 0;
        }
        .inv-hero-title {
            font-size: clamp(1.1rem, 2.5vw, 1.45rem);
            font-weight: 800; color: #fff; margin: 0 0 4px; letter-spacing: -.3px;
        }
        .inv-hero-sub { font-size: 13px; color: rgba(255,255,255,.55); margin: 0; }
        .inv-hero-right { position: relative; z-index: 1; }
        .inv-hero-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; border: none; border-radius: 12px;
            padding: 11px 22px;
            font-size: 13px; font-weight: 800;
            cursor: pointer; transition: all .2s;
            box-shadow: 0 4px 16px rgba(5,150,105,.35);
            text-decoration: none;
        }
        .inv-hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(5,150,105,.45);
            color: #fff; text-decoration: none;
        }

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
        .ki-total    { background: #eff6ff; color: #2563eb; }
        .ki-encours  { background: #fef3c7; color: #d97706; }
        .ki-cloture  { background: #ecfdf5; color: #059669; }
        .ki-produits { background: #f5f3ff; color: #7c3aed; }
        .inv-kpi-val { font-size: 24px; font-weight: 900; color: #0f172a; line-height: 1; }
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
        .inv-total-badge {
            display: inline-flex; align-items: center;
            background: #eff6ff; color: #2563eb;
            border: 1px solid #bfdbfe;
            border-radius: 20px; padding: 3px 12px;
            font-size: 12px; font-weight: 800;
        }

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
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #374151;
        }
        .inv-table tbody tr:last-child td { border-bottom: none; }
        .inv-table tbody tr { transition: background .1s; }
        .inv-table tbody tr:hover td { background: #fafbff; }

        /* ── TITRE CELL ── */
        .titre-cell { display: flex; align-items: center; gap: 10px; }
        .titre-cell-ico {
            width: 36px; height: 36px; border-radius: 11px;
            background: #eff6ff;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #2563eb; flex-shrink: 0;
        }
        .titre-link {
            font-size: 13px; font-weight: 800; color: #0f172a;
            text-decoration: none; display: block;
        }
        .titre-link:hover { color: #2563eb; text-decoration: none; }
        .titre-date-sm { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        /* ── BADGES ── */
        .s-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 10px; font-weight: 800; letter-spacing: .2px;
            white-space: nowrap;
        }
        .s-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .sb-encours  { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .sb-cloture  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .sb-annule   { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
        .cat-badge {
            display: inline-block; padding: 3px 10px; border-radius: 8px;
            font-size: 10px; font-weight: 700;
            background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;
        }
        .cat-badge.all {
            background: #f8fafc; color: #64748b; border-color: #e2e8f0;
        }
        .prod-count {
            display: inline-flex; align-items: center; gap: 5px;
            font-weight: 700; color: #374151;
            font-size: 13px;
        }
        .prod-count small { font-size: 10px; color: #94a3b8; font-weight: 500; }

        /* ── ÉCART ── */
        .ecart-pos { font-size: 14px; font-weight: 900; color: #059669; }
        .ecart-neg { font-size: 14px; font-weight: 900; color: #dc2626; }
        .ecart-zero{ font-size: 14px; font-weight: 900; color: #94a3b8; }
        .ecart-currency { font-size: 10px; font-weight: 600; opacity: .7; }

        /* ── USER CELL ── */
        .user-cell { display: flex; align-items: center; gap: 8px; }
        .user-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #0891b2);
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 12px; font-weight: 600; color: #374151; }

        /* ── DATE CELL ── */
        .date-main { font-size: 12px; font-weight: 600; color: #374151; }
        .date-rel  { font-size: 10px; color: #94a3b8; margin-top: 1px; }

        /* ── ACTIONS ── */
        .act-group { display: flex; align-items: center; gap: 5px; justify-content: flex-end; }
        .act-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 9px;
            border: none; cursor: pointer;
            font-size: 12px; transition: all .15s; text-decoration: none;
        }
        .act-btn:hover { transform: translateY(-1px); text-decoration: none; }
        .ab-view   { background: #eff6ff; color: #2563eb; }
        .ab-view:hover { background: #dbeafe; color: #1d4ed8; }
        .ab-pdf    { background: #fdf4ff; color: #9333ea; }
        .ab-pdf:hover  { background: #f3e8ff; color: #7c3aed; }
        .ab-lock   { background: #fffbeb; color: #d97706; }
        .ab-lock:hover { background: #fef3c7; color: #b45309; }
        .ab-del    { background: #fef2f2; color: #dc2626; }
        .ab-del:hover  { background: #fee2e2; color: #b91c1c; }

        /* ── EMPTY ── */
        .inv-empty {
            text-align: center; padding: 60px 20px;
        }
        .inv-empty-ring {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 30px; color: #2563eb;
            box-shadow: 0 8px 24px rgba(37,99,235,.15);
        }
        .inv-empty-title { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .inv-empty-sub   { font-size: 13px; color: #94a3b8; margin-bottom: 20px; }
        .inv-empty-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; border-radius: 12px; padding: 11px 22px;
            font-size: 13px; font-weight: 800; text-decoration: none;
            box-shadow: 0 4px 16px rgba(5,150,105,.3); transition: all .2s;
        }
        .inv-empty-btn:hover { transform: translateY(-2px); color: #fff; text-decoration: none; box-shadow: 0 8px 24px rgba(5,150,105,.4); }

        /* ── FOOTER ── */
        .inv-footer {
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        }
        .inv-footer-info { font-size: 12px; color: #94a3b8; }

        /* ── RESPONSIVE ── */
        @media(max-width: 1024px) { .inv-kpi-row { grid-template-columns: repeat(2,1fr); } }
        @media(max-width: 768px) {
            .inv-kpi-row { grid-template-columns: 1fr 1fr; }
            .inv-hero { flex-direction: column; align-items: flex-start; }
            .inv-hero-right { width: 100%; }
            .inv-hero-btn { width: 100%; justify-content: center; }
            .hide-sm { display: none !important; }
        }

        /* ── ANIM ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .inv-hero    { animation: fadeUp .4s ease both; }
        .inv-kpi-row { animation: fadeUp .4s .1s ease both; }
        .inv-card    { animation: fadeUp .4s .2s ease both; }
    </style>
@endpush

@section('content')
<div class="inv-wrap">

    {{-- ── HERO ── --}}
    <div class="inv-hero">
        <div class="inv-hero-left">
            <div class="inv-hero-ico">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h1 class="inv-hero-title">Gestion des inventaires</h1>
                <p class="inv-hero-sub">
                    {{ $inventaires->total() }} inventaire(s) au total
                </p>
            </div>
        </div>
        <div class="inv-hero-right">
            <a href="{{ route('inventaires.create') }}" class="inv-hero-btn">
                <i class="fas fa-plus"></i> Nouvel inventaire
            </a>
        </div>
    </div>

    {{-- ── KPI ROW ── --}}
    @php
        $totalInv     = $inventaires->total();
        $enCours      = $inventaires->getCollection()->where('statut','en_cours')->count();
        $clotures     = $inventaires->getCollection()->where('statut','cloture')->count();
        $totalProduits = $inventaires->getCollection()->sum(fn($i) => $i->lignes->count());
    @endphp
    <div class="inv-kpi-row">
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-total">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div class="inv-kpi-val">{{ $totalInv }}</div>
                <div class="inv-kpi-lbl">Total inventaires</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-encours">
                <i class="fas fa-spinner"></i>
            </div>
            <div>
                <div class="inv-kpi-val">{{ $enCours }}</div>
                <div class="inv-kpi-lbl">En cours</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-cloture">
                <i class="fas fa-lock"></i>
            </div>
            <div>
                <div class="inv-kpi-val">{{ $clotures }}</div>
                <div class="inv-kpi-lbl">Clôturés</div>
            </div>
        </div>
        <div class="inv-kpi">
            <div class="inv-kpi-ico ki-produits">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <div class="inv-kpi-val">{{ $totalProduits }}</div>
                <div class="inv-kpi-lbl">Produits comptés</div>
            </div>
        </div>
    </div>

    {{-- ── CARD TABLE ── --}}
    <div class="inv-card">

        {{-- Head --}}
        <div class="inv-card-head">
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap">
                <h6 class="inv-card-title">
                    <span class="inv-card-ico"><i class="fas fa-clipboard-list"></i></span>
                    Historique des inventaires
                </h6>
                <span class="inv-total-badge">{{ $inventaires->total() }} entrée(s)</span>
            </div>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Inventaire</th>
                        <th class="hide-sm">Catégorie</th>
                        <th>Statut</th>
                        <th>Produits</th>
                        <th>Écart valeur</th>
                        <th class="hide-sm">Créé par</th>
                        <th class="hide-sm">Clôture</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaires as $inventaire)
                        @php
                            $ecart = $inventaire->total_ecart_valeur;
                            $initials = collect(explode(' ', $inventaire->user->nom_complet))
                                ->map(fn($w) => strtoupper(substr($w,0,1)))
                                ->take(2)->implode('');
                        @endphp
                        <tr>
                            {{-- Titre --}}
                            <td>
                                <div class="titre-cell">
                                    <div class="titre-cell-ico">
                                        <i class="fas fa-clipboard"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('inventaires.show', $inventaire) }}"
                                           class="titre-link">
                                            {{ $inventaire->titre }}
                                        </a>
                                        <div class="titre-date-sm">
                                            <i class="fas fa-clock" style="font-size:9px"></i>
                                            {{ $inventaire->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Catégorie --}}
                            <td class="hide-sm">
                                @if($inventaire->categorie_filtre && $inventaire->categorie_filtre !== 'toutes')
                                    <span class="cat-badge">
                                        {{ $inventaire->categorie?->nom ?? $inventaire->categorie_filtre }}
                                    </span>
                                @else
                                    <span class="cat-badge all">Toutes</span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td>
                                @if($inventaire->statut === 'en_cours')
                                    <span class="s-badge sb-encours">
                                        <span class="s-dot"></span> En cours
                                    </span>
                                @elseif($inventaire->statut === 'cloture')
                                    <span class="s-badge sb-cloture">
                                        <span class="s-dot"></span> Clôturé
                                    </span>
                                @else
                                    <span class="s-badge sb-annule">
                                        <span class="s-dot"></span> {{ $inventaire->statut_label }}
                                    </span>
                                @endif
                            </td>

                            {{-- Nb produits --}}
                            <td>
                                <span class="prod-count">
                                    {{ $inventaire->lignes->count() }}
                                    <small>produit(s)</small>
                                </span>
                            </td>

                            {{-- Écart --}}
                            <td>
                                @if($ecart > 0)
                                    <span class="ecart-pos">
                                        +{{ number_format($ecart, 0, ',', ' ') }}
                                        <span class="ecart-currency">FCFA</span>
                                    </span>
                                @elseif($ecart < 0)
                                    <span class="ecart-neg">
                                        {{ number_format($ecart, 0, ',', ' ') }}
                                        <span class="ecart-currency">FCFA</span>
                                    </span>
                                @else
                                    <span class="ecart-zero">0 <span class="ecart-currency">FCFA</span></span>
                                @endif
                            </td>

                            {{-- Créé par --}}
                            <td class="hide-sm">
                                <div class="user-cell">
                                    <div class="user-avatar">{{ $initials }}</div>
                                    <span class="user-name">{{ $inventaire->user->nom_complet }}</span>
                                </div>
                            </td>

                            {{-- Date clôture --}}
                            <td class="hide-sm">
                                @if($inventaire->date_cloture)
                                    <div class="date-main">
                                        {{ \Carbon\Carbon::parse($inventaire->date_cloture)->format('d/m/Y') }}
                                    </div>
                                    <div class="date-rel">
                                        {{ \Carbon\Carbon::parse($inventaire->date_cloture)->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:11px">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="act-group">
                                    <a href="{{ route('inventaires.show', $inventaire) }}"
                                       class="act-btn ab-view" title="Voir le détail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventaires.pdf', $inventaire) }}"
                                       class="act-btn ab-pdf" target="_blank" title="Télécharger PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @if($inventaire->statut === 'en_cours')
                                        <form action="{{ route('inventaires.cloturer', $inventaire) }}"
                                              method="POST" style="display:inline"
                                              onsubmit="return confirm('Clôturer cet inventaire ?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="act-btn ab-lock" title="Clôturer">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isAdmin() && $inventaire->statut !== 'en_cours')
                                        <form id="del-inv-{{ $inventaire->id }}"
                                              action="{{ route('inventaires.destroy', $inventaire) }}"
                                              method="POST" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="act-btn ab-del" title="Supprimer"
                                                    onclick="confirmDelete('del-inv-{{ $inventaire->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="inv-empty">
                                    <div class="inv-empty-ring">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <div class="inv-empty-title">Aucun inventaire créé</div>
                                    <div class="inv-empty-sub">
                                        Commencez par créer votre premier inventaire de stock.
                                    </div>
                                    <a href="{{ route('inventaires.create') }}" class="inv-empty-btn">
                                        <i class="fas fa-plus"></i> Créer le premier inventaire
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($inventaires->hasPages())
            <div class="inv-footer">
                <span class="inv-footer-info">
                    Affichage {{ $inventaires->firstItem() }}–{{ $inventaires->lastItem() }}
                    sur {{ $inventaires->total() }} inventaire(s)
                </span>
                <div>{{ $inventaires->withQueryString()->links() }}</div>
            </div>
        @endif

    </div>
</div>
@endsection