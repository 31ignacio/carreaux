<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Gamaco 83</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        html,body{height:100%}

        body{
            font-family:sans-serif;
            min-height:100vh;
            display:flex;
            background:#0E1621;
            overflow-x:hidden;
        }

        /* ══ LEFT — visuel carreaux ══ */
        .left-panel{
            flex:1.1;
            position:relative;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            justify-content:flex-end;
            padding:48px;
        }

        /* Fond photographique simulé : grille de carreaux en SVG */
        .left-panel::before{
            content:'';
            position:absolute;inset:0;z-index:0;
            background:
                linear-gradient(160deg,#1A3A5C 0%,#0E2340 40%,#0A1A2E 100%);
        }

        /* Grille de carreaux décorative */
        .tile-grid{
            position:absolute;inset:0;z-index:1;
            display:grid;
            grid-template-columns:repeat(6,1fr);
            grid-template-rows:repeat(7,1fr);
            gap:3px;
            padding:3px;
            opacity:.18;
        }
        .tile-grid .t{
            border-radius:4px;
            background:linear-gradient(135deg,#5B8FD6,#2563EB);
        }
        .tile-grid .t:nth-child(3n){ background:linear-gradient(135deg,#7C3AED,#4F46E5); }
        .tile-grid .t:nth-child(5n){ background:linear-gradient(135deg,#0EA5E9,#0369A1); }
        .tile-grid .t:nth-child(7n){ background:linear-gradient(135deg,#D97706,#B45309); opacity:.6; }
        .tile-grid .t:nth-child(11n){ background:linear-gradient(135deg,#94A3B8,#64748B); }

        /* Dégradé de masque sur la grille */
        .tile-overlay{
            position:absolute;inset:0;z-index:2;
            background:linear-gradient(160deg,
                rgba(14,22,33,.2) 0%,
                rgba(14,22,33,.55) 50%,
                rgba(14,22,33,.92) 100%);
        }

        /* Bulle décorative */
        .left-bubble{
            position:absolute;z-index:2;
            border-radius:50%;filter:blur(80px);pointer-events:none;
        }
        .lb1{width:350px;height:350px;background:rgba(37,99,235,.2);top:-80px;right:-80px;}
        .lb2{width:280px;height:280px;background:rgba(79,70,229,.15);bottom:-60px;left:40%;}

        .left-content{position:relative;z-index:3}

        .left-logo{
            display:inline-flex;align-items:center;gap:14px;margin-bottom:32px;
        }
        .left-logo-icon{
            width:58px;height:58px;border-radius:16px;
            background:linear-gradient(135deg,#2563EB,#1D4ED8);
            display:flex;align-items:center;justify-content:center;
            font-size:24px;color:#fff;
            box-shadow:0 12px 32px rgba(37,99,235,.4);
            flex-shrink:0;
        }
        .left-logo-text .brand{
            font-size:26px;font-weight:900;color:#fff;letter-spacing:-.03em;line-height:1;
        }
        .left-logo-text .brand span{color:#93C5FD;font-weight:500}
        .left-logo-text .tagline{font-size:12px;color:rgba(255,255,255,.45);font-weight:500;margin-top:2px}

        .left-headline{
            font-size:clamp(1rem,3vw,2rem);font-weight:400;
            color:#fff;line-height:1.1;letter-spacing:-.03em;margin-bottom:16px;
        }
        .left-headline em{
            color:transparent;
            background:linear-gradient(90deg,#60A5FA,#A78BFA);
            -webkit-background-clip:text;background-clip:text;
            font-style:normal;
        }
        .left-sub{
            font-size:14.5px;color:rgba(255,255,255,.55);line-height:1.7;max-width:400px;margin-bottom:32px;
        }

        /* Features */
        .left-features{display:flex;flex-direction:column;gap:12px}
        .feat{
            display:flex;align-items:center;gap:12px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            border-radius:12px;padding:12px 16px;
            backdrop-filter:blur(4px);
        }
        .feat-ico{
            width:36px;height:36px;border-radius:10px;
            display:flex;align-items:center;justify-content:center;
            font-size:15px;color:#fff;flex-shrink:0;
        }
        .feat span{font-size:13px;color:rgba(255,255,255,.8);font-weight:500}

        /* ══ RIGHT — formulaire ══ */
        .right-panel{
            width:min(460px,100%);
            background:#fff;
            display:flex;align-items:center;justify-content:center;
            padding:40px 44px;
            position:relative;
        }

        /* Motif micro sur le panneau blanc */
        .right-panel::before{
            content:'';position:absolute;inset:0;z-index:0;
            background-image:radial-gradient(circle,#E2E8F0 1px,transparent 1px);
            background-size:28px 28px;
            opacity:.4;
        }

        .right-inner{position:relative;z-index:1;width:100%}

        /* Header form */
        .form-header{margin-bottom:30px}
        .form-badge{
            display:inline-flex;align-items:center;gap:7px;
            background:#EFF6FF;border:1px solid #BFDBFE;border-radius:30px;
            padding:5px 14px;font-size:11.5px;font-weight:700;color:#2563EB;margin-bottom:18px;
        }
        .form-title{font-size:26px;font-weight:900;color:#0F172A;letter-spacing:-.03em;margin-bottom:5px}
        .form-sub{font-size:13px;color:#94A3B8}

        /* Alert */
        .alert-err{
            background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;
            padding:11px 14px;display:flex;align-items:center;gap:9px;
            font-size:13px;color:#DC2626;margin-bottom:20px;
        }
        .alert-ok{
            background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;
            padding:11px 14px;font-size:13px;color:#16A34A;margin-bottom:20px;
        }

        /* Champs */
        .field{margin-bottom:18px}
        .field-label{
            display:block;font-size:11px;font-weight:700;text-transform:uppercase;
            letter-spacing:.6px;color:#94A3B8;margin-bottom:7px;
        }
        .field-wrap{
            display:flex;align-items:center;
            background:#F8FAFC;border:1.5px solid #E2E8F0;
            border-radius:12px;overflow:hidden;transition:all .18s;
        }
        .field-wrap:focus-within{
            border-color:#2563EB;background:#fff;
            box-shadow:0 0 0 4px rgba(37,99,235,.09);
        }
        .field-ico{
            width:46px;display:flex;align-items:center;justify-content:center;
            color:#CBD5E1;font-size:14px;flex-shrink:0;transition:color .18s;
        }
        .field-wrap:focus-within .field-ico{color:#2563EB}
        .field-input{
            flex:1;border:none;background:transparent;
            padding:13px 14px 13px 0;font-size:14px;color:#0F172A;
            outline:none;font-family:sans-serif;
        }
        .field-input::placeholder{color:#CBD5E1}
        .field-toggle{
            width:44px;display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:#CBD5E1;font-size:13px;transition:color .15s;
        }
        .field-toggle:hover{color:#2563EB}

        /* Remember */
        .remember-row{display:flex;align-items:center;gap:9px;margin-bottom:24px}
        .remember-row input{accent-color:#2563EB;width:16px;height:16px;cursor:pointer}
        .remember-row label{font-size:13px;color:#94A3B8;cursor:pointer}

        /* Bouton */
        .btn-login{
            width:100%;padding:15px;border-radius:13px;border:none;
            background:linear-gradient(135deg,#1D4ED8,#2563EB);
            color:#fff;font-size:15px;font-weight:800;cursor:pointer;
            display:flex;align-items:center;justify-content:center;gap:10px;
            box-shadow:0 8px 24px rgba(37,99,235,.32);letter-spacing:.2px;
            transition:all .2s;font-family:sans-serif;
        }
        .btn-login:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 12px 32px rgba(37,99,235,.4)}
        .btn-login:active:not(:disabled){transform:translateY(0)}
        .btn-login:disabled{background:#E2E8F0;color:#94A3B8;cursor:not-allowed;box-shadow:none}

        .spinner{
            display:none;width:18px;height:18px;
            border:2.5px solid rgba(255,255,255,.3);
            border-top-color:#fff;border-radius:50%;
            animation:spin .7s linear infinite;flex-shrink:0;
        }
        @keyframes spin{to{transform:rotate(360deg)}}
        .loading .spinner{display:block}
        .loading .btn-icon{display:none}
        .loading .btn-label{opacity:.8}

        /* Footer */
        .form-footer{
            margin-top:24px;text-align:center;
            font-size:12px;color:#CBD5E1;
        }

        /* Carreaux décoratifs coin du formulaire */
        .corner-tiles{
            position:absolute;top:24px;right:24px;
            display:grid;grid-template-columns:repeat(4,14px);gap:3px;z-index:0;opacity:.25;
        }
        .corner-tiles .ct{width:14px;height:14px;border-radius:3px;background:#2563EB}
        .corner-tiles .ct:nth-child(3n){background:#7C3AED}
        .corner-tiles .ct:nth-child(5n){background:#0EA5E9}

        /* Responsive */
        @media(max-width:860px){
            .left-panel{display:none}
            .right-panel{width:100%;padding:32px 24px}
        }
    </style>
</head>
<body>

    {{-- ════ PANNEAU GAUCHE ════ --}}
    <div class="left-panel">
        <!-- Grille carreaux -->
        <div class="tile-grid">
            @for($i=0;$i<42;$i++)<div class="t"></div>@endfor
        </div>
        <div class="tile-overlay"></div>
        <div class="left-bubble lb1"></div>
        <div class="left-bubble lb2"></div>

        <div class="left-content">
            <div class="left-logo">
                <div class="left-logo-icon"><i class="fas fa-th-large"></i></div>
                <div class="left-logo-text">
                    <div class="brand">GAMACO <span>83</span></div>
                    <div class="tagline">Gestion carrelage & revêtements</div>
                </div>
            </div>

            {{-- <h1 class="left-headline">Gérez vos ventes<br>de <em>carreaux</em><br>simplement.</h1> --}}
            <p class="left-sub">
                Stocks, devis, bons de livraison et clients — tout ce dont vous avez besoin
                pour piloter votre activité de carrelage en un seul endroit.
            </p>

            <div class="left-features">
                <div class="feat">
                    <div class="feat-ico" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)"><i class="fas fa-boxes-stacked"></i></div>
                    <span>Suivi du stock en temps réel par référence et couleur</span>
                </div>
                <div class="feat">
                    <div class="feat-ico" style="background:linear-gradient(135deg,#7C3AED,#6D28D9)"><i class="fas fa-file-invoice-dollar"></i></div>
                    <span>Devis et bons générés en quelques secondes</span>
                </div>
                <div class="feat">
                    <div class="feat-ico" style="background:linear-gradient(135deg,#0EA5E9,#0284C7)"><i class="fas fa-chart-line"></i></div>
                    <span>Tableau de bord des ventes et statistiques</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ PANNEAU DROIT — formulaire ════ --}}
    <div class="right-panel">

        <!-- Carreaux décoratifs coin -->
        <div class="corner-tiles">
            @for($i=0;$i<16;$i++)<div class="ct"></div>@endfor
        </div>

        <div class="right-inner">

            <div class="form-header">
                <div class="form-badge">
                    <i class="fas fa-shield-halved"></i> Accès sécurisé
                </div>
                <h2 class="form-title">Bon retour 👋</h2>
                <p class="form-sub">Connectez-vous à votre espace de gestion</p>
            </div>

            @if($errors->any())
                <div class="alert-err">
                    <i class="fas fa-circle-exclamation" style="font-size:15px;flex-shrink:0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="alert-ok">
                    <i class="fas fa-circle-check" style="margin-right:6px"></i>{{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field">
                    <label class="field-label" for="prenom">Prénom</label>
                    <div class="field-wrap">
                        <div class="field-ico"><i class="fas fa-user"></i></div>
                        <select id="prenom" name="prenom" class="field-input" required autofocus
                                style="appearance:none;-webkit-appearance:none;cursor:pointer">
                            <option value="">Sélectionnez votre prénom</option>
                            @foreach($prenoms ?? collect() as $prenomOption)
                                <option value="{{ $prenomOption }}" @selected(old('prenom') === $prenomOption)>
                                    {{ $prenomOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="password">Mot de passe</label>
                    <div class="field-wrap">
                        <div class="field-ico"><i class="fas fa-lock"></i></div>
                        <input type="password" id="password" name="password" class="field-input"
                               placeholder="••••••••" required>
                        <div class="field-toggle" onclick="togglePwd()">
                            <i class="fas fa-eye" id="pwdIcon"></i>
                        </div>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <div class="spinner" id="btnSpinner"></div>
                    <i class="fas fa-right-to-bracket btn-icon" id="btnIcon"></i>
                    <span class="btn-label" id="btnLabel">Se connecter</span>
                </button>
            </form>

            <div class="form-footer">
                Gamaco &copy; {{ date('Y') }} &nbsp;·&nbsp; Gestion carrelage & revêtements
            </div>

        </div>
    </div>

    <script>
        function togglePwd() {
            const inp  = document.getElementById('password');
            const icon = document.getElementById('pwdIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'fas fa-eye-slash' : 'fas fa-eye';
        }

        document.getElementById('loginForm').addEventListener('submit', function () {
            const prenom = document.getElementById('prenom').value.trim();
            const pwd    = document.getElementById('password').value.trim();
            if (!prenom || !pwd) return;

            const btn   = document.getElementById('btnLogin');
            const label = document.getElementById('btnLabel');
            btn.disabled = true;
            btn.classList.add('loading');
            label.textContent = 'Connexion en cours…';
        });
    </script>

</body>
</html>