@extends('layouts.app')

@section('title', 'Bon ' . $bonCommande->reference)
@section('page-title', 'Bon de commande')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('bons-commandes.index') }}">Bons de commande</a></li>
    <li class="breadcrumb-item active">{{ $bonCommande->reference }}</li>
@endsection

@section('content')
@php
    $role = auth()->user()->role;
    $peutLivrer = in_array($role, ['admin', 'magasinier'], true);
    $peutImprimerReste = in_array($role, ['admin', 'magasinier'], true);
    $badgeEtat = match ($bonCommande->livraison_statut) {
        'livree' => 'success',
        'partielle' => 'warning',
        default => 'secondary',
    };
    $derniereLivraison = $bonCommande->livraisons->sortByDesc('livree_le')->first();

    $totalCommande = (float) $bonCommande->lignes->sum('quantite_commandee');
    $totalLivre = (float) $bonCommande->lignes->sum(fn ($l) => (float) ($l->quantite_livree ?? 0));
    $totalReste = max($totalCommande - $totalLivre, 0);
    $pourcentGlobal = $totalCommande > 0 ? min(round(($totalLivre / $totalCommande) * 100), 100) : 0;

    $statut = $bonCommande->livraison_statut;
    $clientNom = $bonCommande->client->nom_complet ?? $bonCommande->client_nom ?? 'Client divers';
    $clientTel = $bonCommande->client->telephone ?? $bonCommande->client_telephone;
@endphp

