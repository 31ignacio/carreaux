@extends('layouts.app')
@section('title', 'Remboursements clients')
@section('page-title', 'Historique des remboursements')

@section('breadcrumb')
    <li class="breadcrumb-item active">Remboursements</li>
@endsection

@push('styles')
<style>
    .content-header { display:none; }
    .rb-wrap { color:#172033; }
    .rb-hero {
        display:flex; align-items:center; gap:18px; position:relative; overflow:hidden;
        padding:26px 30px; margin-bottom:18px; border-radius:18px;
        color:#fff; background:linear-gradient(120deg,#172554 0%,#3730a3 62%,#4f46e5 100%);
        box-shadow:0 12px 30px rgba(49,46,129,.18);
    }
    .rb-hero:after {
        content:""; position:absolute; width:230px; height:230px; right:-55px; top:-115px;
        border-radius:50%; background:rgba(255,255,255,.08); pointer-events:none;
    }
    .rb-hero-icon {
        display:flex; align-items:center; justify-content:center; flex:0 0 54px;
        width:54px; height:54px; border:1px solid rgba(255,255,255,.2);
        border-radius:15px; background:rgba(255,255,255,.12); font-size:22px;
    }
    .rb-hero-copy { position:relative; z-index:1; }
    .rb-eyebrow { margin-bottom:4px; color:rgba(255,255,255,.68); font-size:10px; font-weight:800; letter-spacing:1.1px; text-transform:uppercase; }
    .rb-hero h1 { margin:0 0 5px; color:#fff; font-size:clamp(20px,3vw,27px); font-weight:800; }
    .rb-hero p { margin:0; color:rgba(255,255,255,.75); font-size:13px; }
    .rb-stats {
        display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; margin-bottom:18px;
    }
    .rb-stat {
        display:flex; align-items:center; gap:14px; padding:17px 20px; background:#fff;
        border:1px solid #e8ecf3; border-radius:14px; box-shadow:0 3px 12px rgba(23,32,51,.045);
    }
    .rb-stat-icon {
        display:flex; align-items:center; justify-content:center; flex:0 0 42px;
        width:42px; height:42px; border-radius:12px; background:#eef2ff; color:#4f46e5; font-size:16px;
    }
    .rb-stat-icon.green { color:#15803d; background:#ecfdf3; }
    .rb-stat-label { color:#7b8798; font-size:10px; font-weight:800; letter-spacing:.65px; text-transform:uppercase; }
    .rb-stat-value { margin-top:2px; color:#172033; font-size:20px; font-weight:800; line-height:1.2; }
    .rb-stat-value small { color:#7b8798; font-size:12px; font-weight:700; }
    .rb-panel {
        overflow:hidden; margin-bottom:18px; background:#fff; border:1px solid #e8ecf3;
        border-radius:14px; box-shadow:0 3px 12px rgba(23,32,51,.045);
    }
    .rb-panel-head {
        display:flex; align-items:center; gap:10px; padding:15px 19px;
        border-bottom:1px solid #edf0f5; background:#fbfcfe;
    }
    .rb-panel-head i { color:#4f46e5; }
    .rb-panel-head h2 { margin:0; color:#172033; font-size:13px; font-weight:800; }
    .rb-filter-form { padding:16px 18px; }
    .rb-filter-grid {
        display:grid; grid-template-columns:minmax(180px,1.3fr) minmax(180px,1.2fr) repeat(2,minmax(145px,.8fr)) auto;
        gap:10px; align-items:end;
    }
    .rb-field label {
        display:block; margin:0 0 6px; color:#657184; font-size:10px;
        font-weight:800; letter-spacing:.45px; text-transform:uppercase;
    }
    .rb-control {
        display:flex; align-items:center; min-height:40px; padding:0 11px;
        border:1px solid #dfe5ee; border-radius:9px; background:#fff;
    }
    .rb-control:focus-within { border-color:#818cf8; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
    .rb-control i { margin-right:9px; color:#98a3b3; font-size:12px; }
    .rb-control input,.rb-control select {
        width:100%; min-width:0; border:0; outline:0; background:transparent;
        color:#283448; font-size:12px;
    }
    .rb-control select { cursor:pointer; }
    .rb-filter-actions { display:flex; gap:7px; }
    .rb-filter-actions .btn { min-height:40px; padding:0 13px; border-radius:9px; font-size:12px; font-weight:700; }
    .rb-filter-actions .btn-primary { border-color:#4f46e5; background:#4f46e5; }
    .rb-filter-actions .btn-light { border:1px solid #dfe5ee; background:#fff; color:#657184; }
    .rb-table-head {
        display:flex; justify-content:space-between; align-items:center; gap:12px;
        padding:15px 19px; border-bottom:1px solid #edf0f5;
    }
    .rb-table-title { color:#172033; font-size:13px; font-weight:800; }
    .rb-result-count { padding:4px 9px; border-radius:20px; color:#4f46e5; background:#eef2ff; font-size:10px; font-weight:800; }
    .rb-table { margin:0; }
    .rb-table thead th {
        padding:11px 14px; border:0; border-bottom:1px solid #edf0f5; background:#fbfcfe;
        color:#7b8798; font-size:9px; font-weight:800; letter-spacing:.65px; text-transform:uppercase; white-space:nowrap;
    }
    .rb-table tbody td { padding:12px 14px; border-top:1px solid #f0f2f6; color:#344054; font-size:12px; vertical-align:middle; }
    .rb-table tbody tr:hover { background:#fafbff; }
    .rb-date { color:#283448; font-weight:700; white-space:nowrap; }
    .rb-time { display:block; margin-top:2px; color:#929cad; font-size:10px; font-weight:500; }
    .rb-reference { color:#657184; font-family:monospace; font-size:10px; }
    .rb-client { display:flex; align-items:center; gap:9px; min-width:155px; }
    .rb-avatar {
        display:flex; align-items:center; justify-content:center; flex:0 0 32px;
        width:32px; height:32px; border-radius:10px; color:#4338ca; background:#eef2ff;
        font-size:10px; font-weight:800; text-transform:uppercase;
    }
    .rb-client a { color:#202b3d; font-weight:700; }
    .rb-client a:hover { color:#4f46e5; text-decoration:none; }
    .rb-amount { color:#15803d !important; font-weight:800; white-space:nowrap; }
    .rb-mode { display:inline-block; padding:5px 8px; border:1px solid #e8ecf3; border-radius:20px; background:#f8fafc; color:#536174; font-size:10px; font-weight:700; white-space:nowrap; }
    .rb-download { border-radius:8px; color:#4338ca; border-color:#c7d2fe; background:#fff; font-size:11px; font-weight:700; white-space:nowrap; }
    .rb-download:hover { color:#fff; border-color:#4f46e5; background:#4f46e5; }
    .rb-empty { padding:44px 20px !important; color:#7b8798 !important; text-align:center; }
    .rb-empty i { display:block; margin-bottom:10px; color:#c3cad5; font-size:28px; }
    .rb-pagination { padding:12px 18px; border-top:1px solid #edf0f5; }
    .rb-pagination .pagination { margin:0; justify-content:flex-end; }
    @media(max-width:1050px) {
        .rb-filter-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .rb-filter-actions { grid-column:1/-1; }
    }
    @media(max-width:600px) {
        .rb-hero { align-items:flex-start; padding:20px; }
        .rb-hero-icon { flex-basis:44px; width:44px; height:44px; font-size:18px; }
        .rb-stats { grid-template-columns:1fr; gap:9px; }
        .rb-filter-grid { grid-template-columns:1fr; }
        .rb-filter-actions { grid-column:auto; }
        .rb-filter-actions .btn { flex:1; }
        .rb-table-head { padding:13px 14px; }
    }
</style>
@endpush

@section('content')
<div class="rb-wrap">
    <header class="rb-hero">
        <div class="rb-hero-icon"><i class="fas fa-hand-holding-usd"></i></div>
        <div class="rb-hero-copy">
            <div class="rb-eyebrow">Gestion financière · Clients</div>
            <h1>Historique des remboursements</h1>
            <p>Consultez les versements reçus, retrouvez un client et téléchargez ses reçus.</p>
        </div>
    </header>

    <section class="rb-stats" aria-label="Résumé des remboursements">
        <div class="rb-stat">
            <div class="rb-stat-icon green"><i class="fas fa-coins"></i></div>
            <div>
                <div class="rb-stat-label">Montant total correspondant</div>
                <div class="rb-stat-value">{{ number_format($total, 0, ',', ' ') }} <small>FCFA</small></div>
            </div>
        </div>
        <div class="rb-stat">
            <div class="rb-stat-icon"><i class="fas fa-receipt"></i></div>
            <div>
                <div class="rb-stat-label">Remboursements correspondants</div>
                <div class="rb-stat-value">{{ number_format($nombre, 0, ',', ' ') }}</div>
            </div>
        </div>
    </section>

    <section class="rb-panel">
        <div class="rb-panel-head">
            <i class="fas fa-sliders-h"></i>
            <h2>Rechercher et filtrer</h2>
        </div>
        <form method="GET" action="{{ route('remboursements.index') }}" class="rb-filter-form">
            <div class="rb-filter-grid">
                <div class="rb-field">
                    <label for="remboursement-search">Nom du client</label>
                    <div class="rb-control">
                        <i class="fas fa-search"></i>
                        <input id="remboursement-search" type="search" name="search" placeholder="Rechercher un nom…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="rb-field">
                    <label for="remboursement-client">Client</label>
                    <div class="rb-control">
                        <i class="fas fa-user"></i>
                        <select id="remboursement-client" name="client_id">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected((string) request('client_id') === (string) $client->id)>{{ $client->nom_complet }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="rb-field">
                    <label for="remboursement-date-debut">Du</label>
                    <div class="rb-control">
                        <i class="far fa-calendar-alt"></i>
                        <input id="remboursement-date-debut" type="date" name="date_debut" value="{{ request('date_debut') }}">
                    </div>
                </div>
                <div class="rb-field">
                    <label for="remboursement-date-fin">Au</label>
                    <div class="rb-control">
                        <i class="far fa-calendar-alt"></i>
                        <input id="remboursement-date-fin" type="date" name="date_fin" value="{{ request('date_fin') }}">
                    </div>
                </div>
                <div class="rb-filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filtrer</button>
                    <a class="btn btn-light" href="{{ route('remboursements.index') }}" title="Effacer les filtres" aria-label="Effacer les filtres"><i class="fas fa-undo"></i></a>
                </div>
            </div>
        </form>
    </section>

    <section class="rb-panel">
        <div class="rb-table-head">
            <div class="rb-table-title"><i class="fas fa-list-ul mr-2 text-muted"></i>Opérations</div>
            <span class="rb-result-count">{{ $remboursements->total() }} résultat(s)</span>
        </div>
        <div class="table-responsive">
            <table class="table rb-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Mode de paiement</th>
                        <th>Enregistré par</th>
                        <th class="text-right">Reçu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($remboursements as $remboursement)
                        <tr>
                            <td class="rb-date">
                                {{ $remboursement->created_at->format('d/m/Y') }}
                                <span class="rb-time">{{ $remboursement->created_at->format('H:i') }}</span>
                            </td>
                            <td class="rb-reference">{{ substr($remboursement->reference, 0, 8) }}…</td>
                            <td>
                                <div class="rb-client">
                                    <span class="rb-avatar">{{ $remboursement->client->initiales ?: 'CL' }}</span>
                                    <a href="{{ route('clients.show', $remboursement->client) }}">{{ $remboursement->client->nom_complet }}</a>
                                </div>
                            </td>
                            <td class="rb-amount">{{ number_format($remboursement->montant, 0, ',', ' ') }} FCFA</td>
                            <td><span class="rb-mode">{{ $remboursement->mode_paiement_label }}</span></td>
                            <td>{{ $remboursement->user->nom_complet }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm rb-download" href="{{ route('remboursements.recus', $remboursement) }}">
                                    <i class="fas fa-download mr-1"></i>Télécharger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="rb-empty">
                                <i class="far fa-folder-open"></i>
                                Aucun remboursement ne correspond à ces filtres.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($remboursements->hasPages())
            <div class="rb-pagination">{{ $remboursements->links() }}</div>
        @endif
    </section>
</div>
@endsection
