{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('breadcrumb')
    <li class="breadcrumb-item active">Catégories</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fraunces:opsz,wght@9..144,700;9..144,900&display=swap');

/* ══════════════════════════════════════════
   VARIABLES & BASE
══════════════════════════════════════════ */
:root {
    --ink:    #09090b;
    --muted:  #71717a;
    --border: #e4e4e7;
    --bg:     #fafafa;
    --white:  #ffffff;
    --accent: #16a34a;
    --accent2: #15803d;
    --sh-sm:  0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
    --sh-md:  0 4px 20px rgba(0,0,0,.07), 0 2px 6px rgba(0,0,0,.04);
    --sh-lg:  0 12px 40px rgba(0,0,0,.1), 0 4px 12px rgba(0,0,0,.06);
}

.cat-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink);
}

/* ══════════════════════════════════════════
   HERO BANNER
══════════════════════════════════════════ */
.cat-hero {
    border-radius: 24px;
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    background: var(--ink);
    box-shadow: 0 20px 60px rgba(9,9,11,.25), 0 4px 12px rgba(9,9,11,.15);
}

/* grille géométrique décorative */
.cat-hero::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}
.cat-hero::after {
    content: '';
    position: absolute; right: -60px; bottom: -60px;
    width: 260px; height: 260px; border-radius: 50%;
    background: radial-gradient(circle, rgba(22,163,74,.3) 0%, transparent 70%);
    pointer-events: none;
}

.cat-hero-inner {
    position: relative; z-index: 1;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
}

.cat-hero-eyebrow {
    font-size: 10px; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: rgba(255,255,255,.4);
    margin-bottom: 8px;
}
.cat-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 3vw, 2rem);
    font-weight: 900; color: #fff;
    margin: 0 0 8px; line-height: 1.1;
}
.cat-hero-sub {
    font-size: 13px; color: rgba(255,255,255,.5);
    margin: 0; max-width: 480px; line-height: 1.7; font-weight: 500;
}

.cat-hero-stats {
    display: flex; gap: 20px; margin-top: 16px; flex-wrap: wrap;
}
.hs {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    border-radius: 10px; padding: 8px 14px;
}
.hs-val { font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 900; color: #fff; line-height: 1; }
.hs-lbl { font-size: 10px; color: rgba(255,255,255,.45); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }

.btn-new-cat {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: var(--ink) !important;
    border: none; border-radius: 12px;
    padding: 12px 22px; font-size: 13px; font-weight: 800;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 14px rgba(0,0,0,.2);
    font-family: 'Plus Jakarta Sans', sans-serif;
    white-space: nowrap; text-decoration: none !important;
}
.btn-new-cat:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.25);
    color: var(--ink) !important;
}
.btn-new-cat i { color: var(--accent); }

/* ══════════════════════════════════════════
   BARRE RECHERCHE + FILTRES
══════════════════════════════════════════ */
.cat-toolbar {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 14px 20px;
    margin-bottom: 20px;
    display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
    box-shadow: var(--sh-sm);
}

