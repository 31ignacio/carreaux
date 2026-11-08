{{-- resources/views/errors/403.blade.php --}}
@extends('errors.layout')

@section('title', 'Accès interdit')

@push('error_styles')
<style>
    .error-code { 
        background: linear-gradient(135deg, #f87171 30%, #ef4444 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .error-code::after {
        background: linear-gradient(90deg, transparent, #ef4444, transparent);
    }
</style>
@endpush

@section('content')
<div class="error-code-wrap">
    <div class="error-code">403</div>
</div>

<div class="error-illustration">
    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Cadenas animé -->
        <g transform="translate(100, 100)">
            <!-- Corps du cadenas -->
            <rect x="-35" y="-20" width="70" height="55" rx="6" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.15)" stroke-width="2">
                <animate attributeName="opacity" values=".8;1;.8" dur="3s" repeatCount="indefinite"/>
            </rect>
            
            <!-- Arceau du cadenas -->
            <g>
                <path d="M-18 -20 C-18 -45, 18 -45, 18 -20" stroke="#ef4444" stroke-width="4" fill="none" opacity=".9">
                    <animate attributeName="stroke-dasharray" values="80 20;80 0;80 20" dur="2s" repeatCount="indefinite"/>
                </path>
                <circle cx="-18" cy="-20" r="3" fill="#ef4444"/>
                <circle cx="18" cy="-20" r="3" fill="#ef4444"/>
            </g>
            
            <!-- Curseur animé -->
            <rect x="-8" y="20" width="16" height="12" rx="2" fill="#ef4444" opacity=".6">
                <animate attributeName="y" values="20;18;20" dur="1s" repeatCount="indefinite"/>
            </rect>
            
            <!-- Points de protection -->
            <circle cx="-20" cy="5" r="3" fill="rgba(239,68,68,.3)">
                <animate attributeName="r" values="2;3;2" dur="1.5s" repeatCount="indefinite"/>
                <animate attributeName="opacity" values=".3;.6;.3" dur="1.5s" repeatCount="indefinite"/>
            </circle>
            <circle cx="0" cy="5" r="3" fill="rgba(239,68,68,.3)">
                <animate attributeName="r" values="2;3;2" dur="1.5s" begin=".5s" repeatCount="indefinite"/>
                <animate attributeName="opacity" values=".3;.6;.3" dur="1.5s" begin=".5s" repeatCount="indefinite"/>
            </circle>
            <circle cx="20" cy="5" r="3" fill="rgba(239,68,68,.3)">
                <animate attributeName="r" values="2;3;2" dur="1.5s" begin="1s" repeatCount="indefinite"/>
                <animate attributeName="opacity" values=".3;.6;.3" dur="1.5s" begin="1s" repeatCount="indefinite"/>
            </circle>
        </g>
    </svg>
</div>

<h1 class="error-title">Accès interdit</h1>
<p class="error-message">
    Vous n'avez pas les autorisations nécessaires pour accéder à cette page.
    
    @if(auth()->check())
        <br><small style="color:rgba(255,255,255,.4);font-size:13px;">
            Connecté en tant que : <strong style="color:rgba(255,255,255,.8);">{{ auth()->user()->nom_complet }}</strong>
            ({{ auth()->user()->role_label }})
        </small>
    @else
        <br><small style="color:rgba(255,255,255,.4);font-size:13px;">
            Vous n'êtes pas connecté.
        </small>
    @endif
</p>

<div class="error-actions">
    <a href="{{ url('/') }}" class="btn-primary">
        <i class="fas fa-home"></i> Accueil
    </a>
    @if(auth()->check())
        <a href="javascript:history.back()" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-secondary">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </a>
    @endif
</div>
@endsection