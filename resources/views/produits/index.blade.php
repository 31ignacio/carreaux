{{-- resources/views/produits/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Produits')
@section('page-title', '')

@section('breadcrumb')
    <li class="breadcrumb-item active">Produits</li>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900&family=Syne:wght@700;800&display=swap');

.prod-wrap { font-family: 'DM Sans', sans-serif; }

/* ══ HEADER ══ */
.prod-page-hd { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
.prod-page-hd p { font-size: 12px; color: #94a3b8; margin: 0; font-weight: 500; }

.btn-new-prod {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff !important; border: none; border-radius: 12px;
    padding: 11px 20px; font-size: 13px; font-weight: 700;
    text-decoration: none !important; transition: all .2s; cursor: pointer;
    box-shadow: 0 4px 16px rgba(16,185,129,.3); font-family: 'DM Sans', sans-serif;
}
.btn-new-prod:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(16,185,129,.4); }

.btn-import-prod {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #0f766e, #14b8a6);
    color: #fff !important; border: none; border-radius: 12px;
    padding: 11px 18px; font-size: 13px; font-weight: 700;
    text-decoration: none !important; transition: all .2s; cursor: pointer;
    box-shadow: 0 4px 16px rgba(20,184,166,.28); font-family: 'DM Sans', sans-serif;
}
.btn-import-prod:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(20,184,166,.36); }

.btn-rapport {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
    padding: 10px 16px; font-size: 12px; font-weight: 700;
    color: #2563eb !important; text-decoration: none !important; transition: .15s;
}
.btn-rapport:hover { background: #dbeafe; }

/* ══ STATS ══ */
.prod-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
@media(max-width:768px) { .prod-stats { grid-template-columns: repeat(2, 1fr); } }
.stat-pill { background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 8px rgba(11,15,26,.04); transition: transform .2s; }
.stat-pill:hover { transform: translateY(-2px); }
.stat-pill-ico { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.stat-pill-val { font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1; }
.stat-pill-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; margin-top: 2px; text-transform: uppercase; letter-spacing: .4px; }

/* ══ FILTRES ══ */
.prod-filters { background: #fff; border: 1px solid #f1f5f9; border-radius: 16px; padding: 16px 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(11,15,26,.04); display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
.flt-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 160px; }
.flt-group label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }
.flt-wrap { display: flex; align-items: center; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; transition: .2s; }
.flt-wrap:focus-within { border-color: #059669; background: #fff; box-shadow: 0 0 0 3px rgba(5,150,105,.1); }
.flt-ico { width: 34px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 12px; flex-shrink: 0; }
.flt-wrap:focus-within .flt-ico { color: #059669; }
.flt-inp, .flt-sel { flex: 1; border: none; background: transparent; padding: 9px 10px 9px 0; font-size: 12.5px; color: #0f172a; outline: none; min-width: 0; font-family: 'DM Sans', sans-serif; }
.flt-inp::placeholder { color: #cbd5e1; }
#filter_categorie_id { flex: 1; border: none; background: transparent; padding: 9px 10px 9px 0; font-size: 12.5px; color: #0f172a; outline: none; min-width: 0; font-family: 'DM Sans', sans-serif; appearance: auto; -webkit-appearance: auto; cursor: pointer; }
.btn-filtrer { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; border-radius: 10px; padding: 10px 18px; font-size: 12px; font-weight: 700; cursor: pointer; transition: .2s; white-space: nowrap; align-self: flex-end; font-family: 'DM Sans', sans-serif; box-shadow: 0 4px 12px rgba(37,99,235,.25); }
.btn-filtrer:hover { transform: translateY(-1px); }
.btn-reset { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; border: none; border-radius: 10px; padding: 10px 16px; font-size: 12px; font-weight: 700; color: #64748b !important; text-decoration: none !important; transition: .15s; white-space: nowrap; align-self: flex-end; }
.btn-reset:hover { background: #e2e8f0; }

/* ══ TABLE ══ */
.prod-table-hd { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #fafbff; }
.prod-table-title { font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: .5px; display: flex; align-items: center; gap: 8px; margin: 0; }
.prod-count { background: #0f172a; color: #fff; padding: 2px 9px; border-radius: 20px; font-size: 10px; font-weight: 800; }
.prod-table { width: 100%; border-collapse: collapse; }
.prod-table th { padding: 10px 16px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; background: #f8fafc; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
.prod-table td { padding: 13px 16px; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
.prod-table tr:last-child td { border-bottom: none; }
.prod-table tr:hover td { background: #fafbff; }
.prod-avatar { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 900; flex-shrink: 0; }
.prod-name { font-size: 13px; font-weight: 700; color: #0f172a; }
.prod-meta { font-size: 10px; color: #94a3b8; margin-top: 2px; }
.cat-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; }
.stock-val { font-size: 13px; font-weight: 800; }
.s-ok { color: #059669; } .s-low { color: #d97706; } .s-rup { color: #dc2626; }
.stock-min { font-size: 10px; color: #94a3b8; margin-top: 2px; }
.statut-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; }
.sc-ok { background: #d1fae5; color: #065f46; } .sc-low { background: #fef3c7; color: #92400e; } .sc-rup { background: #fee2e2; color: #991b1b; }
.prod-empty { text-align: center; padding: 60px 20px; color: #cbd5e1; }
.prod-empty i { font-size: 48px; opacity: .3; display: block; margin-bottom: 14px; }
.prod-empty p { font-size: 14px; font-weight: 700; color: #94a3b8; margin: 0; }
.prod-table-ft { padding: 14px 20px; border-top: 1px solid #f1f5f9; background: #fafbff; }
@keyframes rowIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.row-anim { animation: rowIn .25s ease both; }
@media(max-width:768px) {
    .prod-table th:nth-child(3), .prod-table td:nth-child(3),
    .prod-table th:nth-child(4), .prod-table td:nth-child(4),
    .prod-table th:nth-child(5), .prod-table td:nth-child(5) { display:none; }
}
.act-btn { width: 30px; height: 30px; border-radius: 8px; border: none; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; cursor: pointer; transition: all .15s; text-decoration: none; flex-shrink: 0; background: transparent; color: #94a3b8; }
.act-btn:hover { transform: translateY(-1px); }
.act-btn-blue:hover   { background: #eff6ff; color: #2563eb; }
.act-btn-amber:hover  { background: #fffbeb; color: #d97706; }
.act-btn-purple:hover { background: #f5f3ff; color: #7c3aed; }
.act-btn-red:hover    { background: #fef2f2; color: #dc2626; }

/* ══════════════════════════════════
   MODAL
══════════════════════════════════ */
.pm-overlay { position: fixed; inset: 0; z-index: 99999; background: rgba(11,15,26,.6); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; padding: 16px; opacity: 0; pointer-events: none; transition: opacity .25s ease; }
.pm-overlay.open { opacity: 1; pointer-events: all; }
.pm-modal { background: #fff; border-radius: 22px; width: 100%; max-width: 1000px; max-height: 90vh; overflow: hidden; position: relative; display: flex; flex-direction: column; box-shadow: 0 32px 80px rgba(11,15,26,.25), 0 0 0 1px rgba(255,255,255,.08); transform: translateY(24px) scale(.97); transition: transform .3s cubic-bezier(.34,1.4,.64,1); font-family: 'DM Sans', sans-serif; }
.pm-overlay.open .pm-modal { transform: translateY(0) scale(1); }
.pm-hd { padding: 0; flex-shrink: 0; position: relative; overflow: hidden; }
.pm-hd-bg { padding: 22px 28px 20px; display: flex; align-items: center; justify-content: space-between; }
.pm-hd-bg.mode-create { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #065f46 100%); }
.pm-hd-bg.mode-edit   { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #92400e 100%); }
.pm-hd-bg::before { content:''; position:absolute; top:-50px; right:-50px; width:200px; height:200px; border-radius:50%; background:rgba(255,255,255,.05); pointer-events:none; }
.pm-title { font-family: 'Syne', sans-serif; font-size: 1.15rem; color: #fff; margin: 0 0 3px; font-weight: 800; }
.pm-sub { font-size: 11px; color: rgba(255,255,255,.5); margin: 0; }
.pm-close { width: 34px; height: 34px; border-radius: 10px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.8); display: flex; align-items: center; justify-content: center; font-size: 13px; cursor: pointer; transition: .15s; flex-shrink: 0; position: relative; z-index: 1; }
.pm-close:hover { background: rgba(255,255,255,.22); color: #fff; }
.pm-body { flex: 1; overflow-y: auto; padding: 22px 28px; }
.pm-body::-webkit-scrollbar { width: 4px; }
.pm-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.pm-ft { padding: 16px 28px; border-top: 1px solid #f1f5f9; background: #fafbff; flex-shrink: 0; display: flex; align-items: center; justify-content: flex-end; gap: 10px; }
.pm-btn-cancel { padding: 10px 20px; border-radius: 11px; border: 1.5px solid #e2e8f0; background: #fff; color: #64748b; font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: .15s; }
.pm-btn-cancel:hover { background: #f8fafc; }
.pm-btn-save { padding: 10px 24px; border-radius: 11px; border: none; font-size: 13px; font-weight: 800; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; }
.pm-btn-save.mode-create { background: linear-gradient(135deg, #059669, #10b981); color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,.3); }
.pm-btn-save.mode-edit   { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; box-shadow: 0 4px 14px rgba(217,119,6,.3); }
.pm-btn-save:hover { transform: translateY(-2px); }

.pf-section { margin-bottom: 18px; }
.pf-section-hd { display: flex; align-items: center; gap: 9px; padding-bottom: 10px; margin-bottom: 14px; border-bottom: 1.5px solid #f1f5f9; }
.pf-section-ico { width: 26px; height: 26px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #fff; flex-shrink: 0; }
.pf-section-title { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .6px; color: #374151; margin: 0; }

.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
@media(max-width: 640px) { .row-2, .row-3 { grid-template-columns: 1fr; } }

.pf-label { display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-bottom: 6px; }
.pf-label .req { color: #ef4444; }
.pf-label .opt { font-size: 9px; color: #9ca3af; font-weight: 600; text-transform: none; letter-spacing: 0; }
.pf-inp-wrap { display: flex; align-items: center; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: .2s; }
.pf-inp-wrap:focus-within { border-color: #059669; background: #fff; box-shadow: 0 0 0 3px rgba(5,150,105,.08); }
.pf-inp-wrap.mode-edit:focus-within { border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,.08); }
.pf-ico { width: 34px; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 11px; flex-shrink: 0; }
.pf-inp-wrap:focus-within .pf-ico { color: #059669; }
.pf-inp-wrap.mode-edit:focus-within .pf-ico { color: #d97706; }
.pf-inp { flex: 1; border: none; background: transparent; padding: 9px 10px 9px 0; font-size: 12.5px; color: #111827; outline: none; min-width: 0; font-family: 'DM Sans', sans-serif; }
.pf-inp::placeholder { color: #d1d5db; }
.pf-sel { flex: 1; border: none; background: transparent; padding: 9px 6px 9px 0; font-size: 12.5px; color: #111827; outline: none; font-family: 'DM Sans', sans-serif; }

/* SELECT2 dans modaux */
.pf-inp-wrap .select2-container { flex: 1 1 auto; min-width: 0; width: 1% !important; }
.pf-inp-wrap .select2-container--default .select2-selection--single { height: 40px; border: none; background: transparent; border-radius: 0; }
.pf-inp-wrap .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 40px; padding-left: 0; padding-right: 32px; font-size: 12.5px; color: #0f172a; overflow: hidden; text-overflow: ellipsis; }
.pf-inp-wrap .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #d1d5db; }
.pf-inp-wrap .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; right: 8px; }
.pf-inp-wrap .select2-container--default .select2-selection--single .select2-selection__clear { margin-right: 22px; }
.pf-inp-wrap .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: #94a3b8 transparent transparent transparent; }
.pf-inp-wrap .select2-container--default.select2-container--focus .select2-selection--single { border: none; }
.select2-dropdown { border: 1px solid #dbe4ee; border-radius: 12px; overflow: hidden; box-shadow: 0 18px 40px rgba(15,23,42,.14); z-index: 999999 !important; }
.select2-search--dropdown { padding: 10px; background: #f8fafc; }
.select2-search--dropdown .select2-search__field { border: 1px solid #dbe4ee !important; border-radius: 9px !important; padding: 8px 10px !important; font-size: 12px !important; font-family: 'DM Sans', sans-serif !important; }
.select2-results__option { padding: 9px 12px; font-size: 12.5px; font-family: 'DM Sans', sans-serif; }
.select2-results__option--highlighted[aria-selected] { background: #ecfdf5 !important; color: #047857 !important; }
#modalEdit .select2-results__option--highlighted[aria-selected] { background: #fffbeb !important; color: #92400e !important; }

.prix-cell { display: flex; align-items: center; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 9px; overflow: hidden; transition: .2s; }
.prix-cell:focus-within { border-color: #059669; background: #fff; box-shadow: 0 0 0 3px rgba(5,150,105,.07); }
.prix-num { flex: 1; border: none; background: transparent; padding: 8px 6px 8px 10px; font-size: 12.5px; font-weight: 700; color: #111827; outline: none; min-width: 50px; font-family: 'DM Sans', sans-serif; }
.prix-num::placeholder { color: #d1d5db; font-weight: 400; font-size: 11px; }
.prix-sfx { padding: 0 8px; font-size: 10px; font-weight: 700; color: #9ca3af; white-space: nowrap; }

.tpal { width: 100%; border-collapse: collapse; font-size: 11px; }
.tpal th { padding: 8px 10px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; border-bottom: 1px solid #f1f5f9; background: #f8fafc; text-align: left; white-space: nowrap; }
.tpal td { padding: 9px 10px; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
.tpal tr:last-child td { border-bottom: none; }
.pal-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 10px; font-weight: 800; }
.pb-d { background: #d1fae5; color: #065f46; } .pb-m { background: #fef9c3; color: #854d0e; } .pb-g { background: #dbeafe; color: #1e40af; }
.cond-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 10px; font-weight: 700; white-space: nowrap; }
.stock-info-banner { background: #f0fdf4; border: 1px solid #d1fae5; border-radius: 10px; padding: 10px 14px; font-size: 11px; color: #065f46; display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.pm-errors { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 11px 15px; display: flex; align-items: flex-start; gap: 9px; margin-bottom: 16px; font-size: 11px; color: #dc2626; }
.import-help { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; font-size: 11px; color: #475569; }

/* ══ CATÉGORIE CRÉATION RAPIDE ══ */
.cat-field-wrap { display: flex; flex-direction: column; gap: 6px; }
.cat-select-row { display: flex; align-items: center; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: border-color .2s, box-shadow .2s; }
.cat-select-row:focus-within { border-color: #059669; background: #fff; box-shadow: 0 0 0 3px rgba(5,150,105,.08); }
.cat-select-row.mode-edit:focus-within { border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,.08); }
.cat-select-inner { flex: 1; border: none; background: transparent; padding: 9px 6px 9px 0; font-size: 12.5px; color: #111827; outline: none; font-family: 'DM Sans', sans-serif; min-width: 0; }
.btn-add-cat { width: 36px; min-height: 38px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #059669, #10b981); border: none; border-left: 1px solid rgba(255,255,255,.2); color: #fff; font-size: 14px; cursor: pointer; transition: background .15s; }
.btn-add-cat:hover { background: linear-gradient(135deg, #047857, #059669); }
.btn-add-cat.mode-edit { background: linear-gradient(135deg, #d97706, #f59e0b); }
.btn-add-cat.mode-edit:hover { background: linear-gradient(135deg, #b45309, #d97706); }
.quick-cat-panel { display: none; background: #f0fdf4; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 12px 14px; }
.quick-cat-panel.open { display: block; animation: slideDown .18s ease; }
.quick-cat-panel.mode-edit { background: #fffbeb; border-color: #fcd34d; }
@keyframes slideDown { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:translateY(0)} }
.quick-cat-title { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #065f46; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
.quick-cat-title.mode-edit { color: #92400e; }
.quick-cat-fields { display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px; align-items: end; }
@media(max-width:600px) { .quick-cat-fields { grid-template-columns: 1fr; } }
.quick-cat-inp-wrap { display: flex; align-items: center; background: #fff; border: 1.5px solid #a7f3d0; border-radius: 9px; overflow: hidden; transition: .2s; }
.quick-cat-inp-wrap:focus-within { border-color: #059669; box-shadow: 0 0 0 2px rgba(5,150,105,.12); }
.quick-cat-inp-wrap.mode-edit { border-color: #fcd34d; }
.quick-cat-inp-wrap.mode-edit:focus-within { border-color: #d97706; box-shadow: 0 0 0 2px rgba(217,119,6,.1); }
.quick-cat-inp { flex: 1; border: none; background: transparent; padding: 8px 10px; font-size: 12px; color: #111827; outline: none; font-family: 'DM Sans', sans-serif; }
.quick-cat-inp::placeholder { color: #94a3b8; }
.quick-color-wrap { display: flex; align-items: center; gap: 6px; background: #fff; border: 1.5px solid #a7f3d0; border-radius: 9px; padding: 5px 10px; transition: .2s; }
.quick-color-wrap.mode-edit { border-color: #fcd34d; }
.quick-color-wrap input[type=color] { width: 26px; height: 26px; border-radius: 6px; border: none; padding: 0; cursor: pointer; background: transparent; }
.quick-color-txt { font-size: 10px; font-weight: 700; color: #6b7280; font-family: monospace; }
.btn-quick-save { display: inline-flex; align-items: center; gap: 5px; background: linear-gradient(135deg, #059669, #10b981); color: #fff; border: none; border-radius: 9px; padding: 9px 14px; font-size: 12px; font-weight: 700; cursor: pointer; transition: .15s; white-space: nowrap; font-family: 'DM Sans', sans-serif; box-shadow: 0 2px 8px rgba(5,150,105,.2); }
.btn-quick-save:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(5,150,105,.3); }
.btn-quick-save:disabled { opacity: .5; cursor: not-allowed; transform: none; }
.btn-quick-save.mode-edit { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 2px 8px rgba(217,119,6,.2); }
.btn-quick-cancel { background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 11px; padding: 4px 8px; font-family: 'DM Sans', sans-serif; font-weight: 600; transition: .15s; white-space: nowrap; }
.btn-quick-cancel:hover { color: #dc2626; }
.quick-cat-feedback { margin-top: 6px; font-size: 10px; font-weight: 700; display: none; align-items: center; gap: 5px; }
.quick-cat-feedback.success { display: flex; color: #059669; }
.quick-cat-feedback.error   { display: flex; color: #dc2626; }
.quick-swatches { display: flex; gap: 4px; margin-top: 6px; flex-wrap: wrap; }
.quick-swatch { width: 20px; height: 20px; border-radius: 5px; cursor: pointer; border: 2px solid transparent; transition: transform .12s, border-color .12s; }
.quick-swatch:hover { transform: scale(1.2); }
.quick-swatch.selected { border-color: #0f172a; }

/* ══ SCANNER QR ══ */
.barcode-field-wrap { display: flex; flex-direction: column; gap: 8px; }
.barcode-main-row { display: flex; align-items: stretch; gap: 10px; }
.barcode-input-col { flex: 1; display: flex; flex-direction: column; gap: 8px; min-width: 0; }
.barcode-input-row { display: flex; align-items: center; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: .2s; }
.barcode-input-row:focus-within { border-color: #059669; background: #fff; box-shadow: 0 0 0 3px rgba(5,150,105,.08); }
.barcode-input-row.mode-edit:focus-within { border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,.08); }
.btn-scan { display: inline-flex; align-items: center; gap: 5px; background: #0f172a; color: #fff; border: none; padding: 8px 12px; font-size: 11px; font-weight: 700; cursor: pointer; transition: .15s; white-space: nowrap; font-family: 'DM Sans', sans-serif; flex-shrink: 0; border-radius: 0; }
.btn-scan:hover { background: #1e293b; }
.scanner-box { display: none; border: 1.5px solid #e5e7eb; border-radius: 14px; overflow: hidden; background: #000; position: relative; }
.scanner-box.active { display: block; }
.scanner-box video { width: 100% !important; height: 160px !important; object-fit: cover; display: block; }
.scanner-close-btn { position: absolute; top: 8px; right: 8px; z-index: 10; width: 28px; height: 28px; border-radius: 8px; background: rgba(0,0,0,.6); border: 1px solid rgba(255,255,255,.2); color: #fff; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.scanner-line { position: absolute; left: 0; right: 0; top: 50%; height: 2px; background: rgba(5,150,105,.8); animation: scanLine 1.8s ease-in-out infinite; pointer-events: none; }
@keyframes scanLine { 0%{top:20%} 50%{top:80%} 100%{top:20%} }
.scanner-hint { position: absolute; bottom: 8px; left: 0; right: 0; text-align: center; font-size: 10px; color: rgba(255,255,255,.7); font-family: 'DM Sans', sans-serif; font-weight: 600; pointer-events: none; }
.barcode-preview-inline { display: none; flex-direction: column; align-items: center; justify-content: center; background: #fff; border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 10px 14px; min-width: 160px; max-width: 200px; flex-shrink: 0; }
.barcode-preview-inline.visible { display: flex; }
.barcode-number { margin-top: 6px; font-family: 'Courier New', monospace; font-size: 11px; font-weight: 700; color: #0f172a; letter-spacing: 1.5px; text-align: center; word-break: break-all; }
.barcode-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; margin-bottom: 6px; }
.barcode-actions { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; justify-content: center; }
.barcode-action-btn { display: inline-flex; align-items: center; gap: 4px; background: #f1f5f9; border: none; border-radius: 7px; padding: 5px 10px; font-size: 10px; font-weight: 700; color: #475569; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: .15s; }
.barcode-action-btn:hover { background: #e2e8f0; }
.barcode-action-btn.danger { background: #fef2f2; color: #dc2626; }
.barcode-action-btn.danger:hover { background: #fee2e2; }
.barcode-converted-badge { display: none; align-items: center; gap: 4px; background: #fef9c3; border: 1px solid #fde047; border-radius: 6px; padding: 3px 8px; font-size: 10px; font-weight: 700; color: #854d0e; margin-top: 4px; }
.barcode-converted-badge.visible { display: flex; }

/* ══ BULK SELECT ══ */
.bulk-bar { display:none; align-items:center; gap:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; padding:12px 18px; margin-bottom:14px; flex-wrap:wrap; }
.bulk-bar.visible { display:flex; }
.bulk-count { font-size:13px; font-weight:800; color:#dc2626; }
.btn-bulk-del { display:inline-flex; align-items:center; gap:7px; background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:12px; font-weight:800; cursor:pointer; transition:.2s; font-family:'DM Sans',sans-serif; box-shadow:0 4px 14px rgba(220,38,38,.3); }
.btn-bulk-del:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(220,38,38,.4); }
.btn-bulk-cancel { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:9px 14px; font-size:12px; font-weight:700; color:#64748b; cursor:pointer; font-family:'DM Sans',sans-serif; }
.cb-row { width:16px; height:16px; cursor:pointer; accent-color:#dc2626; }
.cb-all  { width:16px; height:16px; cursor:pointer; accent-color:#dc2626; }

</style>
@endpush

@section('content')
<div class="prod-wrap">

    {{-- ══ HEADER ══ --}}
    <div class="prod-page-hd">
        <div><p>{{ $produits->total() }} produit(s) enregistré(s)</p></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('rapports.stock') }}" class="btn-rapport"><i class="fas fa-chart-bar"></i> Rapport stock</a>
            <button type="button" class="btn-import-prod" onclick="openImportModal()"><i class="fas fa-file-excel"></i> Import Excel</button>
            <button type="button" class="btn-new-prod" onclick="openCreateModal()"><i class="fas fa-plus"></i> Nouveau produit</button>
        </div>
    </div>

    {{-- ══ BARRE BULK ══ --}}
<form id="formBulkDelete" action="{{ route('produits.bulk-destroy') }}" method="POST" id="formBulkDelete">
    @csrf
    @method('DELETE')
    <div class="bulk-bar" id="bulkBar">
        <span class="bulk-count" id="bulkCount">0 sélectionné(s)</span>
        <button type="button" class="btn-bulk-del" onclick="confirmBulkDelete()">
            <i class="fas fa-trash"></i> Supprimer définitivement
        </button>
        <button type="button" class="btn-bulk-cancel" onclick="clearSelection()">
            <i class="fas fa-times"></i> Annuler
        </button>
    </div>
</form>

    {{-- ══ STATS ══ --}}
    <div class="prod-stats">
        <div class="stat-pill"><div class="stat-pill-ico" style="background:#dbeafe;color:#2563eb"><i class="fas fa-boxes"></i></div><div><div class="stat-pill-val">{{ $produits->total() }}</div><div class="stat-pill-lbl">Total</div></div></div>
        <div class="stat-pill"><div class="stat-pill-ico" style="background:#d1fae5;color:#059669"><i class="fas fa-check-circle"></i></div><div><div class="stat-pill-val">{{ $stats['normal'] ?? 0 }}</div><div class="stat-pill-lbl">Normal</div></div></div>
        <div class="stat-pill"><div class="stat-pill-ico" style="background:#fef3c7;color:#d97706"><i class="fas fa-exclamation-triangle"></i></div><div><div class="stat-pill-val">{{ $stats['faible'] ?? 0 }}</div><div class="stat-pill-lbl">Faible</div></div></div>
        <div class="stat-pill"><div class="stat-pill-ico" style="background:#fee2e2;color:#dc2626"><i class="fas fa-times-circle"></i></div><div><div class="stat-pill-val">{{ $stats['rupture'] ?? 0 }}</div><div class="stat-pill-lbl">Rupture</div></div></div>
    </div>

    {{-- ══ FILTRES ══ --}}
    <div class="prod-filters">
        <form method="GET" style="display:contents">
            <div class="flt-group" style="flex:2;min-width:200px">
                <label><i class="fas fa-search mr-1"></i> Recherche</label>
                <div class="flt-wrap"><div class="flt-ico"><i class="fas fa-search"></i></div>
                    <input type="text" name="search" class="flt-inp" placeholder="Nom, référence..." value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
            <div class="flt-group">
                <label><i class="fas fa-layer-group mr-1"></i> Catégorie</label>
                <div class="flt-wrap"><div class="flt-ico"><i class="fas fa-layer-group"></i></div>
                    <select name="categorie_id" id="filter_categorie_id" class="flt-sel" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flt-group">
                <label><i class="fas fa-warehouse mr-1"></i> Stock</label>
                <div class="flt-wrap"><div class="flt-ico"><i class="fas fa-warehouse"></i></div>
                    <select name="statut_stock" class="flt-sel" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        <option value="normal"  {{ request('statut_stock') == 'normal'  ? 'selected' : '' }}>✅ Normal</option>
                        <option value="faible"  {{ request('statut_stock') == 'faible'  ? 'selected' : '' }}>⚠️ Faible</option>
                        <option value="rupture" {{ request('statut_stock') == 'rupture' ? 'selected' : '' }}>❌ Rupture</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-filtrer"><i class="fas fa-search"></i> Filtrer</button>
            @if(request()->hasAny(['search','categorie_id','statut_stock']))
                <a href="{{ route('produits.index') }}" class="btn-reset"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    {{-- ══ TABLE ══ --}}
    <div class="prod-table-card">
        <div class="prod-table-hd">
            <h3 class="prod-table-title"><i class="fas fa-list" style="opacity:.5"></i> Liste des produits <span class="prod-count">{{ $produits->total() }}</span></h3>
        </div>
        <div class="table-responsive">
            <table class="prod-table">
                <thead>
                    <tr>
                        <th style="width:36px"><input type="checkbox" class="cb-all" id="cbAll" onchange="toggleAll(this)"></th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th style="text-align:center">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($produits as $i => $produit)
                   <tr class="row-anim" style="animation-delay:{{ $i * 0.03 }}s" data-id="{{ $produit->id }}">
    <td>
        <input type="checkbox" class="cb-row" value="{{ $produit->id }}" onchange="updateBulkBar()">
    </td>
    {{-- ... reste des colonnes inchangé ... --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="prod-avatar" style="background:#cbd5e1;color:#0f172a">{{ strtoupper(substr($produit->libelle, 0, 1)) }}</div>
                                <div>
                                    <div class="prod-name">{{ $produit->libelle }}</div>
                                    <div class="prod-meta">
                                        @if($produit->reference)Réf: {{ $produit->reference }}@endif
                                        @if($produit->code_barre)@if($produit->reference) · @endif<i class="fas fa-barcode" style="font-size:9px"></i> {{ $produit->code_barre }}@endif
                                        @if(!$produit->reference && !$produit->code_barre)—@endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($produit->categorie)
                                <span class="cat-badge" style="background:{{ $produit->categorie->couleur ?? '#e2e8f0' }}22;color:{{ $produit->categorie->couleur ?? '#64748b' }};border:1px solid {{ $produit->categorie->couleur ?? '#e2e8f0' }}55">{{ $produit->categorie->nom }}</span>
                            @else<span style="color:#cbd5e1;font-size:12px">—</span>@endif
                        </td>
                        <td>
                            <div class="stock-val {{ $produit->statut_stock === 'rupture' ? 's-rup' : ($produit->statut_stock === 'faible' ? 's-low' : 's-ok') }}">{{ number_format($produit->stock_actuel, 2, ',', ' ') }} {{ $produit->unite }}</div>
                            <div class="stock-min">Min : {{ $produit->stock_minimum }} {{ $produit->unite }}</div>
                        </td>
                        <td>
                            @if($produit->statut_stock === 'normal')<span class="statut-chip sc-ok"><i class="fas fa-check-circle" style="font-size:9px"></i> Normal</span>
                            @elseif($produit->statut_stock === 'faible')<span class="statut-chip sc-low"><i class="fas fa-exclamation-triangle" style="font-size:9px"></i> Faible</span>
                            @elseif($produit->statut_stock === 'rupture')<span class="statut-chip sc-rup"><i class="fas fa-times-circle" style="font-size:9px"></i> Rupture</span>
                            @endif
                        </td>
                        <td style="text-align:center;white-space:nowrap">
                            <div style="display:inline-flex;align-items:center;gap:4px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:12px;padding:4px;">
                                <a href="{{ route('produits.show', $produit) }}" class="act-btn act-btn-blue" title="Voir"><i class="fas fa-eye"></i></a>
                                <button type="button" class="act-btn act-btn-amber" title="Modifier"
                                        onclick='openEditModal({{ json_encode([
                                            "id"=>$produit->id,"libelle"=>$produit->libelle,
                                            "unite"=>$produit->unite,"categorie_id"=>$produit->categorie_id,
                                            "fournisseur_id"=>$produit->fournisseur_id,"reference"=>$produit->reference,
                                            "stock_actuel"=>$produit->stock_actuel,"stock_minimum"=>$produit->stock_minimum,
                                        ]) }})'>
                                    <i class="fas fa-pen"></i>
                                </button>
                                <a href="{{ route('produits.historique', $produit) }}" class="act-btn act-btn-purple" title="Historique"><i class="fas fa-history"></i></a>
                                <div style="width:1px;height:18px;background:#e2e8f0;margin:0 2px"></div>
                                <button type="button" class="act-btn act-btn-red" title="Supprimer" onclick="confirmDelete('delete-{{ $produit->id }}')"><i class="fas fa-trash"></i></button>
                            </div>
                            <form id="delete-{{ $produit->id }}" action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="prod-empty"><i class="fas fa-box-open"></i><p>Aucun produit trouvé</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="prod-table-ft">@include('partials.pagination', ['paginator' => $produits])</div>
    </div>
</div>

{{-- ══ MODAL IMPORT ══ --}}
<div class="pm-overlay" id="modalImport" onclick="if(event.target===this) closeModal('modalImport')">
    <div class="pm-modal" style="max-width:640px">
        <div class="pm-hd">
            <div class="pm-hd-bg mode-create" style="background:linear-gradient(135deg,#0f172a 0%,#115e59 55%,#14b8a6 100%)">
                <div style="position:relative;z-index:1"><p class="pm-title"><i class="fas fa-file-excel mr-2" style="opacity:.7"></i>Importer des produits</p><p class="pm-sub">Fichier Excel (.xlsx / .xls) ou CSV — max 10 Mo</p></div>
                <button type="button" class="pm-close" onclick="closeModal('modalImport')"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="pm-body">
            @if(session('import_errors') && old('_form') === 'import')
                <div class="pm-errors" style="flex-direction:column;gap:6px">
                    <div style="display:flex;align-items:center;gap:8px;font-weight:700;font-size:12px"><i class="fas fa-exclamation-circle"></i>{{ count(session('import_errors')) }} ligne(s) en erreur</div>
                    <ul style="margin:4px 0 0;padding-left:18px;font-size:11px;color:#b91c1c">
                        @foreach(array_slice(session('import_errors'), 0, 8) as $err)<li>{{ $err }}</li>@endforeach
                        @if(count(session('import_errors')) > 8)<li style="color:#94a3b8">… et {{ count(session('import_errors')) - 8 }} autre(s)</li>@endif
                    </ul>
                </div>
            @endif
            <form id="formImport" action="{{ route('produits.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_form" value="import">
                <div class="pf-section">
                    <div class="pf-section-hd"><span class="pf-section-ico" style="background:linear-gradient(135deg,#0f766e,#14b8a6)"><i class="fas fa-upload"></i></span><h6 class="pf-section-title">Fichier à importer</h6></div>
                    <div id="importDropZone" onclick="document.getElementById('importFileInput').click()" style="border:2px dashed #cbd5e1;border-radius:14px;padding:28px 20px;text-align:center;cursor:pointer;transition:.2s;background:#f8fafc;margin-bottom:6px">
                        <i class="fas fa-cloud-upload-alt" style="font-size:32px;color:#94a3b8;display:block;margin-bottom:10px"></i>
                        <div style="font-size:13px;font-weight:700;color:#64748b;margin-bottom:4px">Cliquez ou glissez votre fichier ici</div>
                        <div style="font-size:11px;color:#94a3b8">Formats acceptés : .xlsx, .xls, .csv — max 10 Mo</div>
                        <input type="file" id="importFileInput" name="fichier" accept=".xlsx,.xls,.csv" required style="display:none">
                    </div>
                    <div id="importFileChosen" style="display:none;align-items:center;gap:10px;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:10px;padding:10px 14px;font-size:12px">
                        <i class="fas fa-file-excel" style="color:#059669;font-size:16px"></i>
                        <span id="importFileName" style="font-weight:700;color:#065f46;flex:1"></span>
                        <span id="importFileSize" style="color:#94a3b8;font-size:11px"></span>
                        <button type="button" onclick="resetImportFile()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:12px"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="import-help">
                    <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px"><i class="fas fa-info-circle" style="color:#0f766e;font-size:13px"></i><strong style="font-size:11px;color:#0f766e">Colonnes attendues</strong></div>
                    <div style="font-size:11px;color:#475569;margin-bottom:8px">La <strong>1ère ligne</strong> doit contenir les en-têtes :</div>
                    <div style="display:flex;flex-wrap:wrap;gap:5px">
                        @foreach([['libelle','requis'],['unite','requis'],['categorie_id','requis'],['stock_actuel','requis'],['stock_minimum','requis'],['reference','optionnel'],['code_barre','optionnel'],['fournisseur_id','optionnel']] as [$col,$type])
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700;background:{{ $type==='requis'?'#dbeafe':'#f1f5f9' }};color:{{ $type==='requis'?'#1e40af':'#64748b' }};font-family:monospace">{{ $col }}@if($type==='requis')<span style="color:#ef4444;font-family:sans-serif">*</span>@endif</span>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        <div class="pm-ft">
            <button type="button" class="pm-btn-cancel" onclick="closeModal('modalImport')">Annuler</button>
            <button type="button" id="importSubmitBtn" class="pm-btn-save mode-create" disabled onclick="submitImport()"><i class="fas fa-file-import"></i> Lancer l'import</button>
        </div>
    </div>
</div>

{{-- ══ MODAL CRÉATION ══ --}}
<div class="pm-overlay" id="modalCreate" onclick="if(event.target===this) closeModal('modalCreate')">
    <div class="pm-modal">
        <div class="pm-hd">
            <div class="pm-hd-bg mode-create">
                <div style="position:relative;z-index:1"><p class="pm-title"><i class="fas fa-plus-circle mr-2" style="opacity:.7"></i>Nouveau produit</p><p class="pm-sub">Renseignez les informations du produit</p></div>
                <button type="button" class="pm-close" onclick="closeModal('modalCreate')"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="pm-body">
            @if($errors->any() && old('_form') === 'create')
                <div class="pm-errors"><i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:1px"></i><div><strong>Corrigez les erreurs :</strong><ul style="margin:4px 0 0;padding-left:14px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>
            @endif
            <form id="formCreate" action="{{ route('produits.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_form" value="create">

                {{-- INFOS GÉNÉRALES --}}
                <div class="pf-section">
                    <div class="pf-section-hd"><span class="pf-section-ico" style="background:linear-gradient(135deg,#475569,#64748b)"><i class="fas fa-tag"></i></span><h6 class="pf-section-title">Informations générales</h6></div>
                    <div class="row-2" style="margin-bottom:12px">
                        <div>
                            <label class="pf-label">Libellé <span class="req">*</span></label>
                            <div class="pf-inp-wrap"><div class="pf-ico"><i class="fas fa-box"></i></div><input type="text" name="libelle" class="pf-inp" value="{{ old('libelle') }}" placeholder="Nom du produit" required></div>
                        </div>
                        <div>
                            <label class="pf-label">Unité <span class="req">*</span></label>
                            <div class="pf-inp-wrap"><div class="pf-ico"><i class="fas fa-ruler"></i></div>
                                <select name="unite" class="pf-sel" id="c_unite" required>
                                    @foreach(['pièce','kg','g','litre','ml','boîte','carton','sac','pack','flacon','tube','bouteille','bidon','plaquette','pot'] as $u)
                                        <option value="{{ $u }}" {{ old('unite','pièce')==$u?'selected':'' }}>{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row-3">
                        {{-- Catégorie + création rapide --}}
                        <div>
                            <label class="pf-label">Catégorie <span class="req">*</span> <span class="opt" style="color:#059669"><i class="fas fa-plus-circle"></i> créer à la volée</span></label>
                            <div class="cat-field-wrap">
                                <div class="cat-select-row" id="c_cat_select_row">
                                    <div class="pf-ico"><i class="fas fa-layer-group"></i></div>
                                    <select name="categorie_id" id="c_categorie" class="cat-select-inner" required>
                                        <option value="">-- Choisir --</option>
                                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('categorie_id')==$cat->id?'selected':'' }}>{{ $cat->nom }}</option>@endforeach
                                    </select>
                                    <button type="button" class="btn-add-cat" onclick="toggleQuickCat('c')" title="Créer une catégorie"><i class="fas fa-plus"></i></button>
                                </div>
                                <div class="quick-cat-panel" id="c_quick_cat_panel">
                                    <div class="quick-cat-title"><i class="fas fa-layer-group"></i> Nouvelle catégorie</div>
                                    <div class="quick-cat-fields">
                                        <div>
                                            <div style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:#065f46;margin-bottom:4px">Nom *</div>
                                            <div class="quick-cat-inp-wrap"><input type="text" id="c_new_cat_nom" class="quick-cat-inp" placeholder="Ex: Boissons…" oninput="updateQuickSaveBtn('c')"></div>
                                        </div>
                                        <div>
                                            <div style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:#065f46;margin-bottom:4px">Couleur</div>
                                            <div class="quick-color-wrap" id="c_quick_color_wrap"><input type="color" id="c_new_cat_color" value="#16a34a" oninput="syncQuickColor('c')"><span class="quick-color-txt" id="c_quick_color_txt">#16a34a</span></div>
                                            <div class="quick-swatches" id="c_quick_swatches"></div>
                                        </div>
                                        <div style="display:flex;flex-direction:column;gap:5px;justify-content:flex-end">
                                            <button type="button" class="btn-quick-save" id="c_btn_quick_save" disabled onclick="saveQuickCat('c')"><i class="fas fa-check"></i> Créer</button>
                                            <button type="button" class="btn-quick-cancel" onclick="toggleQuickCat('c')">Annuler</button>
                                        </div>
                                    </div>
                                    <div class="quick-cat-feedback" id="c_quick_cat_feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="pf-label">Fournisseur <span class="opt">(opt.)</span></label>
                            <div class="pf-inp-wrap"><div class="pf-ico"><i class="fas fa-truck"></i></div>
                                <select name="fournisseur_id" class="pf-sel">
                                    <option value="">-- Aucun --</option>
                                    @foreach($fournisseurs as $f)<option value="{{ $f->id }}" {{ old('fournisseur_id')==$f->id?'selected':'' }}>{{ $f->nom }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="pf-label">Référence <span class="opt">(opt.)</span></label>
                            <div class="pf-inp-wrap"><div class="pf-ico"><i class="fas fa-hashtag"></i></div><input type="text" name="reference" class="pf-inp" value="{{ old('reference') }}" placeholder="REF-001"></div>
                        </div>
                        {{-- Code-barres --}}
                        <div style="grid-column: 1 / -1">
                            <label class="pf-label">Code-barres / QR <span class="opt">(opt.)</span></label>
                            <div class="barcode-field-wrap">
                                <div class="barcode-main-row">
                                    <div class="barcode-input-col">
                                        <div class="barcode-input-row" id="c_barcode_input_row">
                                            <div class="pf-ico"><i class="fas fa-barcode"></i></div>
                                            <input type="text" name="code_barre" id="c_code_barre" class="pf-inp" value="{{ old('code_barre') }}" placeholder="Saisir ou scanner le code" oninput="handleBarcodeInput('c', this)">
                                            <button type="button" class="btn-scan" onclick="startScanner('c')" id="c_scan_btn"><i class="fas fa-camera"></i> Scanner</button>
                                        </div>
                                        <div class="barcode-converted-badge" id="c_barcode_converted_badge"><i class="fas fa-magic"></i> Caractères convertis automatiquement</div>
                                        <div class="scanner-box" id="c_scanner_box">
                                            <button type="button" class="scanner-close-btn" onclick="stopScanner('c')"><i class="fas fa-times"></i></button>
                                            <div id="c_reader"></div>
                                            <div class="scanner-line"></div>
                                            <div class="scanner-hint">Pointez la caméra vers le code-barres ou QR</div>
                                        </div>
                                    </div>
                                    <div class="barcode-preview-inline" id="c_barcode_preview">
                                        <div class="barcode-label"><i class="fas fa-barcode" style="font-size:9px"></i> Aperçu</div>
                                        <svg id="c_barcode_svg"></svg>
                                        <div class="barcode-number" id="c_barcode_number"></div>
                                        <div class="barcode-actions">
                                            <button type="button" class="barcode-action-btn" onclick="printBarcode('c')"><i class="fas fa-print"></i></button>
                                            <button type="button" class="barcode-action-btn danger" onclick="clearBarcode('c')"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACHAT & STOCK --}}
                <div class="pf-section">
                    <div class="pf-section-hd"><span class="pf-section-ico" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-warehouse"></i></span><h6 class="pf-section-title">Achat & Stock</h6></div>
                    <div class="row-3">
                        <div><label class="pf-label">Quantité initiale <span class="req">*</span></label><div class="prix-cell"><input type="number" name="stock_actuel" class="prix-num" value="{{ old('stock_actuel',0) }}" step="0.01" min="0" required><span class="prix-sfx" id="c_sfx_stock">u</span></div></div>
                        <div><label class="pf-label">Stock min. alerte <span class="req">*</span></label><div class="prix-cell"><input type="number" name="stock_minimum" class="prix-num" value="{{ old('stock_minimum',0) }}" step="0.01" min="0" required><span class="prix-sfx" id="c_sfx_min">u</span></div></div>
                    </div>
                </div>

                            </form>
        </div>
        <div class="pm-ft">
            <button type="button" class="pm-btn-cancel" onclick="closeModal('modalCreate')">Annuler</button>
            <button type="button" class="pm-btn-save mode-create" onclick="document.getElementById('formCreate').submit()"><i class="fas fa-save"></i> Créer le produit</button>
        </div>
    </div>
</div>

{{-- ══ MODAL ÉDITION ══ --}}
<div class="pm-overlay" id="modalEdit" onclick="if(event.target===this) closeModal('modalEdit')">
    <div class="pm-modal">
        <div class="pm-hd">
            <div class="pm-hd-bg mode-edit">
                <div style="position:relative;z-index:1"><p class="pm-title" id="editModalTitle"><i class="fas fa-edit mr-2" style="opacity:.7"></i>Modifier le produit</p><p class="pm-sub" id="editModalSub">Modification des informations</p></div>
                <button type="button" class="pm-close" onclick="closeModal('modalEdit')"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="pm-body">
            @if($errors->any() && old('_form') === 'edit')
                <div class="pm-errors"><i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:1px"></i><div><strong>Corrigez les erreurs :</strong><ul style="margin:4px 0 0;padding-left:14px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>
            @endif

            {{-- ══════════════════════════════════════════════════
                 CORRECTION BUG PUT :
                 Le formulaire est POST avec @method('PUT') DEDANS.
                 L'action est définie par JS via openEditModal(p).
            ══════════════════════════════════════════════════ --}}
            <form id="formEdit" method="POST" action="{{ old('_edit_id') ? route('produits.update', old('_edit_id')) : '' }}">
                @csrf
                {{-- @method('PUT') injecté ici en dur — ne pas utiliser action vide --}}
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="_edit_id" id="e_edit_id" value="{{ old('_edit_id') }}">

                <div class="stock-info-banner">
    <i class="fas fa-info-circle"></i>
    <span>Stock actuel : <strong id="e_stock_display">—</strong> — Pour modifier le stock :</span>
    <a href="{{ route('stock.entrees') }}" style="display:inline-flex;align-items:center;gap:5px;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;border-radius:8px;padding:4px 10px;font-size:11px;font-weight:800;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#a7f3d0'" onmouseout="this.style.background='#d1fae5'">
        <i class="fas fa-arrow-down" style="font-size:9px"></i> Entrée stock
    </a>
    <a href="{{ route('stock.sorties.store') }}" style="display:inline-flex;align-items:center;gap:5px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:8px;padding:4px 10px;font-size:11px;font-weight:800;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
        <i class="fas fa-arrow-up" style="font-size:9px"></i> Sortie stock
    </a>
</div>

                {{-- INFOS --}}
                <div class="pf-section">
                    <div class="pf-section-hd"><span class="pf-section-ico" style="background:linear-gradient(135deg,#475569,#64748b)"><i class="fas fa-tag"></i></span><h6 class="pf-section-title">Informations générales</h6></div>
                    <div class="row-2" style="margin-bottom:12px">
                        <div>
                            <label class="pf-label">Libellé <span class="req">*</span></label>
                            <div class="pf-inp-wrap mode-edit"><div class="pf-ico"><i class="fas fa-box"></i></div><input type="text" name="libelle" id="e_libelle" class="pf-inp" required></div>
                        </div>
                        <div>
                            <label class="pf-label">Unité <span class="req">*</span></label>
                            <div class="pf-inp-wrap mode-edit"><div class="pf-ico"><i class="fas fa-ruler"></i></div>
                                <select name="unite" id="e_unite" class="pf-sel" required>
                                    @foreach(['pièce','kg','g','litre','ml','boîte','carton','sac','pack','flacon','tube','bouteille','bidon','plaquette','pot'] as $u)
                                        <option value="{{ $u }}">{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row-3">
                        {{-- Catégorie + création rapide --}}
                        <div>
                            <label class="pf-label">Catégorie <span class="req">*</span> <span class="opt" style="color:#d97706"><i class="fas fa-plus-circle"></i> créer à la volée</span></label>
                            <div class="cat-field-wrap">
                                <div class="cat-select-row mode-edit" id="e_cat_select_row">
                                    <div class="pf-ico"><i class="fas fa-layer-group"></i></div>
                                    <select name="categorie_id" id="e_categorie" class="cat-select-inner" required>
                                        @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->nom }}</option>@endforeach
                                    </select>
                                    <button type="button" class="btn-add-cat mode-edit" onclick="toggleQuickCat('e')" title="Créer une catégorie"><i class="fas fa-plus"></i></button>
                                </div>
                                <div class="quick-cat-panel mode-edit" id="e_quick_cat_panel">
                                    <div class="quick-cat-title mode-edit"><i class="fas fa-layer-group"></i> Nouvelle catégorie</div>
                                    <div class="quick-cat-fields">
                                        <div>
                                            <div style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:#92400e;margin-bottom:4px">Nom *</div>
                                            <div class="quick-cat-inp-wrap mode-edit"><input type="text" id="e_new_cat_nom" class="quick-cat-inp" placeholder="Ex: Boissons…" oninput="updateQuickSaveBtn('e')"></div>
                                        </div>
                                        <div>
                                            <div style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:#92400e;margin-bottom:4px">Couleur</div>
                                            <div class="quick-color-wrap mode-edit" id="e_quick_color_wrap"><input type="color" id="e_new_cat_color" value="#d97706" oninput="syncQuickColor('e')"><span class="quick-color-txt" id="e_quick_color_txt">#d97706</span></div>
                                            <div class="quick-swatches" id="e_quick_swatches"></div>
                                        </div>
                                        <div style="display:flex;flex-direction:column;gap:5px;justify-content:flex-end">
                                            <button type="button" class="btn-quick-save mode-edit" id="e_btn_quick_save" disabled onclick="saveQuickCat('e')"><i class="fas fa-check"></i> Créer</button>
                                            <button type="button" class="btn-quick-cancel" onclick="toggleQuickCat('e')">Annuler</button>
                                        </div>
                                    </div>
                                    <div class="quick-cat-feedback" id="e_quick_cat_feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="pf-label">Fournisseur <span class="opt">(opt.)</span></label>
                            <div class="pf-inp-wrap mode-edit"><div class="pf-ico"><i class="fas fa-truck"></i></div>
                                <select name="fournisseur_id" id="e_fournisseur" class="pf-sel">
                                    <option value="">-- Aucun --</option>
                                    @foreach($fournisseurs as $f)<option value="{{ $f->id }}">{{ $f->nom }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="pf-label">Référence <span class="opt">(opt.)</span></label>
                            <div class="pf-inp-wrap mode-edit"><div class="pf-ico"><i class="fas fa-hashtag"></i></div><input type="text" name="reference" id="e_reference" class="pf-inp" placeholder="REF-001"></div>
                        </div>
                        {{-- Code-barres edit --}}
                        <div style="grid-column: 1 / -1">
                            <label class="pf-label">Code-barres / QR <span class="opt">(opt.)</span></label>
                            <div class="barcode-field-wrap">
                                <div class="barcode-main-row">
                                    <div class="barcode-input-col">
                                        <div class="barcode-input-row mode-edit" id="e_barcode_input_row">
                                            <div class="pf-ico"><i class="fas fa-barcode"></i></div>
                                            <input type="text" name="code_barre" id="e_code_barre" class="pf-inp" placeholder="Saisir ou scanner le code" oninput="handleBarcodeInput('e', this)">
                                            <button type="button" class="btn-scan" onclick="startScanner('e')" id="e_scan_btn" style="background:#92400e"><i class="fas fa-camera"></i> Scanner</button>
                                        </div>
                                        <div class="barcode-converted-badge" id="e_barcode_converted_badge"><i class="fas fa-magic"></i> Caractères convertis automatiquement</div>
                                        <div class="scanner-box" id="e_scanner_box">
                                            <button type="button" class="scanner-close-btn" onclick="stopScanner('e')"><i class="fas fa-times"></i></button>
                                            <div id="e_reader"></div>
                                            <div class="scanner-line"></div>
                                            <div class="scanner-hint">Pointez la caméra vers le code-barres ou QR</div>
                                        </div>
                                    </div>
                                    <div class="barcode-preview-inline" id="e_barcode_preview">
                                        <div class="barcode-label"><i class="fas fa-barcode" style="font-size:9px"></i> Aperçu</div>
                                        <svg id="e_barcode_svg"></svg>
                                        <div class="barcode-number" id="e_barcode_number"></div>
                                        <div class="barcode-actions">
                                            <button type="button" class="barcode-action-btn" onclick="printBarcode('e')"><i class="fas fa-print"></i></button>
                                            <button type="button" class="barcode-action-btn danger" onclick="clearBarcode('e')"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STOCK --}}
                <div class="pf-section">
                    <div class="pf-section-hd"><span class="pf-section-ico" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-warehouse"></i></span><h6 class="pf-section-title">Stock</h6></div>
                    <div class="row-2">
                        <div><label class="pf-label">Stock min. alerte <span class="req">*</span></label><div class="prix-cell"><input type="number" name="stock_minimum" id="e_stock_min" class="prix-num" step="0.01" min="0" required><span class="prix-sfx" id="e_sfx_min">u</span></div></div>
                    </div>
                </div>

                            </form>
        </div>
        <div class="pm-ft">
            <button type="button" class="pm-btn-cancel" onclick="closeModal('modalEdit')">Annuler</button>
            <button type="button" class="pm-btn-save mode-edit" onclick="document.getElementById('formEdit').submit()"><i class="fas fa-save"></i> Enregistrer les modifications</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// ════════════════════════════════════════════════
//  MODAL HELPERS
// ════════════════════════════════════════════════
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) {
    if (id==='modalCreate') stopScanner('c');
    if (id==='modalEdit')   stopScanner('e');
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow='';
}
document.addEventListener('keydown', function(e) {
    if (e.key==='Escape') { closeModal('modalImport'); closeModal('modalCreate'); closeModal('modalEdit'); }
});

// ════════════════════════════════════════════════
//  SELECT2 CATÉGORIE
// ════════════════════════════════════════════════
function initCreateCatSelect2(val) {
    if (typeof $==='undefined' || !$.fn.select2) return;
    var $el = $('#c_categorie');
    if ($el.hasClass('select2-hidden-accessible')) $el.select2('destroy');
    $el.select2({ width:'100%', placeholder:'-- Choisir --', allowClear:true, dropdownParent:$('#modalCreate') });
    if (val) $el.val(val).trigger('change.select2');
}
function initEditCatSelect2(val) {
    if (typeof $==='undefined' || !$.fn.select2) return;
    var $el = $('#e_categorie');
    if ($el.hasClass('select2-hidden-accessible')) $el.select2('destroy');
    $el.select2({ width:'100%', placeholder:'-- Choisir --', allowClear:false, dropdownParent:$('#modalEdit') });
    if (val!==undefined && val!==null) $el.val(String(val)).trigger('change.select2');
}

// ════════════════════════════════════════════════
//  OPEN CREATE
// ════════════════════════════════════════════════
function openCreateModal() {
    openModal('modalCreate');
    setTimeout(function() {
        initCreateCatSelect2('{{ old('categorie_id') }}');
        var cb = document.getElementById('c_code_barre');
        if (cb && cb.value) handleBarcodeInput('c', cb);
    }, 50);
    syncCreate();
    buildQuickSwatches('c');
}

var c_unite = document.getElementById('c_unite');
var c_sd = null;
var c_sm = null;

function syncCreate() {
    var u=c_unite?c_unite.value:'u';
    ['c_sfx_stock','c_sfx_min'].forEach(function(id){var el=document.getElementById(id);if(el)el.textContent=u;});
}
if(c_unite)c_unite.addEventListener('change',syncCreate);
if(c_sd)c_sd.addEventListener('input',syncCreate);
if(c_sm)c_sm.addEventListener('input',syncCreate);

// ════════════════════════════════════════════════
//  OPEN EDIT
// ════════════════════════════════════════════════
function openEditModal(p) {
    // ✅ FIX BUG PUT : définir l'action avec l'URL complète
    var form = document.getElementById('formEdit');
    form.action = '{{ url("produits") }}/' + p.id;
    setVal('e_edit_id', p.id);

    document.getElementById('editModalTitle').innerHTML = '<i class="fas fa-edit mr-2" style="opacity:.7"></i>'+p.libelle;
    document.getElementById('editModalSub').textContent = 'Réf: '+(p.reference||'—')+' · Stock : '+p.stock_actuel+' '+p.unite;
    document.getElementById('e_stock_display').textContent = p.stock_actuel+' '+p.unite;

    setVal('e_libelle',p.libelle); setVal('e_reference',p.reference||'');
    setVal('e_stock_min',p.stock_minimum);
    setVal('e_code_barre',p.code_barre||'');
    setSelect('e_unite',p.unite); setSelect('e_fournisseur',p.fournisseur_id||'');

    openModal('modalEdit');
    setTimeout(function() {
        initEditCatSelect2(p.categorie_id);
        syncEdit();
        if (p.code_barre) handleBarcodeInput('e', document.getElementById('e_code_barre'));
        else hideBarcodePreview('e');
    }, 50);
    buildQuickSwatches('e');
}

var e_unite=document.getElementById('e_unite'), e_sd=null, e_sm=null;
function syncEdit() {
    var u=e_unite?e_unite.value:'u';
    var el=document.getElementById('e_sfx_min'); if(el)el.textContent=u;
}
if(e_unite)e_unite.addEventListener('change',syncEdit);
if(e_sd)e_sd.addEventListener('input',syncEdit);
if(e_sm)e_sm.addEventListener('input',syncEdit);

function setVal(id,val){var el=document.getElementById(id);if(el)el.value=(val!==null&&val!==undefined)?val:'';}
function setSelect(id,val){var el=document.getElementById(id);if(!el)return;el.value=val!==null&&val!==undefined?String(val):'';if(el.value!==String(val))el.selectedIndex=0;}

// ════════════════════════════════════════════════
//  ROUVRIR APRÈS ERREUR VALIDATION
// ════════════════════════════════════════════════
@if(session('import_errors') || $errors->any())
    @if(old('_form') === 'import')
        document.addEventListener('DOMContentLoaded',function(){openModal('modalImport');});
    @elseif(old('_form') === 'create')
        document.addEventListener('DOMContentLoaded',function(){openCreateModal();});
    @elseif(old('_form') === 'edit')
        document.addEventListener('DOMContentLoaded',function(){
            openModal('modalEdit');
            setTimeout(function(){initEditCatSelect2('{{ old('categorie_id') }}');},50);
        });
    @endif
@endif

// ════════════════════════════════════════════════
//  RECHERCHE
// ════════════════════════════════════════════════
var searchTimer=null, searchInput=document.getElementById('searchInput');
if(searchInput){
    searchInput.addEventListener('input',function(){clearTimeout(searchTimer);searchTimer=setTimeout(function(){searchInput.closest('form').submit();},3000);});
}

// ════════════════════════════════════════════════
//  DÉTRUIRE SELECT2 FILTRE
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded',function(){
    if(typeof $!=='undefined'&&$.fn.select2){var $fc=$('#filter_categorie_id');if($fc.hasClass('select2-hidden-accessible'))$fc.select2('destroy');}
});

// ════════════════════════════════════════════════
//  CONFIRM DELETE
// ════════════════════════════════════════════════
function confirmDelete(formId){
    if(typeof Swal!=='undefined'){
        Swal.fire({title:'Supprimer ce produit ?',text:'Cette action est irréversible.',icon:'warning',showCancelButton:true,confirmButtonText:'Oui, supprimer',cancelButtonText:'Annuler',confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b'})
        .then(function(r){if(r.isConfirmed)document.getElementById(formId).submit();});
    }else{if(confirm('Supprimer ce produit ?'))document.getElementById(formId).submit();}
}

// ════════════════════════════════════════════════
//  CRÉATION RAPIDE CATÉGORIE
// ════════════════════════════════════════════════
var QS=['#16a34a','#22c55e','#0ea5e9','#2563eb','#7c3aed','#a855f7','#dc2626','#ef4444','#d97706','#f59e0b','#0891b2','#06b6d4','#be185d','#ec4899','#374151','#64748b','#09090b','#1e40af'];
function buildQuickSwatches(p){var c=document.getElementById(p+'_quick_swatches');if(!c)return;var cur=document.getElementById(p+'_new_cat_color').value;c.innerHTML=QS.map(function(col){return'<div class="quick-swatch'+(col.toLowerCase()===cur.toLowerCase()?' selected':'')+'" style="background:'+col+'" onclick="pickQuickSwatch(\''+p+'\',\''+col+'\')"></div>';}).join('');}
function pickQuickSwatch(p,color){document.getElementById(p+'_new_cat_color').value=color;document.getElementById(p+'_quick_color_txt').textContent=color;buildQuickSwatches(p);}
function syncQuickColor(p){var v=document.getElementById(p+'_new_cat_color').value;document.getElementById(p+'_quick_color_txt').textContent=v;buildQuickSwatches(p);}
function toggleQuickCat(p){var panel=document.getElementById(p+'_quick_cat_panel');var isOpen=panel.classList.contains('open');panel.classList.toggle('open');if(!isOpen){document.getElementById(p+'_new_cat_nom').value='';document.getElementById(p+'_btn_quick_save').disabled=true;var fb=document.getElementById(p+'_quick_cat_feedback');fb.className='quick-cat-feedback';fb.textContent='';buildQuickSwatches(p);setTimeout(function(){document.getElementById(p+'_new_cat_nom').focus();},100);}}
function updateQuickSaveBtn(p){document.getElementById(p+'_btn_quick_save').disabled=!document.getElementById(p+'_new_cat_nom').value.trim();}
async function saveQuickCat(p){
    var nom=document.getElementById(p+'_new_cat_nom').value.trim(), color=document.getElementById(p+'_new_cat_color').value;
    var btn=document.getElementById(p+'_btn_quick_save'), fb=document.getElementById(p+'_quick_cat_feedback');
    if(!nom)return; btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Création…'; fb.className='quick-cat-feedback'; fb.textContent='';
    try{
        var res=await fetch('{{ route("categories.store") }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({nom,couleur:color,description:''})});
        var data=await res.json();
        if(!res.ok){var msg=data.message||'Erreur';if(data.errors&&data.errors.nom)msg=data.errors.nom[0];throw new Error(msg);}
        var sel=document.getElementById(p+'_categorie');
        var opt=document.createElement('option');opt.value=data.id;opt.textContent=data.nom||nom;sel.appendChild(opt);
        if(typeof $!=='undefined'&&$.fn.select2){var $sel=$('#'+p+'_categorie');if($sel.hasClass('select2-hidden-accessible'))$sel.append(new Option(data.nom||nom,data.id,true,true)).trigger('change');else sel.value=data.id;}else{sel.value=data.id;}
        fb.innerHTML='<i class="fas fa-check-circle"></i> "'+( data.nom||nom)+'" créée !';fb.className='quick-cat-feedback success';
        setTimeout(function(){toggleQuickCat(p);},1400);
    }catch(err){fb.innerHTML='<i class="fas fa-exclamation-circle"></i> '+err.message;fb.className='quick-cat-feedback error';btn.disabled=false;btn.innerHTML='<i class="fas fa-check"></i> Créer';}
}

// ════════════════════════════════════════════════
//  CODE-BARRES : CONVERSION + SCANNER + APERÇU
// ════════════════════════════════════════════════
var BMAP={'Insert':'0','End':'1','ArrowDown':'2','PageDown':'3','ArrowLeft':'4','Clear':'5','ArrowRight':'6','Home':'7','ArrowUp':'8','PageUp':'9','à':'0','&':'1','é':'2','"':'3',"'":'4','(':'5','-':'6','è':'7','_':'8','ç':'9'};

function normalizeBarcode(raw){
    var r='',was=false;
    for(var i=0;i<raw.length;i++){var ch=raw[i];if(/\d/.test(ch)){r+=ch;}else if(BMAP[ch]!==undefined){r+=BMAP[ch];was=true;}else if(/[A-Za-z]/.test(ch)){var m=mapLetterDigit(ch);if(m!==null){r+=m;was=true;}else r+=ch;}else{was=true;}}
    return{converted:r,wasConverted:was};
}
var LMAP={'A':'2','Z':'2','E':'3','R':'4','T':'5','Y':'6','U':'7','I':'8','O':'9','P':'0','Q':'1','S':'2','D':'3','F':'4','G':'5','H':'6','J':'7','K':'8','L':'9','M':'0','W':'1','X':'2','C':'3','V':'4','B':'5','N':'6'};
function mapLetterDigit(ch){var uc=ch.toUpperCase();return LMAP[uc]!==undefined?LMAP[uc]:null;}

function handleBarcodeInput(p,inp){
    var raw=inp.value, norm=normalizeBarcode(raw);
    if(norm.converted!==raw){var pos=inp.selectionStart;inp.value=norm.converted;try{inp.setSelectionRange(pos,pos);}catch(e){}}
    var badge=document.getElementById(p+'_barcode_converted_badge');if(badge)badge.classList.toggle('visible',norm.wasConverted&&norm.converted.length>0);
    renderBarcodePreview(p);
}

var _scanners={c:null,e:null},_scanning={c:false,e:false};

function startScanner(p){
    if(_scanning[p])return;
    var box=document.getElementById(p+'_scanner_box'), btn=document.getElementById(p+'_scan_btn'), rid=p+'_reader';
    box.classList.add('active');
    if(btn){btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> En cours…';btn.disabled=true;}
    document.getElementById(rid).innerHTML='';
    var qr=new Html5Qrcode(rid);
    _scanners[p]=qr;_scanning[p]=true;
    qr.start({facingMode:'environment'},{fps:12,qrbox:{width:220,height:140},aspectRatio:2.0},
        function(decoded){
            var norm=normalizeBarcode(decoded);
            var inp=document.getElementById(p+'_code_barre');
            if(inp){inp.value=norm.converted;var badge=document.getElementById(p+'_barcode_converted_badge');if(badge)badge.classList.toggle('visible',norm.wasConverted&&norm.converted.length>0);renderBarcodePreview(p);}
            stopScanner(p);flashScanOk(p);
        },function(){}
    ).catch(function(err){_scanning[p]=false;if(btn){btn.innerHTML='<i class="fas fa-camera"></i> Scanner';btn.disabled=false;}box.classList.remove('active');alert('Caméra inaccessible.\n'+(err&&err.message?err.message:'Vérifiez les permissions.'));});
}

function stopScanner(p){
    var box=document.getElementById(p+'_scanner_box'),btn=document.getElementById(p+'_scan_btn');
    if(_scanners[p]&&_scanning[p]){_scanners[p].stop().then(function(){_scanners[p].clear();_scanners[p]=null;_scanning[p]=false;}).catch(function(){_scanners[p]=null;_scanning[p]=false;});}
    if(box)box.classList.remove('active');
    if(btn){btn.innerHTML='<i class="fas fa-camera"></i> Scanner';btn.disabled=false;btn.style.background=(p==='e')?'#92400e':'#0f172a';}
}
function flashScanOk(p){var r=document.getElementById(p+'_barcode_input_row');if(!r)return;r.style.borderColor='#059669';r.style.boxShadow='0 0 0 3px rgba(5,150,105,.2)';setTimeout(function(){r.style.borderColor='';r.style.boxShadow='';},1200);}

function renderBarcodePreview(p){
    var inp=document.getElementById(p+'_code_barre'), preview=document.getElementById(p+'_barcode_preview'), svg=document.getElementById(p+'_barcode_svg'), num=document.getElementById(p+'_barcode_number');
    if(!inp||!preview||!svg||!num)return;
    var val=inp.value.trim();
    if(!val){hideBarcodePreview(p);return;}
    svg.innerHTML='';
    try{JsBarcode(svg,val,{format:'CODE128',lineColor:'#000000',background:'#ffffff',width:1.8,height:50,displayValue:false,margin:8});num.textContent=val;preview.classList.add('visible');}
    catch(e){hideBarcodePreview(p);}
}
function hideBarcodePreview(p){var pr=document.getElementById(p+'_barcode_preview');if(pr)pr.classList.remove('visible');}
function clearBarcode(p){var i=document.getElementById(p+'_code_barre');if(i)i.value='';var b=document.getElementById(p+'_barcode_converted_badge');if(b)b.classList.remove('visible');hideBarcodePreview(p);}
function printBarcode(p){
    var svg=document.getElementById(p+'_barcode_svg'),num=document.getElementById(p+'_barcode_number');
    if(!svg||!num||!num.textContent)return;
    var win=window.open('','_blank','width=400,height=300');if(!win){alert('Autorisez les pop-ups.');return;}
    win.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Code-barres</title><style>*{margin:0;padding:0}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;background:#fff;font-family:"Courier New",monospace}svg{max-width:300px;width:100%}.n{font-size:16px;font-weight:700;letter-spacing:3px;color:#000;margin-top:10px}</style></head><body>'+svg.outerHTML+'<div class="n">'+num.textContent+'</div><script>window.onload=function(){window.print();window.close();}<\/script></body></html>');
    win.document.close();
}

// ════════════════════════════════════════════════
//  IMPORT
// ════════════════════════════════════════════════
var importInput=document.getElementById('importFileInput'), importDropZone=document.getElementById('importDropZone'), importChosen=document.getElementById('importFileChosen'), importName=document.getElementById('importFileName'), importSize=document.getElementById('importFileSize'), importBtn=document.getElementById('importSubmitBtn');
if(importInput){importInput.addEventListener('change',function(){if(this.files[0])showFile(this.files[0]);});}
if(importDropZone){
    importDropZone.addEventListener('dragover',function(e){e.preventDefault();this.style.borderColor='#14b8a6';this.style.background='#f0fdfa';});
    importDropZone.addEventListener('dragleave',function(){this.style.borderColor='#cbd5e1';this.style.background='#f8fafc';});
    importDropZone.addEventListener('drop',function(e){e.preventDefault();this.style.borderColor='#cbd5e1';this.style.background='#f8fafc';var file=e.dataTransfer.files[0];if(file){var dt=new DataTransfer();dt.items.add(file);importInput.files=dt.files;showFile(file);}});
}
function showFile(file){var ext=file.name.split('.').pop().toLowerCase();if(!['xlsx','xls','csv'].includes(ext)){alert('Format non accepté.');return;}if(file.size>10*1024*1024){alert('Fichier trop lourd (max 10 Mo)');return;}var mb=(file.size/1024/1024).toFixed(2);if(importName)importName.textContent=file.name;if(importSize)importSize.textContent=mb+' Mo';if(importChosen)importChosen.style.display='flex';if(importDropZone)importDropZone.style.display='none';if(importBtn){importBtn.disabled=false;importBtn.style.opacity='1';}}
function resetImportFile(){if(importInput)importInput.value='';if(importChosen)importChosen.style.display='none';if(importDropZone)importDropZone.style.display='block';if(importBtn)importBtn.disabled=true;}
function submitImport(){if(importBtn){importBtn.disabled=true;importBtn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Import en cours...';}document.getElementById('formImport').submit();}
function openImportModal(){resetImportFile();openModal('modalImport');}


// ════════════════════════════════════════════════
//  BULK SELECT + DELETE
// ════════════════════════════════════════════════
function toggleAll(cb) {
    document.querySelectorAll('.cb-row').forEach(function(r){ r.checked = cb.checked; });
    updateBulkBar();
}

function updateBulkBar() {
    var checked = document.querySelectorAll('.cb-row:checked');
    var bar = document.getElementById('bulkBar');
    var count = document.getElementById('bulkCount');
    var cbAll = document.getElementById('cbAll');
    var total = document.querySelectorAll('.cb-row').length;

    if (checked.length > 0) {
        bar.classList.add('visible');
        count.textContent = checked.length + ' sélectionné(s)';
    } else {
        bar.classList.remove('visible');
    }
    cbAll.indeterminate = checked.length > 0 && checked.length < total;
    cbAll.checked = checked.length === total && total > 0;
}

function clearSelection() {
    document.querySelectorAll('.cb-row, #cbAll').forEach(function(r){ r.checked = false; });
    document.getElementById('cbAll').indeterminate = false;
    document.getElementById('bulkBar').classList.remove('visible');
}

function confirmBulkDelete() {
    var checked = document.querySelectorAll('.cb-row:checked');
    if (checked.length === 0) return;

    var form = document.getElementById('formBulkDelete');
    // Nettoyer les anciens inputs cachés
    form.querySelectorAll('input[name="ids[]"]').forEach(function(i){ i.remove(); });
    // Ajouter les ids sélectionnés
    checked.forEach(function(cb) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    var msg = 'Supprimer DÉFINITIVEMENT ' + checked.length + ' produit(s) ?\n\nCette action est IRRÉVERSIBLE.';
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Suppression définitive',
            html: '<strong style="color:#dc2626">' + checked.length + ' produit(s)</strong> seront supprimés <strong>définitivement</strong>.<br>Cette action est <strong>irréversible</strong>.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b'
        }).then(function(r){ if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(msg)) form.submit();
    }
}
</script>
@endpush
