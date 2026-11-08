{{-- resources/views/errors/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Erreur') — {{ config('app.name') }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(145deg, #0f172a 0%, #1a2a4a 45%, #0f2847 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }
        
        /* ── Particules de fond ── */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }
        
        .particle:nth-child(1) { left: 10%; top: 20%; animation-duration: 12s; animation-delay: 0s; width: 8px; height: 8px; }
        .particle:nth-child(2) { left: 20%; top: 60%; animation-duration: 15s; animation-delay: 2s; width: 4px; height: 4px; }
        .particle:nth-child(3) { left: 30%; top: 10%; animation-duration: 10s; animation-delay: 4s; width: 6px; height: 6px; }
        .particle:nth-child(4) { left: 40%; top: 80%; animation-duration: 18s; animation-delay: 1s; width: 10px; height: 10px; }
        .particle:nth-child(5) { left: 50%; top: 30%; animation-duration: 14s; animation-delay: 3s; width: 5px; height: 5px; }
        .particle:nth-child(6) { left: 60%; top: 70%; animation-duration: 11s; animation-delay: 5s; width: 7px; height: 7px; }
        .particle:nth-child(7) { left: 70%; top: 15%; animation-duration: 16s; animation-delay: 0s; width: 4px; height: 4px; }
        .particle:nth-child(8) { left: 80%; top: 50%; animation-duration: 13s; animation-delay: 2s; width: 9px; height: 9px; }
        .particle:nth-child(9) { left: 90%; top: 25%; animation-duration: 17s; animation-delay: 4s; width: 5px; height: 5px; }
        .particle:nth-child(10) { left: 15%; top: 90%; animation-duration: 19s; animation-delay: 1s; width: 6px; height: 6px; }
        
        @keyframes floatParticle {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 1; }
            25% { transform: translateY(-30px) translateX(20px) scale(1.2); opacity: .8; }
            50% { transform: translateY(-60px) translateX(-10px) scale(1); opacity: .6; }
            75% { transform: translateY(-30px) translateX(30px) scale(1.1); opacity: .8; }
            100% { transform: translateY(0) translateX(0) scale(1); opacity: 1; }
        }
        
        /* ── Cercle lumineux de fond ── */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px);
            animation: glowPulse 4s ease-in-out infinite alternate;
        }
        
        .glow-orb-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
            background: rgba(99,102,241,.08);
        }
        
        .glow-orb-2 {
            width: 500px;
            height: 500px;
            bottom: -150px;
            left: -150px;
            background: rgba(16,185,129,.06);
            animation-delay: 2s;
        }
        
        @keyframes glowPulse {
            0% { transform: scale(1); opacity: .5; }
            100% { transform: scale(1.2); opacity: 1; }
        }
        
        /* ── Carte d'erreur ── */
        .error-wrapper {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 50px 40px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 32px 90px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.1);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,.08);
            animation: slideUp 0.6s cubic-bezier(.34, 1.56, .64, 1) both;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        .error-content {
            position: relative;
            z-index: 1;
        }
        
        /* ── Code erreur ── */
        .error-code-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .error-code {
            font-family: 'Syne', sans-serif;
            font-size: 100px;
            font-weight: 900;
            background: linear-gradient(135deg, #fff 30%, rgba(255,255,255,.5) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            letter-spacing: -5px;
            position: relative;
            animation: codePulse 3s ease-in-out infinite;
        }
        
        @keyframes codePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .error-code::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            border-radius: 2px;
        }
        
        /* ── Illustration SVG ── */
        .error-illustration {
            width: 160px;
            height: 160px;
            margin: 0 auto 24px;
            animation: floatIllustration 4s ease-in-out infinite;
        }
        
        @keyframes floatIllustration {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(2deg); }
        }
        
        .error-illustration svg {
            width: 100%;
            height: 100%;
        }
        
        /* ── Titre ── */
        .error-title {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            letter-spacing: -.5px;
        }
        
        .error-message {
            font-size: 15px;
            color: rgba(255,255,255,.6);
            line-height: 1.7;
            margin-bottom: 28px;
        }
        
        .error-message strong {
            color: rgba(255,255,255,.9);
        }
        
        /* ── Boutons ── */
        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .error-actions a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 28px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all .3s cubic-bezier(.34, 1.56, .64, 1);
            font-family: 'DM Sans', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        .error-actions a:hover {
            transform: translateY(-3px);
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            box-shadow: 0 4px 20px rgba(16,185,129,.3);
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 30px rgba(16,185,129,.5);
            color: #fff;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15), transparent 60%);
            opacity: 0;
            transition: opacity .3s;
        }
        
        .btn-primary:hover::before {
            opacity: 1;
        }
        
        .btn-secondary {
            background: rgba(255,255,255,.08);
            color: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.12);
        }
        
        .btn-secondary:hover {
            background: rgba(255,255,255,.15);
            color: #fff;
            border-color: rgba(255,255,255,.2);
        }
        
        .btn-secondary i {
            transition: transform .3s;
        }
        
        .btn-secondary:hover i {
            transform: translateX(-4px);
        }
        
        /* ── Responsive ── */
        @media(max-width: 480px) {
            .error-wrapper { padding: 30px 20px; margin: 10px; }
            .error-code { font-size: 60px; letter-spacing: -3px; }
            .error-title { font-size: 22px; }
            .error-illustration { width: 120px; height: 120px; }
            .error-actions a { width: 100%; justify-content: center; }
            .error-message { font-size: 14px; }
        }
    </style>
    
    @stack('error_styles')
</head>
<body>
    <!-- Particules -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <!-- Glow orbs -->
    <div class="glow-orb glow-orb-1"></div>
    <div class="glow-orb glow-orb-2"></div>
    
    <div class="error-wrapper">
        <div class="error-content">
            @yield('content')
        </div>
    </div>
</body>
</html>