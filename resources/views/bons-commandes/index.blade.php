@extends('layouts.app')
@section('title', 'Bons de commande')
@section('page-title', 'Bons de commande')
@section('breadcrumb')<li class="breadcrumb-item active">Bons de commande</li>@endsection

@section('content')
<style>
    .bc-page { --bc-ink:#172033; --bc-muted:#718096; --bc-line:#e8edf4; --bc-blue:#315de8; color:var(--bc-ink); }
    .bc-head { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:22px; }
    .bc-head h2 { margin:0; font-weight:800; font-size:24px; letter-spacing:-.4px; }
    .bc-head p { margin:5px 0 0; color:var(--bc-muted); }
    .bc-new { border:0; border-radius:9px; padding:10px 15px; font-weight:700; box-shadow:0 5px 12px #315de82b; }
    .bc-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:18px; }
    .bc-stat { background:#fff; border:1px solid var(--bc-line); border-radius:12px; padding:16px 18px; box-shadow:0 3px 12px #16213b08; }
    .bc-stat-label { color:var(--bc-muted); font-size:12px; font-weight:700; }
    .bc-stat-value { margin-top:5px; font-size:25px; font-weight:800; line-height:1.1; }
    .bc-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; background:#94a3b8; vertical-align:1px; }
    .bc-dot.wait { background:#f59e0b; } .bc-dot.part { background:#3b82f6; } .bc-dot.done { background:#10b981; }
    .bc-card { border:1px solid var(--bc-line); border-radius:12px; overflow:hidden; background:#fff; box-shadow:0 4px 16px #16213b08; }
    .bc-filter { display:flex; align-items:center; flex-wrap:wrap; gap:9px; padding:15px; border-bottom:1px solid var(--bc-line); background:#fbfcfe; }
    .bc-filter .form-control { height:38px; border-color:#dfe5ee; border-radius:8px; box-shadow:none; }
    .bc-search { flex:1 1 270px; max-width:420px; }
    .bc-status-filter { width:210px; }
    .bc-size { width:132px; }
    .bc-filter .btn { height:38px; border-radius:8px; font-weight:700; }
    .bc-table { margin:0; }
    .bc-table thead th { padding:12px 15px; border-top:0; border-bottom:1px solid var(--bc-line); background:#f7f9fc; color:#788397; font-size:10px; text-transform:uppercase; letter-spacing:.65px; white-space:nowrap; }
    .bc-table tbody td { padding:14px 15px; border-top:1px solid #eff2f6; vertical-align:middle; }
    .bc-table tbody tr:hover { background:#fafcff; }
    .bc-ref { color:#263b70; font-weight:800; white-space:nowrap; }
    .bc-client { font-weight:700; }
    .bc-sub { color:var(--bc-muted); font-size:11px; margin-top:3px; }
    .bc-count { color:#45536a; font-weight:700; white-space:nowrap; }
    .bc-badge { display:inline-flex; align-items:center; border-radius:20px; padding:5px 9px; font-size:11px; font-weight:700; white-space:nowrap; }
    .bc-badge.wait { color:#9a5b00; background:#fff5dc; }
    .bc-badge.part { color:#2452c4; background:#eaf1ff; }
    .bc-badge.done { color:#08754d; background:#e5f7ef; }
    .bc-open { border-radius:7px; padding:6px 10px; font-size:12px; font-weight:700; white-space:nowrap; }
    .bc-empty { padding:48px 15px!important; text-align:center; color:var(--bc-muted); }
    .bc-empty i { display:block; margin-bottom:10px; font-size:27px; color:#aab5c5; }
    .bc-footer { padding:10px 15px 2px; border-top:1px solid var(--bc-line); }
    @media(max-width:767px) { .bc-stats{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.bc-stat{padding:13px}.bc-stat-value{font-size:21px}.bc-head{align-items:flex-start}.bc-head h2{font-size:21px}.bc-new{padding:9px 11px}.bc-filter>*{width:100%;max-width:none}.bc-table{min-width:780px} }
</style>

<div class="bc-page">
    <div class="bc-head">
        <div><h2>Bons de commande</h2><p>Retrouvez les commandes clients et leur état de livraison.</p></div>
    </div>

    @include('partials.alerts')
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="bc-stats">
        <div class="bc-stat"><div class="bc-stat-label">Toutes les commandes</div><div class="bc-stat-value">{{ number_format($stats['total'], 0, ',', ' ') }}</div></div>
        <div class="bc-stat"><div class="bc-stat-label"><span class="bc-dot wait"></span>En attente de livraison</div><div class="bc-stat-value">{{ number_format($stats['attente'], 0, ',', ' ') }}</div></div>
        <div class="bc-stat"><div class="bc-stat-label"><span class="bc-dot part"></span>Partiellement livrées</div><div class="bc-stat-value">{{ number_format($stats['partielle'], 0, ',', ' ') }}</div></div>
        <div class="bc-stat"><div class="bc-stat-label"><span class="bc-dot done"></span>Entièrement livrées</div><div class="bc-stat-value">{{ number_format($stats['livree'], 0, ',', ' ') }}</div></div>
    </div>

    <div class="bc-card">
        <form class="bc-filter" method="GET" action="{{ route('bons-commandes.index') }}">
            <input class="form-control" type="date" name="date" value="{{ $date }}" aria-label="Filtrer par date de commande">
            <input class="form-control bc-search" name="search" value="{{ request('search') }}" placeholder="Rechercher par référence, client ou téléphone">
            <select class="form-control bc-status-filter" name="statut" aria-label="Filtrer par état">
                <option value="">Tous les états</option>
                @foreach(['attente'=>'En attente','partielle'=>'Partiellement livrées','livree'=>'Entièrement livrées'] as $key=>$label)
                    <option value="{{ $key }}" @selected(request('statut')===$key)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="form-control bc-size" name="per_page" aria-label="Nombre de commandes à afficher">
                @foreach(['20'=>'20 par page','50'=>'50 par page','100'=>'100 par page','all'=>'Tout afficher'] as $value=>$label)
                    <option value="{{ $value }}" @selected((string)request('per_page', $perPageInput)===(string)$value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search mr-1"></i> Afficher</button>
        </form>

        <div class="table-responsive">
            <table class="table bc-table">
                <thead><tr><th>Référence</th><th>Client</th><th>Date de commande</th><th>Articles</th><th>Livraisons</th><th>État</th><th></th></tr></thead>
                <tbody>
                @forelse($bons as $bon)
                    @php
                        [$badge, $etatTexte] = match ($bon->livraison_statut) {
                            'livree' => ['done', 'Entièrement livrée'],
                            'partielle' => ['part', 'Partiellement livrée'],
                            default => ['wait', 'En attente'],
                        };
                    @endphp
                    <tr>
                        <td><span class="bc-ref">{{ $bon->reference }}</span></td>
                        <td><div class="bc-client">{{ $bon->client->nom_complet ?? $bon->client_nom ?? 'Client divers' }}</div><div class="bc-sub">{{ $bon->client_telephone ?: 'Téléphone non renseigné' }}</div></td>
                        <td><div>{{ $bon->created_at->format('d/m/Y') }}</div><div class="bc-sub">{{ $bon->created_at->format('H:i') }}</div></td>
                        <td class="bc-count">{{ $bon->lignes_count }} article(s)</td>
                        <td class="bc-count">{{ $bon->livraisons_count }} livraison(s)</td>
                        <td><span class="bc-badge {{ $badge }}"><span class="bc-dot {{ $badge }}" aria-hidden="true"></span><span>{{ $etatTexte }}</span></span></td>
                        <td class="text-right"><a href="{{ route('bons-commandes.show',$bon) }}" class="btn btn-sm btn-outline-primary bc-open">Consulter <i class="fas fa-chevron-right ml-1"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="bc-empty"><i class="fas fa-clipboard-list"></i>Aucun bon de commande ne correspond à votre recherche.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($bons->hasPages() || request('per_page') !== 'all')
            <div class="bc-footer">@include('partials.pagination', ['paginator'=>$bons])</div>
        @endif
    </div>

</div>
@endsection
