{{-- resources/views/errors/500.blade.php --}}
@extends('errors.layout')

@section('title', 'Erreur serveur')

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
    <div class="error-code">500</div>
</div>

<div class="error-illustration">
    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g transform="translate(100, 100)">
            <!-- Engrenage principal -->
            <g>
                <animateTransform attributeName="transform" type="rotate" 
                    values="0 0 0; 360 0 0" dur="10s" repeatCount="indefinite"/>
                <circle cx="0" cy="0" r="45" stroke="rgba(255,255,255,.1)" stroke-width="3" fill="none"/>
                <circle cx="0" cy="0" r="25" stroke="rgba(255,255,255,.08)" stroke-width="2" fill="none"/>
                
                <!-- Dents de l'engrenage -->
                <g>
                    <rect x="-6" y="-52" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="-6" y="40" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="-52" y="-6" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="40" y="-6" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="28" y="-40" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)" transform="rotate(45 34 -34)"/>
                    <rect x="-40" y="-40" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)" transform="rotate(-45 -34 -34)"/>
                    <rect x="28" y="28" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)" transform="rotate(-45 34 34)"/>
                    <rect x="-40" y="28" width="12" height="12" rx="2" fill="rgba(255,255,255,.12)" transform="rotate(45 -34 34)"/>
                </g>
                
                <!-- Point central -->
                <circle cx="0" cy="0" r="8" fill="#f59e0b" opacity=".4">
                    <animate attributeName="opacity" values=".4;.8;.4" dur="2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="0" cy="0" r="4" fill="#f59e0b" opacity=".6">
                    <animate attributeName="r" values="4;6;4" dur="1.5s" repeatCount="indefinite"/>
                </circle>
            </g>
            
            <!-- Rayons lumineux -->
            <g opacity=".1">
                <line x1="0" y1="-70" x2="0" y2="-85" stroke="#f59e0b" stroke-width="2">
                    <animate attributeName="opacity" values=".1;.3;.1" dur="1.5s" repeatCount="indefinite"/>
                </line>
                <line x1="70" y1="0" x2="85" y2="0" stroke="#f59e0b" stroke-width="2">
                    <animate attributeName="opacity" values=".1;.3;.1" dur="1.5s" begin=".3s" repeatCount="indefinite"/>
                </line>
                <line x1="0" y1="70" x2="0" y2="85" stroke="#f59e0b" stroke-width="2">
                    <animate attributeName="opacity" values=".1;.3;.1" dur="1.5s" begin=".6s" repeatCount="indefinite"/>
                </line>
                <line x1="-70" y1="0" x2="-85" y2="0" stroke="#f59e0b" stroke-width="2">
                    <animate attributeName="opacity" values=".1;.3;.1" dur="1.5s" begin=".9s" repeatCount="indefinite"/>
                </line>
            </g>
        </g>
    </svg>
</div>

<h1 class="error-title">Erreur serveur</h1>
<p class="error-message">
    Une erreur inattendue s'est produite. Nos équipes ont été notifiées.
    <br><small style="color:rgba(255,255,255,.35);font-size:13px;">
        Veuillez réessayer dans quelques instants.
    </small>
</p>

<div class="error-actions">
    <a href="{{ url('/') }}" class="btn-primary">
        <i class="fas fa-home"></i> Accueil
    </a>
    <a href="javascript:location.reload()" class="btn-secondary">
        <i class="fas fa-sync"></i> Réessayer
    </a>
</div>
@endsection