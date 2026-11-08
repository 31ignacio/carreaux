{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier utilisateur — ' . $user->nom_complet)
@section('page-title', 'Modifier l\'utilisateur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@push('styles')
<style>
.ue-wrap { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

/* ── HERO ── */
.ue-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
    border-radius: 20px;
    padding: 26px 32px;
    margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 10px 40px rgba(15,23,42,.2);
}
.ue-hero::before {
    content: ''; position: absolute; top: -50px; right: -50px;
    width: 240px; height: 240px; border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.ue-hero::after {
    content: ''; position: absolute; bottom: -30px; left: 35%;
    width: 160px; height: 160px; border-radius: 50%;
    background: rgba(37,99,235,.1);
}
.ue-hero-left { position: relative; z-index: 1; display: flex; align-items: center; gap: 18px; }
.ue-avatar {
    width: 58px; height: 58px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 900; color: #fff; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.2);
}
.ua-admin { background: linear-gradient(135deg, #7c3aed, #a855f7); }
.ua-caiss { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.ua-caiss-haut { background: linear-gradient(135deg, #d97706, #f59e0b); }
.ua-sup   { background: linear-gradient(135deg, #059669, #10b981); }
.ua-other { background: linear-gradient(135deg, #475569, #64748b); }

.ue-hero-title { font-size: clamp(1rem, 2vw, 1.35rem); font-weight: 800; color: #fff; margin: 0 0 5px; letter-spacing: -.3px; }
.ue-hero-meta  { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.ue-hero-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    border-radius: 20px; padding: 4px 12px;
    font-size: 11px; font-weight: 600; color: rgba(255,255,255,.75);
}
.ue-hero-chip i { font-size: 10px; }
.ue-hero-right { position: relative; z-index: 1; }
.ue-back-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
    color: rgba(255,255,255,.8); border-radius: 11px;
    padding: 9px 18px; font-size: 12px; font-weight: 700;
    text-decoration: none; transition: all .2s;
}
.ue-back-btn:hover { background: rgba(255,255,255,.18); color: #fff; text-decoration: none; }

/* ── LAYOUT ── */
.ue-layout {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 20px;
    align-items: start;
}

/* ── SIDEBAR PROFIL ── */
.ue-profil-card {
    background: #fff; border: 1px solid #e8edf5;
    border-radius: 20px; overflow: hidden;
    box-shadow: 0 2px 16px rgba(11,15,26,.05);
}
.ue-profil-top {
    padding: 28px 24px; text-align: center;
    border-bottom: 1px solid #f1f5f9;
}
.ue-profil-avatar {
    width: 72px; height: 72px; border-radius: 22px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 900; color: #fff;
    margin: 0 auto 14px;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
}
.ue-profil-name  { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
.ue-profil-email { font-size: 12px; color: #94a3b8; margin-bottom: 14px; }
.ue-profil-badges { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }

.role-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 800;
}
.rp-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.rp-admin { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
.rp-caiss { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.rp-caiss-haut { background: #fefce8; color: #d97706; border: 1px solid #fde68a; }
.rp-sup   { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.rp-other { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

.stat-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 800;
}
.sp-actif { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.sp-inact { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

/* ── PROFIL STATS ── */
.ue-profil-stats { padding: 20px 24px; }
.ue-stat-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid #f8fafc;
}
.ue-stat-row:last-child { border-bottom: none; }
.ue-stat-lbl { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
.ue-stat-val { font-size: 12px; font-weight: 700; color: #374151; }

/* ── FORM CARD ── */
.ue-form-card {
    background: #fff; border: 1px solid #e8edf5;
    border-radius: 20px; overflow: hidden;
    box-shadow: 0 2px 16px rgba(11,15,26,.05);
}
.ue-form-head {
    padding: 18px 24px 16px; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 10px;
}
.ue-form-head-ico {
    width: 30px; height: 30px; border-radius: 9px;
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: #fff;
}
.ue-form-head-title {
    font-size: 13px; font-weight: 800; color: #0f172a;
    text-transform: uppercase; letter-spacing: .3px;
}
.ue-form-body { padding: 28px 28px; }

/* ── SECTION DIVIDER ── */
.ue-section {
    margin-bottom: 28px;
}
.ue-section-title {
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .7px;
    color: #94a3b8; margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.ue-section-title::after {
    content: ''; flex: 1; height: 1px; background: #f1f5f9;
}

/* ── FORM GRID ── */
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-1 { display: grid; grid-template-columns: 1fr; gap: 16px; }

/* ── FIELD ── */
.ue-field { margin-bottom: 0; }
.ue-label {
    display: block; font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px;
    color: #64748b; margin-bottom: 8px;
}
.ue-label .req { color: #ef4444; margin-left: 2px; }
.ue-input-wrap {
    display: flex; align-items: center;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 12px; overflow: hidden;
    transition: all .2s;
}
.ue-input-wrap:focus-within {
    border-color: #2563eb; background: #fff;
    box-shadow: 0 0 0 4px rgba(37,99,235,.1);
}
.ue-input-wrap.is-err { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
.ue-input-ico {
    width: 44px; display: flex; align-items: center; justify-content: center;
    color: #c4cdd8; font-size: 13px; flex-shrink: 0;
    transition: color .2s;
}
.ue-input-wrap:focus-within .ue-input-ico { color: #2563eb; }
.ue-input {
    flex: 1; border: none; background: transparent;
    padding: 12px 14px 12px 0; font-size: 13px;
    color: #0f172a; outline: none;
}
.ue-input::placeholder { color: #c4cdd8; }
.ue-select {
    flex: 1; border: none; background: transparent;
    padding: 12px 14px 12px 0; font-size: 13px;
    color: #0f172a; outline: none; cursor: pointer;
    -webkit-appearance: none;
}
.ue-select-wrap {
    position: relative;
}
.ue-select-chevron {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 11px; pointer-events: none;
}
.ue-err-msg { font-size: 11px; color: #ef4444; margin-top: 5px; font-weight: 600; }
.ue-hint    { font-size: 11px; color: #94a3b8; margin-top: 5px; }

/* ── PASSWORD TOGGLE ── */
.ue-pwd-toggle {
    width: 44px; display: flex; align-items: center; justify-content: center;
    color: #c4cdd8; font-size: 13px; cursor: pointer;
    flex-shrink: 0; transition: color .15s;
}
.ue-pwd-toggle:hover { color: #2563eb; }

/* ── 4 RÔLES SUR UNE LIGNE ── */
.role-options-4 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.role-option { display: none; }
.role-option + label {
    display: flex; flex-direction: column; align-items: center;
    padding: 14px 8px; border-radius: 14px;
    border: 2px solid #e2e8f0; background: #f8fafc;
    cursor: pointer; transition: all .2s; text-align: center;
    height: 100%;
}
.role-option + label:hover {
    border-color: #a7f3d0; background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
}
.role-option:checked + label { background: #f0fdf4; border-color: #10b981; box-shadow: 0 4px 16px rgba(16,185,129,.15); }
.role-option[value="admin"]:checked + label { background: #f5f3ff; border-color: #7c3aed; box-shadow: 0 4px 16px rgba(124,58,237,.15); }
.role-option[value="superviseur"]:checked + label { background: #ecfdf5; border-color: #059669; box-shadow: 0 4px 16px rgba(5,150,105,.15); }
.role-option[value="caissier"]:checked + label { background: #eff6ff; border-color: #2563eb; box-shadow: 0 4px 16px rgba(37,99,235,.15); }
.role-option[value="caissierHaut"]:checked + label { background: #fefce8; border-color: #d97706; box-shadow: 0 4px 16px rgba(217,119,6,.15); }

.role-ico-wrap {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; margin-bottom: 8px;
    background: #e2e8f0; color: #64748b;
    transition: all .2s;
}
.role-option:checked + label .role-ico-wrap { background: #d1fae5; color: #059669; }
.role-option[value="admin"]:checked + label .role-ico-wrap { background: #ede9fe; color: #7c3aed; }
.role-option[value="superviseur"]:checked + label .role-ico-wrap { background: #a7f3d0; color: #059669; }
.role-option[value="caissier"]:checked + label .role-ico-wrap { background: #bfdbfe; color: #2563eb; }
.role-option[value="caissierHaut"]:checked + label .role-ico-wrap { background: #fde68a; color: #d97706; }

.role-lbl-main { font-size: 12px; font-weight: 800; color: #0f172a; }
.role-lbl-sub  { font-size: 10px; color: #94a3b8; margin-top: 2px; }

/* ── TOGGLE SWITCH ── */
.ue-toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 12px; padding: 14px 18px;
}
.ue-toggle-info { }
.ue-toggle-title { font-size: 13px; font-weight: 700; color: #0f172a; }
.ue-toggle-sub   { font-size: 11px; color: #94a3b8; margin-top: 2px; }
.switch { position: relative; display: inline-block; width: 48px; height: 26px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute; inset: 0; cursor: pointer;
    background: #e2e8f0; border-radius: 26px; transition: .3s;
}
.slider:before {
    content: ''; position: absolute;
    width: 20px; height: 20px; border-radius: 50%;
    left: 3px; top: 3px; background: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
    transition: .3s;
}
input:checked + .slider { background: #10b981; }
input:checked + .slider:before { transform: translateX(22px); }

/* ── FORM FOOTER ── */
.ue-form-footer {
    padding: 20px 28px; border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    flex-wrap: wrap;
}
.ue-btn-group { display: flex; align-items: center; gap: 10px; }
.ue-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; border-radius: 12px; border: none;
    font-size: 13px; font-weight: 800; cursor: pointer;
    transition: all .2s; text-decoration: none;
}
.ue-btn:hover { transform: translateY(-2px); text-decoration: none; }
.ub-primary {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: #fff; box-shadow: 0 4px 16px rgba(37,99,235,.3);
}
.ub-primary:hover { box-shadow: 0 8px 24px rgba(37,99,235,.4); color: #fff; }
.ub-ghost {
    background: #f1f5f9; color: #475569;
}
.ub-ghost:hover { background: #e2e8f0; color: #1e293b; }
.ub-danger {
    background: #fef2f2; color: #dc2626;
    border: 1px solid #fecaca;
}
.ub-danger:hover { background: #fee2e2; color: #b91c1c; }
.ue-footer-hint { font-size: 11px; color: #94a3b8; }

/* ── ALERT ── */
.ue-alert-err {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 12px; padding: 12px 16px;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 24px; font-size: 13px; color: #dc2626;
}
.ue-alert-err i { margin-top: 1px; flex-shrink: 0; }

/* ── RESPONSIVE ── */
@media(max-width: 900px) {
    .ue-layout { grid-template-columns: 1fr; }
    .form-row-2 { grid-template-columns: 1fr; }
    .role-options-4 { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 600px) {
    .ue-hero { flex-direction: column; align-items: flex-start; }
    .role-options-4 { grid-template-columns: 1fr; }
}

/* ── ANIM ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.ue-hero      { animation: fadeUp .4s ease both; }
.ue-layout    { animation: fadeUp .4s .1s ease both; }
</style>
@endpush

@section('content')

@php
    $initials = collect(explode(' ', $user->nom_complet))
        ->map(fn($w) => strtoupper(substr($w,0,1)))
        ->take(2)->implode('');
    $avatarClass = match($user->role) {
        'admin'       => 'ua-admin',
        'caissier'    => 'ua-caiss',
        'caissierHaut'=> 'ua-caiss-haut',
        'superviseur' => 'ua-sup',
        default       => 'ua-other',
    };
    $roleClass = match($user->role) {
        'admin'       => 'rp-admin',
        'caissier'    => 'rp-caiss',
        'caissierHaut'=> 'rp-caiss-haut',
        'superviseur' => 'rp-sup',
        default       => 'rp-other',
    };
    $roleLabel = match($user->role) {
        'admin'       => 'Administrateur',
        'caissier'    => 'Caissier',
        'caissierHaut'=> 'Caissier Haut',
        'superviseur' => 'Superviseur',
        default       => 'Inconnu',
    };
@endphp

<div class="ue-wrap">

    {{-- ── HERO ── --}}
    <div class="ue-hero">
        <div class="ue-hero-left">
            <div class="ue-avatar {{ $avatarClass }}">{{ $initials }}</div>
            <div>
                <h1 class="ue-hero-title">{{ $user->nom_complet }}</h1>
                <div class="ue-hero-meta">
                    <span class="ue-hero-chip">
                        <i class="fas fa-envelope"></i> {{ $user->email }}
                    </span>
                    <span class="ue-hero-chip">
                        <i class="fas fa-shield-alt"></i> {{ $roleLabel }}
                    </span>
                    @if($user->id === auth()->id())
                        <span class="ue-hero-chip" style="background:rgba(59,130,246,.2); border-color:rgba(59,130,246,.3)">
                            <i class="fas fa-circle" style="font-size:7px; color:#60a5fa"></i>
                            <span style="color:#93c5fd">Votre compte</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="ue-hero-right">
            <a href="{{ route('users.index') }}" class="ue-back-btn">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="ue-layout">

        {{-- ── SIDEBAR PROFIL ── --}}
        <div>
            <div class="ue-profil-card">
                <div class="ue-profil-top">
                    <div class="ue-profil-avatar {{ $avatarClass }}">{{ $initials }}</div>
                    <div class="ue-profil-name">{{ $user->nom_complet }}</div>
                    <div class="ue-profil-email">{{ $user->email }}</div>
                    <div class="ue-profil-badges">
                        <span class="role-pill {{ $roleClass }}">
                            <span class="rp-dot"></span>
                            {{ $roleLabel }}
                        </span>
                        @if($user->actif)
                            <span class="stat-pill sp-actif">
                                <span class="rp-dot"></span> Actif
                            </span>
                        @else
                            <span class="stat-pill sp-inact">
                                <span class="rp-dot"></span> Inactif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="ue-profil-stats">
                    <div class="ue-stat-row">
                        <span class="ue-stat-lbl">ID système</span>
                        <span class="ue-stat-val">#{{ $user->id }}</span>
                    </div>
                    <div class="ue-stat-row">
                        <span class="ue-stat-lbl">Téléphone</span>
                        <span class="ue-stat-val">{{ $user->telephone ?? '—' }}</span>
                    </div>
                    <div class="ue-stat-row">
                        <span class="ue-stat-lbl">Inscrit le</span>
                        <span class="ue-stat-val">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="ue-stat-row">
                        <span class="ue-stat-lbl">Dernière modif.</span>
                        <span class="ue-stat-val">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── FORMULAIRE ── --}}
        <div>
            <div class="ue-form-card">
                <div class="ue-form-head">
                    <span class="ue-form-head-ico"><i class="fas fa-pen"></i></span>
                    <span class="ue-form-head-title">Modifier les informations</span>
                </div>

                <form action="{{ route('users.update', $user) }}"
                      method="POST" id="editUserForm">
                    @csrf @method('PUT')

                    <div class="ue-form-body">

                        {{-- Erreurs globales --}}
                        @if($errors->any())
                            <div class="ue-alert-err">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                                    <ul style="margin: 6px 0 0; padding-left: 16px">
                                        @foreach($errors->all() as $e)
                                            <li style="font-size:12px">{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- ── SECTION : Identité ── --}}
                        <div class="ue-section">
                            <div class="ue-section-title">
                                <i class="fas fa-user" style="font-size:10px"></i>
                                Identité
                            </div>
                            <div class="form-row-2">
                                {{-- Prénom --}}
                                <div class="ue-field">
                                    <label class="ue-label" for="prenom">
                                        Prénom <span class="req">*</span>
                                    </label>
                                    <div class="ue-input-wrap @error('prenom') is-err @enderror">
                                        <div class="ue-input-ico"><i class="fas fa-user"></i></div>
                                        <input type="text" id="prenom" name="prenom"
                                               class="ue-input"
                                               value="{{ old('prenom', $user->prenom) }}"
                                               placeholder="Prénom" required>
                                    </div>
                                    @error('prenom')
                                        <div class="ue-err-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nom --}}
                                <div class="ue-field">
                                    <label class="ue-label" for="nom">
                                        Nom <span class="req">*</span>
                                    </label>
                                    <div class="ue-input-wrap @error('nom') is-err @enderror">
                                        <div class="ue-input-ico"><i class="fas fa-user"></i></div>
                                        <input type="text" id="nom" name="nom"
                                               class="ue-input"
                                               value="{{ old('nom', $user->nom) }}"
                                               placeholder="Nom de famille" required>
                                    </div>
                                    @error('nom')
                                        <div class="ue-err-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECTION : Contact ── --}}
                        <div class="ue-section">
                            <div class="ue-section-title">
                                <i class="fas fa-address-card" style="font-size:10px"></i>
                                Contact
                            </div>
                            <div class="form-row-2">
                                {{-- Email --}}
                                <div class="ue-field">
                                    <label class="ue-label" for="email">
                                        Adresse email <span class="req">*</span>
                                    </label>
                                    <div class="ue-input-wrap @error('email') is-err @enderror">
                                        <div class="ue-input-ico"><i class="fas fa-at"></i></div>
                                        <input type="email" id="email" name="email"
                                               class="ue-input"
                                               value="{{ old('email', $user->email) }}"
                                               placeholder="email@exemple.com" required>
                                    </div>
                                    @error('email')
                                        <div class="ue-err-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div class="ue-field">
                                    <label class="ue-label" for="telephone">Téléphone</label>
                                    <div class="ue-input-wrap @error('telephone') is-err @enderror">
                                        <div class="ue-input-ico"><i class="fas fa-phone"></i></div>
                                        <input type="text" id="telephone" name="telephone"
                                               class="ue-input"
                                               value="{{ old('telephone', $user->telephone) }}"
                                               placeholder="+229 XX XX XX XX">
                                    </div>
                                    @error('telephone')
                                        <div class="ue-err-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECTION : Rôle ── --}}
                        <div class="ue-section">
                            <div class="ue-section-title">
                                <i class="fas fa-shield-alt" style="font-size:10px"></i>
                                Rôle &amp; permissions
                            </div>
                            <div class="role-options-4">
                                {{-- Caissier --}}
                                <div>
                                    <input type="radio" name="role" id="role_caissier"
                                           class="role-option" value="caissier"
                                           {{ old('role', $user->role) === 'caissier' ? 'checked' : '' }}>
                                    <label for="role_caissier">
                                        <div class="role-ico-wrap">
                                            <i class="fas fa-cash-register"></i>
                                        </div>
                                        <div class="role-lbl-main">Caissier</div>
                                        <div class="role-lbl-sub">Ventes uniquement</div>
                                    </label>
                                </div>

                                {{-- Caissier Haut --}}
                                <div>
                                    <input type="radio" name="role" id="role_caissierHaut"
                                           class="role-option" value="caissierHaut"
                                           {{ old('role', $user->role) === 'caissierHaut' ? 'checked' : '' }}>
                                    <label for="role_caissierHaut">
                                        <div class="role-ico-wrap">
                                            <i class="fas fa-cash-register"></i>
                                        </div>
                                        <div class="role-lbl-main">Caissier Haut</div>
                                        <div class="role-lbl-sub">Ventes &amp; caisse</div>
                                    </label>
                                </div>

                                {{-- Superviseur --}}
                                <div>
                                    <input type="radio" name="role" id="role_superviseur"
                                           class="role-option" value="superviseur"
                                           {{ old('role', $user->role) === 'superviseur' ? 'checked' : '' }}>
                                    <label for="role_superviseur">
                                        <div class="role-ico-wrap">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="role-lbl-main">Superviseur</div>
                                        <div class="role-lbl-sub">Rapports &amp; stock</div>
                                    </label>
                                </div>

                                {{-- Admin --}}
                                <div>
                                    <input type="radio" name="role" id="role_admin"
                                           class="role-option" value="admin"
                                           {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                    <label for="role_admin">
                                        <div class="role-ico-wrap">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="role-lbl-main">Administrateur</div>
                                        <div class="role-lbl-sub">Accès total</div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="role" id="role_secretaire" class="role-option" value="secretaire" {{ old('role', $user->role) === 'secretaire' ? 'checked' : '' }}>
                                    <label for="role_secretaire"><div class="role-ico-wrap"><i class="fas fa-clipboard-list"></i></div><div class="role-lbl-main">Secrétaire</div><div class="role-lbl-sub">Commandes clients</div></label>
                                </div>
                                <div>
                                    <input type="radio" name="role" id="role_magasinier" class="role-option" value="magasinier" {{ old('role', $user->role) === 'magasinier' ? 'checked' : '' }}>
                                    <label for="role_magasinier"><div class="role-ico-wrap"><i class="fas fa-boxes"></i></div><div class="role-lbl-main">Magasinier</div><div class="role-lbl-sub">Préparation &amp; livraisons</div></label>
                                </div>
                            </div>
                            @error('role')
                                <div class="ue-err-msg mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ── SECTION : Mot de passe ── --}}
                        <div class="ue-section">
                            <div class="ue-section-title">
                                <i class="fas fa-lock" style="font-size:10px"></i>
                                Nouveau mot de passe
                            </div>
                            <div class="form-row-2">
                                <div class="ue-field">
                                    <label class="ue-label" for="password">Mot de passe</label>
                                    <div class="ue-input-wrap @error('password') is-err @enderror">
                                        <div class="ue-input-ico"><i class="fas fa-lock"></i></div>
                                        <input type="password" id="password" name="password"
                                               class="ue-input" placeholder="Laisser vide = inchangé">
                                        <div class="ue-pwd-toggle" onclick="togglePwd('password','eye1')">
                                            <i class="fas fa-eye" id="eye1"></i>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="ue-err-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                    @enderror
                                    <div class="ue-hint">Laissez vide pour conserver le mot de passe actuel</div>
                                </div>
                                <div class="ue-field">
                                    <label class="ue-label" for="password_confirmation">Confirmer</label>
                                    <div class="ue-input-wrap">
                                        <div class="ue-input-ico"><i class="fas fa-lock"></i></div>
                                        <input type="password" id="password_confirmation"
                                               name="password_confirmation"
                                               class="ue-input" placeholder="Répéter le mot de passe">
                                        <div class="ue-pwd-toggle" onclick="togglePwd('password_confirmation','eye2')">
                                            <i class="fas fa-eye" id="eye2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── SECTION : Statut ── --}}
                        @if($user->id !== auth()->id())
                        <div class="ue-section" style="margin-bottom:0">
                            <div class="ue-section-title">
                                <i class="fas fa-toggle-on" style="font-size:10px"></i>
                                Statut du compte
                            </div>
                            <div class="ue-toggle-row">
                                <div class="ue-toggle-info">
                                    <div class="ue-toggle-title">Compte actif</div>
                                    <div class="ue-toggle-sub">
                                        Un compte inactif ne peut plus se connecter au système
                                    </div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="actif" value="1"
                                           id="toggleActif"
                                           {{ old('actif', $user->actif) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        @endif

                    </div>{{-- /form-body --}}


                    {{-- ── FOOTER ── --}}
                    <div class="ue-form-footer">
                        <span class="ue-footer-hint">
                            <i class="fas fa-info-circle mr-1"></i>
                            Les champs marqués <span style="color:#ef4444">*</span> sont obligatoires
                        </span>
                        <div class="ue-btn-group">
                            <a href="{{ route('users.index') }}" class="ue-btn ub-ghost">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            @if($user->id !== auth()->id())
                                <button type="button"
                                        onclick="confirmDelete('del-u-{{ $user->id }}')"
                                        class="ue-btn ub-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            @endif
                            <button type="submit" class="ue-btn ub-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>

                </form>{{-- FIN du form principal --}}

                {{-- ⚠️ Form suppression EN DEHORS du form principal --}}
                @if($user->id !== auth()->id())
                    <form id="del-u-{{ $user->id }}"
                          action="{{ route('users.destroy', $user) }}"
                          method="POST" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                @endif

            </div>
        </div>

    </div>{{-- /ue-layout --}}
</div>
@endsection

@push('scripts')
<script>
function togglePwd(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'text' ? 'fas fa-eye-slash' : 'fas fa-eye';
}

function confirmDelete(formId) {
    if(confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        document.getElementById(formId).submit();
    }
}
</script>
@endpush
