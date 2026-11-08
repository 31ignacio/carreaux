{{-- resources/views/fournisseurs/show.blade.php --}}
@extends('layouts.app')

@section('title', $fournisseur->nom)
@section('page-title', 'Fiche Fournisseur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
    <li class="breadcrumb-item active">{{ $fournisseur->nom }}</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap');
.fs-wrap { font-family:'DM Sans',sans-serif; }

/* HERO FICHE */
.fs-hero {
    border-radius:20px; padding:28px 32px; margin-bottom:22px;
    position:relative; overflow:hidden;
    box-shadow:0 10px 40px rgba(15,23,42,.18);
}
.fs-hero-bg {
    position:absolute; inset:0; z-index:0;
    background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 60%,#0c4a6e 100%);
}
.fs-hero-orb1 { position:absolute; top:-60px; right:-60px; width:240px; height:240px; border-radius:50%; background:rgba(14,165,233,.12); z-index:0; }
.fs-hero-orb2 { position:absolute; bottom:-40px; left:30%; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.04); z-index:0; }

.fs-hero-content { position:relative; z-index:1; display:flex; align-items:center; gap:22px; flex-wrap:wrap; }
.fs-hero-avatar {
    width:72px; height:72px; border-radius:20px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-family:'Syne',sans-serif; font-size:24px; font-weight:800; color:#fff;
    border:3px solid rgba(255,255,255,.2);
}
.fs-hero-info { flex:1; min-width:0; }
.fs-hero-name { font-family:'Syne',sans-serif; font-size:clamp(1.1rem,3vw,1.5rem); font-weight:800; color:#fff; margin:0 0 6px; }
.fs-hero-meta { display:flex; flex-wrap:wrap; gap:16px; }
.fs-meta-item { display:flex; align-items:center; gap:6px; font-size:12px; color:rgba(255,255,255,.55); }
.fs-meta-item i { font-size:10px; }
.fs-hero-actions { display:flex; gap:8px; align-items:center; }
.fs-action-btn {
    display:inline-flex; align-items:center; gap:7px;
    border-radius:11px; padding:9px 18px; font-size:12px; font-weight:700;
    cursor:pointer; transition:.2s; text-decoration:none; border:none;
}
.fs-action-btn:hover { transform:translateY(-2px); text-decoration:none; }
.fab-edit  { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; box-shadow:0 4px 12px rgba(217,119,6,.3); }
.fab-back  { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.8); }
.fab-back:hover { background:rgba(255,255,255,.18); color:#fff; }
.fab-del   { background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
.fab-del:hover { background:rgba(239,68,68,.25); color:#fff; }

/* BADGE STATUT HERO */
.fs-statut-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800;
}
.fsb-actif   { background:rgba(16,185,129,.2); border:1px solid rgba(16,185,129,.3); color:#34d399; }
.fsb-inactif { background:rgba(148,163,184,.15); border:1px solid rgba(148,163,184,.2); color:#94a3b8; }

/* KPI */
.fs-kpi-row { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:22px; }
@media(max-width:768px){ .fs-kpi-row { grid-template-columns:1fr 1fr; } }
.fs-kpi { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:18px; box-shadow:0 2px 12px rgba(11,15,26,.05); }
.fs-kpi-ico { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:15px; margin-bottom:10px; }
.fs-kpi-val { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; line-height:1; margin-bottom:3px; }
.fs-kpi-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.6; }

/* GRID BODY */
.fs-body { display:grid; grid-template-columns:1fr 300px; gap:18px; align-items:start; }
@media(max-width:1024px){ .fs-body { grid-template-columns:1fr; } }

/* CARDS */
.fs-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; overflow:hidden; box-shadow:0 2px 12px rgba(11,15,26,.04); margin-bottom:14px; }
.fs-card-hd { padding:13px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:10px; background:#fafbff; }
.fs-card-ico { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#fff; flex-shrink:0; }
.fs-card-hd h6 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin:0; }
.fs-card-bd { padding:18px 20px; }

/* INFO GRID */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:600px){ .info-grid { grid-template-columns:1fr; } }
.info-item {}
.info-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#9ca3af; margin-bottom:5px; }
.info-val { font-size:13px; font-weight:600; color:#0f172a; }
.info-val a { color:#0ea5e9; text-decoration:none; font-weight:700; }
.info-val a:hover { text-decoration:underline; }

/* TABLE PRODUITS */
.fs-table { width:100%; font-size:12px; border-collapse:collapse; }
.fs-table th { padding:9px 14px; font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#64748b; border-bottom:1px solid #f1f5f9; background:#f8fafc; }
.fs-table td { padding:11px 14px; vertical-align:middle; border-bottom:1px solid #f9fafb; }
.fs-table tr:last-child td { border-bottom:none; }
.fs-table tr:hover td { background:#f0f9ff; }

/* TABLE MOUVEMENTS */
.mv-badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:700; }
.mv-entree { background:#d1fae5; color:#065f46; }
.mv-sortie { background:#fee2e2; color:#7f1d1d; }

/* STOCK BAR */
.stock-bar { height:4px; border-radius:4px; background:#f1f5f9; overflow:hidden; margin-top:4px; }
.stock-fill { height:100%; border-radius:4px; }

/* Sidebar */
.fs-info-pill { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #f8fafc; }
.fs-info-pill:last-child { border-bottom:none; }
.fip-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; }
.fip-lbl { font-size:10px; color:#9ca3af; font-weight:600; }
.fip-val { font-size:12px; font-weight:700; color:#0f172a; }

.fs-empty { padding:30px 20px; text-align:center; color:#94a3b8; }
.fs-empty i { font-size:30px; display:block; margin-bottom:8px; opacity:.3; }

@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.fs-hero  { animation:fadeUp .35s ease both; }
.fs-kpi-row{ animation:fadeUp .35s .07s ease both; }
.fs-body  { animation:fadeUp .35s .14s ease both; }
</style>
@endpush

@section('content')
@php
    $colors  = ['#0ea5e9','#059669','#d97706','#7c3aed','#dc2626','#0891b2','#16a34a'];
    $color   = $colors[$fournisseur->id % count($colors)];
    $init    = strtoupper(substr($fournisseur->nom, 0, 2));
    $totalAchats = $fournisseur->mouvementsStock()->where('type','entree')->sum(\DB::raw('quantite * prix_unitaire'));
    $nbEntrees   = $fournisseur->mouvementsStock()->where('type','entree')->count();
@endphp

<div class="fs-wrap">

    <!-- HERO -->
    <div class="fs-hero">
        <div class="fs-hero-bg"></div>
        <div class="fs-hero-orb1"></div>
        <div class="fs-hero-orb2"></div>
        <div class="fs-hero-content">
            <div class="fs-hero-avatar" style="background:{{ $color }}">{{ $init }}</div>
            <div class="fs-hero-info">
                <h1 class="fs-hero-name">{{ $fournisseur->nom }}</h1>
                <div class="fs-hero-meta">
                    @if($fournisseur->ville)
                        <span class="fs-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $fournisseur->ville }}</span>
                    @endif
                    @if($fournisseur->telephone)
                        <span class="fs-meta-item"><i class="fas fa-phone"></i> {{ $fournisseur->telephone }}</span>
                    @endif
                    @if($fournisseur->contact_personne)
                        <span class="fs-meta-item"><i class="fas fa-user"></i> {{ $fournisseur->contact_personne }}</span>
                    @endif
                    <span class="fs-statut-badge {{ $fournisseur->actif ? 'fsb-actif' : 'fsb-inactif' }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                        {{ $fournisseur->actif ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            <div class="fs-hero-actions">
                <a href="{{ route('fournisseurs.index') }}" class="fs-action-btn fab-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="fs-action-btn fab-edit">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <button onclick="confirmDelete('del-fs-{{ $fournisseur->id }}')" class="fs-action-btn fab-del">
                    <i class="fas fa-trash"></i>
                </button>
                <form id="del-fs-{{ $fournisseur->id }}" action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-none">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <!-- KPI -->
    <div class="fs-kpi-row">
        <div class="fs-kpi">
            <div class="fs-kpi-ico" style="background:linear-gradient(135deg,#e0f2fe,#bae6fd); color:#0369a1"><i class="fas fa-boxes"></i></div>
            <div class="fs-kpi-val" style="color:#0c4a6e">{{ $fournisseur->produits->count() }}</div>
            <div class="fs-kpi-lbl" style="color:#0c4a6e">Produits liés</div>
        </div>
        <div class="fs-kpi">
            <div class="fs-kpi-ico" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#059669"><i class="fas fa-arrow-down"></i></div>
            <div class="fs-kpi-val" style="color:#065f46">{{ $nbEntrees }}</div>
            <div class="fs-kpi-lbl" style="color:#065f46">Entrées stock</div>
        </div>
        <div class="fs-kpi">
            <div class="fs-kpi-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a); color:#d97706"><i class="fas fa-coins"></i></div>
            <div class="fs-kpi-val" style="color:#92400e; font-size:1.1rem">
                {{ $totalAchats >= 1000000 ? number_format($totalAchats/1000000,1,',',' ').'M' : number_format($totalAchats/1000,0,',',' ').'K' }}
            </div>
            <div class="fs-kpi-lbl" style="color:#92400e">Total achats (FCFA)</div>
        </div>
    </div>

    <!-- BODY -->
    <div class="fs-body">

        <!-- GAUCHE -->
        <div>

            <!-- Infos contact -->
            <div class="fs-card">
                <div class="fs-card-hd">
                    <span class="fs-card-ico" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)"><i class="fas fa-address-card"></i></span>
                    <h6>Informations de contact</h6>
                </div>
                <div class="fs-card-bd">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-lbl">Nom / Raison sociale</div>
                            <div class="info-val">{{ $fournisseur->nom }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-lbl">Personne de contact</div>
                            <div class="info-val">{{ $fournisseur->contact_personne ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-lbl">Téléphone</div>
                            <div class="info-val">
                                @if($fournisseur->telephone)
                                    <a href="tel:{{ $fournisseur->telephone }}">
                                        <i class="fas fa-phone" style="font-size:10px; margin-right:4px"></i>{{ $fournisseur->telephone }}
                                    </a>
                                @else — @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-lbl">Email</div>
                            <div class="info-val">
                                @if($fournisseur->email)
                                    <a href="mailto:{{ $fournisseur->email }}">
                                        <i class="fas fa-envelope" style="font-size:10px; margin-right:4px"></i>{{ $fournisseur->email }}
                                    </a>
                                @else — @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-lbl">Ville</div>
                            <div class="info-val">{{ $fournisseur->ville ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-lbl">Adresse</div>
                            <div class="info-val">{{ $fournisseur->adresse ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits liés -->
            <div class="fs-card">
                <div class="fs-card-hd">
                    <span class="fs-card-ico" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-boxes"></i></span>
                    <h6>Produits liés</h6>
                    <span style="margin-left:auto; font-size:11px; font-weight:700; background:#ede9fe; color:#7c3aed; padding:2px 10px; border-radius:20px">
                        {{ $fournisseur->produits->count() }}
                    </span>
                </div>
                @if($fournisseur->produits->count())
                <div class="table-responsive">
                    <table class="fs-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-right">Stock</th>
                                <th class="text-right">Prix détail</th>
                                <th>Statut stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fournisseur->produits as $p)
                                @php
                                    $pct = $p->stock_minimum > 0 ? min(100, round(($p->stock_actuel / ($p->stock_minimum * 2)) * 100)) : ($p->stock_actuel > 0 ? 100 : 0);
                                    $barColor = $p->statut_stock === 'rupture' ? '#ef4444' : ($p->statut_stock === 'faible' ? '#f59e0b' : '#10b981');
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('produits.show', $p) }}" style="font-weight:700; color:#0f172a; text-decoration:none">
                                            {{ $p->libelle }}
                                        </a>
                                        @if($p->reference)
                                            <div style="font-size:10px; color:#94a3b8">{{ $p->reference }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong style="color:{{ $barColor }}">{{ number_format($p->stock_actuel,0,',',' ') }}</strong>
                                        <small style="color:#94a3b8"> {{ $p->unite }}</small>
                                        <div class="stock-bar">
                                            <div class="stock-fill" style="width:{{ $pct }}%; background:{{ $barColor }}"></div>
                                        </div>
                                    </td>
                                    <td class="text-right" style="font-weight:700">
                                        {{ number_format($p->prix_detail,0,',',' ') }} F
                                    </td>
                                    <td>
                                        <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:700;
                                            background:{{ $p->statut_stock==='rupture' ? '#fee2e2' : ($p->statut_stock==='faible' ? '#fef3c7' : '#d1fae5') }};
                                            color:{{ $p->statut_stock==='rupture' ? '#dc2626' : ($p->statut_stock==='faible' ? '#d97706' : '#059669') }}">
                                            {{ $p->statut_stock_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="fs-empty">
                        <i class="fas fa-box-open"></i>
                        <p>Aucun produit lié à ce fournisseur</p>
                    </div>
                @endif
            </div>

            <!-- Derniers mouvements -->
            <div class="fs-card">
                <div class="fs-card-hd">
                    <span class="fs-card-ico" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="fas fa-exchange-alt"></i></span>
                    <h6>Derniers approvisionnements</h6>
                    <a href="{{ route('stock.entrees') }}" style="margin-left:auto; font-size:10px; color:#059669; font-weight:700; text-decoration:none">
                        Voir tout →
                    </a>
                </div>
                @if($mouvements->count())
                <div class="table-responsive">
                    <table class="fs-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th class="text-right">Quantité</th>
                                <th class="text-right">Prix unit.</th>
                                <th class="text-right">Montant</th>
                                <th>Par</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mouvements as $m)
                                <tr>
                                    <td style="color:#94a3b8; font-size:11px; white-space:nowrap">
                                        {{ $m->created_at->format('d/m/Y') }}<br>
                                        <small>{{ $m->created_at->format('H:i') }}</small>
                                    </td>
                                    <td style="font-weight:600">{{ $m->produit->libelle }}</td>
                                    <td class="text-right" style="font-weight:800; color:#059669">
                                        +{{ number_format($m->quantite,0,',',' ') }}
                                        <small style="color:#94a3b8">{{ $m->produit->unite }}</small>
                                    </td>
                                    <td class="text-right">{{ number_format($m->prix_unitaire,0,',',' ') }} F</td>
                                    <td class="text-right" style="font-weight:700">
                                        {{ number_format($m->quantite * $m->prix_unitaire,0,',',' ') }} F
                                    </td>
                                    <td style="color:#64748b; font-size:11px">{{ $m->user->prenom }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="fs-empty">
                        <i class="fas fa-history"></i>
                        <p>Aucun approvisionnement enregistré</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- DROITE SIDEBAR -->
        <div>

            <!-- Fiche résumé -->
            <div class="fs-card">
                <div class="fs-card-hd">
                    <span class="fs-card-ico" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)"><i class="fas fa-id-card"></i></span>
                    <h6>Fiche rapide</h6>
                </div>
                <div class="fs-card-bd" style="padding:14px 18px">
                    <div class="fs-info-pill">
                        <div class="fip-ico" style="background:#e0f2fe; color:#0369a1"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="fip-lbl">Raison sociale</div>
                            <div class="fip-val">{{ $fournisseur->nom }}</div>
                        </div>
                    </div>
                    <div class="fs-info-pill">
                        <div class="fip-ico" style="background:#d1fae5; color:#059669"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="fip-lbl">Inscrit le</div>
                            <div class="fip-val">{{ $fournisseur->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="fs-info-pill">
                        <div class="fip-ico" style="background:#fef3c7; color:#d97706"><i class="fas fa-edit"></i></div>
                        <div>
                            <div class="fip-lbl">Dernière modif.</div>
                            <div class="fip-val">{{ $fournisseur->updated_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="fs-info-pill">
                        <div class="fip-ico" style="background:#ede9fe; color:#7c3aed"><i class="fas fa-boxes"></i></div>
                        <div>
                            <div class="fip-lbl">Produits référencés</div>
                            <div class="fip-val">{{ $fournisseur->produits->count() }} produit(s)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="fs-card">
                <div class="fs-card-hd">
                    <span class="fs-card-ico" style="background:linear-gradient(135deg,#64748b,#475569)"><i class="fas fa-bolt"></i></span>
                    <h6>Actions</h6>
                </div>
                <div class="fs-card-bd" style="padding:12px 16px; display:flex; flex-direction:column; gap:8px">
                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                       style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;text-decoration:none;font-size:12px;font-weight:700;transition:.2s"
                       onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                        <i class="fas fa-edit"></i> Modifier ce fournisseur
                    </a>
                    <a href="{{ route('stock.entrees.create') }}"
                       style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:linear-gradient(135deg,#059669,#10b981);color:#fff;text-decoration:none;font-size:12px;font-weight:700;transition:.2s"
                       onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                        <i class="fas fa-plus-circle"></i> Nouvelle entrée stock
                    </a>
                    <button onclick="confirmDelete('del-fs2-{{ $fournisseur->id }}')"
                       style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:12px;font-weight:700;cursor:pointer;transition:.2s;width:100%"
                       onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <i class="fas fa-trash"></i> Supprimer ce fournisseur
                    </button>
                    <form id="del-fs2-{{ $fournisseur->id }}" action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection