
@extends('layouts.app')
@section('title', $client->nom_complet)
@section('page-title', 'Fiche Client')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">{{ $client->nom_complet }}</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Syne:wght@700;800&display=swap');

:root {
    --bg:#F4F6FB; --card:#fff; --border:#E8ECF0; --border-md:#D1D9E2;
    --text:#0F1A2A; --muted:#7A8899; --sh:0 2px 14px rgba(15,26,42,.07);
    --sh-md:0 8px 32px rgba(15,26,42,.11);
    --indigo:#4F46E5; --indigo-l:#EEF2FF;
    --green:#16A34A; --green-l:#ECFDF5;
    --amber:#D97706; --amber-l:#FFFBEB;
    --red:#DC2626;   --red-l:#FEF2F2;
    --blue:#2563EB;  --blue-l:#EFF6FF;
    --r:16px;
}
.cs-wrap { font-family:'DM Sans',sans-serif; color:var(--text); }

/* ─── HERO ─────────────────────────────── */
.cs-hero {
    border-radius:22px; padding:30px 32px; margin-bottom:22px;
    background:linear-gradient(135deg,#0F172A 0%,#1E1B4B 50%,#312E81 100%);
    position:relative; overflow:hidden; box-shadow:0 16px 48px rgba(15,23,42,.22);
    animation:fadeUp .35s ease both;
}
.cs-hero::before {
    content:''; position:absolute; width:360px; height:360px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,102,241,.18),transparent 65%);
    top:-120px; right:-100px; pointer-events:none;
}
.cs-hero::after {
    content:''; position:absolute; width:200px; height:200px; border-radius:50%;
    background:rgba(255,255,255,.03); bottom:-60px; left:25%; pointer-events:none;
}
.cs-hero-inner {
    position:relative; z-index:1;
    display:flex; align-items:center; gap:22px; flex-wrap:wrap;
}
.cs-avatar {
    width:70px; height:70px; border-radius:20px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-family:sans-serif; font-size:26px; font-weight:800; color:#fff;
    border:2.5px solid rgba(255,255,255,.18); box-shadow:0 8px 20px rgba(0,0,0,.25);
}
.cs-hero-name {
    font-family:sans-serif; font-size:clamp(1.1rem,3vw,1.55rem);
    font-weight:800; color:#fff; margin:0 0 8px; letter-spacing:-.02em;
}
.cs-hero-meta { display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
.cs-meta-chip {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 12px; border-radius:20px; font-size:10.5px; font-weight:700;
}
.cs-chip-type   { background:rgba(99,102,241,.2); border:1px solid rgba(99,102,241,.35); color:#a5b4fc; }
.cs-chip-actif  { background:rgba(16,185,129,.2); border:1px solid rgba(16,185,129,.35); color:#6ee7b7; }
.cs-chip-inactif{ background:rgba(148,163,184,.15);border:1px solid rgba(148,163,184,.25);color:#94a3b8; }
.cs-meta-item   { font-size:12px; color:rgba(255,255,255,.55); display:flex; align-items:center; gap:5px; }

.cs-hero-acts { margin-left:auto; display:flex; gap:8px; align-items:center; flex-shrink:0; }
.cs-hbtn {
    display:inline-flex; align-items:center; gap:7px;
    border-radius:11px; padding:9px 18px; font-size:12px; font-weight:700;
    cursor:pointer; border:none; font-family:'DM Sans',sans-serif;
    text-decoration:none; transition:all .18s;
}
.cs-hbtn:hover { transform:translateY(-2px); text-decoration:none; }
.cs-hbtn.edit  { background:linear-gradient(135deg,#D97706,#F59E0B); color:#fff; box-shadow:0 4px 14px rgba(217,119,6,.3); }
.cs-hbtn.back  { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); color:rgba(255,255,255,.8); }
.cs-hbtn.back:hover { background:rgba(255,255,255,.18); color:#fff; }
.cs-hbtn.del   { background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
.cs-hbtn.del:hover { background:rgba(239,68,68,.25); color:#fff; }
.cs-hbtn.modal-trigger { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.25); color:#fff; }
.cs-hbtn.modal-trigger:hover { background:rgba(255,255,255,.2); }

/* ─── KPI ─────────────────────────────── */
.cs-kpi-row {
    display:grid; grid-template-columns:repeat(4,1fr);
    gap:14px; margin-bottom:22px;
    animation:fadeUp .35s .07s ease both;
}
.cs-kpi {
    background:var(--card); border:1px solid var(--border); border-radius:var(--r);
    padding:18px 20px; box-shadow:var(--sh); transition:box-shadow .2s,transform .2s;
}
.cs-kpi:hover { box-shadow:var(--sh-md); transform:translateY(-2px); }
.cs-kpi-ico {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:16px; margin-bottom:12px;
}
.cs-kpi-val { font-family:sans-serif; font-size:1.35rem; font-weight:800; line-height:1; margin-bottom:4px; }
.cs-kpi-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); }

/* ─── BODY GRID ─────────────────────────── */
.cs-body {
    display:grid; grid-template-columns:1fr 300px; gap:18px; align-items:start;
    animation:fadeUp .35s .14s ease both;
}
.cs-card {
    background:var(--card); border:1px solid var(--border); border-radius:var(--r);
    box-shadow:var(--sh); overflow:hidden; margin-bottom:16px;
}
.cs-card-hd {
    padding:13px 20px; border-bottom:1px solid var(--border);
    display:flex; align-items:center; gap:10px; background:#FAFBFD;
}
.cs-card-ico {
    width:28px; height:28px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; color:#fff; flex-shrink:0;
}
.cs-card-hd h6 {
    font-size:10px; font-weight:800; text-transform:uppercase;
    letter-spacing:.6px; color:var(--text); margin:0;
}
.cs-card-bd { padding:20px; }

/* info-grid */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.info-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); margin-bottom:4px; }
.info-val { font-size:13px; font-weight:600; color:var(--text); }
.info-val a { color:var(--indigo); text-decoration:none; font-weight:700; }
.info-val a:hover { text-decoration:underline; }

/* Table */
.cs-table { width:100%; border-collapse:collapse; font-size:12px; }
.cs-table th {
    padding:9px 14px; font-size:9.5px; font-weight:700; text-transform:uppercase;
    letter-spacing:.5px; color:var(--muted); border-bottom:1px solid var(--border);
    background:var(--bg); white-space:nowrap;
}
.cs-table td { padding:10px 14px; vertical-align:middle; border-bottom:1px solid #F1F4F7; }
.cs-table tr:last-child td { border-bottom:none; }
.cs-table tr:hover td { background:#FAFBFD; }

/* badges */
.cs-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:10px; font-weight:700; }
.csb-paye   { background:var(--green-l); color:var(--green); }
.csb-credit { background:var(--amber-l); color:var(--amber); }
.csb-annule { background:var(--red-l);   color:var(--red); }

/* sidebar pills */
.cip { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #F4F6FB; }
.cip:last-child { border-bottom:none; }
.cip-ico { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
.cip-lbl { font-size:10.5px; color:var(--muted); font-weight:600; }
.cip-val { font-size:13px; font-weight:700; color:var(--text); }

/* Sidebar action btns */
.cs-act-btn {
    display:flex; align-items:center; gap:9px;
    padding:11px 14px; border-radius:11px;
    font-size:12.5px; font-weight:700; cursor:pointer;
    border:none; font-family:'DM Sans',sans-serif;
    text-decoration:none; transition:all .15s; width:100%;
}
.cs-act-btn:hover { transform:translateY(-1px); text-decoration:none; }
.cs-act-btn.edit  { background:linear-gradient(135deg,#D97706,#F59E0B); color:#fff; box-shadow:0 3px 10px rgba(217,119,6,.25); }
.cs-act-btn.green { background:var(--green-l); border:1px solid #BBF7D0; color:var(--green); }
.cs-act-btn.del   { background:var(--red-l); border:1px solid #FECACA; color:var(--red); }
.cs-act-btn.blue  { background:var(--blue-l); border:1px solid #BFDBFE; color:var(--blue); }

/* empty */
.cs-empty { padding:32px 20px; text-align:center; color:var(--muted); }
.cs-empty i { font-size:28px; display:block; margin-bottom:10px; opacity:.2; }

/* ─── MODALS ─────────────────────────────── */
.csm-overlay {
    position:fixed; inset:0; z-index:99999;
    background:rgba(10,14,26,.65); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center;
    opacity:0; pointer-events:none; transition:opacity .25s; padding:16px;
}
.csm-overlay.open { opacity:1; pointer-events:all; }
.csm-box {
    background:var(--card); border-radius:22px; width:100%; max-width:540px;
    box-shadow:0 32px 80px rgba(10,14,26,.28);
    transform:translateY(22px) scale(.97);
    transition:transform .3s cubic-bezier(.34,1.4,.64,1);
    overflow:hidden; max-height:90vh; display:flex; flex-direction:column;
    font-family:'DM Sans',sans-serif;
}
.csm-overlay.open .csm-box { transform:translateY(0) scale(1); }

.csm-hd {
    padding:20px 26px 18px;
    display:flex; align-items:center; justify-content:space-between;
    flex-shrink:0;
}
.csm-hd.avance  { background:linear-gradient(135deg,#064E3B,#059669); }
.csm-hd.credit  { background:linear-gradient(135deg,#1E1B4B,#4F46E5); }
.csm-hd.delete  { background:linear-gradient(135deg,#7F1D1D,#DC2626); }
.csm-title {
    font-family:sans-serif; font-size:1.1rem; font-weight:800;
    color:#fff; margin:0; display:flex; align-items:center; gap:11px;
}
.csm-title-ico {
    width:36px; height:36px; border-radius:10px;
    background:rgba(255,255,255,.18); display:flex; align-items:center;
    justify-content:center; font-size:15px; color:#fff; flex-shrink:0;
}
.csm-sub { font-size:11px; color:rgba(255,255,255,.55); margin-top:3px; }
.csm-close {
    width:32px; height:32px; border-radius:9px;
    border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.1);
    color:rgba(255,255,255,.8); display:flex; align-items:center; justify-content:center;
    font-size:12px; cursor:pointer; transition:.15s; flex-shrink:0;
}
.csm-close:hover { background:rgba(255,255,255,.22); color:#fff; }

.csm-body { padding:24px 26px; overflow-y:auto; flex:1; }
.csm-body::-webkit-scrollbar { width:4px; }
.csm-body::-webkit-scrollbar-thumb { background:var(--border); border-radius:4px; }

.csm-ft {
    padding:14px 26px; border-top:1px solid var(--border);
    background:#FAFBFD; flex-shrink:0;
    display:flex; align-items:center; justify-content:flex-end; gap:10px;
}
.csm-cancel {
    height:38px; padding:0 18px; border-radius:10px;
    border:1px solid var(--border); background:var(--card);
    color:var(--muted); font-size:13px; font-weight:700;
    cursor:pointer; font-family:'DM Sans',sans-serif;
}
.csm-save {
    height:38px; padding:0 22px; border-radius:10px; border:none;
    font-size:13px; font-weight:800; cursor:pointer; font-family:'DM Sans',sans-serif;
    display:inline-flex; align-items:center; gap:7px; transition:all .15s;
}
.csm-save.green { background:linear-gradient(135deg,#059669,#10B981); color:#fff; box-shadow:0 4px 14px rgba(16,185,129,.3); }
.csm-save.indigo{ background:linear-gradient(135deg,#4F46E5,#6366F1); color:#fff; box-shadow:0 4px 14px rgba(99,102,241,.3); }
.csm-save.red   { background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff; box-shadow:0 4px 14px rgba(220,38,38,.3); }
.csm-save:hover { transform:translateY(-1px); }

/* Form inside modal */
.csm-field { margin-bottom:16px; }
.csm-field:last-child { margin-bottom:0; }
.csm-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); display:block; margin-bottom:6px; }
.csm-inp-wrap {
    display:flex; align-items:center;
    background:var(--bg); border:1.5px solid var(--border);
    border-radius:10px; overflow:hidden; transition:.15s;
}
.csm-inp-wrap:focus-within { border-color:var(--indigo); background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.csm-inp-wrap.green:focus-within { border-color:var(--green); box-shadow:0 0 0 3px rgba(16,185,129,.1); }
.csm-inp-wrap i { width:36px; text-align:center; color:var(--muted); font-size:12px; flex-shrink:0; }
.csm-inp-wrap:focus-within i { color:var(--indigo); }
.csm-inp-wrap.green:focus-within i { color:var(--green); }
.csm-inp-wrap input,
.csm-inp-wrap select {
    flex:1; border:none; background:transparent;
    padding:10px 10px 10px 0; font-size:13px; color:var(--text);
    outline:none; font-family:'DM Sans',sans-serif;
}
.csm-inp-wrap select { appearance:none; }

/* Solde bar inside modal */
.csm-solde-bar {
    background:linear-gradient(135deg,var(--green-l),#D1FAE5);
    border:1px solid #A7F3D0; border-radius:12px;
    padding:14px 18px; display:flex; align-items:center; gap:14px; margin-bottom:20px;
}
.csm-solde-bar.blue { background:var(--indigo-l); border-color:#C7D2FE; }
.csm-solde-val { font-family:sans-serif; font-size:1.5rem; font-weight:900; }
.csm-solde-lbl { font-size:11px; font-weight:600; color:var(--muted); }

/* tabs */
.cs-tabs { display:flex; gap:4px; padding:14px 20px 0; border-bottom:1px solid var(--border); background:#FAFBFD; }
.cs-tab {
    padding:8px 16px; border-radius:10px 10px 0 0; font-size:12px; font-weight:700;
    cursor:pointer; border:none; background:transparent; color:var(--muted);
    font-family:'DM Sans',sans-serif; transition:.15s; border-bottom:2px solid transparent;
}
.cs-tab.active { color:var(--indigo); border-bottom-color:var(--indigo); background:rgba(99,102,241,.05); }

/* Montant total preview dans modal */
.csm-preview {
    background:linear-gradient(135deg,#0F172A,#1E3A5F);
    border-radius:12px; padding:14px 18px;
    display:flex; align-items:center; justify-content:space-between; margin-top:14px;
}
.csm-preview .pv-lbl { font-size:11px; color:rgba(255,255,255,.5); font-weight:600; }
.csm-preview .pv-val { font-family:sans-serif; font-size:1.3rem; font-weight:800; color:#34D399; }

/* delete confirm */
.csm-del-warn {
    background:var(--red-l); border:1px solid #FECACA;
    border-radius:12px; padding:14px 16px; display:flex; gap:12px; margin-bottom:16px;
    font-size:13px; color:var(--red); align-items:flex-start;
}
.csm-del-warn i { font-size:18px; flex-shrink:0; margin-top:1px; }

/* animations */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}

/* responsive */
@media(max-width:900px) {
    .cs-kpi-row { grid-template-columns:repeat(2,1fr); }
    .cs-body    { grid-template-columns:1fr; }
}
@media(max-width:480px) {
    .cs-hero { padding:22px 18px; }
    .cs-avatar { width:56px; height:56px; font-size:20px; }
    .cs-kpi-row { grid-template-columns:1fr 1fr; }
    .info-grid  { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
@php
    $colors = ['#6366f1','#059669','#D97706','#0EA5E9','#DC2626','#7C3AED','#16A34A'];
    $color  = $colors[$client->id % count($colors)];
@endphp

<div class="cs-wrap">

{{-- ─────────────────── HERO ─────────────────── --}}
<div class="cs-hero">
    <div class="cs-hero-inner">
        <div class="cs-avatar" style="background:{{ $color }}">{{ $client->initiales }}</div>
        <div style="flex:1;min-width:0">
            <h1 class="cs-hero-name">{{ $client->nom_complet }}</h1>
            <div class="cs-hero-meta">
                <span class="cs-meta-chip cs-chip-type">
                    {{ $client->type === 'entreprise' ? '🏢' : '👤' }} {{ $client->type_label }}
                </span>
                <span class="cs-meta-chip {{ $client->actif ? 'cs-chip-actif' : 'cs-chip-inactif' }}">
                    <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                    {{ $client->actif ? 'Actif' : 'Inactif' }}
                </span>
                @if($client->telephone)
                    <span class="cs-meta-item"><i class="fas fa-phone"></i> {{ $client->telephone }}</span>
                @endif
                @if($client->ville)
                    <span class="cs-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $client->ville }}</span>
                @endif
            </div>
        </div>
        <div class="cs-hero-acts">
            <a href="{{ route('clients.index') }}" class="cs-hbtn back"><i class="fas fa-arrow-left"></i></a>
            <button class="cs-hbtn modal-trigger" onclick="openModal('modalAvance')">
                <i class="fas fa-piggy-bank"></i> Avance
            </button>
            @if($stats['creances'] > 0)
                <button class="cs-hbtn modal-trigger" onclick="openModal('modalCredit')">
                    <i class="fas fa-hand-holding-usd"></i> Encaisser
                </button>
            @endif
            <a href="{{ route('clients.edit', $client) }}" class="cs-hbtn edit">
                <i class="fas fa-pen"></i> Modifier
            </a>
            <button class="cs-hbtn del" onclick="openModal('modalDelete')">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>

{{-- ─────────────────── KPI ─────────────────── --}}
<div class="cs-kpi-row">
    <div class="cs-kpi">
        <div class="cs-kpi-ico" style="background:var(--indigo-l);color:var(--indigo)"><i class="fas fa-receipt"></i></div>
        <div class="cs-kpi-val" style="color:#1E1B4B">{{ $stats['nb_factures'] }}</div>
        <div class="cs-kpi-lbl">Bons</div>
    </div>
    <div class="cs-kpi">
        <div class="cs-kpi-ico" style="background:var(--green-l);color:var(--green)"><i class="fas fa-coins"></i></div>
        <div class="cs-kpi-val" style="color:#065f46;font-size:1.1rem">
            {{ $stats['total_depense'] >= 1000000 ? number_format($stats['total_depense']/1000000,1,',',' ').'M' : number_format($stats['total_depense']/1000,0,',',' ').'K' }}
        </div>
        <div class="cs-kpi-lbl">Total dépensé</div>
    </div>
    <div class="cs-kpi">
        <div class="cs-kpi-ico" style="background:var(--amber-l);color:var(--amber)"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="cs-kpi-val" style="color:#92400e;font-size:1.05rem">
            {{ $stats['creances'] > 0 ? number_format($stats['creances'],0,',',' ').' F' : '—' }}
        </div>
        <div class="cs-kpi-lbl">Créances</div>
    </div>
    <div class="cs-kpi">
        <div class="cs-kpi-ico" style="background:var(--blue-l);color:var(--blue)"><i class="fas fa-piggy-bank"></i></div>
        <div class="cs-kpi-val" style="color:#1E40AF;font-size:1.05rem">
            {{ number_format((float)$client->solde_avance,0,',',' ') }} F
        </div>
        <div class="cs-kpi-lbl">Avance dispo.</div>
    </div>
</div>

{{-- ─────────────────── BODY ─────────────────── --}}
<div class="cs-body">

    {{-- COLONNE GAUCHE --}}
    <div>

        {{-- Infos client --}}
        <div class="cs-card">
            <div class="cs-card-hd">
                <span class="cs-card-ico" style="background:linear-gradient(135deg,#6366f1,#4f46e5)"><i class="fas fa-user"></i></span>
                <h6>Informations du client</h6>
                <a href="{{ route('clients.edit', $client) }}" style="margin-left:auto;font-size:10.5px;color:var(--indigo);font-weight:700;text-decoration:none">
                    <i class="fas fa-pen mr-1"></i> Modifier →
                </a>
            </div>
            <div class="cs-card-bd">
                <div class="info-grid">
                    <div>
                        <div class="info-lbl">Nom complet</div>
                        <div class="info-val">{{ $client->nom_complet }}</div>
                    </div>
                    <div>
                        <div class="info-lbl">Type</div>
                        <div class="info-val">{{ $client->type_label }}</div>
                    </div>
                    <div>
                        <div class="info-lbl">Téléphone</div>
                        <div class="info-val">
                            @if($client->telephone)
                                <a href="tel:{{ $client->telephone }}"><i class="fas fa-phone" style="font-size:10px;margin-right:4px"></i>{{ $client->telephone }}</a>
                            @else —
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="info-lbl">Email</div>
                        <div class="info-val">
                            @if($client->email)
                                <a href="mailto:{{ $client->email }}"><i class="fas fa-envelope" style="font-size:10px;margin-right:4px"></i>{{ $client->email }}</a>
                            @else —
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="info-lbl">IFU</div>
                        <div class="info-val">{{ $client->ifu ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="info-lbl">Adresse</div>
                        <div class="info-val">{{ $client->adresse ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bons + onglets remboursements/avances --}}
        <div class="cs-card">
            <div style="border-bottom:1px solid var(--border)">
                <div class="cs-tabs">
                    <button class="cs-tab active" onclick="switchTab(this,'tab-factures')">
                        <i class="fas fa-receipt mr-1"></i> Bons <span style="background:var(--indigo-l);color:var(--indigo);padding:1px 7px;border-radius:10px;font-size:10px;margin-left:4px">{{ $stats['nb_factures'] }}</span>
                    </button>
                    <button class="cs-tab" onclick="switchTab(this,'tab-remb')">
                        <i class="fas fa-hand-holding-usd mr-1"></i> Remboursements
                    </button>
                    <button class="cs-tab" onclick="switchTab(this,'tab-avances')">
                        <i class="fas fa-piggy-bank mr-1"></i> Avances
                    </button>
                </div>
            </div>

            {{-- Tab Bons --}}
            <div id="tab-factures">
                @if($factures->count())
                    <div class="table-responsive">
                        <table class="cs-table">
                            <thead>
                                <tr>
                                    <th>Bon</th><th>Date</th><th class="text-right">Total</th>
                                    <th>Mode</th><th>Statut</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factures as $f)
                                <tr>
                                    <td style="font-weight:700;color:var(--indigo)">{{ $f->numero }}</td>
                                    <td style="color:var(--muted);font-size:11px;white-space:nowrap">{{ $f->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right" style="font-weight:800">{{ number_format($f->total,0,',',' ') }} F</td>
                                    <td style="font-size:11.5px;color:var(--text);font-weight:600">{{ $f->mode_paiement_label }}</td>
                                    <td>
                                        <span class="cs-badge {{ $f->statut==='payee' ? 'csb-paye' : ($f->statut==='en_cours' ? 'csb-credit' : 'csb-annule') }}">
                                            {{ $f->statut_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('bons-livraison.show', $f) }}" style="color:var(--indigo);font-size:11px;font-weight:700;text-decoration:none">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:12px 20px;border-top:1px solid var(--border);background:#FAFBFD">
                        @include('partials.pagination', ['paginator' => $factures])
                    </div>
                @else
                    <div class="cs-empty"><i class="fas fa-receipt"></i><p>Aucune bon pour ce client</p></div>
                @endif
            </div>

            {{-- Tab Remboursements --}}
            <div id="tab-remb" style="display:none">
                @if($remboursements->count())
                    <div class="table-responsive">
                        <table class="cs-table">
                            <thead>
                                <tr><th>Date</th><th>Référence</th><th>Mode</th><th>Montant</th><th>Par</th><th></th></tr>
                            </thead>
                            <tbody>
                                @foreach($remboursements as $v)
                                <tr>
                                    <td style="color:var(--muted);font-size:11px;white-space:nowrap">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="font-family:monospace;font-size:11px">{{ $v->reference }}</td>
                                    <td style="font-size:11.5px">{{ $v->mode_paiement_label }}</td>
                                    <td style="font-weight:800;color:var(--green)">{{ number_format($v->montant,0,',',' ') }} F</td>
                                    <td style="color:var(--muted);font-size:11.5px">{{ $v->user->nom_complet ?? '—' }}</td>
                                    <td><a target="_blank" href="{{ route('remboursements.recus', $v) }}" style="color:var(--indigo);font-size:11px;font-weight:700;text-decoration:none"><i class="fas fa-file-pdf"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:12px 20px;border-top:1px solid var(--border);background:#FAFBFD">
                        {{ $remboursements->links() }}
                    </div>
                @else
                    <div class="cs-empty"><i class="fas fa-hand-holding-usd"></i><p>Aucun remboursement enregistré</p></div>
                @endif
            </div>

            {{-- Tab Avances --}}
            <div id="tab-avances" style="display:none">
                @if($mouvementsAvance->count())
                    <div class="table-responsive">
                        <table class="cs-table">
                            <thead>
                                <tr><th>Date</th><th>Opération</th><th>Référence</th><th>Bon</th><th>Mode</th><th>Montant</th><th>Par</th></tr>
                            </thead>
                            <tbody>
                                @foreach($mouvementsAvance as $m)
                                <tr>
                                    <td style="color:var(--muted);font-size:11px;white-space:nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="cs-badge {{ $m->type==='depot' ? 'csb-paye' : 'csb-credit' }}">
                                            {{ $m->type==='depot' ? 'Dépôt' : 'Utilisation' }}
                                        </span>
                                    </td>
                                    <td style="font-family:monospace;font-size:11px">{{ $m->reference }}</td>
                                    <td>
                                        @if($m->facture)
                                            <a href="{{ route('bons-livraison.show',$m->facture) }}" style="color:var(--indigo);font-weight:700;text-decoration:none">{{ $m->facture->numero }}</a>
                                        @else —
                                        @endif
                                    </td>
                                    <td style="font-size:11.5px">{{ $m->mode_paiement ? ucfirst(str_replace('_',' ',$m->mode_paiement)) : 'Avance utilisée' }}</td>
                                    <td style="font-weight:800;color:{{ $m->type==='depot' ? 'var(--green)' : 'var(--red)' }}">
                                        {{ $m->type==='depot' ? '+' : '−' }}{{ number_format($m->montant,0,',',' ') }} F
                                    </td>
                                    <td style="color:var(--muted);font-size:11.5px">{{ $m->user->nom_complet ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:12px 20px;border-top:1px solid var(--border);background:#FAFBFD">
                        {{ $mouvementsAvance->links() }}
                    </div>
                @else
                    <div class="cs-empty"><i class="fas fa-piggy-bank"></i><p>Aucun mouvement d'avance enregistré</p></div>
                @endif
            </div>
        </div>
    </div>

    {{-- SIDEBAR DROITE --}}
    <div>
        {{-- Fiche rapide --}}
        <div class="cs-card">
            <div class="cs-card-hd">
                <span class="cs-card-ico" style="background:linear-gradient(135deg,#6366f1,#4f46e5)"><i class="fas fa-id-card"></i></span>
                <h6>Fiche rapide</h6>
            </div>
            <div class="cs-card-bd" style="padding:14px 18px">
                <div class="cip">
                    <div class="cip-ico" style="background:var(--indigo-l);color:var(--indigo)"><i class="fas fa-calendar"></i></div>
                    <div><div class="cip-lbl">Client depuis</div><div class="cip-val">{{ $client->created_at->format('d/m/Y') }}</div></div>
                </div>
                <div class="cip">
                    <div class="cip-ico" style="background:var(--green-l);color:var(--green)"><i class="fas fa-coins"></i></div>
                    <div><div class="cip-lbl">Total dépensé</div><div class="cip-val">{{ number_format($stats['total_depense'],0,',',' ') }} FCFA</div></div>
                </div>
                <div class="cip">
                    <div class="cip-ico" style="background:var(--blue-l);color:var(--blue)"><i class="fas fa-piggy-bank"></i></div>
                    <div><div class="cip-lbl">Avance disponible</div><div class="cip-val" style="color:var(--blue)">{{ number_format((float)$client->solde_avance,0,',',' ') }} FCFA</div></div>
                </div>
                @if($stats['creances'] > 0)
                <div class="cip">
                    <div class="cip-ico" style="background:var(--amber-l);color:var(--amber)"><i class="fas fa-exclamation-triangle"></i></div>
                    <div><div class="cip-lbl">Créances</div><div class="cip-val" style="color:var(--amber)">{{ number_format($stats['creances'],0,',',' ') }} FCFA</div></div>
                </div>
                @endif
                <div class="cip">
                    <div class="cip-ico" style="background:var(--indigo-l);color:var(--indigo)"><i class="fas fa-receipt"></i></div>
                    <div><div class="cip-lbl">Bons</div><div class="cip-val">{{ $stats['nb_factures'] }} bon(s)</div></div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="cs-card">
            <div class="cs-card-hd">
                <span class="cs-card-ico" style="background:linear-gradient(135deg,#64748b,#475569)"><i class="fas fa-bolt"></i></span>
                <h6>Actions rapides</h6>
            </div>
            <div class="cs-card-bd" style="padding:14px;display:flex;flex-direction:column;gap:8px">
                <button class="cs-act-btn blue" onclick="openModal('modalAvance')">
                    <i class="fas fa-piggy-bank"></i> Ajouter une avance
                </button>
                @if($stats['creances'] > 0)
                <button class="cs-act-btn green" onclick="openModal('modalCredit')">
                    <i class="fas fa-hand-holding-usd"></i> Encaisser un versement
                </button>
                @endif
                <a href="{{ route('clients.edit', $client) }}" class="cs-act-btn edit">
                    <i class="fas fa-pen"></i> Modifier ce client
                </a>
                <form action="{{ route('clients.toggleActif', $client) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="cs-act-btn {{ $client->actif ? 'del' : 'green' }}" style="width:100%">
                        <i class="fas {{ $client->actif ? 'fa-ban' : 'fa-check-circle' }}"></i>
                        {{ $client->actif ? 'Désactiver' : 'Activer' }} le client
                    </button>
                </form>
                <button class="cs-act-btn del" onclick="openModal('modalDelete')">
                    <i class="fas fa-trash"></i> Supprimer ce client
                </button>
            </div>
        </div>
    </div>

</div>
</div>


{{-- ═══════════════════════════════════════════════
     MODAL — Ajouter une avance
═══════════════════════════════════════════════ --}}
<div class="csm-overlay" id="modalAvance" onclick="if(event.target===this) closeModal('modalAvance')">
    <div class="csm-box">
        <div class="csm-hd avance">
            <div>
                <div class="csm-title">
                    <div class="csm-title-ico"><i class="fas fa-piggy-bank"></i></div>
                    Ajouter une avance
                </div>
                <div class="csm-sub">Le solde sera crédité immédiatement au compte du client</div>
            </div>
            <button class="csm-close" onclick="closeModal('modalAvance')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('clients.avances.store', $client) }}">
            @csrf
            <input type="hidden" name="reference" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
            <div class="csm-body">
                <div class="csm-solde-bar">
                    <div class="cip-ico" style="background:rgba(255,255,255,.4);color:var(--green)"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="csm-solde-lbl">Avance actuellement disponible</div>
                        <div class="csm-solde-val" style="color:var(--green)">{{ number_format((float)$client->solde_avance,0,',',' ') }} FCFA</div>
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Montant à déposer (FCFA) *</label>
                    <div class="csm-inp-wrap green">
                        <i class="fas fa-coins"></i>
                        <input type="number" name="montant" min="0.01" step="0.01" required placeholder="Ex : 50 000">
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Mode de paiement *</label>
                    <div class="csm-inp-wrap green">
                        <i class="fas fa-credit-card"></i>
                        <select name="mode_paiement" required>
                            <option value="espece">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                        </select>
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Note (facultatif)</label>
                    <div class="csm-inp-wrap green">
                        <i class="fas fa-comment"></i>
                        <input type="text" name="notes" maxlength="1000" placeholder="Commentaire optionnel…">
                    </div>
                </div>
                <p style="font-size:11.5px;color:var(--muted);margin-top:4px">
                    <i class="fas fa-info-circle mr-1"></i>
                    Les avances sont utilisées automatiquement lors des prochains achats.
                </p>
            </div>
            <div class="csm-ft">
                <button type="button" class="csm-cancel" onclick="closeModal('modalAvance')">Annuler</button>
                <button type="submit" class="csm-save green"><i class="fas fa-check"></i> Ajouter l'avance</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════
     MODAL — Encaisser un versement (crédit)
═══════════════════════════════════════════════ --}}
@if($stats['creances'] > 0)
<div class="csm-overlay" id="modalCredit" onclick="if(event.target===this) closeModal('modalCredit')">
    <div class="csm-box">
        <div class="csm-hd credit">
            <div>
                <div class="csm-title">
                    <div class="csm-title-ico"><i class="fas fa-hand-holding-usd"></i></div>
                    Encaisser un versement
                </div>
                <div class="csm-sub">Le montant sera imputé sur les bons les plus anciens</div>
            </div>
            <button class="csm-close" onclick="closeModal('modalCredit')"><i class="fas fa-times"></i></button>
        </div>
        <form id="formRemboursementClient" method="POST" action="{{ route('clients.remboursements.store', $client) }}">
            @csrf
            <input type="hidden" name="reference" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
            <div class="csm-body">
                <div id="remboursementError" class="alert alert-danger py-2 mb-3" role="alert" style="display:none;font-size:12px"></div>
                <div class="csm-solde-bar blue">
                    <div class="cip-ico" style="background:rgba(255,255,255,.4);color:var(--indigo)"><i class="fas fa-exclamation-circle"></i></div>
                    <div>
                        <div class="csm-solde-lbl">Créances restantes</div>
                        <div class="csm-solde-val" style="color:var(--amber)">{{ number_format($stats['creances'],0,',',' ') }} FCFA</div>
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Versement (FCFA) *</label>
                    <div class="csm-inp-wrap">
                        <i class="fas fa-coins"></i>
                        <input type="number" name="montant" min="1" max="{{ $stats['creances'] }}"
                               step="0.01" required placeholder="Max : {{ number_format($stats['creances'],0,',',' ') }}">
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Mode de paiement *</label>
                    <div class="csm-inp-wrap">
                        <i class="fas fa-credit-card"></i>
                        <select name="mode_paiement" required>
                            <option value="espece">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                        </select>
                    </div>
                </div>
                <div class="csm-field">
                    <label class="csm-label">Note (facultatif)</label>
                    <div class="csm-inp-wrap">
                        <i class="fas fa-comment"></i>
                        <input type="text" name="notes" maxlength="1000" placeholder="Commentaire optionnel…">
                    </div>
                </div>
            </div>
            <div class="csm-ft">
                <button type="button" class="csm-cancel" onclick="closeModal('modalCredit')">Annuler</button>
                <button id="submitRemboursementClient" type="submit" class="csm-save indigo"><i class="fas fa-check"></i> Encaisser</button>
            </div>
        </form>
    </div>
</div>
@endif


{{-- ═══════════════════════════════════════════════
     MODAL — Supprimer le client
═══════════════════════════════════════════════ --}}
<div class="csm-overlay" id="modalDelete" onclick="if(event.target===this) closeModal('modalDelete')">
    <div class="csm-box">
        <div class="csm-hd delete">
            <div>
                <div class="csm-title">
                    <div class="csm-title-ico"><i class="fas fa-trash"></i></div>
                    Supprimer ce client
                </div>
                <div class="csm-sub">Cette action est irréversible</div>
            </div>
            <button class="csm-close" onclick="closeModal('modalDelete')"><i class="fas fa-times"></i></button>
        </div>
        <div class="csm-body">
            <div class="csm-del-warn">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    Vous êtes sur le point de supprimer <strong>{{ $client->nom_complet }}</strong> et toutes ses données associées (bons, avances, remboursements). Cette action est <strong>définitive et irréversible</strong>.
                </div>
            </div>
            <form id="formDeleteClient" action="{{ route('clients.destroy', $client) }}" method="POST">
                @csrf @method('DELETE')
            </form>
        </div>
        <div class="csm-ft">
            <button type="button" class="csm-cancel" onclick="closeModal('modalDelete')">Annuler</button>
            <button type="submit" form="formDeleteClient" class="csm-save red">
                <i class="fas fa-trash"></i> Confirmer la suppression
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Modals ──
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.csm-overlay.open').forEach(m => {
            m.classList.remove('open');
            document.body.style.overflow = '';
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const requestedAction = new URLSearchParams(window.location.search).get('action');
    if (requestedAction === 'avance') openModal('modalAvance');
    if (requestedAction === 'credit' && document.getElementById('modalCredit')) openModal('modalCredit');

    const refundForm = document.getElementById('formRemboursementClient');
    if (refundForm) {
        refundForm.addEventListener('submit', async event => {
            event.preventDefault();

            const submitButton = document.getElementById('submitRemboursementClient');
            const errorBox = document.getElementById('remboursementError');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation du reçu…';
            errorBox.style.display = 'none';
            errorBox.textContent = '';

            try {
                const response = await fetch(refundForm.action, {
                    method: 'POST',
                    body: new FormData(refundForm),
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const contentType = response.headers.get('content-type') || '';

                if (!response.ok) {
                    const result = contentType.includes('application/json') ? await response.json() : null;
                    const validationMessage = result?.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : result?.message;
                    throw new Error(validationMessage || 'Le remboursement n’a pas pu être enregistré.');
                }

                if (!contentType.includes('application/pdf')) {
                    throw new Error('Le reçu PDF n’a pas pu être préparé. Veuillez réessayer.');
                }

                const pdf = await response.blob();
                const disposition = response.headers.get('content-disposition') || '';
                const filename = disposition.match(/filename="?([^";]+)"?/i)?.[1] || 'recu-remboursement.pdf';
                const downloadUrl = URL.createObjectURL(pdf);
                const downloadLink = document.createElement('a');
                downloadLink.href = downloadUrl;
                downloadLink.download = filename;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                downloadLink.remove();
                closeModal('modalCredit');
                window.setTimeout(() => URL.revokeObjectURL(downloadUrl), 1000);
            } catch (error) {
                errorBox.textContent = error.message || 'Une erreur est survenue pendant la préparation du reçu.';
                errorBox.style.display = 'block';
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
});

// ── Tabs ──
function switchTab(btn, tabId) {
    // désactiver tous les onglets
    btn.closest('.cs-card').querySelectorAll('.cs-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    // masquer tous les contenus
    ['tab-factures','tab-remb','tab-avances'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    const target = document.getElementById(tabId);
    if (target) target.style.display = 'block';
}

// ── Confirmation suppression ──
function confirmDelete(formId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title:'Supprimer ce client ?',
            text:'Cette action est irréversible.',
            icon:'warning',
            showCancelButton:true,
            confirmButtonText:'Supprimer',
            cancelButtonText:'Annuler',
            confirmButtonColor:'#DC2626',
            cancelButtonColor:'#6B7280',
        }).then(r => { if(r.isConfirmed) document.getElementById(formId).submit(); });
    } else {
        if(confirm('Supprimer ce client ?')) document.getElementById(formId).submit();
    }
}
</script>
@endpush
