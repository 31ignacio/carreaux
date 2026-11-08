{{-- resources/views/errors/404.blade.php --}}
@extends('errors.layout')
@section('title', 'Page non trouvée')
@push('error_styles')
<style>
    .error-code {
        background: linear-gradient(135deg, #60a5fa 30%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        animation: floatCode 3.5s ease-in-out infinite;
    }

    .error-code::after {
        background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    }

    /* Glitch subtil au survol du code */
    .error-code-wrap {
        position: relative;
        cursor: default;
    }

    .error-code-wrap:hover .error-code {
        animation: floatCode 3.5s ease-in-out infinite, glitch 0.35s ease-in-out;
    }

    @keyframes floatCode {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-6px); }
    }

    @keyframes glitch {
        0%   { transform: translate(0,0); }
        20%  { transform: translate(-3px,2px); }
        40%  { transform: translate(3px,-2px); }
        60%  { transform: translate(-2px,-1px); }
        80%  { transform: translate(2px,1px); }
        100% { transform: translate(0,0); }
    }

    /* Particules flottantes en fond */
    .error-particles {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .error-particle {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: rgba(59, 130, 246, .4);
        animation: driftUp linear infinite;
    }

    .error-particle:nth-child(1) { left: 8%;  width: 3px; height: 3px; animation-duration: 9s;  animation-delay: 0s; }
    .error-particle:nth-child(2) { left: 22%; width: 5px; height: 5px; animation-duration: 12s; animation-delay: 1.5s; background: rgba(255,255,255,.15); }
    .error-particle:nth-child(3) { left: 38%; width: 2px; height: 2px; animation-duration: 7s;  animation-delay: .5s; }
    .error-particle:nth-child(4) { left: 55%; width: 4px; height: 4px; animation-duration: 11s; animation-delay: 3s;  background: rgba(255,255,255,.12); }
    .error-particle:nth-child(5) { left: 68%; width: 3px; height: 3px; animation-duration: 8s;  animation-delay: 2s; }
    .error-particle:nth-child(6) { left: 80%; width: 6px; height: 6px; animation-duration: 13s; animation-delay: .8s; background: rgba(255,255,255,.1); }
    .error-particle:nth-child(7) { left: 92%; width: 3px; height: 3px; animation-duration: 10s; animation-delay: 4s; }

    @keyframes driftUp {
        0%   { transform: translateY(110vh) translateX(0);   opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { transform: translateY(-10vh) translateX(20px); opacity: 0; }
    }

    /* Boussole plus vivante */
    .error-illustration svg {
        filter: drop-shadow(0 0 18px rgba(59, 130, 246, .25));
    }

    .error-illustration circle[stroke] {
        animation: ringPulse 4s ease-in-out infinite;
        transform-origin: center;
    }
    .error-illustration circle[stroke]:nth-of-type(1) { animation-delay: 0s; }
    .error-illustration circle[stroke]:nth-of-type(2) { animation-delay: .4s; }
    .error-illustration circle[stroke]:nth-of-type(3) { animation-delay: .8s; }

    @keyframes ringPulse {
        0%, 100% { opacity: .6; transform: scale(1); }
        50%      { opacity: 1;  transform: scale(1.03); }
    }

    /* Titre / message : entrée douce */
    .error-title, .error-message, .error-actions {
        animation: fadeUpIn .7s ease both;
    }
    .error-title   { animation-delay: .15s; }
    .error-message { animation-delay: .3s; }
    .error-actions { animation-delay: .45s; }

    @keyframes fadeUpIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Boutons plus toniques */
    .btn-primary, .btn-secondary {
        position: relative;
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 26px rgba(59, 130, 246, .35);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -60%;
        width: 40%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.35), transparent);
        transform: skewX(-20deg);
        transition: left .5s ease;
    }

    .btn-primary:hover::before {
        left: 130%;
    }

    .btn-primary i, .btn-secondary i {
        transition: transform .25s ease;
    }

    .btn-primary:hover i.fa-home {
        transform: scale(1.15) translateY(-1px);
    }

    .btn-secondary:hover i.fa-arrow-left {
        transform: translateX(-4px);
    }
</style>
@endpush
@section('content')

<div class="error-particles">
    <span class="error-particle"></span>
    <span class="error-particle"></span>
    <span class="error-particle"></span>
    <span class="error-particle"></span>
    <span class="error-particle"></span>
    <span class="error-particle"></span>
    <span class="error-particle"></span>
</div>

<div class="error-code-wrap">
    <div class="error-code">404</div>
</div>

<div class="error-illustration">
    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Compass animé -->
        <g transform="translate(100, 100)">
            <circle cx="0" cy="0" r="70" stroke="rgba(255,255,255,.1)" stroke-width="2" fill="none"/>
            <circle cx="0" cy="0" r="55" stroke="rgba(255,255,255,.08)" stroke-width="1" fill="none"/>
            <circle cx="0" cy="0" r="40" stroke="rgba(255,255,255,.05)" stroke-width="1" fill="none"/>

            <!-- Aiguille Nord -->
            <g>
                <polygon points="0,-50 8,-10 -8,-10" fill="#3b82f6" opacity=".9">
                    <animateTransform attributeName="transform" type="rotate"
                        values="0 0 0; 360 0 0" dur="20s" repeatCount="indefinite"/>
                </polygon>
            </g>

            <!-- Aiguille Sud -->
            <g>
                <polygon points="0,50 6,12 -6,12" fill="rgba(255,255,255,.3)">
                    <animateTransform attributeName="transform" type="rotate"
                        values="0 0 0; 360 0 0" dur="20s" repeatCount="indefinite"/>
                </polygon>
            </g>

            <!-- Points cardinaux -->
            <circle cx="0" cy="-65" r="4" fill="#3b82f6">
                <animate attributeName="opacity" values="1;0.3;1" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="0" cy="65" r="3" fill="rgba(255,255,255,.2)"/>
            <circle cx="-65" cy="0" r="3" fill="rgba(255,255,255,.2)"/>
            <circle cx="65" cy="0" r="3" fill="rgba(255,255,255,.2)"/>
        </g>

        <!-- Lignes décoratives -->
        <path d="M30 170 L50 150 M170 30 L150 50" stroke="rgba(255,255,255,.05)" stroke-width="2"/>
        <path d="M30 30 L50 50 M170 170 L150 150" stroke="rgba(255,255,255,.03)" stroke-width="2"/>
    </svg>
</div>

<h1 class="error-title">Oups ! Page introuvable</h1>
<p class="error-message">
    La page que vous recherchez a peut-être été déplacée, supprimée ou n'existe pas.
    <br><small style="color:rgba(255,255,255,.35);font-size:13px;">
        Code d'erreur : <strong>404</strong>
    </small>
</p>
<div class="error-actions">
    <a href="{{ url('/') }}" class="btn-primary">
        <i class="fas fa-home"></i> Accueil
    </a>
    <a href="javascript:history.back()" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>
@endsection