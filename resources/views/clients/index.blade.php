{{-- resources/views/clients/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Clients')

@section('page-title', 'Clients')

@section('breadcrumb')

    <li class="breadcrumb-item active">Clients</li>

@endsection



@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap');

        .cl-wrap {
            font-family: 'DM Sans', sans-serif;
        }



        .cl-hero {

            background: linear-gradient(135deg, #0f172a 0%, #3730a3 60%, #1e1b4b 100%);

            border-radius: 20px;
            padding: 24px 30px;
            margin-bottom: 22px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;

            position: relative;
            overflow: hidden;

            box-shadow: 0 10px 40px rgba(15, 23, 42, .2);

        }

        .cl-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .cl-hero::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 40%;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(99, 102, 241, .1);
            pointer-events: none;
        }

        .cl-hero-left {
            position: relative;
            z-index: 1;
        }

        .cl-hero-title {
            font-family: sans-serif;
            font-size: clamp(1rem, 2.5vw, 1.35rem);
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
        }

        .cl-hero-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, .45);
            margin: 0;
        }

        .cl-hero-right {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 8px;
        }

        .cl-new-btn {

            display: inline-flex;
            align-items: center;
            gap: 8px;

            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;

            border-radius: 12px;
            padding: 11px 22px;
            font-size: 13px;
            font-weight: 800;

            text-decoration: none;
            transition: all .2s;
            box-shadow: 0 4px 14px rgba(99, 102, 241, .35);

        }

        .cl-new-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(99, 102, 241, .45);
            color: #fff;
            text-decoration: none;
        }



        .cl-kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        @media(max-width:900px) {
            .cl-kpi-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .cl-kpi {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 2px 12px rgba(11, 15, 26, .05);
            transition: .2s;
        }

        .cl-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .cl-kpi-ico {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .cl-kpi-val {
            font-family: sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 3px;
        }

        .cl-kpi-lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            opacity: .6;
        }



        .cl-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(11, 15, 26, .05);
        }

        .cl-card-hd {
            padding: 14px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .cl-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #0f172a;
            margin: 0;
        }

        .cl-card-ico {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
        }



        .cl-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cl-search {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: .2s;
            min-width: 200px;
        }

        .cl-search:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
        }

        .cl-search-ico {
            width: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c4cdd8;
            font-size: 11px;
        }

        .cl-search:focus-within .cl-search-ico {
            color: #6366f1;
        }

        .cl-search input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px 8px 8px 0;
            font-size: 12px;
            color: #0f172a;
            outline: none;
        }

        .cl-sel {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            outline: none;
            cursor: pointer;
            transition: .2s;
        }

        .cl-sel:focus {
            border-color: #6366f1;
        }



        .cl-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        .cl-table thead tr {
            background: #f8fafc;
        }

        .cl-table th {
            padding: 10px 16px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        .cl-table td {
            padding: 11px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }

        .cl-table tr:last-child td {
            border-bottom: none;
        }

        .cl-table tr:hover td {
            background: #fafbff;
        }



        .cl-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            color: #fff;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .cl-nom-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cl-nom strong {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .cl-nom small {
            font-size: 10px;
            color: #94a3b8;
            display: block;
        }



        .cl-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .cb-part {
            background: #f0fdf4;
            color: #15803d;
        }

        .cb-ent {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .cb-actif {
            background: #d1fae5;
            color: #065f46;
        }

        .cb-inactif {
            background: #f1f5f9;
            color: #64748b;
        }

        .cb-credit {
            background: #fef3c7;
            color: #92400e;
        }



        .cl-actions {
            display: flex;
            gap: 5px;
            justify-content: flex-end;
        }

        .cl-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1.5px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            cursor: pointer;
            transition: .15s;
            text-decoration: none;
        }

        .cl-btn:hover {
            transform: scale(1.1);
            text-decoration: none;
        }

        .cb-view {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .cb-edit {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .cb-del {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }



        .cl-footer {
            padding: 14px 22px;
            border-top: 1px solid #f1f5f9;
            background: #fafbff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }



        .cl-empty {
            padding: 50px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .cl-empty i {
            font-size: 40px;
            display: block;
            margin-bottom: 12px;
            opacity: .3;
        }



        @media(max-width:640px) {
            .hide-sm {
                display: none !important;
            }
        }



        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .cl-hero {
            animation: fadeUp .4s ease both;
        }

        .cl-kpi-row {
            animation: fadeUp .4s .08s ease both;
        }

        .cl-card {
            animation: fadeUp .4s .16s ease both;
        }
    </style>
@endpush



@section('content')

    <div class="cl-wrap">



        <div class="cl-hero">

            <div class="cl-hero-left">

                <h1 class="cl-hero-title"><i class="fas fa-users mr-2" style="opacity:.6"></i>Clients</h1>

                <p class="cl-hero-sub">{{ $stats['total'] }} client(s) enregistré(s)</p>

            </div>

            <div class="cl-hero-right">

                <a href="{{ route('clients.create') }}" class="cl-new-btn">

                    <i class="fas fa-user-plus"></i> Nouveau client

                </a>

            </div>

        </div>



        <div class="cl-kpi-row">

            <div class="cl-kpi">

                <div class="cl-kpi-ico" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe); color:#7c3aed"><i
                        class="fas fa-users"></i></div>

                <div class="cl-kpi-val" style="color:#1e1b4b">{{ $stats['total'] }}</div>

                <div class="cl-kpi-lbl" style="color:#1e1b4b">Total clients</div>

            </div>

            <div class="cl-kpi">

                <div class="cl-kpi-ico" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#059669"><i
                        class="fas fa-user-check"></i></div>

                <div class="cl-kpi-val" style="color:#065f46">{{ $stats['actifs'] }}</div>

                <div class="cl-kpi-lbl" style="color:#065f46">Actifs</div>

            </div>

            <div class="cl-kpi">

                <div class="cl-kpi-ico" style="background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8"><i
                        class="fas fa-building"></i></div>

                <div class="cl-kpi-val" style="color:#1e40af">{{ $stats['entreprises'] }}</div>

                <div class="cl-kpi-lbl" style="color:#1e40af">Entreprises</div>

            </div>

            <div class="cl-kpi">

                <div class="cl-kpi-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a); color:#d97706"><i
                        class="fas fa-hand-holding-usd"></i></div>

                <div class="cl-kpi-val" style="color:#92400e; font-size:1.1rem">

                    {{ $stats['creances'] >= 1000 ? number_format($stats['creances'] / 1000, 0, ',', ' ') . 'K' : number_format($stats['creances'], 0, ',', ' ') }}

                </div>

                <div class="cl-kpi-lbl" style="color:#92400e">Créances (FCFA)</div>

            </div>

        </div>



        <div class="cl-card">

            <div class="cl-card-hd">

                <h6 class="cl-card-title">

                    <span class="cl-card-ico"><i class="fas fa-list"></i></span>

                    Liste des clients

                    <span
                        style="padding:2px 10px; background:#ede9fe; color:#7c3aed; border-radius:20px; font-size:11px">{{ $clients->total() }}</span>

                </h6>

                <form method="GET" action="{{ route('clients.index') }}" class="cl-filters">

                    <div class="cl-search">

                        <div class="cl-search-ico"><i class="fas fa-search"></i></div>

                        <input type="text" name="search" placeholder="Nom, téléphone..."
                            value="{{ request('search') }}" autocomplete="off">

                    </div>

                    <select name="type" class="cl-sel" onchange="this.form.submit()">

                        <option value="">Tous types</option>

                        <option value="particulier" {{ request('type') == 'particulier' ? 'selected' : '' }}>Particulier</option>

                        <option value="entreprise" {{ request('type') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>

                    </select>

                    <select name="statut" class="cl-sel" onchange="this.form.submit()">

                        <option value="">Tous statuts</option>

                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>

                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>

                    </select>

                    @if (request()->hasAny(['search', 'type', 'statut']))
                        <a href="{{ route('clients.index') }}"
                            style="padding:8px 12px; background:#f1f5f9; border-radius:10px; font-size:12px; font-weight:700; color:#64748b; text-decoration:none">

                            <i class="fas fa-times"></i>

                        </a>
                    @endif

                </form>

            </div>



            <div class="table-responsive">

                <table class="cl-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Client</th>

                            <th>Type</th>

                            <th>Téléphone</th>

                            <th class="hide-sm">Email</th>

                            <th class="hide-sm">Ifu</th>

                            <th class="hide-sm">Bons</th>

                            <th>Avance disponible</th>
                            <th>Credit initial</th>

                            <th>Remboursé</th>

                            <th>Solde dû</th>

                            <th>Statut</th>

                            <th style="text-align:right">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($clients as $i => $c)
                            @php

                                $colors = ['#6366f1', '#059669', '#d97706', '#0ea5e9', '#dc2626', '#7c3aed', '#16a34a'];

                                $color = $colors[$c->id % count($colors)];

                            @endphp

                            <tr>

                                <td style="color:#94a3b8; font-size:11px">{{ $clients->firstItem() + $loop->index }}</td>

                                <td>

                                    <div class="cl-nom-wrap">

                                        <div class="cl-avatar" style="background:{{ $color }}">{{ $c->initiales }}
                                        </div>

                                        <div class="cl-nom">

                                            <strong>{{ $c->nom_complet }}</strong>

                                            @if ($c->adresse)
                                                <small>{{ $c->adresse }}</small>
                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="cl-badge {{ $c->type === 'entreprise' ? 'cb-ent' : 'cb-part' }}">

                                        <i class="fas {{ $c->type === 'entreprise' ? 'fa-building' : 'fa-user' }}"
                                            style="font-size:8px"></i>

                                        {{ $c->type_label }}

                                    </span>

                                </td>

                                <td>

                                    @if ($c->telephone)
                                        <a href="tel:{{ $c->telephone }}"
                                            style="color:#6366f1; font-weight:600; text-decoration:none">

                                            <i class="fas fa-phone"
                                                style="font-size:9px; margin-right:4px"></i>{{ $c->telephone }}

                                        </a>
                                    @else
                                        <span style="color:#cbd5e1"> - </span>
                                    @endif

                                </td>

                                <td class="hide-sm" style="color:#64748b; font-size:11px">{{ $c->email ?? ' - ' }}
                                </td>

                                <td class="hide-sm" style="color:#64748b">{{ $c->ifu ?? ' - ' }}</td>

                                <td class="hide-sm">

                                    <span class="cl-badge" style="background:#ede9fe; color:#7c3aed">

                                        <i class="fas fa-receipt" style="font-size:8px"></i>

                                        {{ $c->factures_count }}

                                    </span>

                                </td>

                                <td class="text-success font-weight-bold">
                                    {{ number_format((float) $c->solde_avance, 0, ',', ' ') }} F</td>
                                <td>{{ number_format($c->credits_initiaux ?? 0, 0, ',', ' ') }} F</td>

                                <td>{{ number_format($c->remboursements_total ?? 0, 0, ',', ' ') }} F</td>

                                <td>

                                    @if ((float) ($c->solde_calcule ?? 0) > 0)
                                        <span class="cl-badge cb-credit">

                                            <i class="fas fa-exclamation" style="font-size:8px"></i>

                                            {{ number_format($c->solde_calcule, 0, ',', ' ') }} F

                                        </span>

                                        <small
                                            class="d-block">{{ (float) ($c->remboursements_total ?? 0) > 0 ? 'Partiellement remboursé' : 'À payer' }}</small>
                                    @else
                                        <span class="badge badge-success">À jour</span>
                                    @endif

                                </td>

                                <td>

                                    <span class="cl-badge {{ $c->actif ? 'cb-actif' : 'cb-inactif' }}">

                                        <span
                                            style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block"></span>

                                        {{ $c->actif ? 'Actif' : 'Inactif' }}

                                    </span>

                                </td>

                                <td>

                                    <div class="cl-actions">

                                        <a href="{{ route('clients.show', $c) }}" class="cl-btn cb-view"
                                            title="Voir"><i class="fas fa-eye"></i></a>

                                        <a href="{{ route('clients.edit', $c) }}" class="cl-btn cb-edit"
                                            title="Modifier"><i class="fas fa-edit"></i></a>

                                        <button onclick="confirmDelete('del-c-{{ $c->id }}')"
                                            class="cl-btn cb-del" title="Supprimer"><i class="fas fa-trash"></i></button>

                                        <form id="del-c-{{ $c->id }}" action="{{ route('clients.destroy', $c) }}"
                                            method="POST" class="d-none">

                                            @csrf @method('DELETE')

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10">
                                    <div class="cl-empty"><i class="fas fa-users"></i>
                                        <p>Aucun client trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>



            <div class="cl-footer">

                <span style="font-size:11px; color:#94a3b8">{{ $clients->total() }} client(s)</span>

                @include('partials.pagination', ['paginator' => $clients])

            </div>

        </div>

    </div>

@endsection
