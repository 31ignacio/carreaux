{{-- resources/views/fournisseurs/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Fournisseurs')
@section('page-title', 'Fournisseurs')

@section('breadcrumb')
    <li class="breadcrumb-item active">Fournisseurs</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap');
.fv-wrap { font-family:'DM Sans',sans-serif; }

.fv-hero {
    background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 55%,#0c4a6e 100%);
    border-radius:20px; padding:24px 30px; margin-bottom:22px;
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    position:relative; overflow:hidden;
    box-shadow:0 10px 40px rgba(15,23,42,.2);
}
.fv-hero::before { content:''; position:absolute; top:-50px; right:-50px; width:200px; height:200px; border-radius:50%; background:rgba(255,255,255,.04); pointer-events:none; }
.fv-hero-left { position:relative; z-index:1; }
.fv-hero-title { font-family:'Syne',sans-serif; font-size:clamp(1rem,2.5vw,1.35rem); font-weight:800; color:#fff; margin:0 0 4px; }
.fv-hero-sub { font-size:11px; color:rgba(255,255,255,.45); margin:0; }
.fv-hero-right { position:relative; z-index:1; }
.fv-new-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg,#0ea5e9,#0284c7);
    color:#fff; border-radius:12px; padding:11px 22px;
    font-size:13px; font-weight:800; text-decoration:none;
    transition:all .2s; box-shadow:0 4px 14px rgba(14,165,233,.35);
}
.fv-new-btn:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(14,165,233,.45); color:#fff; text-decoration:none; }

.fv-kpi-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
@media(max-width:900px){ .fv-kpi-row { grid-template-columns:repeat(2,1fr); } }
.fv-kpi { background:#fff; border:1px solid #e8edf5; border-radius:16px; padding:18px; box-shadow:0 2px 12px rgba(11,15,26,.05); transition:.2s; }
.fv-kpi:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.08); }
.fv-kpi-ico { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:15px; margin-bottom:10px; }
.fv-kpi-val { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; line-height:1; margin-bottom:3px; }
.fv-kpi-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.6; }

