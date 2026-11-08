@extends('layouts.app')

@section('title', 'Caisse')
@section('page-title', 'Point de Vente')

@section('breadcrumb')
    <li class="breadcrumb-item active">Caisse</li>
@endsection

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900;1,9..40,400&family=Syne:wght@700;800&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .pos-wrap {
            font-family: 'DM Sans', sans-serif;
            display: grid;
            grid-template-columns: 1fr 330px;
            height: calc(100vh - 118px);
            min-height: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(11, 15, 26, .15);
        }

        .pos-left {
            background: #fff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #f1f5f9;
            overflow: hidden;
            min-height: 0;
        }

        .pos-client-top {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            flex-shrink: 0;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .client-select-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
            min-width: 0;
        }

        .client-select-wrap:focus-within {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .1);
        }

        .cli-ico {
            width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 12px;
            flex-shrink: 0;
        }

        .client-select-wrap:focus-within .cli-ico {
            color: #059669;
        }

        .client-sel {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px 6px 8px 0;
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            min-width: 0;
        }

        .select-arrow {
            padding-right: 8px;
            color: #cbd5e1;
            font-size: 10px;
            flex-shrink: 0;
            pointer-events: none;
        }

        .btn-new-client {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            border-radius: 9px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            cursor: pointer;
            transition: .15s;
        }

        .btn-new-client:hover {
            background: #f0fdf4;
            border-color: #a7f3d0;
        }

        .pos-search-bar {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            background: #fafbff;
            position: relative;
            flex-shrink: 0;
        }

        .pos-search-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 10px;
            align-items: start;
        }

        @media(max-width:768px) {
            .pos-search-grid {
                grid-template-columns: 1fr;
            }
        }

        .pos-search-block {
            position: relative;
            min-width: 0;
        }

        .pos-search-label {
            display: block;
            margin-bottom: 5px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
        }

        .pos-search-wrap {
            display: flex;
            align-items: center;
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: border-color .2s, box-shadow .2s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
        }

        .pos-search-wrap:focus-within {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .1);
        }

        .pos-search-ico {
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 14px;
            flex-shrink: 0;
        }

        .pos-search-wrap:focus-within .pos-search-ico {
            color: #059669;
        }

        .pos-search-inp {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 6px;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            outline: none;
        }

        .pos-search-inp::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }

        .pos-search-kbd {
            margin-right: 10px;
            padding: 2px 7px;
            background: #f1f5f9;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .pos-scan-hint {
            margin-top: 5px;
            font-size: 10px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pos-scan-hint i {
            color: #059669;
        }

        .scan-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            background: #d1fae5;
            color: #065f46;
            font-size: 9px;
            font-weight: 800;
            transition: opacity .3s;
        }

        .scan-indicator.hidden {
            opacity: 0;
        }

        .scan-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #059669;
            animation: scanPulse .6s ease-in-out infinite alternate;
        }

        @keyframes scanPulse {
            from {
                opacity: .3;
                transform: scale(.8);
            }

            to {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        .pos-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 16px 48px rgba(11, 15, 26, .14);
            z-index: 9999;
            display: none;
            overflow: hidden;
            max-height: 340px;
            overflow-y: auto;
        }

        .pos-dropdown.open {
            display: block;
        }

        .pos-res-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f8fafc;
            transition: background .1s;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .pos-res-item:last-child {
            border-bottom: none;
        }

        .pos-res-item:hover,
        .pos-res-item.active {
            background: #f0fdf4;
        }

        .res-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            color: #059669;
            text-transform: uppercase;
        }

        .res-avatar.rupture {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .res-info {
            flex: 1;
            min-width: 0;
        }

        .res-name {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .res-ref {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .res-paliers {
            display: flex;
            gap: 6px;
            margin-top: 5px;
            flex-wrap: wrap;
        }

        .res-pal {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .rp-d {
            background: #d1fae5;
            color: #065f46;
        }

        .rp-m {
            background: #fef9c3;
            color: #854d0e;
        }

        .rp-g {
            background: #dbeafe;
            color: #1e40af;
        }

        .res-stock {
            text-align: right;
            flex-shrink: 0;
        }

        .res-stock-val {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
        }

        .res-stock-lbl {
            font-size: 9px;
            color: #94a3b8;
        }

        .stock-ok {
            color: #059669;
        }

        .stock-low {
            color: #d97706;
        }

        .pos-no-result {
            padding: 18px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        .pos-panier-hd {
            padding: 8px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .pos-panier-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
        }

        .pos-panier-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #059669;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
        }

        .pos-clear-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 7px;
            padding: 4px 9px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
            border: none;
        }

        .pos-clear-btn:hover {
            background: #fee2e2;
        }

        .pos-table-wrap {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
        }

        .pos-table-wrap::-webkit-scrollbar {
            width: 3px;
        }

        .pos-table-wrap::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .pos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pos-table th {
            padding: 8px 12px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #94a3b8;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            z-index: 1;
            white-space: nowrap;
        }

        .pos-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .pos-table tr:last-child td {
            border-bottom: none;
        }

        .pos-table tr:hover td {
            background: #fafbff;
        }

        .prod-name {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }

        .pal-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
            margin-top: 2px;
        }

        .pb-d {
            background: #d1fae5;
            color: #065f46;
        }

        .pb-m {
            background: #fef9c3;
            color: #854d0e;
        }

        .pb-g {
            background: #dbeafe;
            color: #1e40af;
        }

        .qte-ctrl {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            overflow: hidden;
            width: 100px;
        }

        .qte-btn {
            width: 28px;
            height: 30px;
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .1s, color .1s;
            flex-shrink: 0;
        }

        .qte-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .qte-inp {
            flex: 1;
            border: none;
            background: transparent;
            text-align: center;
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            outline: none;
            min-width: 0;
            padding: 0;
        }

        .qte-inp::-webkit-inner-spin-button,
        .qte-inp::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .qte-inp[type=number] {
            -moz-appearance: textfield;
        }

        .prix-cell-val {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            text-align: right;
        }

        .sous-total-val {
            font-size: 13px;
            font-weight: 900;
            color: #059669;
            text-align: right;
        }

        .del-btn {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .del-btn:hover {
            background: #fee2e2;
        }

        .pos-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            padding: 40px;
            text-align: center;
        }

        .pos-empty i {
            font-size: 44px;
            margin-bottom: 12px;
            opacity: .4;
        }

        .pos-empty p {
            font-size: 12px;
            font-weight: 600;
            margin: 0;
        }

        .pos-empty small {
            font-size: 10px;
            margin-top: 5px;
            display: block;
        }

        .pos-right {
            background: #0f172a;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }

        .pos-right::-webkit-scrollbar {
            width: 3px;
        }

        .pos-right::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .15);
            border-radius: 4px;
        }

        .pos-right-hd {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            flex-shrink: 0;
        }

        .pos-right-hd h2 {
            font-family: sans-serif;
            font-size: .95rem;
            color: #fff;
            margin: 0 0 2px;
            font-weight: 800;
        }

        .pos-right-hd p {
            font-size: 10px;
            color: rgba(255, 255, 255, .35);
            margin: 0;
        }

        .pos-totaux {
            padding: 10px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .total-row:last-child {
            margin-bottom: 0;
        }

        .total-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, .45);
            font-weight: 600;
        }

        .total-val {
            font-size: 13px;
            font-weight: 800;
            color: rgba(255, 255, 255, .8);
        }

        .grand-total {
            margin: 10px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #059669, #10b981);
            padding: 12px 16px;
            flex-shrink: 0;
        }

        .gt-lbl {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, .7);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 2px;
        }

        .gt-val {
            font-family: sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .gt-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, .6);
            margin-top: 2px;
        }

        .pos-remise {
            padding: 0 16px 10px;
            flex-shrink: 0;
        }

        .mode-lbl {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: rgba(255, 255, 255, .35);
            margin-bottom: 6px;
        }

        .remise-wrap {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 9px;
            overflow: hidden;
            transition: border-color .2s;
        }

        .remise-wrap:focus-within {
            border-color: rgba(255, 255, 255, .25);
        }

        .remise-ico {
            width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .3);
            font-size: 10px;
        }

        .remise-inp {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px 0;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            outline: none;
        }

        .remise-inp::placeholder {
            color: rgba(255, 255, 255, .25);
            font-weight: 400;
        }

        .remise-sfx {
            padding: 0 10px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, .3);
        }

        .pos-modes {
            padding: 0 16px 10px;
            flex-shrink: 0;
        }

        .mode-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .mode-btn {
            padding: 7px 4px;
            border-radius: 9px;
            border: 1.5px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .05);
            color: rgba(255, 255, 255, .5);
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: .15s;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .mode-btn:hover {
            border-color: rgba(255, 255, 255, .25);
            color: rgba(255, 255, 255, .8);
        }

        .mode-btn.active {
            border-color: #10b981;
            background: rgba(16, 185, 129, .15);
            color: #10b981;
        }

        .mode-btn.active-credit {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .pos-montant {
            padding: 0 16px 10px;
            flex-shrink: 0;
        }

        .montant-wrap {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .08);
            border: 1.5px solid rgba(255, 255, 255, .15);
            border-radius: 11px;
            overflow: hidden;
            transition: .2s;
        }

        .montant-wrap:focus-within {
            border-color: #10b981;
            background: rgba(255, 255, 255, .1);
        }

        .montant-ico {
            width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .35);
            font-size: 12px;
        }

        .montant-inp {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            outline: none;
        }

        .montant-inp::placeholder {
            color: rgba(255, 255, 255, .2);
            font-weight: 400;
            font-size: 11px;
        }

        .montant-sfx {
            padding: 0 12px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, .35);
        }

        .pos-monnaie {
            margin: 0 16px 10px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, .05);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .mon-lbl {
            font-size: 10px;
            color: rgba(255, 255, 255, .4);
            font-weight: 600;
        }

        .mon-val {
            font-size: 14px;
            font-weight: 900;
            color: #fbbf24;
        }

        .pos-monnaie.surplus .mon-val {
            color: #34d399;
        }

        .pos-monnaie.insuffisant .mon-val {
            color: #f87171;
        }

        .pos-attente-btn {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 9px;
            background: rgba(255, 255, 255, .08);
            border: 1.5px solid rgba(255, 255, 255, .15);
            color: rgba(255, 255, 255, .6);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
        }

        .pos-attente-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .9);
        }

        .pos-attente-btn:disabled {
            opacity: .3;
            cursor: not-allowed;
        }

        .attente-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 9px;
            padding: 7px 10px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: background .15s;
        }

        .attente-item:hover {
            background: rgba(255, 255, 255, .1);
        }

        .attente-info {
            flex: 1;
        }

        .attente-num {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, .8);
        }

        .attente-sub {
            font-size: 9px;
            color: rgba(255, 255, 255, .4);
            margin-top: 1px;
        }

        .attente-restore {
            font-size: 9px;
            font-weight: 700;
            color: #10b981;
            background: rgba(16, 185, 129, .15);
            border: none;
            border-radius: 5px;
            padding: 3px 7px;
            cursor: pointer;
            margin-left: 5px;
        }

        .attente-del {
            font-size: 9px;
            font-weight: 700;
            color: #f87171;
            background: rgba(248, 113, 113, .12);
            border: none;
            border-radius: 5px;
            padding: 3px 7px;
            cursor: pointer;
            margin-left: 3px;
        }

        .pos-valider-wrap {
            padding: 8px 16px 14px;
            flex-shrink: 0;
            margin-top: auto;
            position: sticky;
            bottom: 0;
            background: #0f172a;
            border-top: 1px solid rgba(255, 255, 255, .06);
        }

        .pos-valider-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 6px 18px rgba(16, 185, 129, .3);
            font-family: 'DM Sans', sans-serif;
        }

        .pos-valider-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 26px rgba(16, 185, 129, .4);
        }

        .pos-valider-btn:disabled {
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .25);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .pos-valider-btn.credit-mode {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            box-shadow: 0 6px 18px rgba(245, 158, 11, .3);
        }

        @keyframes rowIn {
            from {
                opacity: 0;
                transform: translateX(-8px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        .row-anim {
            animation: rowIn .2s ease both;
        }

        /* Modal client */
        .cat-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s;
            padding: 16px;
        }

        .cat-modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .cat-modal {
            background: #fff;
            border-radius: 18px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 24px 64px rgba(11, 15, 26, .2);
            transform: translateY(20px) scale(.97);
            transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
            overflow: hidden;
            font-family: 'DM Sans', sans-serif;
        }

        .cat-modal-overlay.open .cat-modal {
            transform: translateY(0) scale(1);
        }

        .cat-modal-hd {
            padding: 18px 22px 14px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cat-modal-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .cat-modal-close {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: .15s;
        }

        .cat-modal-close:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        .cat-field {
            margin-bottom: 14px;
        }

        .cat-field label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .cat-field input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12px;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        .cat-field input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .1);
        }

        .btn-cancel {
            padding: 9px 16px;
            border-radius: 9px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: .15s;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
        }

        .btn-save {
            padding: 9px 20px;
            border-radius: 9px;
            border: none;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 185, 129, .3);
            font-family: 'DM Sans', sans-serif;
            transition: opacity .15s;
        }

        .btn-save:hover {
            opacity: .9;
        }

        /* Modal confirmation du TOTAL — avant enregistrement */
        .confirm-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 100000;
            background: rgba(15, 23, 42, .72);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .22s;
            padding: 16px;
        }

        .confirm-modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .confirm-modal {
            background: #fff;
            border-radius: 22px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 32px 90px rgba(0, 0, 0, .35);
            transform: translateY(24px) scale(.96);
            transition: transform .28s cubic-bezier(.34, 1.56, .64, 1);
            overflow: hidden;
            font-family: 'DM Sans', sans-serif;
        }

        .confirm-modal-overlay.open .confirm-modal {
            transform: translateY(0) scale(1);
        }

        .confirm-modal-hd {
            padding: 22px 26px 6px;
            text-align: center;
        }

        .confirm-modal-hd .cm-ico {
            width: 46px;
            height: 46px;
            margin: 0 auto 10px;
            border-radius: 14px;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 19px;
        }

        .confirm-modal-hd .cm-ico.credit {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        .confirm-modal-hd h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .confirm-total-big {
            text-align: center;
            padding: 6px 26px 18px;
        }

        .confirm-total-val {
            font-family: sans-serif;
            font-size: 2.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.05;
        }

        .confirm-total-sub {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 600;
        }

        .confirm-recap {
            margin: 0 26px 20px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 12px 16px;
            border: 1.5px solid #f1f5f9;
        }

        .confirm-recap-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 12.5px;
        }

        .confirm-recap-row+.confirm-recap-row {
            border-top: 1px dashed #e2e8f0;
        }

        .confirm-recap-lbl {
            color: #64748b;
            font-weight: 600;
        }

        .confirm-recap-val {
            color: #0f172a;
            font-weight: 800;
        }

        .confirm-recap-val.pos {
            color: #059669;
        }

        .confirm-recap-val.neg {
            color: #dc2626;
        }

        .confirm-modal-actions {
            display: flex;
            gap: 10px;
            padding: 0 26px 26px;
        }

        .btn-confirm-modif {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: .15s;
        }

        .btn-confirm-modif:hover {
            background: #f1f5f9;
        }

        .btn-confirm-continuer {
            flex: 1.4;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(16, 185, 129, .32);
            font-family: 'DM Sans', sans-serif;
            transition: transform .2s, box-shadow .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .btn-confirm-continuer:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(16, 185, 129, .42);
        }

        .btn-confirm-continuer:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-confirm-continuer.credit-mode {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            box-shadow: 0 6px 18px rgba(245, 158, 11, .32);
        }

        /* Select2 */
        .select2-container--default .select2-selection--single {
            border: none !important;
            background: transparent !important;
            height: auto !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            line-height: 34px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-dropdown {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 12px 40px rgba(11, 15, 26, .12) !important;
            overflow: hidden !important;
        }

        .select2-search--dropdown .select2-search__field {
            border-radius: 7px !important;
            border: 1.5px solid #e2e8f0 !important;
            font-size: 12px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }

        .select2-results__option {
            font-size: 12px !important;
            font-weight: 500 !important;
            padding: 7px 12px !important;
            color: #0f172a !important;
        }

        .select2-results__option--highlighted {
            background: #f0fdf4 !important;
            color: #059669 !important;
        }

        .select2-results__option[aria-selected="true"] {
            background: #ecfdf5 !important;
            color: #059669 !important;
            font-weight: 700 !important;
        }

        @media(max-width:960px) {
            .pos-wrap {
                grid-template-columns: 1fr;
                height: auto;
                min-height: 0;
            }

            .pos-left {
                min-height: 400px;
            }

            .pos-right {
                min-height: 520px;
                overflow-y: visible;
            }

            .pos-valider-wrap {
                position: static;
            }
        }
    </style>
@endpush

@section('content')

    <div class="pos-wrap">

        {{-- GAUCHE --}}
        <div class="pos-left">

            <div class="pos-client-top">
                <div class="client-select-wrap">
                    <div class="cli-ico"><i class="fas fa-user-tag"></i></div>
                    <select id="clientSelect" class="form-control client-sel select2">
                        <option value="">— Client Divers —</option>
                        @foreach ($clients ?? [] as $client)
                            <option value="{{ $client->id }}" data-tel="{{ $client->telephone ?? '' }}"
                                data-nom="{{ $client->nom }}" data-avance="{{ $client->solde_avance }}">
                                {{ $client->nom }} {{ $client->prenom }}
                            </option>
                        @endforeach
                    </select>
                    <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                </div>
                <button type="button" class="btn-new-client" onclick="openModalNouveauClient()" title="Nouveau client">
                    <i class="fas fa-user-plus"></i>
                </button>
            </div>

            <div class="pos-search-bar">
                <div class="pos-search-grid">
                    <div class="pos-search-block">
                        <label class="pos-search-label" for="posSearch">Recherche produit</label>
                        <div class="pos-search-wrap">
                            <div class="pos-search-ico"><i class="fas fa-search"></i></div>
                            <input type="text" id="posSearch" class="pos-search-inp" placeholder="Nom, référence..."
                                autocomplete="off">
                            <span class="pos-search-kbd">F2</span>
                        </div>
                        <div class="pos-dropdown" id="posDropdown"></div>
                    </div>

                    <div class="pos-search-block">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:5px">
                            <label class="pos-search-label" for="posScan" style="margin-bottom:0">Scan code-barres</label>
                            <span class="scan-indicator hidden" id="scanIndicator">
                                <span class="scan-dot"></span> Scan...
                            </span>
                        </div>
                        <div class="pos-search-wrap">
                            <div class="pos-search-ico"><i class="fas fa-barcode"></i></div>
                            <input type="text" id="posScan" class="pos-search-inp" placeholder="Scanner ici..."
                                autocomplete="off" inputmode="numeric" pattern="[0-9a-zA-Z\-]*">
                        </div>
                        <div class="pos-scan-hint">
                            <i class="fas fa-info-circle"></i>
                            <span>Fonctionne même si le pavé numérique est désactivé.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pos-panier-hd">
                <div style="display:flex;align-items:center;gap:7px">
                    <span class="pos-panier-title">Panier</span>
                    <span class="pos-panier-count" id="panierCount">0</span>
                </div>
                <button class="pos-clear-btn" onclick="clearPanier()">
                    <i class="fas fa-trash-alt"></i> Vider
                </button>
            </div>

            <div class="pos-table-wrap" id="panierTableWrap">
                <div class="pos-empty" id="panierEmpty">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Panier vide</p>
                    <small>Recherchez un produit ou scannez un code-barres</small>
                </div>
                <table class="pos-table" id="panierTable" style="display:none">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th style="width:110px;text-align:center">Quantité</th>
                            <th style="width:100px;text-align:right">Prix unit.</th>
                            <th style="width:100px;text-align:right">Sous-total</th>
                            <th style="width:32px"></th>
                        </tr>
                    </thead>
                    <tbody id="panierBody"></tbody>
                </table>
            </div>

        </div>

        {{-- DROITE --}}
        <div class="pos-right">

            <div class="pos-right-hd">
                <h2><i class="fas fa-cash-register mr-2" style="opacity:.6;font-size:.85rem"></i>Paiement</h2>
                <p id="posDateHeure"></p>
            </div>

            <div class="pos-totaux">
                <div class="total-row">
                    <span class="total-lbl">Sous-total</span>
                    <span class="total-val" id="dispSousTotal">0 FCFA</span>
                </div>
                <div class="total-row">
                    <span class="total-lbl">Remise</span>
                    <span class="total-val" id="dispRemise" style="color:#f87171">- 0 FCFA</span>
                </div>
            </div>

            <div class="grand-total">
                <div class="gt-lbl">Total à payer</div>
                <div class="gt-val" id="dispTotal">0 FCFA</div>
                <div class="gt-sub" id="dispNbArticles">0 article(s)</div>
            </div>

            <div class="pos-remise">
                <div class="mode-lbl">Remise</div>
                <div class="remise-wrap">
                    <div class="remise-ico"><i class="fas fa-percent"></i></div>
                    <input type="number" id="inputRemise" class="remise-inp" placeholder="0" min="0"
                        step="1" value="0">
                    <span class="remise-sfx">FCFA</span>
                </div>
            </div>

            <div class="pos-modes">
                <div class="mode-lbl">Mode de paiement</div>
                <div class="mode-grid">
                    <button class="mode-btn active" data-mode="espece" onclick="selectMode('espece',this)">
                        <i class="fas fa-money-bill-wave"></i> Espèce
                    </button>
                    <button class="mode-btn" data-mode="mobile_money" onclick="selectMode('mobile_money',this)">
                        <i class="fas fa-mobile-alt"></i> Mobile Money
                    </button>
                    <button class="mode-btn" data-mode="carte" onclick="selectMode('carte',this)">
                        <i class="fas fa-credit-card"></i> Carte
                    </button>
                    <button class="mode-btn" data-mode="credit" onclick="selectMode('credit',this)">
                        <i class="fas fa-handshake"></i> Crédit
                    </button>
                </div>
            </div>

            <div class="pos-montant" id="montantRecuWrap">
                <div class="mode-lbl">Montant reçu</div>
                <div class="montant-wrap">
                    <div class="montant-ico"><i class="fas fa-coins"></i></div>
                    <input type="number" id="inputMontantRecu" class="montant-inp" placeholder="Saisir montant..."
                        min="0" step="1">
                    <span class="montant-sfx">FCFA</span>
                </div>
            </div>

            <div class="pos-monnaie" id="monnaieBox">
                <span class="mon-lbl"><i class="fas fa-exchange-alt mr-1"></i>Monnaie</span>
                <span class="mon-val" id="dispMonnaie">— FCFA</span>
            </div>

            <div style="padding:0 16px 8px;flex-shrink:0">
                <button class="pos-attente-btn" id="btnAttente" onclick="mettreEnAttente()" disabled>
                    <i class="fas fa-pause-circle"></i> Mettre en attente
                </button>
            </div>

            <div id="attenteList" style="display:none;padding:0 16px 8px;flex-shrink:0"></div>

            <div class="pos-valider-wrap">
                <button class="pos-valider-btn" id="btnValider" disabled onclick="validerVente()">
                    <i class="fas fa-check-circle"></i>
                    <span id="btnValiderTxt">Valider la vente</span>
                </button>
            </div>

        </div>

    </div>


    {{-- MODAL NOUVEAU CLIENT --}}
    <div class="cat-modal-overlay" id="modalNouveauClient" onclick="if(event.target===this)closeModalClient()">
        <div class="cat-modal" style="max-width:400px">
            <div class="cat-modal-hd">
                <h2 class="cat-modal-title">
                    <span
                        style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Nouveau client
                </h2>
                <button type="button" class="cat-modal-close" onclick="closeModalClient()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding:18px 22px">
                <div class="cat-field">
                    <label>Nom <span style="color:#ef4444">*</span></label>
                    <input type="text" id="newClientNom" placeholder="Nom du client">
                </div>
                <div class="cat-field">
                    <label>Numéro IFU</label>
                    <input type="text" id="newClientIfu" placeholder="Numéro IFU">
                </div>
                <div class="cat-field" style="margin-bottom:0">
                    <label>Téléphone</label>
                    <input type="text" id="newClientTel" placeholder="Ex : 97 00 00 00">
                </div>
            </div>
            <div style="padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;gap:8px;justify-content:flex-end">
                <button type="button" class="btn-cancel" onclick="closeModalClient()">Annuler</button>
                <button type="button" class="btn-save" id="btnSaveClient" onclick="enregistrerNouveauClient()">
                    <i class="fas fa-check mr-1"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL CONFIRMATION DU TOTAL — avant enregistrement en base --}}
    <div class="confirm-modal-overlay" id="modalConfirmTotal" onclick="if(event.target===this)fermerConfirmTotal()">
        <div class="confirm-modal">
            <div class="confirm-modal-hd">
                <div class="cm-ico" id="confirmModalIco"><i class="fas fa-check-circle"></i></div>
                <h3 id="confirmModalTitre">Confirmer la vente</h3>
            </div>
            <div class="confirm-total-big">
                <div class="confirm-total-val" id="confirmTotalVal">0 FCFA</div>
                <div class="confirm-total-sub" id="confirmTotalSub">0 article(s)</div>
            </div>
            <div class="confirm-recap" id="confirmRecap"></div>
            <div class="confirm-modal-actions">
                <button type="button" class="btn-confirm-modif" onclick="fermerConfirmTotal()">
                    <i class="fas fa-pencil-alt mr-1"></i> Modifier
                </button>
                <button type="button" class="btn-confirm-continuer" id="btnConfirmContinuer"
                    onclick="confirmerEtEnregistrerVente()">
                    <i class="fas fa-arrow-right"></i> Continuer
                </button>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        // ═══════════════════════════════════════════
        //  STATE
        // ═══════════════════════════════════════════
        let panier = [];
        let nextId = 1;
        let modePaiement = 'espece';
        let clientId = null;
        let searchTimer = null;
        let dropdownIdx = -1;
        let qteTimers = {};
        let facturesEnAttente = [];
        let attenteId = 1;

        // ═══════════════════════════════════════════
        //  HORLOGE
        // ═══════════════════════════════════════════
        function updateClock() {
            const el = document.getElementById('posDateHeure');
            if (!el) return;
            const now = new Date();
            const date = now.toLocaleDateString('fr-FR', {
                weekday: 'long',
                day: '2-digit',
                month: 'long'
            });
            const time = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            el.textContent = date + ' · ' + time;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ═══════════════════════════════════════════
        //  PALIERS PRIX
        // ═══════════════════════════════════════════
        // ═══════════════════════════════════════════
        //  SCAN CODE-BARRES (NumLock OFF compatible)
        // ═══════════════════════════════════════════
        const scanInp = document.getElementById('posScan');
        const scanIndicator = document.getElementById('scanIndicator');

        const KEYCODE_TO_CHAR = {
            'Numpad0': '0',
            'Numpad1': '1',
            'Numpad2': '2',
            'Numpad3': '3',
            'Numpad4': '4',
            'Numpad5': '5',
            'Numpad6': '6',
            'Numpad7': '7',
            'Numpad8': '8',
            'Numpad9': '9',
            'Digit0': '0',
            'Digit1': '1',
            'Digit2': '2',
            'Digit3': '3',
            'Digit4': '4',
            'Digit5': '5',
            'Digit6': '6',
            'Digit7': '7',
            'Digit8': '8',
            'Digit9': '9',
            'KeyA': 'a',
            'KeyB': 'b',
            'KeyC': 'c',
            'KeyD': 'd',
            'KeyE': 'e',
            'KeyF': 'f',
            'KeyG': 'g',
            'KeyH': 'h',
            'KeyI': 'i',
            'KeyJ': 'j',
            'KeyK': 'k',
            'KeyL': 'l',
            'KeyM': 'm',
            'KeyN': 'n',
            'KeyO': 'o',
            'KeyP': 'p',
            'KeyQ': 'q',
            'KeyR': 'r',
            'KeyS': 's',
            'KeyT': 't',
            'KeyU': 'u',
            'KeyV': 'v',
            'KeyW': 'w',
            'KeyX': 'x',
            'KeyY': 'y',
            'KeyZ': 'z',
            'Minus': '-',
            'NumpadSubtract': '-',
        };

        let scanBuffer = '';
        let scanFlushTimer = null;
        let scanIndicatorTimer = null;

        scanInp.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.code === 'NumpadEnter') {
                e.preventDefault();
                clearTimeout(scanFlushTimer);
                const val = scanBuffer.trim() || this.value.trim();
                scanBuffer = '';
                this.value = '';
                hideScanIndicator();
                if (val) processScanValue(val);
                return;
            }
            if (e.key === 'Escape') {
                e.preventDefault();
                scanBuffer = '';
                this.value = '';
                hideScanIndicator();
                clearTimeout(scanFlushTimer);
                return;
            }
            if (e.key === 'Backspace' || e.code === 'Backspace') {
                scanBuffer = scanBuffer.slice(0, -1);
                this.value = scanBuffer;
                e.preventDefault();
                return;
            }
            let ch = KEYCODE_TO_CHAR[e.code] || null;
            if (!ch && e.key && e.key.length === 1 && e.key !== ' ') ch = e.key;
            if (ch) {
                e.preventDefault();
                if (e.shiftKey && ch.match(/[a-z]/)) ch = ch.toUpperCase();
                scanBuffer += ch;
                this.value = scanBuffer;
                showScanIndicator();
                clearTimeout(scanFlushTimer);
                scanFlushTimer = setTimeout(() => {
                    const val = scanBuffer.trim();
                    scanBuffer = '';
                    scanInp.value = '';
                    hideScanIndicator();
                    if (val) processScanValue(val);
                }, 250);
            }
        });

        scanInp.addEventListener('input', function() {
            if (scanBuffer) return;
            const val = this.value.trim();
            if (!val) return;
            clearTimeout(scanFlushTimer);
            scanFlushTimer = setTimeout(() => {
                const v = scanInp.value.trim();
                scanInp.value = '';
                if (v) processScanValue(v);
            }, 500);
        });

        function showScanIndicator() {
            scanIndicator.classList.remove('hidden');
            clearTimeout(scanIndicatorTimer);
        }

        function hideScanIndicator() {
            scanIndicatorTimer = setTimeout(() => scanIndicator.classList.add('hidden'), 400);
        }

        // ═══════════════════════════════════════════
        //  RECHERCHE PRODUIT (AJAX)
        // ═══════════════════════════════════════════
        const searchInp = document.getElementById('posSearch');
        const dropdown = document.getElementById('posDropdown');
        let searchReqId = 0;

        function fetchProduits(q, onSuccess) {
            const reqId = ++searchReqId;
            fetch('{{ route('caisse.recherche') }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if (reqId === searchReqId) onSuccess(data);
                })
                .catch(() => closeDropdown());
        }

        function processScanValue(code) {
            if (!code) return;
            fetchProduits(code, function(data) {
                const exact = data.find(p => (p.code_barre || '') === code || (p.reference || '') === code);
                if (exact) {
                    addProduit(exact);
                    toast('Scanne : ' + exact.libelle, 'success');
                } else if (data.length === 1) {
                    addProduit(data[0]);
                    toast('Ajoute : ' + data[0].libelle, 'success');
                } else {
                    toast('Aucun produit pour ce code : ' + code, 'warning');
                }
            });
        }

        searchInp.addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(searchTimer);
            dropdownIdx = -1;
            if (!q) {
                closeDropdown();
                return;
            }
            searchTimer = setTimeout(() => {
                fetchProduits(q, renderDropdown);
            }, 180);
        });

        searchInp.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('.pos-res-item');
            if (!items.length) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                dropdownIdx = Math.min(dropdownIdx + 1, items.length - 1);
                highlightDropdown(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                dropdownIdx = Math.max(dropdownIdx - 1, 0);
                highlightDropdown(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (dropdownIdx >= 0) items[dropdownIdx].click();
                else if (items.length === 1) items[0].click();
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        function highlightDropdown(items) {
            items.forEach((it, i) => it.classList.toggle('active', i === dropdownIdx));
        }

        function renderDropdown(data) {
            if (!data.length) {
                dropdown.innerHTML =
                    '<div class="pos-no-result"><i class="fas fa-search mr-2"></i>Aucun produit trouve</div>';
                dropdown.classList.add('open');
                return;
            }
            dropdown.innerHTML = data.map(p => {
                const sv = parseFloat(p.stock_actuel);
                const sc = sv <= 0 ? 'rupture' : (sv <= 5 ? 'stock-low' : 'stock-ok');
                const ac = sv <= 0 ? 'res-avatar rupture' : 'res-avatar';
                return `<div class="pos-res-item" onclick='addProduit(${JSON.stringify(p)})'>
            <div class="${ac}">${(p.libelle||'?')[0].toUpperCase()}</div>
            <div class="res-info">
                <div class="res-name">${escHtml(p.libelle)}</div>
                <div class="res-ref">${p.reference ? 'Ref : '+escHtml(p.reference) : ''}</div>
            </div>
            
            </div>`;
            }).join('');
            dropdown.classList.add('open');
        }

        function closeDropdown() {
            dropdown.classList.remove('open');
            dropdown.innerHTML = '';
            dropdownIdx = -1;
        }
        document.addEventListener('click', e => {
            if (!e.target.closest('.pos-search-bar')) closeDropdown();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'F2') {
                e.preventDefault();
                searchInp.focus();
                searchInp.select();
            }
        });

        // ═══════════════════════════════════════════
        //  PANIER
        // ═══════════════════════════════════════════
        function addProduit(produit) {
            closeDropdown();
            searchInp.value = '';
            searchInp.focus();
            if (parseFloat(produit.stock_actuel) <= 0) {
                toast('Rupture de stock.', 'warning');
                return;
            }

            const existing = panier.find(l => l.produit_id === produit.id);
            if (existing) {
                const newQte = existing.quantite + 1;
                if (newQte > parseFloat(produit.stock_actuel)) {
                    toast('Stock max : ' + fmtQte(produit.stock_actuel) + ' ' + produit.unite, 'warning');
                    return;
                }
                existing.quantite = newQte;
                updateLigneDOM(existing.id);
                updateTotaux();
                return;
            }

            const ligne = {
                id: nextId++,
                produit_id: produit.id,
                libelle: produit.libelle,
                unite: produit.unite,
                stock_actuel: parseFloat(produit.stock_actuel),
                quantite: 1,
                prix: null,
            };
            panier.push(ligne);
            appendLigneDOM(ligne);
            updateTotaux();
        }

        function appendLigneDOM(ligne) {
            document.getElementById('panierEmpty').style.display = 'none';
            document.getElementById('panierTable').style.display = '';
            const tbody = document.getElementById('panierBody');
            const tr = document.createElement('tr');
            tr.id = 'row-' + ligne.id;
            tr.className = 'row-anim';
            tr.innerHTML = buildLigneHTML(ligne);
            tbody.appendChild(tr);
        }

        function buildLigneHTML(ligne) {
            const step = isDecimalUnit(ligne.unite) ? 0.25 : 1;
            return `<td>
                <div class="prod-name">${escHtml(ligne.libelle)}</div>
            </td>
            <td style="text-align:center">
                <div class="qte-ctrl">
                    <button class="qte-btn" onclick="changeQte(${ligne.id}, -${step})">-</button>
                    <input type="number" class="qte-inp" id="qte-${ligne.id}"
                        value="${ligne.quantite}" min="0.001" step="0.001"
                        oninput="setQteInput(${ligne.id}, this)" onfocus="this.select()">
                    <button class="qte-btn" onclick="changeQte(${ligne.id}, ${step})">+</button>
                </div>
                <div style="font-size:9px;color:#94a3b8;margin-top:2px">${escHtml(ligne.unite)}</div>
            </td>
            <td><input type="number" class="prix-cell-val" id="prix-${ligne.id}" value="${ligne.prix ?? ''}" min="0.01" step="1" oninput="setPrix(${ligne.id}, this.value)" style="width:110px;text-align:right"></td>
            <td><div class="sous-total-val" id="st-${ligne.id}">${fmt((ligne.prix || 0) * ligne.quantite)} FCFA</div></td>
            <td><button class="del-btn" onclick="removeLigne(${ligne.id})"><i class="fas fa-times"></i></button></td>`;
        }

        function updateLigneDOM(id) {
            const ligne = panier.find(l => l.id === id);
            if (!ligne) return;
            const qteEl = document.getElementById('qte-' + id);
            const prixEl = document.getElementById('prix-' + id);
            const stEl = document.getElementById('st-' + id);
            if (qteEl) qteEl.value = ligne.quantite;
            if (prixEl && document.activeElement !== prixEl) prixEl.value = ligne.prix;
            if (stEl) stEl.textContent = fmt((ligne.prix || 0) * ligne.quantite) + ' FCFA';
        }

        function setPrix(id, value) {
            const ligne = panier.find(l => l.id === id);
            if (!ligne) return;
            ligne.prix = value === '' ? null : Math.max(parseFloat(value) || 0, 0);
            const stEl = document.getElementById('st-' + id);
            if (stEl) stEl.textContent = fmt((ligne.prix || 0) * ligne.quantite) + ' FCFA';
            updateTotaux();
        }

        // ═══════════════════════════════════════════
        //  CHANGEMENT QUANTITE
        // ═══════════════════════════════════════════
        function changeQte(id, delta) {
            const ligne = panier.find(l => l.id === id);
            if (!ligne) return;
            const newQte = ligne.quantite + delta;
            if (newQte <= 0) {
                removeLigne(id);
                return;
            }
            if (newQte > ligne.stock_actuel) {
                toast('Stock max : ' + fmtQte(ligne.stock_actuel) + ' ' + ligne.unite, 'warning');
                return;
            }
            ligne.quantite = newQte;
            updateLigneDOM(id);
            updateTotaux();
        }

        function setQteInput(id, input) {
            clearTimeout(qteTimers[id]);
            const val = parseFloat(input.value);
            if (!isNaN(val) && val > 0) {
                const ligne = panier.find(l => l.id === id);
                const stEl = document.getElementById('st-' + id);
                if (ligne && stEl) stEl.textContent = fmt((ligne.prix || 0) * val) + ' FCFA';
            }
            qteTimers[id] = setTimeout(() => {
                const v = parseFloat(input.value);
                if (!isNaN(v) && v > 0) setQte(id, v);
                else if (!isNaN(v) && v <= 0) removeLigne(id);
            }, 400);
        }

        function setQte(id, val) {
            if (isNaN(val) || val <= 0) {
                removeLigne(id);
                return;
            }
            const ligne = panier.find(l => l.id === id);
            if (!ligne) return;
            if (val > ligne.stock_actuel) {
                toast('Stock max : ' + fmtQte(ligne.stock_actuel) + ' ' + ligne.unite, 'warning');
                val = ligne.stock_actuel;
            }
            ligne.quantite = roundQte(val);
            updateLigneDOM(id);
            updateTotaux();
        }

        function removeLigne(id) {
            panier = panier.filter(l => l.id !== id);
            const tr = document.getElementById('row-' + id);
            if (tr) tr.remove();
            if (!panier.length) {
                document.getElementById('panierEmpty').style.display = '';
                document.getElementById('panierTable').style.display = 'none';
            }
            updateTotaux();
        }

        function clearPanier() {
            panier = [];
            nextId = 1;
            document.getElementById('panierBody').innerHTML = '';
            document.getElementById('panierEmpty').style.display = '';
            document.getElementById('panierTable').style.display = 'none';
            updateTotaux();
        }

        // ═══════════════════════════════════════════
        //  TOTAUX
        // ═══════════════════════════════════════════
        function updateTotaux() {
            const remise = parseFloat(document.getElementById('inputRemise')?.value) || 0;
            const sousTotal = panier.reduce((s, l) => s + (l.prix || 0) * l.quantite, 0);
            const total = Math.max(sousTotal - remise, 0);
            const nbArt = panier.reduce((s, l) => s + l.quantite, 0);

            setText('dispSousTotal', fmt(sousTotal) + ' FCFA');
            setText('dispRemise', '- ' + fmt(remise) + ' FCFA');
            setText('dispTotal', fmt(total) + ' FCFA');
            setText('dispNbArticles', panier.length + ' ligne(s) - ' + fmtQte(nbArt) + ' article(s)');
            setText('panierCount', panier.length);

            updateMonnaie(total);

            const btn = document.getElementById('btnValider');
            const txt = document.getElementById('btnValiderTxt');
            const att = document.getElementById('btnAttente');
            const prixSaisis = panier.length > 0 && panier.every(l => Number(l.prix) > 0);
            if (btn) btn.disabled = !prixSaisis;
            if (att) att.disabled = !prixSaisis;
            if (txt) {
                if (modePaiement === 'credit') {
                    txt.textContent = 'Enregistrer a credit - ' + fmt(total) + ' FCFA';
                    btn?.classList.add('credit-mode');
                } else {
                    txt.textContent = 'Valider - ' + fmt(total) + ' FCFA';
                    btn?.classList.remove('credit-mode');
                }
            }
        }

        function updateMonnaie(total) {
            const recu = parseFloat(document.getElementById('inputMontantRecu').value) || 0;
            const monnaie = recu - total;
            const box = document.getElementById('monnaieBox');
            const el = document.getElementById('dispMonnaie');
            if (modePaiement === 'credit') {
                el.textContent = '--';
                box.className = 'pos-monnaie';
                return;
            }
            if (recu === 0) {
                el.textContent = '-- FCFA';
                box.className = 'pos-monnaie';
            } else if (monnaie < 0) {
                el.textContent = '- ' + fmt(Math.abs(monnaie)) + ' FCFA';
                box.className = 'pos-monnaie insuffisant';
            } else {
                el.textContent = fmt(monnaie) + ' FCFA';
                box.className = 'pos-monnaie surplus';
            }
        }

        function getCurrentTotal() {
            const remise = parseFloat(document.getElementById('inputRemise').value) || 0;
            return Math.max(panier.reduce((s, l) => s + (l.prix || 0) * l.quantite, 0) - remise, 0);
        }

        // ═══════════════════════════════════════════
        //  MODE PAIEMENT
        // ═══════════════════════════════════════════
        function selectMode(mode, btn) {
            modePaiement = mode;
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active', 'active-credit'));
            const wrap = document.getElementById('montantRecuWrap');
            if (mode === 'credit') {
                btn.classList.add('active-credit');
                wrap.style.opacity = '.4';
                wrap.style.pointerEvents = 'none';
            } else {
                btn.classList.add('active');
                wrap.style.opacity = '';
                wrap.style.pointerEvents = '';
            }
            updateTotaux();
        }

        // ═══════════════════════════════════════════
        //  VALIDATION EN 2 ÉTAPES
        //  1) validerVente()              → affiche le total en grand + recap, ne rien enregistrer
        //  2) confirmerEtEnregistrerVente() → appelée seulement au clic sur "Continuer"
        // ═══════════════════════════════════════════
        let ventePendingPayload = null;

        function validerVente() {
            if (!panier.length) return;
            const remise = parseFloat(document.getElementById('inputRemise').value) || 0;
            const montantRecu = parseFloat(document.getElementById('inputMontantRecu').value) || 0;
            const sousTotal = panier.reduce((s, l) => s + (l.prix || 0) * l.quantite, 0);
            const total = Math.max(sousTotal - remise, 0);
            const montantRecuEffectif = modePaiement === 'credit' ? 0 : (montantRecu || total);
            const clientSelect = document.getElementById('clientSelect');
            const selOpt = clientSelect.options[clientSelect.selectedIndex];
            const clientNom = (selOpt && selOpt.value) ? selOpt.dataset.nom : '';
            const avanceDisponible = (selOpt && selOpt.value) ? (parseFloat(selOpt.dataset.avance) || 0) : 0;

            if (modePaiement === 'credit' && !clientId) {
                toast('Sélectionnez un client pour enregistrer un bon à crédit.', 'error');
                clientSelect.focus();
                return;
            }

            if (modePaiement === 'espece' && avanceDisponible <= 0 && montantRecu > 0 && montantRecu < total) {
                toast('Montant recu insuffisant (manque ' + fmt(total - montantRecu) + ' FCFA)', 'error');
                document.getElementById('inputMontantRecu').focus();
                return;
            }

            // On stocke tout ce qu'il faut pour l'enregistrement final, sans rien envoyer au serveur pour l'instant
            ventePendingPayload = {
                remise,
                montantRecu,
                montantRecuEffectif,
                total,
                clientNom,
                clientId,
                avanceDisponible,
                payload: {
                    _token: '{{ csrf_token() }}',
                    operation_reference: (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => { const r = Math.random() * 16 | 0; return (c === 'x' ? r : (r & 3 | 8)).toString(16); }),
                    mode_paiement: modePaiement,
                    montant_recu: montantRecuEffectif,
                    remise: remise,
                    client_nom: clientNom || null,
                    client_id: clientId,
                    produits: panier.map(l => ({
                        id: l.produit_id,
                        quantite: l.quantite,
                        prix_unitaire: l.prix,
                    })),
                },
            };

            ouvrirConfirmTotal(ventePendingPayload);
        }

        function ouvrirConfirmTotal(v) {
            const avanceUtilisee = Math.min(v.avanceDisponible || 0, v.total);
            const montantApresAvance = Math.max(v.total - avanceUtilisee, 0);
            const resteCredit = Math.max(montantApresAvance - v.montantRecuEffectif, 0);
            const isCredit = resteCredit > 0;
            const monnaie = Math.max(v.montantRecuEffectif - montantApresAvance, 0);
            const nbArt = panier.reduce((s, l) => s + l.quantite, 0);

            document.getElementById('confirmModalIco').className = 'cm-ico' + (isCredit ? ' credit' : '');
            document.getElementById('confirmModalIco').innerHTML = isCredit ? '<i class="fas fa-handshake"></i>' :
                '<i class="fas fa-check-circle"></i>';
            document.getElementById('confirmModalTitre').textContent = isCredit ? 'Confirmer la vente a credit' :
                'Confirmer la vente';
            document.getElementById('confirmTotalVal').textContent = fmt(v.total) + ' FCFA';
            document.getElementById('confirmTotalSub').textContent = panier.length + ' ligne(s) - ' + fmtQte(nbArt) +
                ' article(s)';

            let recapHtml = '';
            if (v.clientNom) {
                recapHtml +=
                    `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Client</span><span class="confirm-recap-val">${escHtml(v.clientNom)}</span></div>`;
            }
            recapHtml +=
                `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Mode de paiement</span><span class="confirm-recap-val">${labelModePaiement(modePaiement)}</span></div>`;
            if (v.remise > 0) {
                recapHtml +=
                    `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Remise</span><span class="confirm-recap-val neg">- ${fmt(v.remise)} FCFA</span></div>`;
            }
            if (avanceUtilisee > 0) {
                recapHtml += `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Avance utilisée</span><span class="confirm-recap-val pos">${fmt(avanceUtilisee)} FCFA</span></div>`;
                recapHtml += `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Solde d’avance après achat</span><span class="confirm-recap-val">${fmt(Math.max((v.avanceDisponible || 0) - avanceUtilisee, 0))} FCFA</span></div>`;
            }
            if (v.montantRecuEffectif > 0) {
                recapHtml +=
                    `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Montant recu</span><span class="confirm-recap-val">${fmt(v.montantRecuEffectif)} FCFA</span></div>`;
            }
            if (monnaie > 0) {
                recapHtml +=
                    `<div class="confirm-recap-row"><span class="confirm-recap-lbl">Monnaie a rendre</span><span class="confirm-recap-val pos">${fmt(monnaie)} FCFA</span></div>`;
            }
            if (isCredit) {
                recapHtml +=
                    `<div class="confirm-recap-row"><span class="confirm-recap-lbl">A encaisser plus tard</span><span class="confirm-recap-val neg">${fmt(resteCredit)} FCFA</span></div>`;
            }
            document.getElementById('confirmRecap').innerHTML = recapHtml;

            const btnContinuer = document.getElementById('btnConfirmContinuer');
            btnContinuer.disabled = false;
            btnContinuer.className = 'btn-confirm-continuer' + (isCredit ? ' credit-mode' : '');
            btnContinuer.innerHTML = '<i class="fas fa-arrow-right"></i> Continuer';

            document.getElementById('modalConfirmTotal').classList.add('open');
        }

        function fermerConfirmTotal() {
            document.getElementById('modalConfirmTotal').classList.remove('open');
        }

        function labelModePaiement(mode) {
            return {
                espece: 'Espece',
                mobile_money: 'Mobile Money',
                carte: 'Carte',
                credit: 'Credit'
            } [mode] || mode;
        }

        function confirmerEtEnregistrerVente() {
            if (!ventePendingPayload) return;
            const {
                total,
                montantRecuEffectif
            } = ventePendingPayload;

            const btnContinuer = document.getElementById('btnConfirmContinuer');
            btnContinuer.disabled = true;
            btnContinuer.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

            fetch('{{ route('caisse.valider') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(ventePendingPayload.payload),
                })
                .then(async r => {
                    let data;
                    try {
                        data = await r.json();
                    } catch (error) {
                        throw new Error('Le serveur a renvoyé une réponse invalide (HTTP ' + r.status + ').');
                    }
                    if (!r.ok) {
                        const errors = data.erreurs || Object.values(data.errors || {}).flat();
                        throw new Error(errors.length ? errors.join('\n') : (data.message || 'Erreur HTTP ' + r.status));
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        fermerConfirmTotal();
                        const selectedClient = document.querySelector(`#clientSelect option[value="${ventePendingPayload.clientId}"]`);
                        if (selectedClient) selectedClient.dataset.avance = data.solde_avance || 0;
                        showSuccessModal(data, total, montantRecuEffectif);
                        resetCaisse();
                        ventePendingPayload = null;
                    } else {
                        toast(data.message || "Erreur lors de l'enregistrement", 'error');
                        btnContinuer.disabled = false;
                        btnContinuer.innerHTML = '<i class="fas fa-arrow-right"></i> Continuer';
                    }
                })
                .catch(err => {
                    toast('Erreur : ' + err.message, 'error');
                    btnContinuer.disabled = false;
                    btnContinuer.innerHTML = '<i class="fas fa-arrow-right"></i> Continuer';
                });
        }

        function showSuccessModal(data, total, montantRecu) {
            const monnaie = Number(data.monnaie) || 0;
            const isCredit = Boolean(data.is_credit);
            const avanceUtilisee = Number(data.avance_utilisee) || 0;
            if (typeof Swal === 'undefined') {
                alert('Vente enregistree ! Bon : ' + data.numero);
                return;
            }
            const detailsAvance = avanceUtilisee > 0 ? `<div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid #f1f5f9"><span style="color:#64748b;font-size:12px">Avance utilisée</span><strong>${fmt(avanceUtilisee)} FCFA</strong></div><div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid #f1f5f9"><span style="color:#64748b;font-size:12px">Solde avance</span><strong>${fmt(data.solde_avance)} FCFA</strong></div>` : '';
            Swal.fire({
                icon: isCredit ? 'info' : 'success',
                title: isCredit ? 'Vente a credit' : 'Vente enregistree',
                html: `<div style="font-family:'DM Sans',sans-serif;text-align:left;line-height:1.8">
            <div style="font-size:12px;color:#64748b;margin-bottom:10px">Bon <strong style="color:#0f172a">${data.numero}</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid #f1f5f9">
                <span style="color:#64748b;font-size:12px">Total</span><strong style="font-size:14px">${fmt(total)} FCFA</strong>
            </div>
            ${!isCredit ? `
                ${detailsAvance}
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid #f1f5f9">
                    <span style="color:#64748b;font-size:12px">Recu</span><strong>${fmt(montantRecu)} FCFA</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-top:1px solid #f1f5f9">
                    <span style="color:#64748b;font-size:12px">Monnaie</span><strong style="font-size:16px;color:#059669">${fmt(monnaie)} FCFA</strong>
                </div>` : `
                <div style="padding:8px;background:#fffbeb;border-radius:7px;border:1px solid #fde68a;color:#92400e;font-size:11px;margin-top:6px">
                    ${detailsAvance}Crédit restant : <strong>${fmt(data.reste_a_payer || total)} FCFA</strong>.
                </div>`}
        </div>`,
                showCancelButton: true,
                confirmButtonText: 'Continuer',
                cancelButtonText: 'Imprimer',
                confirmButtonColor: '#10b981',
            }).then(r => {
                if (r.dismiss === Swal.DismissReason.cancel) imprimerTicket(data.facture_id);
            });
        }

        function imprimerTicket(id) {
            const popup = window.open('{{ url('caisse/ticket') }}/' + id, '_blank', 'width=400,height=700');
            if (!popup) {
                toast('Autorisez les popups pour imprimer', 'warning');
                window.open('{{ url('caisse/ticket') }}/' + id, '_blank');
            }
        }

        function resetCaisse() {
            panier = [];
            nextId = 1;
            document.getElementById('panierBody').innerHTML = '';
            document.getElementById('panierEmpty').style.display = '';
            document.getElementById('panierTable').style.display = 'none';
            document.getElementById('inputRemise').value = 0;
            document.getElementById('inputMontantRecu').value = '';
            document.getElementById('clientSelect').value = '';
            if (typeof $ !== 'undefined' && $.fn?.select2) $('#clientSelect').trigger('change.select2');
            clientId = null;
            updateTotaux();
        }

        // ═══════════════════════════════════════════
        //  CLIENT
        // ═══════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof $ !== 'undefined' && $.fn?.select2) {
                $('#clientSelect').select2({
                    placeholder: '— Client Divers —',
                    allowClear: true
                });
                $('#clientSelect').on('change', function() {
                    clientId = this.value || null;
                });
            } else {
                document.getElementById('clientSelect').addEventListener('change', function() {
                    clientId = this.value || null;
                });
            }
        });

        function openModalNouveauClient() {
            ['newClientNom', 'newClientIfu', 'newClientTel'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = '';
                    el.style.borderColor = '';
                }
            });
            document.getElementById('modalNouveauClient').classList.add('open');
            setTimeout(() => document.getElementById('newClientNom').focus(), 250);
        }

        function closeModalClient() {
            document.getElementById('modalNouveauClient').classList.remove('open');
        }

        function enregistrerNouveauClient() {
            const nom = document.getElementById('newClientNom').value.trim();
            const ifu = document.getElementById('newClientIfu').value.trim();
            const tel = document.getElementById('newClientTel').value.trim();
            if (!nom) {
                document.getElementById('newClientNom').style.borderColor = '#fca5a5';
                document.getElementById('newClientNom').focus();
                return;
            }

            const btn = document.getElementById('btnSaveClient');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> En cours...';

            fetch('{{ route('clients.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nom,
                        ifu,
                        telephone: tel,
                        type: 'particulier'
                    }),
                })
                .then(r => {
                    if (!r.ok) return r.text().then(t => {
                        throw new Error('HTTP ' + r.status + ' : ' + t);
                    });
                    return r.json();
                })
                .then(data => {
                    if (data.success) {
                        const opt = new Option(data.client.nom, data.client.id);
                        opt.dataset.nom = data.client.nom;
                        opt.dataset.tel = data.client.telephone ?? '';
                        opt.dataset.avance = data.client.solde_avance ?? 0;
                        document.getElementById('clientSelect').appendChild(opt);
                        document.getElementById('clientSelect').value = data.client.id;
                        clientId = data.client.id;
                        if (typeof $ !== 'undefined' && $.fn?.select2) $('#clientSelect').trigger('change.select2');
                        closeModalClient();
                        toast(data.client.nom + ' ajoute', 'success');
                    } else {
                        toast(data.message || 'Erreur', 'error');
                    }
                })
                .catch(err => toast('Erreur : ' + err.message, 'error'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-1"></i> Enregistrer';
                });
        }

        // ═══════════════════════════════════════════
        //  ATTENTE
        // ═══════════════════════════════════════════
        function mettreEnAttente() {
            if (!panier.length) return;
            const remise = parseFloat(document.getElementById('inputRemise').value) || 0;
            const total = Math.max(panier.reduce((s, l) => s + (l.prix || 0) * l.quantite, 0) - remise, 0);
            const sel = document.getElementById('clientSelect');
            const opt = sel.options[sel.selectedIndex];
            facturesEnAttente.push({
                id: attenteId++,
                panier: JSON.parse(JSON.stringify(panier)),
                remise,
                mode: modePaiement,
                clientId: sel.value || null,
                clientNom: opt?.dataset?.nom || '',
                total,
                nbLignes: panier.length,
                heure: new Date().toLocaleTimeString('fr-FR', {
                    hour: '2-digit',
                    minute: '2-digit'
                }),
            });
            resetCaisse();
            renderAttenteList();
            toast('Vente mise en attente', 'success');
        }

        function restaurerAttente(id) {
            const idx = facturesEnAttente.findIndex(a => a.id === id);
            if (idx === -1) return;
            if (panier.length > 0 && !confirm('Le panier actuel sera mis en attente. Continuer ?')) return;
            if (panier.length > 0) mettreEnAttente();
            const att = facturesEnAttente.splice(idx, 1)[0];
            panier = att.panier;
            nextId = Math.max(...panier.map(l => l.id), 0) + 1;
            modePaiement = att.mode;
            document.getElementById('inputRemise').value = att.remise;
            document.getElementById('panierBody').innerHTML = '';
            document.getElementById('panierEmpty').style.display = 'none';
            document.getElementById('panierTable').style.display = '';
            panier.forEach(ligne => {
                const tbody = document.getElementById('panierBody');
                const tr = document.createElement('tr');
                tr.id = 'row-' + ligne.id;
                tr.innerHTML = buildLigneHTML(ligne);
                tbody.appendChild(tr);
            });
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active', 'active-credit'));
            const mb = document.querySelector(`[data-mode="${att.mode}"]`);
            if (mb) selectMode(att.mode, mb);
            document.getElementById('clientSelect').value = att.clientId || '';
            clientId = att.clientId || null;
            if (typeof $ !== 'undefined' && $.fn?.select2) $('#clientSelect').trigger('change.select2');
            updateTotaux();
            renderAttenteList();
            toast('Vente restauree', 'success');
        }

        function supprimerAttente(id) {
            facturesEnAttente = facturesEnAttente.filter(a => a.id !== id);
            renderAttenteList();
        }

        function renderAttenteList() {
            const container = document.getElementById('attenteList');
            if (!facturesEnAttente.length) {
                container.style.display = 'none';
                return;
            }
            container.style.display = 'block';
            container.innerHTML =
                `<div style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:rgba(255,255,255,.35);margin-bottom:6px">
            <i class="fas fa-pause-circle mr-1"></i>En attente (${facturesEnAttente.length})
        </div>` +
                facturesEnAttente.map(a => `<div class="attente-item">
            <div class="attente-info">
                <div class="attente-num">${escHtml(a.clientNom || 'Client Divers')} - ${fmt(a.total)} FCFA</div>
                <div class="attente-sub">${a.nbLignes} art. - ${a.heure}</div>
            </div>
            <button class="attente-restore" onclick="restaurerAttente(${a.id})"><i class="fas fa-play"></i></button>
            <button class="attente-del"     onclick="supprimerAttente(${a.id})"><i class="fas fa-times"></i></button>
        </div>`).join('');
        }

        // ═══════════════════════════════════════════
        //  EVENEMENTS
        // ═══════════════════════════════════════════
        document.getElementById('inputRemise').addEventListener('input', updateTotaux);
        document.getElementById('inputMontantRecu').addEventListener('input', function() {
            updateMonnaie(getCurrentTotal());
        });

        // ═══════════════════════════════════════════
        //  UTILITAIRES
        // ═══════════════════════════════════════════
        function roundQte(n) {
            return Math.round(n * 1000) / 1000;
        }

        function isDecimalUnit(u) {
            return ['kg', 'g', 'l', 'litre', 'litres', 'm', 'ml', 'cl', 't', 'tonne', 'tonnes'].includes((u || '')
                .toLowerCase().trim());
        }

        function fmtQte(n) {
            const v = parseFloat(n);
            return isNaN(v) ? '0' : v.toLocaleString('fr-FR', {
                maximumFractionDigits: 3
            });
        }

        function fmt(n) {
            return Math.round(n).toLocaleString('fr-FR');
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        }

        function escHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function toast(msg, type) {
            if (typeof toastr !== 'undefined') {
                if (type === 'error') toastr.error(msg);
                else if (type === 'warning') toastr.warning(msg);
                else toastr.success(msg);
            } else alert(msg);
        }
    </script>
@endpush
