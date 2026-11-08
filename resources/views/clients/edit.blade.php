{{-- resources/views/clients/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Modifier Client')
@section('page-title', 'Modifier Client')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item"><a href="{{ route('clients.show', $client) }}">{{ $client->nom_complet }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap');
.cf-wrap { font-family:'DM Sans',sans-serif; }
.cf-hero { background:linear-gradient(135deg,#0f172a 0%,#92400e 60%,#78350f 100%); border-radius:18px; padding:22px 28px; margin-bottom:22px; display:flex; align-items:center; justify-content:space-between; position:relative; overflow:hidden; box-shadow:0 8px 32px rgba(15,23,42,.2); }
.cf-hero::before { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.05); pointer-events:none; }
.cf-hero h1 { font-family:,sans-serif; font-size:1.2rem; color:#fff; margin:0 0 3px; }
.cf-hero p  { font-size:11px; color:rgba(255,255,255,.45); margin:0; }
.cf-back { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.8); border-radius:10px; padding:8px 16px; font-size:12px; font-weight:700; text-decoration:none; transition:.2s; position:relative; z-index:1; }
.cf-back:hover { background:rgba(255,255,255,.18); color:#fff; text-decoration:none; }
.cf-grid { display:grid; grid-template-columns:1fr 280px; gap:18px; align-items:start; }
@media(max-width:1024px){ .cf-grid { grid-template-columns:1fr; } }
.cf-card { background:#fff; border:1px solid #e8edf5; border-radius:16px; overflow:hidden; box-shadow:0 2px 12px rgba(11,15,26,.04); margin-bottom:14px; }
.cf-card-hd { padding:12px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:10px; background:#fafbff; }
.cf-card-ico { width:26px; height:26px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; flex-shrink:0; }
.cf-card-hd h6 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#374151; margin:0; }
.cf-card-bd { padding:18px 20px; }
.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:640px){ .row-2 { grid-template-columns:1fr; } }
.cf-label { display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; margin-bottom:6px; }
.cf-label .req { color:#ef4444; }
.cf-label .opt { font-size:9px; color:#9ca3af; font-weight:600; text-transform:none; letter-spacing:0; }
.cf-field { display:flex; align-items:center; background:#f9fafb; border:1.5px solid #e5e7eb; border-radius:10px; overflow:hidden; transition:.2s; }
.cf-field:focus-within { border-color:#d97706; background:#fff; box-shadow:0 0 0 3px rgba(217,119,6,.08); }
.cf-field.err { border-color:#ef4444; }
.cf-ico { width:34px; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:11px; }
.cf-field:focus-within .cf-ico { color:#d97706; }
.cf-inp { flex:1; border:none; background:transparent; padding:9px 10px 9px 0; font-size:12.5px; color:#111827; outline:none; }
.cf-inp::placeholder { color:#d1d5db; }
.cf-err { font-size:10px; color:#ef4444; margin-top:4px; font-weight:600; }
.ifu-hint { font-size:10px; color:#6b7280; margin-top:4px; display:flex; align-items:center; gap:4px; font-weight:500; }
.ifu-hint i { color:#d97706; font-size:9px; }
.cf-field.ifu-ok  { border-color:#059669 !important; box-shadow:0 0 0 3px rgba(5,150,105,.08) !important; }
.cf-field.ifu-ok .cf-ico { color:#059669 !important; }
.cf-field.ifu-err-fmt { border-color:#ef4444 !important; box-shadow:0 0 0 3px rgba(239,68,68,.08) !important; }
.cf-field.ifu-err-fmt .cf-ico { color:#ef4444 !important; }
.ifu-status { font-size:10px; margin-top:4px; font-weight:700; display:flex; align-items:center; gap:4px; }
.ifu-status.ok  { color:#059669; }
.ifu-status.err { color:#ef4444; }
.type-cards { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.type-card { position:relative; cursor:pointer; }
.type-card input { position:absolute; opacity:0; }
.type-card-inner { padding:14px; border-radius:12px; border:2px solid #e5e7eb; background:#f9fafb; text-align:center; transition:.2s; cursor:pointer; }
.type-card-inner:hover { border-color:#fde68a; }
.type-card input:checked + .type-card-inner { border-color:#d97706; background:#fffbeb; box-shadow:0 0 0 3px rgba(217,119,6,.1); }
.type-card-ico { font-size:20px; margin-bottom:6px; display:block; }
.type-card-lbl { font-size:12px; font-weight:800; color:#374151; }
.type-card-sub { font-size:10px; color:#9ca3af; margin-top:2px; }
.toggle-wrap { display:flex; align-items:center; gap:12px; padding:10px 0; }
.toggle-lbl { font-size:12px; font-weight:600; color:#374151; }
.toggle-sub { font-size:10px; color:#9ca3af; }
.toggle-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
.toggle-switch input { opacity:0; width:0; height:0; }
.toggle-slider { position:absolute; cursor:pointer; inset:0; background:#e5e7eb; border-radius:24px; transition:.25s; }
.toggle-slider:before { content:''; position:absolute; width:18px; height:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.25s; box-shadow:0 1px 4px rgba(0,0,0,.15); }
.toggle-switch input:checked + .toggle-slider { background:#d97706; }
.toggle-switch input:checked + .toggle-slider:before { transform:translateX(20px); }
.preview-avatar { width:64px; height:64px; border-radius:18px; margin:0 auto 14px; display:flex; align-items:center; justify-content:center; font-family:,sans-serif; font-size:22px; font-weight:800; color:#fff; transition:.3s; }
.preview-name { font-family:,sans-serif; font-size:1rem; font-weight:800; text-align:center; color:#0f172a; margin-bottom:2px; }
.preview-row { display:flex; align-items:center; gap:8px; padding:8px 0; border-bottom:1px solid #f8fafc; font-size:12px; color:#374151; }
.preview-row:last-child { border-bottom:none; }
.preview-ico { width:24px; height:24px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:10px; flex-shrink:0; }
.cf-footer { padding:16px 20px; border-top:1px solid #f1f5f9; background:#fafbff; display:flex; align-items:center; justify-content:flex-end; gap:10px; }
.cf-btn { display:inline-flex; align-items:center; gap:7px; padding:10px 20px; border-radius:11px; border:none; font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
.cf-btn:hover { transform:translateY(-2px); }
.cb-amber  { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; box-shadow:0 4px 14px rgba(217,119,6,.3); }
.cb-ghost  { background:#f3f4f6; color:#374151; text-decoration:none; }
.cb-ghost:hover { background:#e5e7eb; color:#111827; text-decoration:none; }
.cf-alert { background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:11px 15px; display:flex; align-items:flex-start; gap:9px; margin-bottom:16px; font-size:11px; color:#dc2626; }
.quick-link { display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; background:#f8fafc; border:1px solid #f1f5f9; text-decoration:none; color:#374151; font-size:12px; font-weight:600; margin-bottom:8px; transition:.15s; }
.quick-link:hover { background:#f1f5f9; text-decoration:none; color:#0f172a; }
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.cf-hero { animation:fadeUp .35s ease both; }
.cf-grid { animation:fadeUp .35s .08s ease both; }
</style>
@endpush

@section('content')
@php
    $colors = ['#6366f1','#059669','#d97706','#0ea5e9','#dc2626','#7c3aed','#16a34a'];
    $color = $colors[$client->id % count($colors)];
    $formattedIfu = $client->ifu
        ? substr($client->ifu, 0, 3).' '.substr($client->ifu, 3, 3).' '.substr($client->ifu, 6, 3).' '.substr($client->ifu, 9, 4)
        : '—';
@endphp
<div class="cf-wrap">
    <div class="cf-hero">
        <div style="position:relative;z-index:1">
            <h1><i class="fas fa-edit mr-2" style="opacity:.6"></i>{{ $client->nom_complet }}</h1>
            <p>Modifier les informations du client</p>
        </div>
        <a href="{{ route('clients.show', $client) }}" class="cf-back"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="cf-grid">
            <div>
                @if($errors->any())
                    <div class="cf-alert">
                        <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:1px"></i>
                        <div><strong>Corrigez les erreurs :</strong>
                            <ul style="margin:4px 0 0;padding-left:14px">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-tag"></i></span>
                        <h6>Type de client</h6>
                    </div>
                    <div class="cf-card-bd">
                        <div class="type-cards">
                            <label class="type-card">
                                <input type="radio" name="type" value="particulier" {{ old('type', $client->type) === 'particulier' ? 'checked' : '' }}>
                                <div class="type-card-inner"><span class="type-card-ico">👤</span><div class="type-card-lbl">Particulier</div><div class="type-card-sub">Client individuel</div></div>
                            </label>
                            <label class="type-card">
                                <input type="radio" name="type" value="entreprise" {{ old('type', $client->type) === 'entreprise' ? 'checked' : '' }}>
                                <div class="type-card-inner"><span class="type-card-ico">🏢</span><div class="type-card-lbl">Entreprise</div><div class="type-card-sub">Société, commerce...</div></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-user"></i></span>
                        <h6>Identité</h6>
                    </div>
                    <div class="cf-card-bd">
                        <div class="row-2" style="margin-bottom:12px">
                            <div>
                                <label class="cf-label">Nom <span class="req">*</span></label>
                                <div class="cf-field @error('nom') err @enderror">
                                    <div class="cf-ico"><i class="fas fa-user"></i></div>
                                    <input type="text" name="nom" id="prevNom" class="cf-inp" value="{{ old('nom', $client->nom) }}" required>
                                </div>
                                @error('nom')<p class="cf-err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="cf-label">Prénom <span class="opt">(opt.)</span></label>
                                <div class="cf-field">
                                    <div class="cf-ico"><i class="fas fa-user"></i></div>
                                    <input type="text" name="prenom" id="prevPrenom" class="cf-inp" value="{{ old('prenom', $client->prenom) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row-2">
                            <div>
                                <label class="cf-label">N° IFU <span class="opt">(opt.) — Identifiant Fiscal Unique</span></label>
                                <div class="cf-field @error('ifu') err @enderror" id="ifuField">
                                    <div class="cf-ico"><i class="fas fa-id-card"></i></div>
                                    <input type="text" name="ifu" id="prevIfu" class="cf-inp" value="{{ old('ifu', $client->ifu) }}" placeholder="Ex : 3202112909623" maxlength="20" oninput="validateIfu(this)">
                                </div>
                                <div class="ifu-status" id="ifuStatus" style="display:none"></div>
                                <p class="ifu-hint"><i class="fas fa-info-circle"></i> 13 chiffres pour le Bénin (IFU DGI)</p>
                                @error('ifu')<p class="cf-err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="cf-label">Adresse <span class="opt">(opt.)</span></label>
                                <div class="cf-field">
                                    <div class="cf-ico"><i class="fas fa-map"></i></div>
                                    <input type="text" name="adresse" class="cf-inp" value="{{ old('adresse', $client->adresse) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="fas fa-phone"></i></span>
                        <h6>Contact</h6>
                    </div>
                    <div class="cf-card-bd">
                        <div class="row-2">
                            <div>
                                <label class="cf-label">Téléphone <span class="opt">(opt.)</span></label>
                                <div class="cf-field @error('telephone') err @enderror">
                                    <div class="cf-ico"><i class="fas fa-phone"></i></div>
                                    <input type="text" name="telephone" id="prevTel" class="cf-inp" value="{{ old('telephone', $client->telephone) }}">
                                </div>
                                @error('telephone')<p class="cf-err">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="cf-label">Email <span class="opt">(opt.)</span></label>
                                <div class="cf-field @error('email') err @enderror">
                                    <div class="cf-ico"><i class="fas fa-envelope"></i></div>
                                    <input type="email" name="email" id="prevEmail" class="cf-inp" value="{{ old('email', $client->email) }}">
                                </div>
                                @error('email')<p class="cf-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#64748b,#475569)"><i class="fas fa-cog"></i></span>
                        <h6>Statut</h6>
                    </div>
                    <div class="cf-card-bd">
                        <div class="toggle-wrap">
                            <label class="toggle-switch">
                                <input type="hidden" name="actif" value="0">
                                <input type="checkbox" name="actif" value="1" id="actifToggle" {{ old('actif', $client->actif) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <div>
                                <div class="toggle-lbl" id="actifLbl">{{ $client->actif ? 'Client actif' : 'Client inactif' }}</div>
                                <div class="toggle-sub">Les clients inactifs n'apparaissent pas dans la caisse</div>
                            </div>
                        </div>
                    </div>
                    <div class="cf-footer">
                        <a href="{{ route('clients.show', $client) }}" class="cf-btn cb-ghost"><i class="fas fa-eye"></i> Voir la fiche</a>
                        <button type="submit" class="cf-btn cb-amber"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-eye"></i></span>
                        <h6>Aperçu</h6>
                    </div>
                    <div class="cf-card-bd" style="text-align:center; padding:24px 20px">
                        <div class="preview-avatar" id="prevAvatar" style="background:linear-gradient(135deg,{{ $color }},{{ $color }}cc)">{{ $client->initiales }}</div>
                        <div class="preview-name" id="prevNameDisp">{{ $client->nom_complet }}</div>
                        <div id="prevTypeDisp" style="margin-bottom:12px">
                            <span style="background:{{ $client->type === 'entreprise' ? '#eff6ff' : '#ede9fe' }};color:{{ $client->type === 'entreprise' ? '#1d4ed8' : '#7c3aed' }};padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700">
                                {{ $client->type === 'entreprise' ? '🏢 Entreprise' : '👤 Particulier' }}
                            </span>
                        </div>
                        <div style="text-align:left">
                            <div class="preview-row">
                                <div class="preview-ico" style="background:#ede9fe; color:#6366f1"><i class="fas fa-id-card"></i></div>
                                <span id="prevIfuDisp" style="color:{{ $client->ifu ? '#0f172a' : '#94a3b8' }}; font-size:11px; font-family:monospace; letter-spacing:.5px">{{ $formattedIfu }}</span>
                            </div>
                            <div class="preview-row">
                                <div class="preview-ico" style="background:#d1fae5; color:#059669"><i class="fas fa-phone"></i></div>
                                <span id="prevTelDisp">{{ $client->telephone ?? '—' }}</span>
                            </div>
                            <div class="preview-row">
                                <div class="preview-ico" style="background:#fef3c7; color:#d97706"><i class="fas fa-envelope"></i></div>
                                <span id="prevEmailDisp" style="font-size:11px; word-break:break-all">{{ $client->email ?? '—' }}</span>
                            </div>
                            <div class="preview-row">
                                <div class="preview-ico" style="background:#d1fae5; color:#059669"><i class="fas fa-circle" id="prevStatutIco" style="font-size:7px; color:{{ $client->actif ? '#059669' : '#94a3b8' }}"></i></div>
                                <span id="prevStatutDisp" style="color:{{ $client->actif ? '#059669' : '#94a3b8' }}; font-weight:700; font-size:11px">{{ $client->actif ? 'Actif' : 'Inactif' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cf-card">
                    <div class="cf-card-hd">
                        <span class="cf-card-ico" style="background:linear-gradient(135deg,#64748b,#475569)"><i class="fas fa-link"></i></span>
                        <h6>Actions rapides</h6>
                    </div>
                    <div class="cf-card-bd" style="padding:12px 16px">
                        <a href="{{ route('clients.show', $client) }}" class="quick-link"><i class="fas fa-eye" style="color:#6366f1"></i> Voir la fiche complète</a>
                        <a href="{{ route('clients.index') }}" class="quick-link"><i class="fas fa-list" style="color:#64748b"></i> Liste des clients</a>
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
    const colors = ['#6366f1','#059669','#d97706','#0ea5e9','#dc2626','#7c3aed','#16a34a'];
    const nom = document.getElementById('prevNom');
    const prenom = document.getElementById('prevPrenom');
    const ifuInp = document.getElementById('prevIfu');
    const tel = document.getElementById('prevTel');
    const email = document.getElementById('prevEmail');
    const toggle = document.getElementById('actifToggle');
    const radios = document.querySelectorAll('input[name="type"]');

    window.validateIfu = function(input) {
        const val = input.value.replace(/\D/g, '');
        input.value = val;
        const field = document.getElementById('ifuField');
        const status = document.getElementById('ifuStatus');

        if (val.length === 0) {
            field.classList.remove('ifu-ok', 'ifu-err-fmt');
            status.style.display = 'none';
        } else if (val.length === 13) {
            field.classList.add('ifu-ok');
            field.classList.remove('ifu-err-fmt');
            status.style.display = 'flex';
            status.className = 'ifu-status ok';
            status.innerHTML = '<i class="fas fa-check-circle"></i> Format IFU valide (13 chiffres)';
        } else {
            field.classList.add('ifu-err-fmt');
            field.classList.remove('ifu-ok');
            status.style.display = 'flex';
            status.className = 'ifu-status err';
            status.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + val.length + '/13 chiffres';
        }

        update();
    };

    function update() {
        const n = ((prenom?.value.trim() || '') + ' ' + (nom?.value.trim() || '')).trim();
        const init = n.split(' ').filter(Boolean).map(p => p[0].toUpperCase()).join('').substring(0, 2) || '?';
        const color = colors[(nom?.value.charCodeAt(0) || 0) % colors.length];
        const type = document.querySelector('input[name="type"]:checked')?.value || 'particulier';
        const isEnt = type === 'entreprise';

        document.getElementById('prevAvatar').textContent = init;
        document.getElementById('prevAvatar').style.background = 'linear-gradient(135deg,' + color + ',' + color + 'cc)';
        document.getElementById('prevNameDisp').textContent = n || '—';
        document.getElementById('prevTelDisp').textContent = tel?.value || '—';
        document.getElementById('prevEmailDisp').textContent = email?.value || '—';

        const ifuVal = ifuInp?.value || '';
        document.getElementById('prevIfuDisp').textContent = ifuVal.length === 13
            ? ifuVal.substring(0, 3) + ' ' + ifuVal.substring(3, 6) + ' ' + ifuVal.substring(6, 9) + ' ' + ifuVal.substring(9, 13)
            : (ifuVal || '—');
        document.getElementById('prevIfuDisp').style.color = ifuVal ? '#0f172a' : '#94a3b8';

        document.getElementById('prevTypeDisp').innerHTML = `<span style="background:${isEnt?'#eff6ff':'#ede9fe'};color:${isEnt?'#1d4ed8':'#7c3aed'};padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700">${isEnt?'🏢 Entreprise':'👤 Particulier'}</span>`;

        const actif = toggle?.checked;
        document.getElementById('prevStatutDisp').textContent = actif ? 'Actif' : 'Inactif';
        document.getElementById('prevStatutDisp').style.color = actif ? '#059669' : '#94a3b8';
        document.getElementById('prevStatutIco').style.color = actif ? '#059669' : '#94a3b8';
        document.getElementById('actifLbl').textContent = actif ? 'Client actif' : 'Client inactif';
    }

    [nom, prenom, tel, email].forEach(el => el?.addEventListener('input', update));
    toggle?.addEventListener('change', update);
    radios.forEach(r => r.addEventListener('change', update));

    if (ifuInp?.value) {
        validateIfu(ifuInp);
    } else {
        update();
    }
})();
</script>
@endpush
