{{-- resources/views/fournisseurs/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier Fournisseur')
@section('page-title', 'Modifier Fournisseur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap');
.ff-wrap { font-family:'DM Sans',sans-serif; }
.ff-hero {
    background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 55%,#78350f 100%);
    border-radius:18px; padding:22px 28px; margin-bottom:22px;
    display:flex; align-items:center; justify-content:space-between;
    position:relative; overflow:hidden; box-shadow:0 8px 32px rgba(15,23,42,.2);
}
.ff-hero::before { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.05); pointer-events:none; }
.ff-hero h1 { font-family:'Syne',sans-serif; font-size:1.2rem; color:#fff; margin:0 0 3px; }
.ff-hero p  { font-size:11px; color:rgba(255,255,255,.45); margin:0; }
.ff-back { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.8); border-radius:10px; padding:8px 16px; font-size:12px; font-weight:700; text-decoration:none; transition:.2s; position:relative; z-index:1; }
.ff-back:hover { background:rgba(255,255,255,.18); color:#fff; text-decoration:none; }
.ff-grid { display:grid; grid-template-columns:1fr 280px; gap:18px; align-items:start; }
@media(max-width:1024px){ .ff-grid { grid-template-columns:1fr; } }
.ff-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; overflow:hidden; box-shadow:0 2px 12px rgba(11,15,26,.04); margin-bottom:14px; }
.ff-card-hd { padding:12px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:10px; background:#fafbff; }
.ff-card-ico { width:26px; height:26px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; flex-shrink:0; }
.ff-card-hd h6 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin:0; }
.ff-card-bd { padding:18px 20px; }
.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:640px){ .row-2 { grid-template-columns:1fr; } }
.ff-label { display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; margin-bottom:6px; }
.ff-label .req { color:#ef4444; }
.ff-label .opt { font-size:9px; color:#9ca3af; font-weight:600; text-transform:none; letter-spacing:0; }
.ff-field { display:flex; align-items:center; background:#f9fafb; border:1.5px solid #e5e7eb; border-radius:10px; overflow:hidden; transition:.2s; }
.ff-field:focus-within { border-color:#d97706; background:#fff; box-shadow:0 0 0 3px rgba(217,119,6,.08); }
.ff-field.err { border-color:#ef4444; }
.ff-ico { width:34px; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:11px; }
.ff-field:focus-within .ff-ico { color:#d97706; }
.ff-inp { flex:1; border:none; background:transparent; padding:9px 10px 9px 0; font-size:12.5px; color:#111827; outline:none; }
.ff-inp::placeholder { color:#d1d5db; }
.ff-err { font-size:10px; color:#ef4444; margin-top:4px; font-weight:600; }
.toggle-wrap { display:flex; align-items:center; gap:12px; padding:10px 0; }
.toggle-lbl { font-size:12px; font-weight:600; color:#374151; }
.toggle-sub { font-size:10px; color:#9ca3af; }
.toggle-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
.toggle-switch input { opacity:0; width:0; height:0; }
.toggle-slider { position:absolute; cursor:pointer; inset:0; background:#e5e7eb; border-radius:24px; transition:.25s; }
.toggle-slider:before { content:''; position:absolute; width:18px; height:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.25s; box-shadow:0 1px 4px rgba(0,0,0,.15); }
.toggle-switch input:checked + .toggle-slider { background:#d97706; }
.toggle-switch input:checked + .toggle-slider:before { transform:translateX(20px); }
.preview-avatar { width:64px; height:64px; border-radius:18px; margin:0 auto 14px; display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff; transition:.3s; }
.preview-name { font-family:'Syne',sans-serif; font-size:1rem; font-weight:800; text-align:center; color:#0f172a; margin-bottom:4px; }
.preview-ville { font-size:11px; color:#94a3b8; text-align:center; margin-bottom:12px; }
.preview-info-row { display:flex; align-items:center; gap:8px; padding:8px 0; border-bottom:1px solid #f8fafc; font-size:12px; color:#374151; }
.preview-info-row:last-child { border-bottom:none; }
.preview-info-ico { width:24px; height:24px; border-radius:7px; background:#f0f9ff; display:flex; align-items:center; justify-content:center; font-size:10px; color:#d97706; flex-shrink:0; }
.ff-footer { padding:16px 20px; border-top:1px solid #f1f5f9; background:#fafbff; display:flex; align-items:center; justify-content:flex-end; gap:10px; }
.ff-btn { display:inline-flex; align-items:center; gap:7px; padding:10px 20px; border-radius:11px; border:none; font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
.ff-btn:hover { transform:translateY(-2px); }
.fb-amber { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; box-shadow:0 4px 14px rgba(217,119,6,.25); }
.fb-ghost { background:#f3f4f6; color:#374151; text-decoration:none; }
.fb-ghost:hover { background:#e5e7eb; color:#111827; text-decoration:none; }
.ff-alert { background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:11px 15px; display:flex; align-items:flex-start; gap:9px; margin-bottom:16px; font-size:11px; color:#dc2626; }
/* Lien rapide */
.quick-link { display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; background:#f8fafc; border:1px solid #f1f5f9; text-decoration:none; color:#374151; font-size:12px; font-weight:600; margin-bottom:8px; transition:.15s; }
.quick-link:hover { background:#f1f5f9; text-decoration:none; color:#0f172a; }
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.ff-hero { animation:fadeUp .35s ease both; }
.ff-grid { animation:fadeUp .35s .08s ease both; }
</style>
@endpush

@section('content')
<div class="ff-wrap">

    <div class="ff-hero">
        <div style="position:relative;z-index:1">
            <h1><i class="fas fa-edit mr-2" style="opacity:.6"></i>{{ $fournisseur->nom }}</h1>
            <p>Modifier les informations du fournisseur</p>
        </div>
        <a href="{{ route('fournisseurs.index') }}" class="ff-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST" id="ffForm">
        @csrf @method('PUT')
        <div class="ff-grid">

            <!-- FORMULAIRE -->
            <div>
                @if($errors->any())
                    <div class="ff-alert">
                        <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:1px"></i>
                        <div>
                            <strong>Corrigez les erreurs :</strong>
                            <ul style="margin:4px 0 0;padding-left:14px">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Identité -->
                <div class="ff-card">
                    <div class="ff-card-hd">
                        <span class="ff-card-ico" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-building"></i></span>
                        <h6>Identité</h6>
                    </div>
                    <div class="ff-card-bd">
                        <div style="margin-bottom:12px">
                            <label class="ff-label">Nom du fournisseur <span class="req">*</span></label>
                            <div class="ff-field @error('nom') err @enderror">
                                <div class="ff-ico"><i class="fas fa-building"></i></div>
                                <input type="text" name="nom" id="prevNom" class="ff-inp"
                                       value="{{ old('nom', $fournisseur->nom) }}" required>
                            </div>
                            @error('nom')<p class="ff-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="row-2">
                            <div>
                                <label class="ff-label">Ville <span class="opt">(opt.)</span></label>
                                <div class="ff-field">
                                    <div class="ff-ico"><i class="fas fa-map-marker-alt"></i></div>
                                    <input type="text" name="ville" id="prevVille" class="ff-inp"
                                           value="{{ old('ville', $fournisseur->ville) }}">
                                </div>
                            </div>
                            <div>
                                <label class="ff-label">Adresse <span class="opt">(opt.)</span></label>
                                <div class="ff-field">
                                    <div class="ff-ico"><i class="fas fa-map"></i></div>
                                    <input type="text" name="adresse" class="ff-inp"
                                           value="{{ old('adresse', $fournisseur->adresse) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="ff-card">
                    <div class="ff-card-hd">
                        <span class="ff-card-ico" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="fas fa-address-card"></i></span>
                        <h6>Contact</h6>
                    </div>
                    <div class="ff-card-bd">
                        <div style="margin-bottom:12px">
                            <label class="ff-label">Personne de contact <span class="opt">(opt.)</span></label>
                            <div class="ff-field">
                                <div class="ff-ico"><i class="fas fa-user"></i></div>
                                <input type="text" name="contact_personne" id="prevContact" class="ff-inp"
                                       value="{{ old('contact_personne', $fournisseur->contact_personne) }}">
                            </div>
                        </div>
                        <div class="row-2">
                            <div>
                                <label class="ff-label">Téléphone <span class="opt">(opt.)</span></label>
                                <div class="ff-field @error('telephone') err @enderror">
                                    <div class="ff-ico"><i class="fas fa-phone"></i></div>
                                    <input type="text" name="telephone" id="prevTel" class="ff-inp"
                                           value="{{ old('telephone', $fournisseur->telephone) }}">
                                </div>
                                @error('telephone')<p class="ff-err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ff-label">Email <span class="opt">(opt.)</span></label>
                                <div class="ff-field @error('email') err @enderror">
                                    <div class="ff-ico"><i class="fas fa-envelope"></i></div>
                                    <input type="email" name="email" id="prevEmail" class="ff-inp"
                                           value="{{ old('email', $fournisseur->email) }}">
                                </div>
                                @error('email')<p class="ff-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="ff-card">
                    <div class="ff-card-hd">
                        <span class="ff-card-ico" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-toggle-on"></i></span>
                        <h6>Statut</h6>
                    </div>
                    <div class="ff-card-bd">
                        <div class="toggle-wrap">
                            <label class="toggle-switch">
                                <input type="hidden" name="actif" value="0">
                                <input type="checkbox" name="actif" value="1" id="actifToggle"
                                       {{ old('actif', $fournisseur->actif) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <div>
                                <div class="toggle-lbl" id="actifLbl">
                                    {{ $fournisseur->actif ? 'Fournisseur actif' : 'Fournisseur inactif' }}
                                </div>
                                <div class="toggle-sub">Les fournisseurs inactifs n'apparaissent pas dans les sélections</div>
                            </div>
                        </div>
                    </div>
                    <div class="ff-footer">
                        <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="ff-btn fb-ghost">
                            <i class="fas fa-eye"></i> Voir la fiche
                        </a>
                        <button type="submit" class="ff-btn fb-amber">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div>
                <div class="ff-card">
                    <div class="ff-card-hd">
                        <span class="ff-card-ico" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-eye"></i></span>
                        <h6>Aperçu</h6>
                    </div>
                    <div class="ff-card-bd" style="text-align:center; padding:24px 20px">
                        @php $colors = ['#0ea5e9','#059669','#d97706','#7c3aed','#dc2626','#0891b2','#16a34a']; $col = $colors[$fournisseur->id % count($colors)]; @endphp
                        <div class="preview-avatar" id="prevAvatar"
                             style="background:linear-gradient(135deg,{{ $col }},{{ $col }}cc)">
                            {{ strtoupper(substr($fournisseur->nom,0,2)) }}
                        </div>
                        <div class="preview-name" id="prevNameDisp">{{ $fournisseur->nom }}</div>
                        <div class="preview-ville" id="prevVilleDisp">{{ $fournisseur->ville ?? '—' }}</div>
                        <div style="text-align:left; margin-top:8px">
                            <div class="preview-info-row">
                                <div class="preview-info-ico"><i class="fas fa-user"></i></div>
                                <span id="prevContactDisp">{{ $fournisseur->contact_personne ?? '—' }}</span>
                            </div>
                            <div class="preview-info-row">
                                <div class="preview-info-ico"><i class="fas fa-phone"></i></div>
                                <span id="prevTelDisp">{{ $fournisseur->telephone ?? '—' }}</span>
                            </div>
                            <div class="preview-info-row">
                                <div class="preview-info-ico"><i class="fas fa-envelope"></i></div>
                                <span id="prevEmailDisp" style="font-size:11px; word-break:break-all">{{ $fournisseur->email ?? '—' }}</span>
                            </div>
                            <div class="preview-info-row">
                                <div class="preview-info-ico"><i class="fas fa-circle" id="prevStatutIco" style="font-size:7px; color:{{ $fournisseur->actif ? '#059669' : '#94a3b8' }}"></i></div>
                                <span id="prevStatutDisp" style="color:{{ $fournisseur->actif ? '#059669' : '#94a3b8' }}; font-weight:700; font-size:11px">
                                    {{ $fournisseur->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liens rapides -->
                <div class="ff-card">
                    <div class="ff-card-hd">
                        <span class="ff-card-ico" style="background:linear-gradient(135deg,#64748b,#475569)"><i class="fas fa-link"></i></span>
                        <h6>Actions rapides</h6>
                    </div>
                    <div class="ff-card-bd" style="padding:12px 16px">
                        <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="quick-link">
                            <i class="fas fa-eye" style="color:#0ea5e9"></i> Voir la fiche complète
                        </a>
                        <a href="{{ route('stock.entrees.create') }}" class="quick-link">
                            <i class="fas fa-plus-circle" style="color:#059669"></i> Nouvelle entrée stock
                        </a>
                        <a href="{{ route('fournisseurs.index') }}" class="quick-link">
                            <i class="fas fa-list" style="color:#7c3aed"></i> Liste des fournisseurs
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const colors = ['#0ea5e9','#059669','#d97706','#7c3aed','#dc2626','#0891b2','#16a34a'];
    const nom     = document.getElementById('prevNom');
    const ville   = document.getElementById('prevVille');
    const contact = document.getElementById('prevContact');
    const tel     = document.getElementById('prevTel');
    const email   = document.getElementById('prevEmail');
    const toggle  = document.getElementById('actifToggle');

    function update() {
        const n    = nom?.value.trim() || '';
        const init = n.length >= 2 ? n.substring(0,2).toUpperCase() : (n[0]?.toUpperCase() || '?');
        const color= colors[n.charCodeAt(0) % colors.length] || colors[0];
        document.getElementById('prevAvatar').textContent       = init;
        document.getElementById('prevAvatar').style.background  = 'linear-gradient(135deg,'+color+','+color+'cc)';
        document.getElementById('prevNameDisp').textContent     = n || '—';
        document.getElementById('prevVilleDisp').textContent    = ville?.value || '—';
        document.getElementById('prevContactDisp').textContent  = contact?.value || '—';
        document.getElementById('prevTelDisp').textContent      = tel?.value || '—';
        document.getElementById('prevEmailDisp').textContent    = email?.value || '—';
        const actif = toggle?.checked;
        document.getElementById('prevStatutDisp').textContent   = actif ? 'Actif' : 'Inactif';
        document.getElementById('prevStatutDisp').style.color   = actif ? '#059669' : '#94a3b8';
        document.getElementById('prevStatutIco').style.color    = actif ? '#059669' : '#94a3b8';
        document.getElementById('actifLbl').textContent = actif ? 'Fournisseur actif' : 'Fournisseur inactif';
    }

    [nom, ville, contact, tel, email].forEach(el => el?.addEventListener('input', update));
    toggle?.addEventListener('change', update);
})();
</script>
@endpush