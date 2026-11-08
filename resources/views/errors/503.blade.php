{{-- resources/views/errors/503-robot.blade.php --}}
@extends('errors.layout')

@section('title', 'Maintenance en cours')

@push('error_styles')
<style>
    .error-code { 
        background: linear-gradient(135deg, #a78bfa 30%, #7c3aed 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .error-code::after {
        background: linear-gradient(90deg, transparent, #7c3aed, transparent);
    }

    /* ── Robot ── */
    .robot-container {
        position: relative;
        width: 160px;
        height: 180px;
        margin: 0 auto 24px;
        animation: robotFloat 4s ease-in-out infinite;
    }

    @keyframes robotFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }

    .robot-head {
        position: absolute;
        width: 70px;
        height: 60px;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 12px 12px 4px 4px;
        border: 2px solid rgba(255,255,255,.1);
        background: rgba(255,255,255,.04);
        overflow: hidden;
    }

    .robot-head::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,.05) 0%, transparent 50%);
    }

    /* Antennes */
    .robot-antenna {
        position: absolute;
        width: 2px;
        height: 20px;
        top: -18px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255,255,255,.1);
    }

    .robot-antenna::before {
        content: '●';
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 8px;
        color: #7c3aed;
        animation: antennaBlink 1.5s ease-in-out infinite;
    }

    @keyframes antennaBlink {
        0%, 100% { opacity: .3; }
        50% { opacity: 1; }
    }

    /* Yeux */
    .robot-eyes {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 14px;
    }

    .robot-eye {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: radial-gradient(circle at 40% 40%, rgba(124,58,237,.4), rgba(124,58,237,.05));
        border: 1px solid rgba(124,58,237,.15);
        animation: eyePulse 2s ease-in-out infinite;
    }

    .robot-eye:nth-child(2) { animation-delay: .3s; }

    @keyframes eyePulse {
        0%, 100% { transform: scale(1); opacity: .5; }
        50% { transform: scale(1.2); opacity: 1; }
    }

    /* Bouche - barre de progression */
    .robot-mouth {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        width: 35px;
        height: 4px;
        background: rgba(255,255,255,.05);
        border-radius: 2px;
        overflow: hidden;
    }

    .robot-mouth-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #7c3aed, #a78bfa);
        border-radius: 2px;
        animation: mouthProgress 3s ease-in-out infinite;
    }

    @keyframes mouthProgress {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; }
    }

    /* Corps */
    .robot-body {
        position: absolute;
        width: 50px;
        height: 55px;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 8px 8px 16px 16px;
        border: 2px solid rgba(255,255,255,.08);
        background: rgba(255,255,255,.03);
    }

    .robot-body::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.05);
    }

    /* Bras */
    .robot-arm {
        position: absolute;
        width: 8px;
        height: 30px;
        top: 52px;
        border-radius: 4px;
        border: 2px solid rgba(255,255,255,.06);
        background: rgba(255,255,255,.02);
        transform-origin: top center;
        animation: armSwing 2.5s ease-in-out infinite;
    }

    .robot-arm-left {
        left: -12px;
        transform: rotate(15deg);
    }

    .robot-arm-right {
        right: -12px;
        transform: rotate(-15deg);
        animation-delay: 0.5s;
    }

    @keyframes armSwing {
        0%, 100% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
    }

    .robot-arm-left {
        animation-name: armSwingLeft;
    }

    @keyframes armSwingLeft {
        0%, 100% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
    }

    /* Mains */
    .robot-hand {
        position: absolute;
        width: 10px;
        height: 10px;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.05);
        background: rgba(255,255,255,.02);
    }

    /* Jambes */
    .robot-leg {
        position: absolute;
        width: 8px;
        height: 25px;
        bottom: 0;
        border-radius: 4px;
        border: 2px solid rgba(255,255,255,.06);
        background: rgba(255,255,255,.02);
    }

    .robot-leg-left { left: 32px; }
    .robot-leg-right { right: 32px; }

    .robot-foot {
        position: absolute;
        width: 14px;
        height: 4px;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.03);
    }

    /* Outils flottants */
    .tool-float {
        position: absolute;
        font-size: 20px;
        opacity: .15;
        animation: toolFloat 4s ease-in-out infinite;
    }

    .tool-float-1 { top: -10px; right: -20px; animation-delay: 0s; }
    .tool-float-2 { bottom: 20px; left: -20px; animation-delay: 1.5s; }
    .tool-float-3 { top: 60%; right: -25px; animation-delay: 3s; font-size: 16px; }

    @keyframes toolFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: .1; }
        50% { transform: translateY(-15px) rotate(15deg); opacity: .25; }
    }
</style>
@endpush

@section('content')
<div class="error-code-wrap">
    <div class="error-code">503</div>
</div>

<div class="robot-container">
    <!-- Outils flottants -->
    <div class="tool-float tool-float-1">🔧</div>
    <div class="tool-float tool-float-2">⚡</div>
    <div class="tool-float tool-float-3">🛠️</div>

    <!-- Tête -->
    <div class="robot-head">
        <div class="robot-antenna"></div>
        <div class="robot-eyes">
            <div class="robot-eye"></div>
            <div class="robot-eye"></div>
        </div>
        <div class="robot-mouth">
            <div class="robot-mouth-fill"></div>
        </div>
    </div>

    <!-- Bras -->
    <div class="robot-arm robot-arm-left">
        <div class="robot-hand"></div>
    </div>
    <div class="robot-arm robot-arm-right">
        <div class="robot-hand"></div>
    </div>

    <!-- Corps -->
    <div class="robot-body"></div>

    <!-- Jambes -->
    <div class="robot-leg robot-leg-left">
        <div class="robot-foot"></div>
    </div>
    <div class="robot-leg robot-leg-right">
        <div class="robot-foot"></div>
    </div>
</div>

<h1 class="error-title">Nous faisons une petite mise à jour</h1>
<p class="error-message">
    Notre équipe travaille dur pour améliorer votre expérience.
    <br><small style="color:rgba(255,255,255,.35);font-size:13px;">
        Le site sera de retour dans quelques minutes. Merci de votre patience !
    </small>
</p>

<div class="error-actions">
    <a href="javascript:location.reload()" class="btn-primary">
        <i class="fas fa-sync"></i> Rafraîchir
    </a>
    <a href="{{ url('/') }}" class="btn-secondary">
        <i class="fas fa-home"></i> Accueil
    </a>
</div>
@endsection