{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', '')

@section('breadcrumb')
    <li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800;9..40,900&family=Syne:wght@700;800&display=swap');

.usr-wrap { font-family: 'DM Sans', sans-serif; }

/* ══ HERO ══ */
.usr-hero {
    background: linear-gradient(140deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
    border-radius: 20px; padding: 26px 32px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 12px 40px rgba(15,23,42,.22);
}
.usr-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:240px; height:240px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,102,241,.15),transparent 65%);
    pointer-events:none;
}
.usr-hero::after {
    content:''; position:absolute; bottom:-40px; left:30%;
    width:180px; height:180px; border-radius:50%;
    background:rgba(255,255,255,.03); pointer-events:none;
}
.usr-hero-left { position:relative; z-index:1; display:flex; align-items:center; gap:18px; }
.usr-hero-ico {
    width:52px; height:52px; border-radius:16px;
    background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#fff; flex-shrink:0;
}
.usr-hero-title {
    font-family:'Syne',sans-serif;
    font-size:clamp(1.05rem,2.5vw,1.35rem); font-weight:800;
    color:#fff; margin:0 0 3px; letter-spacing:-.2px;
}
.usr-hero-sub { font-size:12px; color:rgba(255,255,255,.5); margin:0; }
.usr-hero-btn {
    position:relative; z-index:1;
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg,#10b981,#059669);
    color:#fff; border:none; border-radius:12px;
    padding:11px 22px; font-size:13px; font-weight:800;
    cursor:pointer; transition:all .2s; text-decoration:none;
    box-shadow:0 4px 16px rgba(5,150,105,.35);
    font-family:'DM Sans',sans-serif;
}
.usr-hero-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(5,150,105,.45); color:#fff; text-decoration:none; }