<style>
    .bc-detail { --bc-navy:#20345f; --bc-ink:#243550; --bc-muted:#718096; --bc-line:#e7ecf2; --bc-soft:#f6f8fb;
        --bc-green:#10b981; --bc-green-d:#08754d; --bc-green-bg:#effaf5; --bc-green-bd:#bce8d5;
        --bc-amber:#f59e0b; --bc-amber-d:#9a5b00; --bc-amber-bg:#fff8e8; --bc-amber-bd:#f5d58f;
        --bc-blue:#3b82f6; --bc-blue-d:#2452c4; --bc-blue-bg:#f1f5ff; --bc-blue-bd:#c9d8ff; }

    .bc-detail .card { border:0; border-radius:14px; box-shadow:0 1px 2px rgba(31,45,61,.06), 0 8px 24px rgba(31,45,61,.06); overflow:hidden; margin-bottom:20px; }
    .bc-detail .card-header { background:#fff; border-bottom:1px solid var(--bc-line); padding:16px 20px; }
    .bc-detail .card-title { color:var(--bc-navy); font-size:15px; font-weight:700; margin:0; }
    .bc-detail .card-body { padding:20px; }
    .bc-detail .badge { border-radius:20px; padding:6px 12px; font-size:12px; font-weight:600; }
    .bc-muted { color:var(--bc-muted); font-size:13px; }

    /* En-tête */
    .bc-hero { background:linear-gradient(135deg,#20345f 0%,#2b4580 100%); border-radius:14px; color:#fff; padding:24px 26px; margin-bottom:20px; box-shadow:0 10px 28px rgba(32,52,95,.22); }
    .bc-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap; }
    .bc-hero h2 { margin:0 0 6px; font-size:28px; font-weight:800; letter-spacing:-.3px; color:#fff; }
    .bc-hero-client { font-size:14px; color:rgba(255,255,255,.82); }
    .bc-hero-client i { width:18px; opacity:.7; }
    .bc-hero .btn-back { background:rgba(255,255,255,.12); color:#fff; border:1px solid rgba(255,255,255,.28); border-radius:10px; font-weight:600; }
    .bc-hero .btn-back:hover { background:rgba(255,255,255,.22); color:#fff; }

    .bc-stepper { display:flex; align-items:flex-start; margin-top:26px; }
    .bc-step { flex:1; position:relative; text-align:center; }
    .bc-step::before { content:""; position:absolute; top:13px; left:-50%; width:100%; height:3px; background:rgba(255,255,255,.2); z-index:0; }
    .bc-step:first-child::before { display:none; }
    .bc-step.done::before, .bc-step.current::before { background:var(--bc-green); }
    .bc-step-dot { position:relative; z-index:1; width:28px; height:28px; border-radius:50%; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; font-size:12px; background:#3a5089; color:rgba(255,255,255,.55); border:2px solid rgba(255,255,255,.25); }
    .bc-step.done .bc-step-dot { background:var(--bc-green); border-color:var(--bc-green); color:#fff; }
    .bc-step.current.wait .bc-step-dot { background:var(--bc-amber); border-color:var(--bc-amber); color:#fff; box-shadow:0 0 0 5px rgba(245,158,11,.25); }
    .bc-step.current.part .bc-step-dot { background:var(--bc-blue); border-color:var(--bc-blue); color:#fff; box-shadow:0 0 0 5px rgba(59,130,246,.28); }
    .bc-step.current.done .bc-step-dot { background:var(--bc-green); border-color:var(--bc-green); color:#fff; box-shadow:0 0 0 5px rgba(16,185,129,.25); }
    .bc-step-label { font-size:12px; font-weight:600; color:rgba(255,255,255,.6); }
    .bc-step.done .bc-step-label, .bc-step.current .bc-step-label { color:#fff; }

    /* Indicateurs */
    .bc-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; }
    .bc-kpi { background:#fff; border-radius:14px; padding:16px 18px; box-shadow:0 1px 2px rgba(31,45,61,.06), 0 8px 24px rgba(31,45,61,.06); border-left:4px solid var(--bc-line); }
    .bc-kpi.k-order { border-left-color:var(--bc-navy); }
    .bc-kpi.k-done { border-left-color:var(--bc-green); }
    .bc-kpi.k-left { border-left-color:var(--bc-amber); }
    .bc-kpi-value { font-size:24px; font-weight:800; color:var(--bc-navy); line-height:1.1; }
    .bc-kpi-label { color:var(--bc-muted); font-size:13px; margin-top:4px; }

    /* Table produits */
    .bc-table th { border-top:0; background:var(--bc-soft); color:#788397; font-size:12px; font-weight:600; white-space:nowrap; padding:12px 16px; }
    .bc-table td { vertical-align:middle; padding:14px 16px; border-color:var(--bc-line); }
    .bc-table tbody tr:hover { background:#fafcff; }
    .bc-product { font-weight:700; color:var(--bc-ink); }
    .bc-progress { height:7px; background:#e9eef5; border-radius:10px; overflow:hidden; min-width:110px; margin-top:7px; }
    .bc-progress > span { display:block; height:100%; border-radius:10px; background:var(--bc-blue); }
    .bc-progress.is-full > span { background:var(--bc-green); }
    .bc-progress.is-zero > span { background:transparent; }
    .bc-reste { display:inline-block; font-weight:700; border-radius:8px; padding:3px 10px; background:var(--bc-amber-bg); color:var(--bc-amber-d); }
    .bc-reste.is-zero { background:var(--bc-green-bg); color:var(--bc-green-d); }

    /* Colonne latérale */
    .bc-aside-sticky { position:sticky; top:12px; }
    .bc-info-row { display:flex; align-items:flex-start; gap:12px; padding:12px 0; border-bottom:1px solid #edf0f4; }
    .bc-info-row:first-child { padding-top:0; }
    .bc-info-row:last-child { border-bottom:0; padding-bottom:0; }
    .bc-info-icon { flex:0 0 34px; height:34px; border-radius:10px; background:var(--bc-blue-bg); color:var(--bc-blue-d); display:flex; align-items:center; justify-content:center; font-size:14px; }
    .bc-info-label { color:var(--bc-muted); font-size:12px; }
    .bc-info-value { color:var(--bc-ink); font-weight:600; margin-top:1px; }

    /* Formulaire de livraison */
    .bc-all { background:var(--bc-amber-bg); border:1px solid var(--bc-amber-bd); border-radius:10px; padding:11px 14px; margin-bottom:14px; }
    .bc-all label { color:var(--bc-amber-d); }
    .bc-line { border:1px solid var(--bc-line); border-radius:12px; padding:14px; margin-bottom:12px; transition:border-color .15s, background .15s; }
    .bc-line:last-child { margin-bottom:0; }
    .bc-line.is-selected { border-color:var(--bc-amber-bd); background:var(--bc-amber-bg); }
    .bc-line-name { font-weight:700; color:var(--bc-ink); margin-bottom:4px; }
    .bc-line-stats { display:flex; gap:6px; flex-wrap:wrap; margin:8px 0; }
    .bc-chip { font-size:12px; border-radius:20px; padding:3px 10px; background:var(--bc-soft); color:#586579; border:1px solid var(--bc-line); }
    .bc-chip.reste { background:var(--bc-amber-bg); color:var(--bc-amber-d); border-color:var(--bc-amber-bd); font-weight:600; }
    .bc-chip.stock-ok { background:var(--bc-green-bg); color:var(--bc-green-d); border-color:var(--bc-green-bd); }
    .bc-chip.stock-ko { background:#fff1f1; color:#b42318; border-color:#f7c4c0; }
    .bc-detail .form-control { border-radius:10px; }
    .bc-detail .btn { border-radius:10px; font-weight:600; }

    /* Historique en frise */
    .bc-timeline { padding:6px 20px 20px; }
    .bc-event { position:relative; padding:0 0 22px 30px; }
    .bc-event:last-child { padding-bottom:0; }
    .bc-event::before { content:""; position:absolute; left:8px; top:18px; bottom:-4px; width:2px; background:var(--bc-line); }
    .bc-event:last-child::before { display:none; }
    .bc-event::after { content:""; position:absolute; left:0; top:4px; width:18px; height:18px; border-radius:50%; background:#fff; border:4px solid var(--bc-green); }
    .bc-event-head { display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:10px; }
    .bc-event-date { color:#253957; font-weight:700; }
    .bc-event-by { color:var(--bc-muted); font-size:13px; }
    .bc-event-box { border:1px solid var(--bc-line); border-radius:12px; overflow:hidden; }
    .bc-event-box .bc-table th { padding:9px 14px; }
    .bc-event-box .bc-table td { padding:10px 14px; }
    .bc-empty { text-align:center; color:var(--bc-muted); padding:34px 20px; }
    .bc-empty i { display:block; font-size:28px; margin-bottom:10px; color:#cbd5e1; }

    @media(max-width:991px) { .bc-aside-sticky { position:static; } }
    @media(max-width:767px) {
        .bc-hero { padding:20px 18px; }
        .bc-hero h2 { font-size:22px; }
        .bc-kpis { grid-template-columns:1fr; gap:10px; }
        .bc-step-label { font-size:11px; }
        .bc-table { min-width:520px; }
    }
</style>

<div class="bc-detail">

    {{-- En-tête --}}
    <div class="bc-hero">
        <div class="bc-hero-top">
            <div>
                <h2>{{ $bonCommande->reference }}</h2>
                <div class="bc-hero-client"><i class="fas fa-user"></i>{{ $clientNom }}@if($clientTel) &nbsp;<i class="fas fa-phone ml-2"></i>{{ $clientTel }}@endif</div>
            </div>
            <a href="{{ route('bons-commandes.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
        </div>

        <div class="bc-stepper" aria-label="État de la commande">
            <div class="bc-step {{ $statut === 'attente' ? 'current wait' : 'done' }}">
                <div class="bc-step-dot"><i class="fas {{ $statut === 'attente' ? 'fa-clock' : 'fa-check' }}"></i></div>
                <div class="bc-step-label">En attente</div>
            </div>
            <div class="bc-step {{ $statut === 'partielle' ? 'current part' : ($statut === 'livree' ? 'done' : '') }}">
                <div class="bc-step-dot"><i class="fas {{ $statut === 'livree' ? 'fa-check' : 'fa-truck' }}"></i></div>
                <div class="bc-step-label">Partiellement livrée</div>
            </div>
            <div class="bc-step {{ $statut === 'livree' ? 'current done' : '' }}">
                <div class="bc-step-dot"><i class="fas fa-flag-checkered"></i></div>
                <div class="bc-step-label">Entièrement livrée</div>
            </div>
        </div>
    </div>


    {{-- Indicateurs --}}
    <div class="bc-kpis">
        <div class="bc-kpi k-order">
            <div class="bc-kpi-value">{{ number_format($totalCommande, 2, ',', ' ') }}</div>
            <div class="bc-kpi-label">Quantité commandée</div>
        </div>
        <div class="bc-kpi k-done">
            <div class="bc-kpi-value">{{ number_format($totalLivre, 2, ',', ' ') }}</div>
            <div class="bc-kpi-label">Déjà livré ({{ $pourcentGlobal }} %)</div>
        </div>
        <div class="bc-kpi k-left">
            <div class="bc-kpi-value">{{ number_format($totalReste, 2, ',', ' ') }}</div>
            <div class="bc-kpi-label">Reste à livrer</div>
        </div>
    </div>

    <div class="row">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title">Commande et reste à livrer</h3>
                    <span class="bc-muted">{{ $bonCommande->lignes->count() }} article(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table bc-table mb-0">
                        <thead><tr><th>Produit et avancement</th><th class="text-right">Commandé</th><th class="text-right">Déjà livré</th><th class="text-right">Reste</th></tr></thead>
                        <tbody>
                            @forelse($bonCommande->lignes as $ligne)
                                @php
                                    $dejaLivree = (float)($ligne->quantite_livree ?? 0);
                                    $reste = max((float)$ligne->quantite_commandee - $dejaLivree, 0);
                                    $pct = (float)$ligne->quantite_commandee > 0 ? min(round(($dejaLivree / (float)$ligne->quantite_commandee) * 100), 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="bc-product">{{ $ligne->libelle }}</div>
                                        <div class="bc-progress {{ $pct >= 100 ? 'is-full' : ($pct <= 0 ? 'is-zero' : '') }}"><span style="width:{{ $pct }}%"></span></div>
                                    </td>
                                    <td class="text-right text-nowrap">{{ number_format($ligne->quantite_commandee, 2, ',', ' ') }} {{ $ligne->unite }}</td>
                                    <td class="text-right text-nowrap">{{ number_format($dejaLivree, 2, ',', ' ') }} {{ $ligne->unite }}</td>
                                    <td class="text-right text-nowrap"><span class="bc-reste {{ $reste <= 0 ? 'is-zero' : '' }}">{{ number_format($reste, 2, ',', ' ') }} {{ $ligne->unite }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted p-4">Aucun article dans ce bon.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title">Historique des livraisons</h3>
                    <span class="bc-muted">{{ $bonCommande->livraisons->count() }} livraison(s)</span>
                </div>
                @forelse($bonCommande->livraisons->sortByDesc('livree_le') as $livraison)
                    @if($loop->first)<div class="bc-timeline pt-4">@endif
                    <div class="bc-event">
                        <div class="bc-event-head">
                            <div>
                                <div class="bc-event-date">{{ $livraison->livree_le->format('d/m/Y à H:i') }}</div>
                                <div class="bc-event-by">Remis par {{ $livraison->user->nom_complet ?? 'Magasinier' }}</div>
                            </div>
                            <a href="{{ route('bons-commandes.livraisons.pdf', [$bonCommande, $livraison]) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print mr-1"></i> Imprimer le bon</a>
                        </div>
                        <div class="bc-event-box">
                            <div class="table-responsive"><table class="table bc-table mb-0">
                                <thead><tr><th>Article livré lors de cette sortie</th><th class="text-right">Quantité remise</th></tr></thead>
                                <tbody>@forelse($livraison->lignes as $item)<tr><td>{{ $item->libelle }}</td><td class="text-right text-nowrap font-weight-bold">{{ number_format($item->quantite, 2, ',', ' ') }} {{ $item->unite }}</td></tr>@empty<tr><td colspan="2" class="text-muted">Aucun article enregistré pour cette livraison.</td></tr>@endforelse</tbody>
                            </table></div>
                        </div>
                    </div>
                    @if($loop->last)</div>@endif
                @empty
                    <div class="bc-empty"><i class="fas fa-box-open"></i>Aucune livraison enregistrée pour le moment.</div>
                @endforelse
            </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">
            <div class="bc-aside-sticky">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Informations du bon</h3></div>
                    <div class="card-body">
                        <div class="bc-info-row"><div class="bc-info-icon"><i class="fas fa-user"></i></div><div><div class="bc-info-label">Client</div><div class="bc-info-value">{{ $clientNom }}</div>@if($clientTel)<div class="bc-muted">{{ $clientTel }}</div>@endif</div></div>
                        <div class="bc-info-row"><div class="bc-info-icon"><i class="fas fa-hashtag"></i></div><div><div class="bc-info-label">Référence</div><div class="bc-info-value">{{ $bonCommande->reference }}</div></div></div>
                        <div class="bc-info-row"><div class="bc-info-icon"><i class="fas fa-calendar-alt"></i></div><div><div class="bc-info-label">Date de commande</div><div class="bc-info-value">{{ $bonCommande->created_at->format('d/m/Y à H:i') }}</div></div></div>
                        <div class="bc-info-row"><div class="bc-info-icon"><i class="fas fa-truck"></i></div><div><div class="bc-info-label">Dernière livraison</div><div class="bc-info-value">{{ $derniereLivraison ? $derniereLivraison->livree_le->format('d/m/Y à H:i') : 'Aucune livraison' }}</div></div></div>
                        <div class="bc-info-row"><div class="bc-info-icon"><i class="fas fa-user-edit"></i></div><div><div class="bc-info-label">Enregistré par</div><div class="bc-info-value">{{ $bonCommande->user->nom_complet ?? '—' }}</div></div></div>
                    </div>
                </div>

                @if($peutImprimerReste)
                    <a class="btn btn-outline-primary btn-block mb-3" href="{{ route('bons-commandes.reste', $bonCommande) }}" target="_blank">
                        <i class="fas fa-print mr-1"></i> Imprimer le ticket du reste à livrer
                    </a>
                @endif

                @if($peutLivrer && $bonCommande->livraison_statut !== 'livree' && $bonsRestants->isNotEmpty())
                    <div class="card">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-truck text-warning mr-2"></i>Préparer une livraison</h3></div>
                        <form method="POST" action="{{ route('bons-commandes.livraisons.store', $bonCommande) }}" id="deliveryForm">
                            @csrf
                            <div class="card-body">
                                <p class="bc-muted">Cochez uniquement les articles remis et indiquez la quantité livrée lors de cette sortie. Pour livrer tout le reste, cochez la case ci-dessous.</p>
                                <div class="bc-all custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="toutLivre" name="tout_livre" value="1">
                                    <label class="custom-control-label font-weight-bold" for="toutLivre">J’ai tout livré</label>
                                </div>
                                @foreach($bonsRestants as $ligne)
                                    @php
                                        $dejaLivree = (float)($ligne->quantite_livree ?? 0);
                                        $reste = max((float)$ligne->quantite_commandee - $dejaLivree, 0);
                                        $stock = (float)($produits->get($ligne->produit_id)?->stock_actuel ?? 0);
                                    @endphp
                                    <div class="bc-line">
                                        <div class="bc-line-name">{{ $ligne->libelle }}</div>
                                        <div class="bc-line-stats">
                                            <span class="bc-chip">Commandé : {{ number_format($ligne->quantite_commandee, 2, ',', ' ') }} {{ $ligne->unite }}</span>
                                            <span class="bc-chip">Déjà livré : {{ number_format($dejaLivree, 2, ',', ' ') }}</span>
                                            <span class="bc-chip reste">Reste : {{ number_format($reste, 2, ',', ' ') }} {{ $ligne->unite }}</span>
                                            @if($bonCommande->nature === 'commande')<span class="bc-chip {{ $stock < $reste ? 'stock-ko' : 'stock-ok' }}">Stock disponible : {{ number_format($stock, 2, ',', ' ') }} {{ $ligne->unite }}</span>@endif
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input delivery-select" type="checkbox" id="select-{{ $ligne->id }}" name="lignes_selectionnees[]" value="{{ $ligne->id }}" @checked(in_array((string)$ligne->id, (array)old('lignes_selectionnees', []), true))>
                                            <label class="custom-control-label small font-weight-bold" for="select-{{ $ligne->id }}">Livrer cet article</label>
                                        </div>
                                        <label class="small font-weight-bold mt-2 mb-1" for="qte-{{ $ligne->id }}">Quantité remise maintenant</label>
                                        <input id="qte-{{ $ligne->id }}" class="form-control delivery-quantity" type="number" name="quantites[{{ $ligne->id }}]" min="0.01" max="{{ $reste }}" step="0.01" value="{{ old('quantites.'.$ligne->id) }}" placeholder="Quantité remise" @disabled(!in_array((string)$ligne->id, (array)old('lignes_selectionnees', []), true))>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card-footer bg-white"><button class="btn btn-warning btn-block" type="submit"><i class="fas fa-truck mr-1"></i> Valider cette livraison</button></div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const toutLivre = document.getElementById('toutLivre');
    function actualiserChampsLivraison() {
        document.querySelectorAll('.delivery-select').forEach(selection => {
            const quantity = document.getElementById(selection.id.replace('select-', 'qte-'));
            selection.disabled = toutLivre?.checked ?? false;
            quantity.disabled = (toutLivre?.checked ?? false) || !selection.checked;
            selection.closest('.bc-line')?.classList.toggle('is-selected', selection.checked && !selection.disabled);
        });
    }
    toutLivre?.addEventListener('change', actualiserChampsLivraison);
    document.querySelectorAll('.delivery-select').forEach(selection => selection.addEventListener('change', actualiserChampsLivraison));
    actualiserChampsLivraison();
</script>
@endsection
