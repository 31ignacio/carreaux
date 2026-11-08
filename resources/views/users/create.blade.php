{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')
@section('page-title', 'Nouvel Utilisateur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
    <li class="breadcrumb-item active">Nouveau</li>
@endsection

@push('styles')
    <style>
        .uc-wrap {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ── HERO ── */
        .uc-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
            border-radius: 20px;
            padding: 26px 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, .2);
        }

        .uc-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .uc-hero::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: 30%;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(37, 99, 235, .1);
            pointer-events: none;
        }

        .uc-hero-left {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .uc-hero-ico {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, .3), rgba(99, 102, 241, .3));
            border: 2px solid rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: rgba(255, 255, 255, .9);
        }

        .uc-hero-title {
            font-size: clamp(1rem, 2vw, 1.3rem);
            font-weight: 800;
            color: #fff;
            margin: 0 0 5px;
        }

        .uc-hero-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, .5);
            margin: 0;
        }

        .uc-hero-right {
            position: relative;
            z-index: 1;
        }

        .uc-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            color: rgba(255, 255, 255, .8);
            border-radius: 11px;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s;
        }

        .uc-back-btn:hover {
            background: rgba(255, 255, 255, .18);
            color: #fff;
            text-decoration: none;
        }

        /* ── LAYOUT ── */
        .uc-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            align-items: start;
        }

        /* ── SIDEBAR INFO ── */
        .uc-info-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(11, 15, 26, .05);
        }

        .uc-info-top {
            padding: 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #f8faff, #f0f4ff);
        }

        .uc-avatar-preview {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: linear-gradient(135deg, #475569, #64748b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 900;
            color: #fff;
            margin: 0 auto 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .12);
            transition: all .3s;
        }

        .uc-preview-name {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
            min-height: 22px;
        }

        .uc-preview-email {
            font-size: 11px;
            color: #94a3b8;
            min-height: 16px;
        }

        .uc-preview-role {
            margin-top: 12px;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 13px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .rp-admin {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .rp-caiss {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .rp-sup {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .rp-caiss-haut {
            background: #fefce8;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        /* ── INFO ITEMS ── */
        .uc-info-body {
            padding: 20px;
        }

        .uc-tip {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            margin-bottom: 10px;
        }

        .uc-tip:last-child {
            margin-bottom: 0;
        }

        .uc-tip-ico {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .uc-tip-text {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        .uc-tip-text strong {
            color: #0f172a;
            font-weight: 700;
        }

        /* ── FORM CARD ── */
        .uc-form-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(11, 15, 26, .05);
        }

        .uc-form-head {
            padding: 18px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .uc-form-head-ico {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
        }

        .uc-form-head-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .uc-form-body {
            padding: 28px;
        }

        /* ── SECTION ── */
        .uc-section {
            margin-bottom: 28px;
        }

        .uc-section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #94a3b8;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .uc-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        /* ── GRID ── */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── FIELD ── */
        .uc-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .uc-label .req {
            color: #ef4444;
            margin-left: 2px;
        }

        .uc-input-wrap {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all .2s;
        }

        .uc-input-wrap:focus-within {
            border-color: #10b981;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, .1);
        }

        .uc-input-wrap.is-err {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .1);
        }

        .uc-input-ico {
            width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c4cdd8;
            font-size: 13px;
            flex-shrink: 0;
            transition: color .2s;
        }

        .uc-input-wrap:focus-within .uc-input-ico {
            color: #10b981;
        }

        .uc-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 14px 12px 0;
            font-size: 13px;
            color: #0f172a;
            outline: none;
        }

        .uc-input::placeholder {
            color: #c4cdd8;
        }

        .uc-err-msg {
            font-size: 11px;
            color: #ef4444;
            margin-top: 5px;
            font-weight: 600;
        }

        /* ── PASSWORD TOGGLE ── */
        .uc-pwd-toggle {
            width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c4cdd8;
            font-size: 13px;
            cursor: pointer;
            flex-shrink: 0;
            transition: color .15s;
        }

        .uc-pwd-toggle:hover {
            color: #10b981;
        }

        /* ── 4 RÔLES SUR UNE LIGNE ── */
        .role-options-4 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .role-options-4 .role-option {
            display: none;
        }

        .role-options-4 .role-option + label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 14px 8px;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
            height: 100%;
        }

        .role-options-4 .role-option + label:hover {
            border-color: #a7f3d0;
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .role-options-4 .role-option:checked + label {
            background: #f0fdf4;
            border-color: #10b981;
            box-shadow: 0 4px 16px rgba(16, 185, 129, .15);
        }

        .role-options-4 .role-option[value="admin"]:checked + label {
            background: #f5f3ff;
            border-color: #7c3aed;
            box-shadow: 0 4px 16px rgba(124, 58, 237, .15);
        }

        .role-options-4 .role-option[value="superviseur"]:checked + label {
            background: #ecfdf5;
            border-color: #059669;
            box-shadow: 0 4px 16px rgba(5, 150, 105, .15);
        }

        .role-options-4 .role-option[value="caissier"]:checked + label {
            background: #eff6ff;
            border-color: #2563eb;
            box-shadow: 0 4px 16px rgba(37, 99, 235, .15);
        }

        .role-options-4 .role-option[value="caissierHaut"]:checked + label {
            background: #fefce8;
            border-color: #d97706;
            box-shadow: 0 4px 16px rgba(217, 119, 6, .15);
        }

        .role-options-4 .role-ico-wrap {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 8px;
            background: #e2e8f0;
            color: #64748b;
            transition: all .2s;
        }

        .role-options-4 .role-option:checked + label .role-ico-wrap {
            background: #d1fae5;
            color: #059669;
        }

        .role-options-4 .role-option[value="admin"]:checked + label .role-ico-wrap {
            background: #ede9fe;
            color: #7c3aed;
        }

        .role-options-4 .role-option[value="superviseur"]:checked + label .role-ico-wrap {
            background: #a7f3d0;
            color: #059669;
        }

        .role-options-4 .role-option[value="caissier"]:checked + label .role-ico-wrap {
            background: #bfdbfe;
            color: #2563eb;
        }

        .role-options-4 .role-option[value="caissierHaut"]:checked + label .role-ico-wrap {
            background: #fde68a;
            color: #d97706;
        }

        .role-lbl-main {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
        }

        .role-lbl-sub {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── FOOTER ── */
        .uc-form-footer {
            padding: 20px 28px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .uc-btn-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .uc-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 12px;
            border: none;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }

        .uc-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
        }

        .ub-primary {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            box-shadow: 0 4px 16px rgba(16, 185, 129, .3);
        }

        .ub-primary:hover {
            box-shadow: 0 8px 24px rgba(16, 185, 129, .4);
            color: #fff;
        }

        .ub-ghost {
            background: #f1f5f9;
            color: #475569;
        }

        .ub-ghost:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .uc-footer-hint {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ── ALERT ── */
        .uc-alert-err {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #dc2626;
        }

        /* ── RESPONSIVE ── */
        @media(max-width:900px) {
            .uc-layout {
                grid-template-columns: 1fr;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
            }

            .role-options-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:600px) {
            .uc-hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .role-options-4 {
                grid-template-columns: 1fr;
            }
        }

        /* ── ANIM ── */
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

        .uc-hero {
            animation: fadeUp .4s ease both;
        }

        .uc-layout {
            animation: fadeUp .4s .1s ease both;
        }
    </style>
@endpush

@section('content')
    <div class="uc-wrap">

        {{-- ── HERO ── --}}
        <div class="uc-hero">
            <div class="uc-hero-left">
                <div class="uc-hero-ico"><i class="fas fa-user-plus"></i></div>
                <div>
                    <h1 class="uc-hero-title">Créer un utilisateur</h1>
                    <p class="uc-hero-sub">Ajoutez un nouveau membre à l'équipe</p>
                </div>
            </div>
            <div class="uc-hero-right">
                <a href="{{ route('users.index') }}" class="uc-back-btn">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="uc-layout">

            {{-- ── SIDEBAR PREVIEW ── --}}
            <div>
                <div class="uc-info-card">

                    {{-- Aperçu live --}}
                    <div class="uc-info-top">
                        <div class="uc-avatar-preview" id="avatarPreview">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="uc-preview-name" id="previewName">Nom complet</div>
                        <div class="uc-preview-email" id="previewEmail">email@exemple.com</div>
                        <div class="uc-preview-role">
                            <span class="role-pill rp-caiss" id="previewRole">
                                <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                                Caissier
                            </span>
                        </div>
                    </div>

                    {{-- Conseils --}}
                    <div class="uc-info-body">
                        <div class="uc-tip">
                            <div class="uc-tip-ico" style="background:#eff6ff; color:#2563eb">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="uc-tip-text">
                                <strong>Rôle Admin</strong> : accès total à toutes les fonctionnalités du système.
                            </div>
                        </div>
                        <div class="uc-tip">
                            <div class="uc-tip-ico" style="background:#eff6ff; color:#2563eb">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div class="uc-tip-text">
                                <strong>Caissier</strong> : gestion des ventes et de la caisse uniquement.
                            </div>
                        </div>
                        <div class="uc-tip">
                            <div class="uc-tip-ico" style="background:#fefce8; color:#d97706">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div class="uc-tip-text">
                                <strong>Caissier Haut</strong> : gestion des ventes et de la caisse haut niveau.
                            </div>
                        </div>
                        <div class="uc-tip">
                            <div class="uc-tip-ico" style="background:#ecfdf5; color:#059669">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="uc-tip-text">
                                <strong>Superviseur</strong> : consultation des rapports et du stock.
                            </div>
                        </div>
                        <div class="uc-tip">
                            <div class="uc-tip-ico" style="background:#fef3c7; color:#d97706">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="uc-tip-text">
                                Le mot de passe doit comporter <strong>au moins 6 caractères</strong>.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── FORMULAIRE ── --}}
            <div>
                <div class="uc-form-card">
                    <div class="uc-form-head">
                        <span class="uc-form-head-ico"><i class="fas fa-user-plus"></i></span>
                        <span class="uc-form-head-title">Informations du compte</span>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="uc-form-body">

                            {{-- ── Identité ── --}}
                            <div class="uc-section">
                                <div class="uc-section-title">
                                    <i class="fas fa-user" style="font-size:10px"></i> Identité
                                </div>
                                <div class="form-row-2">
                                    <div>
                                        <label class="uc-label" for="prenom">
                                            Prénom <span class="req">*</span>
                                        </label>
                                        <div class="uc-input-wrap @error('prenom') is-err @enderror">
                                            <div class="uc-input-ico"><i class="fas fa-user"></i></div>
                                            <input type="text" id="prenom" name="prenom" class="uc-input"
                                                value="{{ old('prenom') }}" placeholder="Prénom" required
                                                oninput="updatePreview()">
                                        </div>
                                        @error('prenom')
                                            <div class="uc-err-msg"><i
                                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="uc-label" for="nom">
                                            Nom <span class="req">*</span>
                                        </label>
                                        <div class="uc-input-wrap @error('nom') is-err @enderror">
                                            <div class="uc-input-ico"><i class="fas fa-user"></i></div>
                                            <input type="text" id="nom" name="nom" class="uc-input"
                                                value="{{ old('nom') }}" placeholder="Nom de famille" required
                                                oninput="updatePreview()">
                                        </div>
                                        @error('nom')
                                            <div class="uc-err-msg"><i
                                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Contact ── --}}
                            <div class="uc-section">
                                <div class="uc-section-title">
                                    <i class="fas fa-address-card" style="font-size:10px"></i> Contact
                                </div>
                                <div class="form-row-2">
                                    <div>
                                        <label class="uc-label" for="email">
                                            Email <span class="req">*</span>
                                        </label>
                                        <div class="uc-input-wrap @error('email') is-err @enderror">
                                            <div class="uc-input-ico"><i class="fas fa-at"></i></div>
                                            <input type="email" id="email" name="email" class="uc-input"
                                                value="{{ old('email') }}" placeholder="email@exemple.com" required
                                                oninput="updatePreview()">
                                        </div>
                                        @error('email')
                                            <div class="uc-err-msg"><i
                                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="uc-label" for="telephone">Téléphone</label>
                                        <div class="uc-input-wrap">
                                            <div class="uc-input-ico"><i class="fas fa-phone"></i></div>
                                            <input type="text" id="telephone" name="telephone" class="uc-input"
                                                value="{{ old('telephone') }}" placeholder="+229 XX XX XX XX">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Rôle ── --}}
                            <div class="uc-section">
                                <div class="uc-section-title">
                                    <i class="fas fa-shield-alt" style="font-size:10px"></i> Rôle &amp; permissions
                                </div>
                                <div class="role-options-4">
                                    <div>
                                        <input type="radio" name="role" id="role_caissier" class="role-option"
                                            value="caissier" {{ old('role', 'caissier') === 'caissier' ? 'checked' : '' }}
                                            onchange="updatePreview()">
                                        <label for="role_caissier">
                                            <div class="role-ico-wrap"><i class="fas fa-cash-register"></i></div>
                                            <div class="role-lbl-main">Caissier</div>
                                            <div class="role-lbl-sub">Ventes uniquement</div>
                                        </label>
                                    </div>

                                    <div>
                                        <input type="radio" name="role" id="role_caissierHaut" class="role-option"
                                            value="caissierHaut" {{ old('role') === 'caissierHaut' ? 'checked' : '' }}
                                            onchange="updatePreview()">
                                        <label for="role_caissierHaut">
                                            <div class="role-ico-wrap"><i class="fas fa-cash-register"></i></div>
                                            <div class="role-lbl-main">Caissier Haut</div>
                                            <div class="role-lbl-sub">Ventes &amp; caisse</div>
                                        </label>
                                    </div>

                                    <div>
                                        <input type="radio" name="role" id="role_superviseur" class="role-option"
                                            value="superviseur" {{ old('role') === 'superviseur' ? 'checked' : '' }}
                                            onchange="updatePreview()">
                                        <label for="role_superviseur">
                                            <div class="role-ico-wrap"><i class="fas fa-chart-line"></i></div>
                                            <div class="role-lbl-main">Superviseur</div>
                                            <div class="role-lbl-sub">Rapports &amp; stock</div>
                                        </label>
                                    </div>

                                    <div>
                                        <input type="radio" name="role" id="role_admin" class="role-option"
                                            value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}
                                            onchange="updatePreview()">
                                        <label for="role_admin">
                                            <div class="role-ico-wrap"><i class="fas fa-shield-alt"></i></div>
                                            <div class="role-lbl-main">Administrateur</div>
                                            <div class="role-lbl-sub">Accès total</div>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="role" id="role_secretaire" class="role-option" value="secretaire" {{ old('role') === 'secretaire' ? 'checked' : '' }} onchange="updatePreview()">
                                        <label for="role_secretaire"><div class="role-ico-wrap"><i class="fas fa-clipboard-list"></i></div><div class="role-lbl-main">Secrétaire</div><div class="role-lbl-sub">Commandes clients</div></label>
                                    </div>
                                    <div>
                                        <input type="radio" name="role" id="role_magasinier" class="role-option" value="magasinier" {{ old('role') === 'magasinier' ? 'checked' : '' }} onchange="updatePreview()">
                                        <label for="role_magasinier"><div class="role-ico-wrap"><i class="fas fa-boxes"></i></div><div class="role-lbl-main">Magasinier</div><div class="role-lbl-sub">Préparation &amp; livraisons</div></label>
                                    </div>
                                </div>
                                @error('role')
                                    <div class="uc-err-msg mt-2"><i
                                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Mot de passe ── --}}
                            <div class="uc-section" style="margin-bottom:0">
                                <div class="uc-section-title">
                                    <i class="fas fa-lock" style="font-size:10px"></i> Mot de passe
                                </div>
                                <div class="form-row-2">
                                    <div>
                                        <label class="uc-label" for="password">
                                            Mot de passe <span class="req">*</span>
                                        </label>
                                        <div class="uc-input-wrap @error('password') is-err @enderror">
                                            <div class="uc-input-ico"><i class="fas fa-lock"></i></div>
                                            <input type="password" id="password" name="password" class="uc-input"
                                                placeholder="Min. 6 caractères" required>
                                            <div class="uc-pwd-toggle" onclick="togglePwd('password','eye1')">
                                                <i class="fas fa-eye" id="eye1"></i>
                                            </div>
                                        </div>
                                        @error('password')
                                            <div class="uc-err-msg"><i
                                                    class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="uc-label" for="password_confirmation">
                                            Confirmer <span class="req">*</span>
                                        </label>
                                        <div class="uc-input-wrap">
                                            <div class="uc-input-ico"><i class="fas fa-lock"></i></div>
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" class="uc-input"
                                                placeholder="Répéter le mot de passe" required>
                                            <div class="uc-pwd-toggle"
                                                onclick="togglePwd('password_confirmation','eye2')">
                                                <i class="fas fa-eye" id="eye2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /form-body --}}

                        {{-- ── FOOTER ── --}}
                        <div class="uc-form-footer">
                            <span class="uc-footer-hint">
                                <i class="fas fa-info-circle mr-1"></i>
                                Les champs marqués <span style="color:#ef4444">*</span> sont obligatoires
                            </span>
                            <div class="uc-btn-group">
                                <a href="{{ route('users.index') }}" class="uc-btn ub-ghost">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="uc-btn ub-primary">
                                    <i class="fas fa-user-plus"></i> Créer le compte
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>{{-- /uc-layout --}}
    </div>