/* ══ KPI ══ */
.usr-kpi-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
.usr-kpi {
    background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:18px 20px;
    display:flex; align-items:center; gap:14px;
    box-shadow:0 2px 12px rgba(11,15,26,.05);
    transition:transform .2s, box-shadow .2s;
}
.usr-kpi:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(11,15,26,.1); }
.usr-kpi-ico { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.ki-total { background:#eff6ff; color:#2563eb; }
.ki-actif { background:#ecfdf5; color:#059669; }
.ki-inact { background:#fef2f2; color:#dc2626; }
.ki-role  { background:#f5f3ff; color:#7c3aed; }
.usr-kpi-val { font-size:24px; font-weight:900; color:#0f172a; line-height:1; }
.usr-kpi-lbl { font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-top:3px; }

/* ══ CARD ══ */
.usr-card { background:#fff; border:1px solid #e8edf5; border-radius:20px; overflow:hidden; box-shadow:0 2px 16px rgba(11,15,26,.05); }
.usr-card-head {
    padding:16px 24px; border-bottom:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
    background:#fafbff;
}
.usr-card-title {
    display:flex; align-items:center; gap:10px;
    font-size:11px; font-weight:800; color:#0f172a;
    text-transform:uppercase; letter-spacing:.5px; margin:0;
}
.usr-card-ico {
    width:28px; height:28px; border-radius:8px;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    display:flex; align-items:center; justify-content:center;
    font-size:11px; color:#fff;
}
.usr-count-badge {
    background:#0f172a; color:#fff;
    padding:2px 10px; border-radius:20px;
    font-size:10px; font-weight:800;
}

/* ══ TABLE ══ */
.usr-table { width:100%; border-collapse:collapse; font-size:12px; }
.usr-table th {
    padding:11px 18px; font-size:9.5px; font-weight:800;
    text-transform:uppercase; letter-spacing:.6px; color:#94a3b8;
    background:#f8fafc; border-bottom:1px solid #f1f5f9; white-space:nowrap;
}
.usr-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid #f8fafc; }
.usr-table tbody tr:last-child td { border-bottom:none; }
.usr-table tbody tr { transition:background .1s; }
.usr-table tbody tr:hover td { background:#fafbff; }
.usr-table tbody tr.inactive td { opacity:.55; }

/* ══ USER CELL ══ */
.user-cell { display:flex; align-items:center; gap:12px; }
.user-avatar {
    width:40px; height:40px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:900; color:#fff; flex-shrink:0;
    letter-spacing:.5px;
}
.ua-admin { background:linear-gradient(135deg,#7c3aed,#a855f7); }
.ua-caiss { background:linear-gradient(135deg,#2563eb,#3b82f6); }
.ua-caiss-haut { background:linear-gradient(135deg,#d97706,#f59e0b); }
.ua-sup   { background:linear-gradient(135deg,#059669,#10b981); }
.ua-other { background:linear-gradient(135deg,#475569,#64748b); }
.user-name  { font-size:13px; font-weight:700; color:#0f172a; }
.user-email { font-size:10px; color:#94a3b8; margin-top:2px; }

.you-chip {
    display:inline-flex; align-items:center; gap:3px;
    background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;
    border-radius:20px; padding:1px 7px; font-size:9px; font-weight:800;
    margin-left:6px; vertical-align:middle;
}

/* ══ BADGES ══ */
.role-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:20px; font-size:10px; font-weight:800;
}
.rb-dot { width:5px; height:5px; border-radius:50%; background:currentColor; flex-shrink:0; }
.rb-admin { background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
.rb-caiss { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
.rb-caiss-haut { background:#fefce8; color:#d97706; border:1px solid #fde68a; }
.rb-sup   { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
.rb-other { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }

.stat-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:20px; font-size:10px; font-weight:800;
}
.sb-actif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
.sb-inact { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
.sb-dot   { width:5px; height:5px; border-radius:50%; background:currentColor; flex-shrink:0; }

/* ══ ACTIONS — capsule icônes ══ */
.act-capsule {
    display:inline-flex; align-items:center; gap:2px;
    background:#f8fafc; border:1px solid #f1f5f9;
    border-radius:12px; padding:4px;
}
.act-ico {
    width:30px; height:30px; border-radius:8px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:12px; cursor:pointer; transition:all .15s;
    text-decoration:none; background:transparent; color:#94a3b8;
    font-family:'DM Sans',sans-serif;
}
.act-ico:hover { transform:translateY(-1px); text-decoration:none; }
.act-ico.edit:hover    { background:#fffbeb; color:#d97706; }
.act-ico.toggle-on:hover  { background:#f1f5f9; color:#475569; }
.act-ico.toggle-off:hover { background:#ecfdf5; color:#059669; }
.act-ico.del:hover     { background:#fef2f2; color:#dc2626; }

.act-sep { width:1px; height:18px; background:#e2e8f0; margin:0 2px; flex-shrink:0; }

/* ══ DATES ══ */
.date-val { font-size:12px; font-weight:600; color:#374151; }
.date-rel { font-size:10px; color:#94a3b8; margin-top:1px; }

/* ══ EMPTY ══ */
.usr-empty { text-align:center; padding:60px 20px; }
.usr-empty-ring {
    width:80px; height:80px; border-radius:50%;
    background:linear-gradient(135deg,#eff6ff,#dbeafe);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; font-size:30px; color:#2563eb;
    box-shadow:0 8px 24px rgba(37,99,235,.15);
}
.usr-empty-title { font-size:16px; font-weight:800; color:#0f172a; margin-bottom:6px; }
.usr-empty-sub   { font-size:13px; color:#94a3b8; }

/* ══ FOOTER ══ */
.usr-footer {
    padding:14px 24px; border-top:1px solid #f1f5f9; background:#fafbff;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
}
.usr-footer-info { font-size:12px; color:#94a3b8; }

/* ══ RESPONSIVE ══ */
@media(max-width:1024px) { .usr-kpi-row { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px) {
    .usr-hero { flex-direction:column; align-items:flex-start; padding:20px; }
    .usr-hero-btn { width:100%; justify-content:center; }
    .usr-kpi-row { grid-template-columns:1fr 1fr; }
    .hide-sm { display:none !important; }
}

/* ══ ANIMATIONS ══ */
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.usr-hero    { animation:fadeUp .35s ease both; }
.usr-kpi-row { animation:fadeUp .35s .1s ease both; }
.usr-card    { animation:fadeUp .35s .18s ease both; }
</style>
@endpush

@section('content')
<div class="usr-wrap">

    {{-- ══ HERO ══ --}}
    <div class="usr-hero">
        <div class="usr-hero-left">
            <div class="usr-hero-ico"><i class="fas fa-users"></i></div>
            <div>
                <h1 class="usr-hero-title">Gestion des utilisateurs</h1>
                <p class="usr-hero-sub">{{ $users->total() }} compte(s) enregistré(s) dans le système</p>
            </div>
        </div>
        <a href="{{ route('users.create') }}" class="usr-hero-btn">
            <i class="fas fa-user-plus"></i> Nouvel utilisateur
        </a>
    </div>

    {{-- ══ KPI ══ --}}
    @php
        $allUsers = $users->getCollection();
        $actifs   = $allUsers->where('actif', true)->count();
        $inactifs = $allUsers->where('actif', false)->count();
        $admins   = $allUsers->where('role', 'admin')->count();
        $caissiersHaut = $allUsers->where('role', 'caissierHaut')->count();
    @endphp
    <div class="usr-kpi-row">
        <div class="usr-kpi">
            <div class="usr-kpi-ico ki-total"><i class="fas fa-users"></i></div>
            <div><div class="usr-kpi-val">{{ $users->total() }}</div><div class="usr-kpi-lbl">Total</div></div>
        </div>
        <div class="usr-kpi">
            <div class="usr-kpi-ico ki-actif"><i class="fas fa-user-check"></i></div>
            <div><div class="usr-kpi-val">{{ $actifs }}</div><div class="usr-kpi-lbl">Actifs</div></div>
        </div>
        <div class="usr-kpi">
            <div class="usr-kpi-ico ki-inact"><i class="fas fa-user-slash"></i></div>
            <div><div class="usr-kpi-val">{{ $inactifs }}</div><div class="usr-kpi-lbl">Inactifs</div></div>
        </div>
        <div class="usr-kpi">
            <div class="usr-kpi-ico ki-role"><i class="fas fa-shield-alt"></i></div>
            <div><div class="usr-kpi-val">{{ $admins }}</div><div class="usr-kpi-lbl">Admins</div></div>
        </div>
    </div>

    {{-- ══ TABLE ══ --}}
    <div class="usr-card">

        <div class="usr-card-head">
            <h6 class="usr-card-title">
                <span class="usr-card-ico"><i class="fas fa-users"></i></span>
                Liste des utilisateurs
                <span class="usr-count-badge">{{ $users->total() }}</span>
            </h6>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:10px">
                <span class="role-badge rb-admin"><span class="rb-dot"></span> Admin</span>
                <span class="role-badge rb-caiss"><span class="rb-dot"></span> Caissier</span>
                <span class="role-badge rb-caiss-haut"><span class="rb-dot"></span> Caissier Haut</span>
                <span class="role-badge rb-sup"><span class="rb-dot"></span> Superviseur</span>
                <span class="role-badge rb-other"><span class="rb-dot"></span> Secrétaire</span>
                <span class="role-badge rb-other"><span class="rb-dot"></span> Magasinier</span>
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="usr-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th class="hide-sm">Téléphone</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th class="hide-sm">Inscrit le</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $initials    = collect(explode(' ', $user->nom_complet))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                        $avatarClass = match($user->role) { 
                            'admin'       => 'ua-admin',
                            'caissier'    => 'ua-caiss',
                            'caissierHaut'=> 'ua-caiss-haut',
                            'superviseur' => 'ua-sup',
                            'secretaire'  => 'ua-other',
                            'magasinier'  => 'ua-other',
                            default       => 'ua-other' 
                        };
                        $roleClass   = match($user->role) { 
                            'admin'       => 'rb-admin',
                            'caissier'    => 'rb-caiss',
                            'caissierHaut'=> 'rb-caiss-haut',
                            'superviseur' => 'rb-sup',
                            'secretaire'  => 'rb-other',
                            'magasinier'  => 'rb-other',
                            default       => 'rb-other' 
                        };
                        $roleLabel   = match($user->role) {
                            'admin'       => 'Administrateur',
                            'caissier'    => 'Caissier',
                            'caissierHaut'=> 'Caissier Haut',
                            'superviseur' => 'Superviseur',
                            'secretaire'  => 'Secrétaire',
                            'magasinier'  => 'Magasinier',
                            default       => 'Inconnu',
                        };
                        $isMe        = $user->id === auth()->id();
                    @endphp
                    <tr class="{{ !$user->actif ? 'inactive' : '' }}">

                        {{-- Utilisateur --}}
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar {{ $avatarClass }}">{{ $initials }}</div>
                                <div>
                                    <div class="user-name">
                                        {{ $user->nom_complet }}
                                        @if($isMe)
                                            <span class="you-chip">
                                                <i class="fas fa-circle" style="font-size:5px"></i> Vous
                                            </span>
                                        @endif
                                    </div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Téléphone --}}
                        <td class="hide-sm">
                            @if($user->telephone)
                                <span style="font-size:12px;font-weight:600;color:#374151">
                                    <i class="fas fa-phone" style="font-size:9px;color:#94a3b8;margin-right:4px"></i>
                                    {{ $user->telephone }}
                                </span>
                            @else
                                <span style="color:#cbd5e1;font-size:11px">—</span>
                            @endif
                        </td>

                        {{-- Rôle --}}
                        <td>
                            <span class="role-badge {{ $roleClass }}">
                                <span class="rb-dot"></span>
                                {{ $roleLabel }}
                            </span>
                        </td>

                        {{-- Statut --}}
                        <td>
                            <span class="stat-badge {{ $user->actif ? 'sb-actif' : 'sb-inact' }}">
                                <span class="sb-dot"></span>
                                {{ $user->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="hide-sm">
                            <div class="date-val">{{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="date-rel">{{ $user->created_at->diffForHumans() }}</div>
                        </td>

                        {{-- Actions --}}
                        <td style="text-align:right">
                            <div class="act-capsule" style="justify-content:flex-end">

                                {{-- Modifier --}}
                                <a href="{{ route('users.edit', $user) }}"
                                   class="act-ico edit" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>

                                @if(!$isMe)
                                    <div class="act-sep"></div>

                                    {{-- Toggle actif/inactif --}}
                                    <form action="{{ route('users.toggle', $user) }}"
                                          method="POST" style="display:contents">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="act-ico {{ $user->actif ? 'toggle-on' : 'toggle-off' }}"
                                                title="{{ $user->actif ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas {{ $user->actif ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>

                                    <div class="act-sep"></div>

                                    {{-- Supprimer --}}
                                    <button type="button" class="act-ico del" title="Supprimer"
                                            onclick="confirmDeleteUser('del-u-{{ $user->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-u-{{ $user->id }}"
                                          action="{{ route('users.destroy', $user) }}"
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                @else
                                    {{-- Compte courant : pas de toggle/delete --}}
                                    <div class="act-sep"></div>
                                    <span class="act-ico" style="cursor:default;opacity:.3" title="Compte actuel">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="usr-empty">
                                <div class="usr-empty-ring"><i class="fas fa-users"></i></div>
                                <div class="usr-empty-title">Aucun utilisateur trouvé</div>
                                <div class="usr-empty-sub">Ajoutez le premier utilisateur du système.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="usr-footer">
                <span class="usr-footer-info">
                    Affichage {{ $users->firstItem() }}–{{ $users->lastItem() }}
                    sur {{ $users->total() }} utilisateur(s)
                </span>
                <div>{{ $users->withQueryString()->links() }}</div>
            </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
function confirmDeleteUser(formId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title:             'Supprimer cet utilisateur ?',
            text:              'Cette action est irréversible.',
            icon:              'warning',
            showCancelButton:  true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText:  'Annuler',
            confirmButtonColor:'#dc2626',
            cancelButtonColor: '#64748b',
        }).then(r => { if (r.isConfirmed) document.getElementById(formId).submit(); });
    } else {
        if (confirm('Supprimer cet utilisateur ?')) document.getElementById(formId).submit();
    }
}
</script>
@endpush
