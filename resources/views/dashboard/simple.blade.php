@push('styles')
    <style>
        /* ══════════════════════════════════════
           DASHBOARD CARDS
            ══════════════════════════════════════ */
        .dg-section {
            margin-bottom: 2rem;
        }

        .dg-section-hd {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .dg-section-hd::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .dg-badge {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-caissier {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-superviseur {
            background: #ede9fe;
            color: #5b21b6;
            border: 1px solid #c4b5fd;
        }

        /* Grille */
        .dg-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        /* Carte */
        .dg-card {
            position: relative;
            flex: 1 1 280px;
            /* largeur minimum */
            max-width: 340px;
            /* largeur max */
            border-radius: 24px;
            padding: 38px 28px 32px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 18px;
            text-decoration: none;
            overflow: hidden;
            cursor: pointer;
            transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), box-shadow .25s ease;
        }

        .dg-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity .3s;
            border-radius: 20px;
        }

        .dg-card:hover {
            transform: translateY(-6px) scale(1.03);
            text-decoration: none;
        }

        .dg-card:hover::before {
            opacity: 1;
        }

        .dg-card:active {
            transform: translateY(-2px) scale(.98);
        }

        /* Cercles décoratifs */
        .dg-card .deco {
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .10);
            right: -20px;
            bottom: -20px;
            pointer-events: none;
        }

        .dg-card .deco2 {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            right: 28px;
            bottom: 28px;
            pointer-events: none;
        }

        /* Icône */
        .dg-ico {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            z-index: 1;
        }

        .dg-card:hover .dg-ico {
            transform: rotate(-8deg) scale(1.15);
        }

        /* Texte */
        .dg-label {
            font-size: 20px;
            font-weight: 700;
            color: rgba(255, 255, 255, .95);
            z-index: 1;
            letter-spacing: .01em;
        }

        .dg-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, .6);
            font-weight: 500;
            margin-top: -8px;
            z-index: 1;
        }

        /* Couleurs */
        .c-caisse {
            background: linear-gradient(135deg, #059669, #047857);
            box-shadow: 0 8px 28px rgba(5, 150, 105, .35);
        }

        .c-caisse::before {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .c-facture {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 8px 28px rgba(37, 99, 235, .35);
        }

        .c-facture::before {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .c-alerte {
            background: linear-gradient(135deg, #d97706, #b45309);
            box-shadow: 0 8px 28px rgba(217, 119, 6, .35);
        }

        .c-alerte::before {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .c-produit {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            box-shadow: 0 8px 28px rgba(124, 58, 237, .35);
        }

        .c-produit::before {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .c-entree {
            background: linear-gradient(135deg, #16a34a, #15803d);
            box-shadow: 0 8px 28px rgba(22, 163, 74, .35);
        }

        .c-entree::before {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .c-sortie {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            box-shadow: 0 8px 28px rgba(220, 38, 38, .35);
        }

        .c-sortie::before {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .c-mouvement {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            box-shadow: 0 8px 28px rgba(8, 145, 178, .35);
        }

        .c-mouvement::before {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        /* Point pulse (alertes) */
        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 14px;
            right: 14px;
            animation: pulse 1.8s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, .6);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
            }
        }

        /* Ripple */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            transform: scale(0);
            animation: rippleAnim .55s linear;
            pointer-events: none;
            z-index: 10;
        }

        @keyframes rippleAnim {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Anim entrée */
        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .dg-card {
            animation: cardIn .4s ease both;
        }

        .dg-section {
            padding: 16px;
            border: 1px solid #e7edf4;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(28, 48, 74, .06);
        }

        .dg-wrap {
            gap: 12px;
        }

        .dg-card {
            flex: 1 1 220px;
            max-width: none;
            min-height: 88px;
            flex-direction: row;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border-radius: 14px;
            box-shadow: 0 5px 16px rgba(28, 48, 74, .12);
        }

        .dg-card:hover {
            transform: translateY(-2px);
        }

        .dg-card .dg-ico {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            font-size: 18px;
        }

        .dg-card .dg-label {
            font-size: 16px;
        }

        .dg-card .dg-sub {
            margin-top: 2px;
            font-size: 11px;
        }

        .dg-card.c-caisse,
        .dg-card.c-facture,
        .dg-card.c-alerte,
        .dg-card.c-produit,
        .dg-card.c-entree,
        .dg-card.c-sortie,
        .dg-card.c-mouvement {
            background: linear-gradient(135deg, #315b8a, #23466f);
            box-shadow: 0 5px 16px rgba(35, 70, 111, .16);
        }

        @media (max-width: 575px) {
            .dg-section {
                padding: 12px;
            }

            .dg-card {
                flex-basis: 100%;
            }
        }
    </style>
@endpush


{{-- ══════════════════════════════════════
     SECTION CAISSIER
══════════════════════════════════════ --}}
@if (auth()->user()->isCaissier() || auth()->user()->isCaissierHaut())
    <div class="dg-section mt-3">

        <div class="dg-section-hd">
            <span class="dg-badge badge-caissier">
                <i class="fas fa-circle" style="font-size:6px"></i> Caissier
            </span>
        </div>

        <div class="dg-wrap">

            <a href="{{ route('caisse.index') }}" class="dg-card c-caisse" style="animation-delay:.05s">
                <div class="dg-ico"><i class="fas fa-cash-register"></i></div>
                <div>
                    <div class="dg-label">Caisse</div>
                    <div class="dg-sub">Point de vente</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>
            <a href="{{ route('bons-livraison.index') }}" class="dg-card c-facture" style="animation-delay:.10s">
                <div class="dg-ico"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <div class="dg-label">Bons</div>
                    <div class="dg-sub">Historique</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>
            <a href="{{ route('bons-commandes.index') }}" class="dg-card c-facture" style="animation-delay:.14s">
                <div class="dg-ico"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <div class="dg-label">Bons de commande</div>
                    <div class="dg-sub">Commandes du jour</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>



        </div>
    </div>
@endif

@if (auth()->user()->isMagasinier())
    <div class="dg-section mt-3">
        <div class="dg-section-hd"><span class="dg-badge badge-superviseur">Magasin</span></div>
        <div class="dg-wrap">
            <a href="{{ route('bons-commandes.index') }}" class="dg-card c-facture">
                <div class="dg-ico"><i class="fas fa-clipboard-list"></i></div>
                <div><div class="dg-label">Bons de commande</div><div class="dg-sub">Commandes du jour</div></div>
                <div class="deco"></div><div class="deco2"></div>
            </a>
            <a href="{{ route('bons-livraison.index') }}" class="dg-card c-facture">
                <div class="dg-ico"><i class="fas fa-file-invoice"></i></div>
                <div><div class="dg-label">Bons de livraison</div><div class="dg-sub">Historique des ventes</div></div>
                <div class="deco"></div><div class="deco2"></div>
            </a>
            <a href="{{ route('stock.entrees') }}" class="dg-card c-entree">
                <div class="dg-ico"><i class="fas fa-arrow-circle-down"></i></div>
                <div><div class="dg-label">Entrées de stock</div><div class="dg-sub">Consulter et enregistrer</div></div>
                <div class="deco"></div><div class="deco2"></div>
            </a>
            <a href="{{ route('stock.sorties') }}" class="dg-card c-sortie">
                <div class="dg-ico"><i class="fas fa-arrow-circle-up"></i></div>
                <div><div class="dg-label">Sorties de stock</div><div class="dg-sub">Consulter les mouvements</div></div>
                <div class="deco"></div><div class="deco2"></div>
            </a>
        </div>
    </div>
@endif


{{-- ══════════════════════════════════════
     SECTION SUPERVISEUR
══════════════════════════════════════ --}}
@if (auth()->user()->isSuperviseur())
    <div class="dg-section mt-3">
        <div class="sales-grid">
            <a href="{{ route('bons-livraison.index') }}" class="sales-card">
                <span class="finance-icon"><i class="fas fa-coins"></i></span>
                <span>
                    <span class="sales-value d-block">{{ number_format($caJourCaissier, 0, ',', ' ') }} <small>FCFA</small></span>
                    <span class="sales-label">Chiffre d’affaires du jour</span>
                </span>
            </a>
        </div>

        {{-- <div class="dg-section-hd">
        <span class="dg-badge badge-superviseur">
            <i class="fas fa-circle" style="font-size:10px"></i> Superviseur
        </span>
    </div> --}}

        <div class="dg-wrap">

            {{-- <a href="{{ route('produits.index') }}" class="dg-card c-produit" style="animation-delay:.20s">
            <div class="dg-ico"><i class="fas fa-barcode"></i></div>
            <div>
                <div class="dg-label">Produits</div>
                <div class="dg-sub">Catalogue</div>
            </div>
            <div class="deco"></div><div class="deco2"></div>
        </a> --}}


            <a href="{{ route('stock.entrees') }}" class="dg-card c-entree" style="animation-delay:.25s">
                <div class="dg-ico"><i class="fas fa-arrow-circle-down"></i></div>
                <div>
                    <div class="dg-label">Entrées</div>
                    <div class="dg-sub">Stock reçu</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>

            <a href="{{ route('stock.sorties') }}" class="dg-card c-sortie" style="animation-delay:.30s">
                <div class="dg-ico"><i class="fas fa-arrow-circle-up"></i></div>
                <div>
                    <div class="dg-label">Sorties</div>
                    <div class="dg-sub">Stock sorti</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>

            <a href="{{ route('stock.mouvements') }}" class="dg-card c-mouvement" style="animation-delay:.35s">
                <div class="dg-ico"><i class="fas fa-exchange-alt"></i></div>
                <div>
                    <div class="dg-label">Mouvements</div>
                    <div class="dg-sub">Historique</div>
                </div>
                <div class="deco"></div>
                <div class="deco2"></div>
            </a>

        </div>
    </div>
@endif


@push('scripts')
    <script>
        // ── Effet ripple au clic ──
        document.querySelectorAll('.dg-card').forEach(card => {
            card.addEventListener('click', function(e) {
                const r = document.createElement('span');
                r.className = 'ripple';
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                r.style.cssText = `width:${size}px;height:${size}px;` +
                    `left:${e.clientX - rect.left - size / 2}px;` +
                    `top:${e.clientY  - rect.top  - size / 2}px`;
                this.appendChild(r);
                r.addEventListener('animationend', () => r.remove());
            });
        });

    </script>
@endpush