.cat-search-wrap {
    flex: 1; min-width: 220px;
    display: flex; align-items: center;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 12px; overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.cat-search-wrap:focus-within {
    border-color: var(--accent);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(22,163,74,.12);
}
.cat-search-ico {
    width: 40px; display: flex; align-items: center; justify-content: center;
    color: #a1a1aa; font-size: 13px; flex-shrink: 0;
    transition: color .2s;
}
.cat-search-wrap:focus-within .cat-search-ico { color: var(--accent); }
.cat-search-inp {
    flex: 1; border: none; background: transparent;
    padding: 10px 8px; font-size: 13px; font-weight: 500;
    color: var(--ink); outline: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.cat-search-inp::placeholder { color: #a1a1aa; }

.cat-search-clear {
    width: 32px; height: 32px; margin-right: 4px;
    border-radius: 8px; border: none; background: none;
    color: #a1a1aa; cursor: pointer; display: none;
    align-items: center; justify-content: center; font-size: 11px;
    transition: background .15s, color .15s;
}
.cat-search-clear:hover { background: #f4f4f5; color: var(--ink); }
.cat-search-clear.visible { display: flex; }

/* Spinner de chargement dans la barre de recherche */
.cat-search-spinner {
    width: 32px; height: 32px; margin-right: 4px;
    display: none; align-items: center; justify-content: center;
    color: var(--accent); font-size: 12px;
}
.cat-search-spinner.visible { display: flex; }

.cat-toolbar-right {
    display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
.cat-view-count {
    font-size: 11px; font-weight: 700; color: var(--muted);
    background: var(--bg); border: 1px solid var(--border);
    border-radius: 20px; padding: 5px 13px; white-space: nowrap;
}

/* ══════════════════════════════════════════
   BOARD
══════════════════════════════════════════ */
.cat-board {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: var(--sh-md);
}
.cat-board-head {
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: #fafafa;
}
.cat-board-title {
    display: flex; align-items: center; gap: 9px;
    font-size: 11px; font-weight: 800; color: var(--ink);
    text-transform: uppercase; letter-spacing: .06em; margin: 0;
}
.cat-board-ico {
    width: 30px; height: 30px; border-radius: 9px;
    background: var(--ink); color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: 11px;
}
.cat-result-badge {
    font-size: 10px; font-weight: 800; color: #fff;
    background: var(--ink); padding: 3px 10px; border-radius: 20px;
}
.cat-result-badge.searching { background: var(--accent); }

/* ══════════════════════════════════════════
   GRILLE CARTES
══════════════════════════════════════════ */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
    padding: 20px;
    /* transition douce lors du rechargement AJAX */
    transition: opacity .15s;
}
.cat-grid.loading { opacity: .4; pointer-events: none; }

/* ══════════════════════════════════════════
   CARD
══════════════════════════════════════════ */
.cat-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 0;
    display: flex; flex-direction: column;
    transition: transform .22s cubic-bezier(.34,1.2,.64,1), box-shadow .22s;
    position: relative; overflow: hidden;
    animation: cardIn .3s ease both;
    box-shadow: var(--sh-sm);
    cursor: default;
}
.cat-card:hover {
    transform: translateY(-5px) scale(1.012);
    box-shadow: 0 16px 40px rgba(9,9,11,.12), 0 4px 12px rgba(9,9,11,.06);
}

/* bande couleur haut */
.cat-card-stripe {
    height: 6px;
    background: var(--cat-color, #16a34a);
    width: 100%;
    flex-shrink: 0;
}

.cat-card-body { padding: 18px 20px 14px; flex: 1; display: flex; flex-direction: column; gap: 12px; }

.cat-card-top { display: flex; align-items: flex-start; gap: 12px; }

.cat-color-dot {
    width: 44px; height: 44px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; color: #fff; font-weight: 900;
    flex-shrink: 0; letter-spacing: -.02em;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}
.cat-info { flex: 1; min-width: 0; }
.cat-name { font-size: 15px; font-weight: 800; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0 0 4px; }
.cat-desc { font-size: 11.5px; color: var(--muted); font-weight: 500; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

.cat-card-foot {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 12px; border-top: 1px solid #f4f4f5;
}
.cat-prod-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; color: var(--muted);
    background: #f4f4f5; padding: 4px 11px; border-radius: 20px;
}
.cat-prod-badge i { font-size: 9px; }
.cat-actions { display: flex; gap: 6px; }

.cat-act-btn {
    width: 32px; height: 32px; border-radius: 9px;
    border: 1px solid var(--border); background: var(--bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; cursor: pointer; transition: all .15s;
    outline: none;
}
.cat-act-btn.edit  { color: #2563eb; }
.cat-act-btn.edit:hover  { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
.cat-act-btn.del   { color: #dc2626; }
.cat-act-btn.del:hover   { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
.cat-act-btn.del:disabled { opacity: .3; cursor: not-allowed; }

/* highlight search */
.cat-highlight { background: #fef08a; border-radius: 3px; padding: 0 2px; font-weight: 800; }

/* ══════════════════════════════════════════
   VIDE / NO RESULTS
══════════════════════════════════════════ */
.cat-empty {
    text-align: center; padding: 80px 24px;
    color: #d4d4d8;
}
.cat-empty-ico {
    width: 72px; height: 72px; border-radius: 22px;
    background: #f4f4f5; display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 28px; color: #a1a1aa;
}
.cat-empty p { font-size: 15px; font-weight: 800; color: #71717a; margin: 0 0 6px; }
.cat-empty small { font-size: 12px; color: #a1a1aa; font-weight: 500; }

#noResults { display: none; }
#noResults.visible { display: block; }

/* ══════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════ */
.cat-board-foot {
    padding: 16px 22px;
    border-top: 1px solid var(--border);
    background: #fafafa;
}

/* ══════════════════════════════════════════
   ALERTES SESSION
══════════════════════════════════════════ */
.cat-alert {
    border-radius: 12px; padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 600; margin-bottom: 18px;
}
.cat-alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.cat-alert.error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.cat-modal-overlay {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(9,9,11,.6);
    backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity .2s; padding: 16px;
}
.cat-modal-overlay.open { opacity: 1; pointer-events: all; }

.cat-modal {
    background: var(--white); border-radius: 22px;
    width: 100%; max-width: 490px;
    box-shadow: 0 32px 80px rgba(9,9,11,.25);
    transform: translateY(22px) scale(.97);
    transition: transform .28s cubic-bezier(.34,1.4,.64,1);
    overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif;
}
.cat-modal-overlay.open .cat-modal { transform: translateY(0) scale(1); }

.cat-modal-hd {
    padding: 20px 24px 16px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.cat-modal-title {
    font-size: 15px; font-weight: 800; color: var(--ink); margin: 0;
    display: flex; align-items: center; gap: 10px;
}
.cat-modal-title-ico {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: #fff;
}
.ico-green { background: linear-gradient(135deg,#16a34a,#22c55e); }
.ico-blue  { background: linear-gradient(135deg,#2563eb,#3b82f6); }
.ico-red   { background: linear-gradient(135deg,#dc2626,#ef4444); }

.cat-modal-close {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--bg); color: var(--muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; cursor: pointer; transition: .15s;
}
.cat-modal-close:hover { background: #fee2e2; border-color: #fecaca; color: #dc2626; }

.cat-modal-body { padding: 20px 24px; }
.cat-modal-ft   { padding: 14px 24px; border-top: 1px solid var(--border); display: flex; gap: 10px; justify-content: flex-end; }

.cat-field { margin-bottom: 16px; }
.cat-field:last-child { margin-bottom: 0; }
.cat-field label {
    display: block; font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--muted); margin-bottom: 7px;
}
.cat-field input[type=text],
.cat-field textarea {
    width: 100%; border: 1.5px solid var(--border); border-radius: 11px;
    padding: 10px 14px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink); outline: none; transition: .2s; background: var(--bg);
}
.cat-field input[type=text]:focus,
.cat-field textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    background: var(--white);
}
.cat-field textarea { resize: vertical; min-height: 80px; }
.input-error { border-color: #fca5a5 !important; background: #fff8f8 !important; }
.field-error-msg { font-size: 11px; color: #dc2626; font-weight: 600; margin-top: 5px; display: flex; align-items: center; gap: 5px; }

/* Couleur */
.color-picker-wrap {
    display: flex; align-items: center; gap: 12px;
    border: 1.5px solid var(--border); border-radius: 11px;
    padding: 8px 14px; background: var(--bg); transition: .2s;
}
.color-picker-wrap:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
.color-picker-wrap input[type=color] { width: 32px; height: 32px; border-radius: 8px; border: none; padding: 0; cursor: pointer; background: transparent; }
.color-preview-txt { font-size: 12px; font-weight: 700; color: var(--muted); font-family: monospace; flex: 1; }
.color-swatches { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 9px; }
.color-swatch {
    width: 24px; height: 24px; border-radius: 7px; cursor: pointer;
    border: 2px solid transparent; transition: transform .15s, border-color .15s;
}
.color-swatch:hover   { transform: scale(1.2); }
.color-swatch.selected { border-color: var(--ink); transform: scale(1.1); }

/* Boutons modal */
.btn-cancel {
    padding: 10px 18px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    color: var(--muted); font-size: 13px; font-weight: 700;
    cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: .15s;
}
.btn-cancel:hover { background: #f4f4f5; }
.btn-save {
    padding: 10px 22px; border-radius: 10px; border: none;
    background: var(--ink);
    color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
    transition: all .2s; box-shadow: 0 4px 12px rgba(9,9,11,.2);
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(9,9,11,.28); }
.btn-del-confirm {
    padding: 10px 22px; border-radius: 10px; border: none;
    background: #dc2626; color: #fff; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: .2s; box-shadow: 0 4px 12px rgba(220,38,38,.3);
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn-del-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(220,38,38,.4); }

@keyframes cardIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="cat-wrap">
    {{-- ══ HERO ══ --}}
    <div class="cat-hero">
        <div class="cat-hero-inner">
            <div>
                <div class="cat-hero-eyebrow"><i class="fas fa-tags mr-1"></i> Gestion du catalogue</div>
                <h1 class="cat-hero-title">Catégories</h1>
                <p class="cat-hero-sub">Organisez vos familles de produits pour une navigation rapide en caisse et une gestion de stock précise.</p>
                <div class="cat-hero-stats">
                    <div class="hs">
                        <div>
                            <div class="hs-val" id="heroTotal">{{ $totalCategories }}</div>
                            <div class="hs-lbl">Catégories</div>
                        </div>
                    </div>
                    <div class="hs">
                        <div>
                            <div class="hs-val">{{ $totalProduits }}</div>
                            <div class="hs-lbl">Produits liés</div>
                        </div>
                    </div>
                    <div class="hs">
                        <div>
                            <div class="hs-val">{{ $categories->lastPage() }}</div>
                            <div class="hs-lbl">Pages</div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn-new-cat" onclick="openCreate()">
                <i class="fas fa-plus"></i> Nouvelle catégorie
            </button>
        </div>
    </div>

    {{-- ══ TOOLBAR : RECHERCHE ══ --}}
    <div class="cat-toolbar">
        <div class="cat-search-wrap">
            <div class="cat-search-ico"><i class="fas fa-search"></i></div>
            <input type="text" id="catSearch" class="cat-search-inp"
                   placeholder="Rechercher parmi toutes les catégories…"
                   autocomplete="off"
                   value="{{ $search }}"
                   oninput="scheduleSearch(this.value)">
            {{-- Spinner affiché pendant la requête --}}
            <span class="cat-search-spinner" id="catSearchSpinner">
                <i class="fas fa-circle-notch fa-spin"></i>
            </span>
            <button class="cat-search-clear {{ $search ? 'visible' : '' }}" id="catSearchClear"
                    onclick="clearSearch()" title="Effacer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cat-toolbar-right">
            <span class="cat-view-count" id="catViewCount">
                {{ $categories->count() }} / {{ $totalCategories }}
            </span>
            <button class="btn-new-cat" style="padding:9px 16px;font-size:12px" onclick="openCreate()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- ══ BOARD ══ --}}
    <div class="cat-board">
        <div class="cat-board-head">
            <h2 class="cat-board-title">
                <span class="cat-board-ico"><i class="fas fa-th-large"></i></span>
                Liste des catégories
            </h2>
            <span class="cat-result-badge {{ $search ? 'searching' : '' }}" id="catBadge">
                {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }} / {{ $totalCategories }}
            </span>
        </div>

        {{-- Grille — rendue côté serveur au premier chargement, puis mise à jour par AJAX --}}
        @if($categories->isEmpty() && !$search)
            <div class="cat-empty" style="margin:20px">
                <div class="cat-empty-ico"><i class="fas fa-tags"></i></div>
                <p>Aucune catégorie</p>
                <small>Créez votre première catégorie ci-dessus</small>
            </div>
        @else
            <div class="cat-grid" id="catGrid">
                @include('categories._grid', ['categories' => $categories, 'search' => $search])
            </div>

            {{-- No results (affiché par JS si grille vide après AJAX) --}}
            <div class="cat-empty" id="noResults" @if($categories->isNotEmpty()) style="display:none" @endif>
                <div class="cat-empty-ico"><i class="fas fa-search"></i></div>
                <p>Aucun résultat</p>
                <small>Essayez avec un autre terme de recherche</small>
            </div>

            <div class="cat-board-foot" id="paginationWrap">
                @include('partials.pagination', ['paginator' => $categories])
            </div>
        @endif
    </div>

</div>{{-- /cat-wrap --}}


{{-- ══════════════════════════════════════
    MODAL CRÉER
══════════════════════════════════════ --}}
<div class="cat-modal-overlay" id="modalCreate" onclick="closeOnOverlay(event,'modalCreate')">
    <div class="cat-modal">
        <div class="cat-modal-hd">
            <h2 class="cat-modal-title">
                <span class="cat-modal-title-ico ico-green"><i class="fas fa-plus"></i></span>
                Nouvelle catégorie
            </h2>
            <button type="button" class="cat-modal-close" onclick="closeModal('modalCreate')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <input type="hidden" name="_from" value="create">
            <div class="cat-modal-body">
                <div class="cat-field">
                    <label>Nom <span style="color:#dc2626">*</span></label>
                    <input type="text" name="nom" id="createNom"
                           placeholder="Ex : Alimentation, Boissons…"
                           value="{{ old('_from') === 'create' ? old('nom','') : '' }}"
                           class="{{ $errors->has('nom') && old('_from') === 'create' ? 'input-error' : '' }}"
                           required>
                    @if($errors->has('nom') && old('_from') === 'create')
                        <span class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('nom') }}</span>
                    @endif
                </div>
                <div class="cat-field">
                    <label>Description</label>
                    <textarea name="description" id="createDesc"
                              placeholder="Description optionnelle…">{{ old('_from') === 'create' ? old('description','') : '' }}</textarea>
                </div>
                <div class="cat-field">
                    <label>Couleur <span style="color:#dc2626">*</span></label>
                    <div class="color-picker-wrap">
                        <input type="color" name="couleur" id="createColor"
                               value="{{ old('_from') === 'create' ? old('couleur','#16a34a') : '#16a34a' }}"
                               oninput="syncColorTxt('create')">
                        <span class="color-preview-txt" id="createColorTxt">
                            {{ old('_from') === 'create' ? old('couleur','#16a34a') : '#16a34a' }}
                        </span>
                    </div>
                    <div class="color-swatches" id="createSwatches"></div>
                </div>
            </div>
            <div class="cat-modal-ft">
                <button type="button" class="btn-cancel" onclick="closeModal('modalCreate')">Annuler</button>
                <button type="submit" class="btn-save"><i class="fas fa-check mr-1"></i> Créer</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════
    MODAL ÉDITER
══════════════════════════════════════ --}}
<div class="cat-modal-overlay" id="modalEdit" onclick="closeOnOverlay(event,'modalEdit')">
    <div class="cat-modal">
        <div class="cat-modal-hd">
            <h2 class="cat-modal-title">
                <span class="cat-modal-title-ico ico-blue"><i class="fas fa-pen"></i></span>
                Modifier la catégorie
            </h2>
            <button type="button" class="cat-modal-close" onclick="closeModal('modalEdit')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <input type="hidden" name="_from" value="edit">
            <div class="cat-modal-body">
                <div class="cat-field">
                    <label>Nom <span style="color:#dc2626">*</span></label>
                    <input type="text" name="nom" id="editNom" required
                           value="{{ old('_from') === 'edit' ? old('nom','') : '' }}"
                           class="{{ $errors->has('nom') && old('_from') === 'edit' ? 'input-error' : '' }}">
                    @if($errors->has('nom') && old('_from') === 'edit')
                        <span class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('nom') }}</span>
                    @endif
                </div>
                <div class="cat-field">
                    <label>Description</label>
                    <textarea name="description" id="editDesc"></textarea>
                </div>
                <div class="cat-field">
                    <label>Couleur <span style="color:#dc2626">*</span></label>
                    <div class="color-picker-wrap">
                        <input type="color" name="couleur" id="editColor"
                               value="{{ old('_from') === 'edit' ? old('couleur','#16a34a') : '#16a34a' }}"
                               oninput="syncColorTxt('edit')">
                        <span class="color-preview-txt" id="editColorTxt">
                            {{ old('_from') === 'edit' ? old('couleur','#16a34a') : '#16a34a' }}
                        </span>
                    </div>
                    <div class="color-swatches" id="editSwatches"></div>
                </div>
            </div>
            <div class="cat-modal-ft">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Annuler</button>
                <button type="submit" class="btn-save"><i class="fas fa-save mr-1"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════
    MODAL SUPPRIMER
══════════════════════════════════════ --}}
<div class="cat-modal-overlay" id="modalDelete" onclick="closeOnOverlay(event,'modalDelete')">
    <div class="cat-modal" style="max-width:420px">
        <div class="cat-modal-hd">
            <h2 class="cat-modal-title">
                <span class="cat-modal-title-ico ico-red"><i class="fas fa-trash"></i></span>
                Supprimer
            </h2>
            <button type="button" class="cat-modal-close" onclick="closeModal('modalDelete')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cat-modal-body">
            <p style="font-size:14px;color:#374151;margin:0;line-height:1.7">
                Supprimer la catégorie <strong id="deleteNomLabel" style="color:var(--ink)"></strong> ?
            </p>
            <p style="font-size:12px;color:#a1a1aa;margin:10px 0 0">
                <i class="fas fa-exclamation-triangle mr-1" style="color:#f59e0b"></i>
                Cette action est irréversible.
            </p>
        </div>
        <div class="cat-modal-ft">
            <button type="button" class="btn-cancel" onclick="closeModal('modalDelete')">Annuler</button>
            <form method="POST" id="deleteForm" action="" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn-del-confirm">
                    <i class="fas fa-trash mr-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════
//  SWATCHES COULEUR
// ════════════════════════════════════════════════
const SWATCHES = [
    '#16a34a','#22c55e','#0ea5e9','#2563eb','#7c3aed','#a855f7',
    '#dc2626','#ef4444','#d97706','#f59e0b','#0891b2','#06b6d4',
    '#be185d','#ec4899','#374151','#64748b','#09090b','#1e40af',
];

function buildSwatches(prefix, currentColor) {
    const c = document.getElementById(prefix+'Swatches');
    if (!c) return;
    c.innerHTML = SWATCHES.map(col => `
        <div class="color-swatch ${col.toLowerCase() === (currentColor||'').toLowerCase() ? 'selected' : ''}"
             style="background:${col}" title="${col}"
             onclick="pickSwatch('${prefix}','${col}')"></div>
    `).join('');
}
function pickSwatch(prefix, color) {
    document.getElementById(prefix+'Color').value          = color;
    document.getElementById(prefix+'ColorTxt').textContent = color;
    buildSwatches(prefix, color);
}
function syncColorTxt(prefix) {
    const v = document.getElementById(prefix+'Color').value;
    document.getElementById(prefix+'ColorTxt').textContent = v;
    buildSwatches(prefix, v);
}

// ════════════════════════════════════════════════
//  MODAL HELPERS
// ════════════════════════════════════════════════
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
function closeOnOverlay(e, id) { if (e.target === document.getElementById(id)) closeModal(id); }
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') ['modalCreate','modalEdit','modalDelete'].forEach(closeModal);
});

function openCreate() {
    buildSwatches('create', document.getElementById('createColor').value || '#16a34a');
    openModal('modalCreate');
    setTimeout(() => document.getElementById('createNom').focus(), 260);
}
function openEdit(id, nom, desc, couleur) {
    document.getElementById('editForm').action          = '{{ url("categories") }}/' + id;
    document.getElementById('editNom').value            = nom;
    document.getElementById('editDesc').value           = desc;
    document.getElementById('editColor').value          = couleur;
    document.getElementById('editColorTxt').textContent = couleur;
    buildSwatches('edit', couleur);
    openModal('modalEdit');
    setTimeout(() => document.getElementById('editNom').focus(), 260);
}
function openDelete(id, nom) {
    document.getElementById('deleteForm').action          = '{{ url("categories") }}/' + id;
    document.getElementById('deleteNomLabel').textContent = '"' + nom + '"';
    openModal('modalDelete');
}

// ════════════════════════════════════════════════
//  RECHERCHE SERVER-SIDE (AJAX + debounce 350ms)
//  Cherche dans TOUTES les catégories de la BDD
// ════════════════════════════════════════════════
let searchTimer   = null;
let currentSearch = '{{ $search }}';
let abortCtrl     = null;                   // pour annuler une requête en vol

const SEARCH_URL = '{{ route("categories.index") }}';

function scheduleSearch(value) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => doSearch(value), 350);   // debounce 350 ms
}

async function doSearch(value) {
    const term = value.trim();

    // Rien de changé → on ne refait pas la requête
    if (term === currentSearch) return;
    currentSearch = term;

    // UI : bouton clear & badge
    document.getElementById('catSearchClear').classList.toggle('visible', term.length > 0);
    document.getElementById('catBadge').classList.toggle('searching', term.length > 0);

    // Annuler la requête précédente si encore en vol
    if (abortCtrl) abortCtrl.abort();
    abortCtrl = new AbortController();

    // Spinner ON, grille en opacité réduite
    document.getElementById('catSearchSpinner').classList.add('visible');
    document.getElementById('catSearchClear').classList.remove('visible');  // masque X pendant le chargement
    document.getElementById('catGrid')?.classList.add('loading');

    try {
        const url = new URL(SEARCH_URL, window.location.origin);
        if (term) url.searchParams.set('search', term);

        const res  = await fetch(url.toString(), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            signal: abortCtrl.signal,
        });

        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();

        // ── Mettre à jour la grille ──
        const grid = document.getElementById('catGrid');
        if (grid) grid.innerHTML = data.html;

        // ── Mettre à jour la pagination ──
        const pagWrap = document.getElementById('paginationWrap');
        if (pagWrap) {
            pagWrap.innerHTML = data.pagination;
            // Attacher les clics de pagination pour qu'ils passent aussi par AJAX
            bindPaginationLinks();
        }

        // ── Affichage "aucun résultat" ──
        const noRes = document.getElementById('noResults');
        if (noRes) noRes.classList.toggle('visible', data.count === 0);

        // ── Mise à jour des compteurs ──
        document.getElementById('catViewCount').textContent =
            term ? `${data.count} résultat(s)` : `${data.count} / ${data.total}`;

        document.getElementById('catBadge').textContent =
            term
                ? `${data.count} / ${data.total}`
                : `${data.from}–${data.to} / ${data.total}`;

    } catch (err) {
        if (err.name !== 'AbortError') {
            console.error('Erreur recherche catégories :', err);
        }
    } finally {
        // Spinner OFF
        document.getElementById('catSearchSpinner').classList.remove('visible');
        document.getElementById('catGrid')?.classList.remove('loading');
        // Réafficher le X si un terme est présent
        if (currentSearch.length > 0) {
            document.getElementById('catSearchClear').classList.add('visible');
        }
    }
}

function clearSearch() {
    const inp = document.getElementById('catSearch');
    inp.value = '';
    inp.focus();
    doSearch('');
}

// ════════════════════════════════════════════════
//  PAGINATION AJAX
//  Intercept les clics sur les liens de pagination
//  pour garder le terme de recherche actif
// ════════════════════════════════════════════════
function bindPaginationLinks() {
    document.querySelectorAll('#paginationWrap a[href]').forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const url = new URL(link.href);

            // Conserver le terme de recherche en cours
            if (currentSearch) url.searchParams.set('search', currentSearch);

            document.getElementById('catGrid')?.classList.add('loading');

            try {
                const res  = await fetch(url.toString(), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();

                document.getElementById('catGrid').innerHTML     = data.html;
                document.getElementById('paginationWrap').innerHTML = data.pagination;
                bindPaginationLinks();  // re-bind après mise à jour du DOM

                document.getElementById('catViewCount').textContent = `${data.count} / ${data.total}`;
                document.getElementById('catBadge').textContent     = `${data.from}–${data.to} / ${data.total}`;

                // Scroll doux vers le haut du board
                document.querySelector('.cat-board')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

            } catch (err) {
                console.error('Erreur pagination :', err);
            } finally {
                document.getElementById('catGrid')?.classList.remove('loading');
            }
        });
    });
}

// Raccourci clavier : "/" pour focus search
document.addEventListener('keydown', e => {
    if (e.key === '/' && !e.ctrlKey && !e.metaKey && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
        e.preventDefault();
        document.getElementById('catSearch').focus();
    }
});

// ════════════════════════════════════════════════
//  INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    buildSwatches('create', document.getElementById('createColor').value || '#16a34a');
    buildSwatches('edit',   document.getElementById('editColor').value   || '#16a34a');

    // Attacher la pagination dès le chargement initial
    bindPaginationLinks();

    @if($errors->any() && old('_from') === 'create')
        openModal('modalCreate');
    @endif

    @if($errors->any() && old('_from') === 'edit')
        @if(old('nom'))
            document.getElementById('editNom').value = @json(old('nom'));
        @endif
        @if(old('description'))
            document.getElementById('editDesc').value = @json(old('description'));
        @endif
        @if(old('couleur'))
            document.getElementById('editColor').value          = @json(old('couleur'));
            document.getElementById('editColorTxt').textContent = @json(old('couleur'));
            buildSwatches('edit', @json(old('couleur')));
        @endif
        openModal('modalEdit');
    @endif
});
</script>
@endpush