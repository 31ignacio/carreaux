{{-- resources/views/errors/419.blade.php --}}
@extends('errors.layout')

@section('title', 'Session expirée')

@push('error_styles')
<style>
    .error-code { 
        background: linear-gradient(135deg, #fbbf24 30%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .error-code::after {
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
    }
</style>
@endpush

@section('content')
<div class="error-code-wrap">
    <div class="error-code">419</div>
</div>

<div class="error-illustration">
    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g transform="translate(100, 100)">
            <!-- Horloge animée -->
            <circle cx="0" cy="0" r="55" stroke="rgba(255,255,255,.1)" stroke-width="3" fill="none"/>
            <circle cx="0" cy="0" r="45" stroke="rgba(255,255,255,.05)" stroke-width="1" fill="none"/>
            
            <!-- Aiguilles -->
            <line x1="0" y1="0" x2="0" y2="-35" stroke="#f59e0b" stroke-width="3" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" 
                    values="0 0 0; 360 0 0" dur="60s" repeatCount="indefinite"/>
            </line>
            <line x1="0" y1="0" x2="20" y2="-15" stroke="rgba(255,255,255,.5)" stroke-width="2" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" 
                    values="0 0 0; 360 0 0" dur="5s" repeatCount="indefinite"/>
            </line>
            
            <!-- Centre -->
            <circle cx="0" cy="0" r="5" fill="#f59e0b" opacity=".6">
                <animate attributeName="r" values="5;7;5" dur="1.5s" repeatCount="indefinite"/>
            </circle>
            
            <!-- Repères horaires -->
            <g stroke="rgba(255,255,255,.2)" stroke-width="2">
                <line x1="0" y1="-48" x2="0" y2="-42"/>
                <line x1="0" y1="48" x2="0" y2="42"/>
                <line x1="-48" y1="0" x2="-42" y2="0"/>
                <line x1="48" y1="0" x2="42" y2="0"/>
            </g>
            
            <!-- Points de temps -->
            <circle cx="0" cy="-48" r="2" fill="#f59e0b" opacity=".4"/>
            <circle cx="0" cy="48" r="2" fill="rgba(255,255,255,.2)"/>
            <circle cx="-48" cy="0" r="2" fill="rgba(255,255,255,.2)"/>
            <circle cx="48" cy="0" r="2" fill="rgba(255,255,255,.2)"/>
        </g>
    </svg>
</div>

<h1 class="error-title">Session expirée</h1>
<p class="error-message">
    Votre session a expiré pour des raisons de sécurité.
    <br><small style="color:rgba(255,255,255,.35);font-size:13px;">
        Veuillez rafraîchir la page et réessayer.
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