@endsection

@push('scripts')
    <script>
        // ── Aperçu live ──
        const roleConfig = {
            caissier: {
                label: 'Caissier',
                cls: 'rp-caiss',
                avatarBg: 'linear-gradient(135deg,#2563eb,#3b82f6)'
            },
            caissierHaut: {
                label: 'Caissier Haut',
                cls: 'rp-caiss-haut',
                avatarBg: 'linear-gradient(135deg,#d97706,#f59e0b)'
            },
            superviseur: {
                label: 'Superviseur',
                cls: 'rp-sup',
                avatarBg: 'linear-gradient(135deg,#059669,#10b981)'
            },
            admin: {
                label: 'Administrateur',
                cls: 'rp-admin',
                avatarBg: 'linear-gradient(135deg,#7c3aed,#a855f7)'
            },
            secretaire: { label: 'Secrétaire', cls: 'rp-other', avatarBg: 'linear-gradient(135deg,#0891b2,#22d3ee)' },
            magasinier: { label: 'Magasinier', cls: 'rp-other', avatarBg: 'linear-gradient(135deg,#ea580c,#fb923c)' },
        };

        function updatePreview() {
            const prenom = document.getElementById('prenom').value.trim();
            const nom = document.getElementById('nom').value.trim();
            const email = document.getElementById('email').value.trim();
            const role = document.querySelector('input[name="role"]:checked')?.value || 'caissier';
            const cfg = roleConfig[role] || roleConfig.caissier;

            // Nom
            const fullName = [prenom, nom].filter(Boolean).join(' ');
            document.getElementById('previewName').textContent = fullName || 'Nom complet';

            // Email
            document.getElementById('previewEmail').textContent = email || 'email@exemple.com';

            // Avatar initiales
            const avatar = document.getElementById('avatarPreview');
            if (prenom || nom) {
                const initials = [prenom, nom]
                    .filter(Boolean)
                    .map(w => w[0].toUpperCase())
                    .join('');
                avatar.style.background = cfg.avatarBg;
                avatar.innerHTML = `<span style="font-size:22px;font-weight:900;color:#fff">${initials}</span>`;
            } else {
                avatar.style.background = 'linear-gradient(135deg,#475569,#64748b)';
                avatar.innerHTML = '<i class="fas fa-user" style="font-size:22px;color:#fff"></i>';
            }

            // Role pill
            const pill = document.getElementById('previewRole');
            pill.className = `role-pill ${cfg.cls}`;
            pill.innerHTML =
                `<span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>${cfg.label}`;
        }

        function togglePwd(inputId, iconId) {
            const inp = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'fas fa-eye-slash' : 'fas fa-eye';
        }

        // Initialiser l'aperçu
        updatePreview();
    </script>
@endpush
