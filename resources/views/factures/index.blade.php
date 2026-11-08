@extends('layouts.app')

@section('title', 'Bons')
@section('page-title', 'Gestion des Bons')

@section('breadcrumb')
    <li class="breadcrumb-item active">Bons</li>
@endsection

@push('styles')
    <style>
        :root {
            --dark: #0f172a;
            --border: #e2e8f0;
            --sh: 0 4px 24px rgba(15, 52, 96, .09);
            --r: 16px;
        }

        /* ══ HERO ══ */
        .f-hero {
            background: linear-gradient(140deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
            border-radius: var(--r);
            padding: 22px 26px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(15, 52, 96, .28);
        }

        .f-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, .15), transparent 65%);
            pointer-events: none;
        }

        .f-hero-title {
            font-size: clamp(.95rem, 3vw, 1.3rem);
            font-weight: 800;
            color: #fff;
            margin: 0;
        }

        .f-hero-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, .55);
            margin-top: 3px;
        }

        .h-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 14px;
            border: none;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            text-decoration: none !important;
            white-space: nowrap;
        }

        .h-btn:hover {
            transform: translateY(-2px);
            text-decoration: none !important;
        }

        .h-btn-success {
            background: linear-gradient(135deg, #34d399, #10b981);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, .3);
        }

        .h-btn-success:hover {
            box-shadow: 0 6px 18px rgba(16, 185, 129, .4);
        }

        .filter-active {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        /* ══ KPI ══ */
        .kpi {
            border-radius: 14px;
            padding: 18px;
            box-shadow: var(--sh);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .12);
        }

        .kpi::after {
            content: '';
            position: absolute;
            bottom: -16px;
            right: -16px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
        }

        .kpi-ico {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            margin-bottom: 10px;
        }

        .kpi-v {
            font-size: 1.4rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 3px;
        }

        .kpi-l {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            opacity: .65;
        }

        .kpi-b {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }

        .kpi-b .kpi-ico {
            background: rgba(59, 130, 246, .18);
            color: #3b82f6;
        }

        .kpi-b .kpi-v {
            color: #1e3a8a;
        }

        .kpi-b .kpi-l {
            color: #1e3a8a;
        }

        .kpi-g {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .kpi-g .kpi-ico {
            background: rgba(16, 185, 129, .18);
            color: #10b981;
        }

        .kpi-g .kpi-v {
            color: #065f46;
        }

        .kpi-g .kpi-l {
            color: #065f46;
        }

        .kpi-o {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
        }

        .kpi-o .kpi-ico {
            background: rgba(245, 158, 11, .22);
            color: #d97706;
        }

        .kpi-o .kpi-v {
            color: #92400e;
        }

        .kpi-o .kpi-l {
            color: #92400e;
        }

        .kpi-r {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
        }

        .kpi-r .kpi-ico {
            background: rgba(239, 68, 68, .18);
            color: #ef4444;
        }

        .kpi-r .kpi-v {
            color: #7f1d1d;
        }

        .kpi-r .kpi-l {
            color: #7f1d1d;
        }

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
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pd-card-title {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--dark);
            margin: 0;
        }

        .pd-card-ico {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #fff;
            flex-shrink: 0;
        }

        .filter-wrap {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            background: #fafbff;
        }

        /* ══ STATUT BADGES ══ */
        .s-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .s-payee {
            background: #d1fae5;
            color: #059669;
        }

        .s-cours {
            background: #fef3c7;
            color: #d97706;
        }

        .s-annulee {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ══ PRIX TYPE ══ */
        .p-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .p-detail {
            background: #dbeafe;
            color: #2563eb;
        }

        .p-moyen {
            background: #ede9fe;
            color: #7c3aed;
        }

        .p-gros {
            background: #f1f5f9;
            color: #475569;
        }

        /* ══ RESPONSIVE ══ */
        @media(max-width:767px) {
            .f-hero {
                padding: 16px;
            }

            .kpi {
                padding: 14px;
                margin-bottom: 10px;
            }

            .kpi-v {
                font-size: 1.1rem;
            }

            .kpi-ico {
                width: 32px;
                height: 32px;
                font-size: .8rem;
                margin-bottom: 7px;
            }

            .hide-sm {
                display: none !important;
            }

            .h-btn {
                padding: 7px 11px;
                font-size: 11px;
            }
        }

        @media(max-width:420px) {
            .kpi-v {
                font-size: 1rem;
            }

            .kpi-l {
                font-size: 9px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ════════════ HERO ════════════ --}}
    <div class="f-hero">
        <div class="d-flex align-items-center justify-content-between" style="position:relative; z-index:1; gap:12px">
            <div style="min-width:0">
                <p class="f-hero-sub mb-1">
                    <i class="fas fa-file-invoice mr-1"></i> Gestion commerciale
                    @if (request()->hasAny(['search', 'statut', 'date_debut', 'date_fin']))
                        <span class="filter-active ml-2">
                            <i class="fas fa-filter"></i> Filtre actif
                        </span>
                        @if (request('search'))
                            <span class="filter-active ml-1">"{{ request('search') }}"</span>
                        @endif
                        @if (request('statut'))
                            <span class="filter-active ml-1">{{ request('statut') }}</span>
                        @endif
                    @endif
                </p>
                <h4 class="f-hero-title">Bons</h4>
            </div>
            @if (auth()->user()->isAdmin() || auth()->user()->isCaissier() || auth()->user()->isCaissierHaut())
                <a href="{{ route('caisse.index') }}" class="h-btn h-btn-success">
                    <i class="fas fa-cash-register"></i>
                    <span class="d-none d-sm-inline">Nouvelle vente</span>
                </a>
            @endif
        </div>
    </div>

    {{-- ════════════ KPI ════════════ --}}
    @php
        $col = $factures->getCollection();
        $totalCA = $col->where('statut', 'payee')->sum('total');
        $nbPayees = $col->where('statut', 'payee')->count();
        $nbEnCours = $col->where('statut', 'en_cours')->count();
        $nbAnnulees = $col->where('statut', 'annulee')->count();
    @endphp
    <div class="row mb-2">
        <div class="col-6 col-md-3">
            <div class="kpi kpi-b">
                <div class="kpi-ico"><i class="fas fa-file-invoice"></i></div>
                <div class="kpi-v">{{ number_format($factures->total(), 0, ',', ' ') }}</div>
                <div class="kpi-l">Total bons de livraison</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi kpi-g">
                <div class="kpi-ico"><i class="fas fa-check-circle"></i></div>
                <div class="kpi-v">{{ number_format($nbPayees, 0, ',', ' ') }}</div>
                <div class="kpi-l">Payées</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi kpi-o">
                <div class="kpi-ico"><i class="fas fa-clock"></i></div>
                <div class="kpi-v">{{ number_format($nbEnCours, 0, ',', ' ') }}</div>
                <div class="kpi-l">En cours</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi kpi-r">
                <div class="kpi-ico"><i class="fas fa-ban"></i></div>
                <div class="kpi-v">{{ number_format($nbAnnulees, 0, ',', ' ') }}</div>
                <div class="kpi-l">Annulées</div>
            </div>
        </div>
    </div>

    {{-- ════════════ TABLE ════════════ --}}
    <div class="pd-card">

        <div class="pd-card-head">
            <h6 class="pd-card-title">
                <span class="pd-card-ico" style="background:#0f172a">
                    <i class="fas fa-file-invoice"></i>
                </span>
                Liste des bons de livraison
                <span class="badge badge-dark ml-1">{{ $factures->total() }}</span>
            </h6>
            @if (request()->hasAny(['search', 'statut', 'date_debut', 'date_fin']))
                <a href="{{ route('bons-livraison.index') }}" class="btn btn-sm btn-outline-secondary"
                    style="border-radius:10px; font-size:11px">
                    <i class="fas fa-times mr-1"></i> Effacer les filtres
                </a>
            @endif
        </div>

        {{-- Filtres --}}
        <div class="filter-wrap">
            <form method="GET" action="{{ route('bons-livraison.index') }}">
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">
                            <i class="fas fa-search mr-1"></i> Recherche
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="N° bon ou client..." value="{{ request('search') }}">
                    </div>

                    <div class="col-12 col-sm-6 col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">
                            <i class="fas fa-filter mr-1"></i> Statut
                        </label>
                        <select name="statut" class="form-control form-control-sm">
                            <option value="">Tous statuts</option>
                            <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>✅ Payée</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>⏳ En cours</option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>❌ Annulée</option>
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
                            @if (request()->hasAny(['search', 'statut', 'date_debut', 'date_fin']))
                                <a href="{{ route('bons-livraison.index') }}" class="btn btn-secondary btn-sm flex-fill">
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
                        <th
                            style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                            #</th>
                        <th
                            style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                            N° Bon</th>
                        <th
                            style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                            Client</th>
                        <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                            class="hide-sm">Paiement</th>
                        <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                            class="text-right">Total</th>
                        <th
                            style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700">
                            Statut</th>
                        <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                            class="d-none d-md-table-cell">Caissier</th>
                        <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                            class="d-none d-sm-table-cell">Date</th>
                        <th style="padding:11px 16px; color:#64748b; font-size:10px; text-transform:uppercase; letter-spacing:.5px; font-weight:700"
                            class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                        <tr>
                            <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8; font-size:11px">
                                {{ $factures->firstItem() + $loop->index }}
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle">
                                <a href="{{ route('bons-livraison.show', $facture) }}" class="font-weight-bold"
                                    style="color:#0f3460">
                                    {{ $facture->numero }}
                                </a>
                            </td>
                            <td>
                                {{ $facture->client ? $facture->client->nom . ' ' . $facture->client->prenom : 'Client Divers' }}
                            </td>

                            <td style="padding:11px 16px; vertical-align:middle; color:#64748b" class="hide-sm">
                                {{ $facture->mode_paiement_label }}
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle; font-weight:800; color:#0f172a"
                                class="text-right">
                                {{ number_format($facture->total, 0, ',', ' ') }}
                                <small class="text-muted font-weight-normal">FCFA</small>
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle">
                                @if ($facture->statut == 'payee')
                                    <span class="s-badge s-payee">
                                        <i class="fas fa-check-circle"></i> Payée
                                    </span>
                                @elseif($facture->statut == 'en_cours')
                                    <span class="s-badge s-cours">
                                        <i class="fas fa-clock"></i> En cours
                                    </span>
                                @else
                                    <span class="s-badge s-annulee">
                                        <i class="fas fa-ban"></i> Annulée
                                    </span>
                                @endif
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle; color:#64748b"
                                class="d-none d-md-table-cell">
                                {{ $facture->user->prenom ?? '_' }}
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle; color:#94a3b8; font-size:11px"
                                class="d-none d-sm-table-cell">
                                {{ $facture->created_at->format('d/m/y H:i') }}
                            </td>
                            <td style="padding:11px 16px; vertical-align:middle" class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        data-toggle="dropdown" style="border-radius:8px; border:1px solid #e2e8f0">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0"
                                        style="border-radius:12px; min-width:180px; padding:6px">
                                        <a class="dropdown-item" href="{{ route('bons-livraison.show', $facture) }}"
                                            style="border-radius:8px; font-size:12px; padding:8px 12px">
                                            <i class="fas fa-eye text-info mr-2"></i> Voir le bon de livraison
                                        </a>
                                        <a class="dropdown-item" href="{{ route('caisse.ticket', $facture) }}"
                                            target="_blank" style="border-radius:8px; font-size:12px; padding:8px 12px">
                                            <i class="fas fa-print text-primary mr-2"></i> Imprimer
                                        </a>
                                        <a class="dropdown-item" href="{{ route('bons-livraison.pdf', $facture) }}"
                                            target="_blank" style="border-radius:8px; font-size:12px; padding:8px 12px">
                                            <i class="fas fa-file-pdf text-success mr-2"></i> Télécharger PDF
                                        </a>
                                        @if (auth()->user()->isAdmin())
                                            @if ($facture->statut !== 'annulee')
                                                <div class="dropdown-divider my-1"></div>
                                                <form action="{{ route('bons-livraison.annuler', $facture) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        style="border-radius:8px; font-size:12px; padding:8px 12px"
                                                        onclick="return confirm('Annuler ce bon ?')">
                                                        <i class="fas fa-ban mr-2"></i> Annuler
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($facture->statut == 'annulee')
                                                <form action="{{ route('bons-livraison.destroy', $facture) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item delete-btn"
                                                        style="border-radius:8px; font-size:12px; padding:8px 12px">
                                                        <i class="fas fa-trash text-danger mr-2"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                                <i class="fas fa-file-invoice fa-2x d-block mb-2" style="opacity:.4"></i>
                                Aucun bon trouvé
                                @if (request()->hasAny(['search', 'statut', 'date_debut', 'date_fin']))
                                    <div class="mt-2">
                                        <a href="{{ route('bons-livraison.index') }}" class="btn btn-sm btn-outline-primary"
                                            style="border-radius:10px">
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
        <div style="padding:14px 20px; border-top:1px solid var(--border); background:#fafbff">
            @include('partials.pagination', ['paginator' => $factures])
        </div>

    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                let form = this.closest('form');

                Swal.fire({
                    title: 'Supprimer ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

@endsection