.fv-card { background:#fff; border:1px solid #e8edf5; border-radius:20px; overflow:hidden; box-shadow:0 2px 16px rgba(11,15,26,.05); }
.fv-card-hd { padding:14px 22px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
.fv-card-title { display:flex; align-items:center; gap:10px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#0f172a; margin:0; }
.fv-card-ico { width:30px; height:30px; border-radius:9px; background:linear-gradient(135deg,#0ea5e9,#0284c7); display:flex; align-items:center; justify-content:center; font-size:12px; color:#fff; }
.fv-badge-count { display:inline-flex; align-items:center; justify-content:center; padding:2px 10px; background:#e0f2fe; color:#0369a1; border-radius:20px; font-size:11px; font-weight:700; }

.fv-search { display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; overflow:hidden; transition:.2s; min-width:220px; }
.fv-search:focus-within { border-color:#0ea5e9; box-shadow:0 0 0 3px rgba(14,165,233,.1); }
.fv-search-ico { width:36px; display:flex; align-items:center; justify-content:center; color:#c4cdd8; font-size:12px; }
.fv-search:focus-within .fv-search-ico { color:#0ea5e9; }
.fv-search input { flex:1; border:none; background:transparent; padding:8px 10px 8px 0; font-size:12px; color:#0f172a; outline:none; }

.fv-table { width:100%; font-size:12px; border-collapse:collapse; }
.fv-table thead tr { background:#f8fafc; }
.fv-table th { padding:11px 16px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; border-bottom:1px solid #f1f5f9; white-space:nowrap; }
.fv-table td { padding:12px 16px; vertical-align:middle; border-bottom:1px solid #f8fafc; }
.fv-table tr:last-child td { border-bottom:none; }
.fv-table tr:hover td { background:#f0f9ff; }

.fv-avatar { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:900; color:#fff; flex-shrink:0; text-transform:uppercase; }
.fv-nom-wrap { display:flex; align-items:center; gap:10px; }
.fv-nom strong { font-size:13px; font-weight:700; color:#0f172a; }
.fv-nom small { font-size:10px; color:#94a3b8; display:block; }

.fv-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; }
.fb-actif   { background:#d1fae5; color:#065f46; }
.fb-inactif { background:#f1f5f9; color:#64748b; }
.fb-count   { background:#e0f2fe; color:#0369a1; }

.fv-actions { display:flex; gap:5px; }
.fv-btn { width:30px; height:30px; border-radius:8px; border:1.5px solid; display:flex; align-items:center; justify-content:center; font-size:11px; cursor:pointer; transition:.15s; text-decoration:none; }
.fv-btn:hover { transform:scale(1.1); text-decoration:none; }
.fb-view { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
.fb-edit { background:#fffbeb; border-color:#fde68a; color:#92400e; }
.fb-del  { background:#fef2f2; border-color:#fecaca; color:#dc2626; }

.fv-footer { padding:14px 22px; border-top:1px solid #f1f5f9; background:#fafbff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.fv-count-txt { font-size:11px; color:#94a3b8; font-weight:600; }

.fv-empty { padding:50px 20px; text-align:center; color:#94a3b8; }
.fv-empty i { font-size:40px; margin-bottom:12px; display:block; opacity:.3; }
.fv-empty p { font-size:13px; font-weight:600; margin:0; }

@media(max-width:640px) { .hide-sm { display:none !important; } }

@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.fv-hero   { animation:fadeUp .4s ease both; }
.fv-kpi-row{ animation:fadeUp .4s .08s ease both; }
.fv-card   { animation:fadeUp .4s .16s ease both; }
</style>
@endpush

@section('content')
@php
    $total  = $fournisseurs->total();
    $actifs = $fournisseurs->getCollection()->where('actif', true)->count();
@endphp

<div class="fv-wrap">

    <!-- HERO -->
    <div class="fv-hero">
        <div class="fv-hero-left">
            <h1 class="fv-hero-title"><i class="fas fa-truck mr-2" style="opacity:.6"></i>Fournisseurs</h1>
            <p class="fv-hero-sub">{{ $total }} fournisseur(s) enregistré(s)</p>
        </div>
        <div class="fv-hero-right">
            <a href="{{ route('fournisseurs.create') }}" class="fv-new-btn">
                <i class="fas fa-plus"></i> Nouveau fournisseur
            </a>
        </div>
    </div>

    <!-- KPI -->
    <div class="fv-kpi-row">
        <div class="fv-kpi">
            <div class="fv-kpi-ico" style="background:linear-gradient(135deg,#e0f2fe,#bae6fd); color:#0369a1"><i class="fas fa-truck"></i></div>
            <div class="fv-kpi-val" style="color:#0c4a6e">{{ $total }}</div>
            <div class="fv-kpi-lbl" style="color:#0c4a6e">Total fournisseurs</div>
        </div>
        <div class="fv-kpi">
            <div class="fv-kpi-ico" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#059669"><i class="fas fa-check-circle"></i></div>
            <div class="fv-kpi-val" style="color:#065f46">{{ $fournisseurs->getCollection()->where('actif', true)->count() }}</div>
            <div class="fv-kpi-lbl" style="color:#065f46">Actifs</div>
        </div>
        <div class="fv-kpi">
            <div class="fv-kpi-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a); color:#d97706"><i class="fas fa-pause-circle"></i></div>
            <div class="fv-kpi-val" style="color:#92400e">{{ $fournisseurs->getCollection()->where('actif', false)->count() }}</div>
            <div class="fv-kpi-lbl" style="color:#92400e">Inactifs</div>
        </div>
        <div class="fv-kpi">
            <div class="fv-kpi-ico" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe); color:#7c3aed"><i class="fas fa-boxes"></i></div>
            <div class="fv-kpi-val" style="color:#4c1d95">{{ $fournisseurs->getCollection()->sum('produits_count') }}</div>
            <div class="fv-kpi-lbl" style="color:#4c1d95">Produits liés</div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="fv-card">
        <div class="fv-card-hd">
            <h6 class="fv-card-title">
                <span class="fv-card-ico"><i class="fas fa-list"></i></span>
                Liste des fournisseurs
                <span class="fv-badge-count">{{ $total }}</span>
            </h6>
            <div class="fv-search">
                <div class="fv-search-ico"><i class="fas fa-search"></i></div>
                <input type="text" id="fvSearch" placeholder="Rechercher..." autocomplete="off">
            </div>
        </div>

        <div class="table-responsive">
            <table class="fv-table" id="fvTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fournisseur</th>
                        <th class="hide-sm">Contact</th>
                        <th>Téléphone</th>
                        <th class="hide-sm">Email</th>
                        <th class="hide-sm">Ville</th>
                        <th>Produits</th>
                        <th>Statut</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody id="fvBody">
                    @forelse($fournisseurs as $i => $f)
                        @php
                            $colors = ['#0ea5e9','#059669','#d97706','#7c3aed','#dc2626','#0891b2','#16a34a'];
                            $color  = $colors[$f->id % count($colors)];
                            $init   = strtoupper(substr($f->nom, 0, 2));
                        @endphp
                        <tr>
                            <td style="color:#94a3b8; font-size:11px">{{ $fournisseurs->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fv-nom-wrap">
                                    <div class="fv-avatar" style="background:{{ $color }}">{{ $init }}</div>
                                    <div class="fv-nom">
                                        <strong>{{ $f->nom }}</strong>
                                        @if($f->contact_personne)
                                            <small>{{ $f->contact_personne }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="hide-sm" style="color:#64748b">{{ $f->contact_personne ?? '—' }}</td>
                            <td>
                                @if($f->telephone)
                                    <a href="tel:{{ $f->telephone }}" style="color:#0ea5e9; font-weight:600; text-decoration:none">
                                        <i class="fas fa-phone" style="font-size:9px; margin-right:4px"></i>{{ $f->telephone }}
                                    </a>
                                @else
                                    <span style="color:#cbd5e1">—</span>
                                @endif
                            </td>
                            <td class="hide-sm" style="color:#64748b; font-size:11px">{{ $f->email ?? '—' }}</td>
                            <td class="hide-sm" style="color:#64748b">{{ $f->ville ?? '—' }}</td>
                            <td>
                                <span class="fv-badge fb-count">
                                    <i class="fas fa-box" style="font-size:8px"></i>
                                    {{ $f->produits_count }}
                                </span>
                            </td>
                            <td>
                                <span class="fv-badge {{ $f->actif ? 'fb-actif' : 'fb-inactif' }}">
                                    <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block"></span>
                                    {{ $f->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td>
                                <div class="fv-actions" style="justify-content:flex-end">
                                    <a href="{{ route('fournisseurs.show', $f) }}" class="fv-btn fb-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('fournisseurs.edit', $f) }}" class="fv-btn fb-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('del-f-{{ $f->id }}')" class="fv-btn fb-del" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-f-{{ $f->id }}" action="{{ route('fournisseurs.destroy', $f) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="fv-empty">
                                    <i class="fas fa-truck"></i>
                                    <p>Aucun fournisseur enregistré</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="fv-footer">
            <span class="fv-count-txt">{{ $fournisseurs->total() }} fournisseur(s) au total</span>
            @include('partials.pagination', ['paginator' => $fournisseurs])
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('fvSearch')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#fvBody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush