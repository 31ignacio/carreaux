{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gamaco') | Gestion de Stock</title>

    {{-- jQuery UI (requis avant AdminLTE) --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Font Awesome 5 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- AdminLTE 3 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    {{-- Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- DataTables Responsive --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <style>
        /* â•â• SIDEBAR â•â• */
        .sidebar-dark-primary {
            background-color: #1a1a2e !important;
        }

        .brand-link {
            background-color: #16213e !important;
            border-bottom: 1px solid #0f3460 !important;
        }

        .nav-sidebar .nav-item .nav-link.active {
            background-color: #868789 !important;
        }

        .nav-sidebar .nav-link:hover {
            background-color: #0f3460 !important;
        }

        /* â•â• GENERAL â•â• */
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .btn {
            border-radius: 6px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .content-wrapper {
            background: #f4f6f9;
        }

        .select2-container {
            width: 100% !important;
        }

        .rupture {
            color: #e74c3c;
            font-weight: bold;
        }

        .stock-faible {
            color: #f39c12;
            font-weight: bold;
        }

        .stock-normal {
            color: #2ecc71;
            font-weight: bold;
        }

        /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        RESPONSIVE â€” MOBILE FIRST
        â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */

        /* â”€â”€ Tablette â”€â”€ */
        @media (max-width: 992px) {
            .small-box h3 {
                font-size: 1.6rem !important;
            }

            .info-box-number {
                font-size: 1.2rem !important;
            }

            .card-header h3 {
                font-size: 1rem !important;
            }
        }

        /* â”€â”€ Mobile â”€â”€ */
        @media (max-width: 768px) {

            /* Layout */
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .content-header {
                padding: 10px 10px 0 10px !important;
            }

            .content {
                padding: 0 10px 10px 10px !important;
            }

            h1.m-0 {
                font-size: 1.1rem !important;
            }

            /* Navbar */
            .main-header.navbar {
                padding: 0 8px !important;
            }

            .navbar-nav .nav-link span.ml-1 {
                display: none;
            }

            .navbar-nav .badge.ml-1 {
                display: none;
            }

            /* Cards */
            .card {
                border-radius: 8px;
                margin-bottom: 12px;
            }

            .card-header {
                padding: 8px 12px !important;
            }

            .card-body {
                padding: 10px !important;
            }

            .card-footer {
                padding: 8px 12px !important;
            }

            /* Small boxes dashboard */
            .small-box {
                margin-bottom: 10px;
                border-radius: 8px;
            }

            .small-box h3 {
                font-size: 1.4rem !important;
            }

            .small-box p {
                font-size: 11px !important;
            }

            .small-box .icon {
                display: none;
            }

            /* Info boxes */
            .info-box {
                margin-bottom: 10px;
            }

            .info-box-number {
                font-size: 1.1rem !important;
            }

            .info-box-icon {
                width: 60px !important;
                font-size: 1.5rem !important;
                line-height: 60px !important;
            }

            .info-box-content {
                padding: 8px 10px !important;
            }

            /* Tableaux */
            .table-responsive {
                border: none !important;
            }

            table.table {
                min-width: 550px;
                font-size: 12px;
            }

            .table td,
            .table th {
                padding: 6px 8px !important;
            }

            /* Boutons dans tableaux */
            .btn-group-sm .btn {
                padding: 3px 6px !important;
                font-size: 11px !important;
            }

            .btn-group {
                flex-wrap: wrap;
                gap: 2px;
            }

            /* Formulaires filtres */
            .form-control {
                font-size: 13px !important;
            }

            label {
                font-size: 12px !important;
            }

            /* Badges */
            .badge {
                font-size: 0.7rem !important;
                padding: 3px 7px !important;
            }

            /* Breadcrumb */
            .breadcrumb {
                font-size: 11px !important;
                padding: 4px 8px !important;
            }

            .breadcrumb-item+.breadcrumb-item::before {
                padding: 0 3px;
            }

            /* Pagination */
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-item {
                margin: 1px;
            }

            .page-link {
                padding: 4px 8px !important;
                font-size: 12px !important;
            }

            /* Footer */
            .main-footer {
                text-align: center;
                font-size: 11px;
            }

            .main-footer .float-right {
                float: none !important;
                display: block !important;
                margin-top: 4px;
            }

            /* Modals */
            .modal-dialog {
                margin: 8px !important;
            }

            .modal-body {
                padding: 12px !important;
            }

            .modal-footer {
                padding: 8px 12px !important;
            }

            /* Caisse */
            .panier-item {
                font-size: 12px;
            }

            #totalBox {
                border-radius: 8px;
            }

            .qty-btn {
                width: 24px !important;
                height: 24px !important;
            }

            /* Select2 */
            .select2-container--bootstrap4 .select2-selection {
                font-size: 13px !important;
            }

            /* Alertes toastr */
            #toast-container {
                top: 60px !important;
                right: 5px !important;
            }

            #toast-container>div {
                width: 280px !important;
            }

            /* Charts */
            canvas {
                max-height: 200px !important;
            }

            /* Progress bars */
            .progress {
                height: 12px !important;
            }
        }

        /* â”€â”€ TrÃ¨s petit mobile (< 400px) â”€â”€ */
        @media (max-width: 400px) {
            table.table {
                min-width: 480px;
                font-size: 11px;
            }

            .small-box h3 {
                font-size: 1.2rem !important;
            }

            .btn {
                font-size: 12px !important;
                padding: 4px 8px !important;
            }

            .card-header h3 {
                font-size: 0.9rem !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @php $alertesCount = \App\Models\Alerte::where('traitee', false)->count(); @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        @if ($alertesCount > 0)
                            <span class="badge badge-danger navbar-badge">{{ $alertesCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">{{ $alertesCount }} alerte(s)</span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('alertes.index') }}" class="dropdown-item dropdown-footer">
                            Voir toutes les alertes
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                        <span class="ml-1">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item text-danger"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-bold"><b>Flexi</b> Stock</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}"
                            class="img-circle elevation-2" alt="User">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block text-white">{{ auth()->user()->nom_complet }}</a>
                        <small class="text-muted">{{ auth()->user()->role_label }}</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Tableau de bord</p>
                            </a>
                        </li>
                        @if (auth()->user()->isAdmin() || auth()->user()->isMagasinier() || auth()->user()->isCaissier())
                            <li class="nav-item">
                                <a href="{{ route('bons-commandes.index') }}"
                                    class="nav-link {{ request()->routeIs('bons-commandes.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-check"></i>
                                    <p>Bons de commande</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <li class="nav-header">GESTION STOCK</li>

                            <li
                                class="nav-item has-treeview {{ request()->routeIs('produits.*', 'categories.*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->routeIs('produits.*', 'categories.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>Produits <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('produits.index') }}"
                                            class="nav-link {{ request()->routeIs('produits.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Liste des produits</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('categories.index') }}"
                                            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Catégories</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (auth()->user()->isAdmin() || auth()->user()->isMagasinier() || auth()->user()->isCaissier())
                            <li class="nav-item has-treeview {{ request()->routeIs('stock.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Stock <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('stock.entrees') }}"
                                            class="nav-link {{ request()->routeIs('stock.entrees*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Entrées</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('stock.sorties') }}"
                                            class="nav-link {{ request()->routeIs('stock.sorties*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Sorties</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <li
                                class="nav-item has-treeview {{ request()->routeIs('inventaires.*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->routeIs('inventaires.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Inventaire <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('inventaires.create') }}"
                                            class="nav-link {{ request()->routeIs('inventaires.create') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Nouvel inventaire</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('inventaires.index') }}"
                                            class="nav-link {{ request()->routeIs('inventaires.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Historique</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('alertes.index') }}"
                                    class="nav-link {{ request()->routeIs('alertes.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bell"></i>
                                    <p>Alertes
                                        @if ($alertesCount > 0)
                                            <span class="badge badge-danger right">{{ $alertesCount }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-header">VENTES</li>
                        @if (auth()->user()->isAdmin() || auth()->user()->isCaissier() || auth()->user()->isCaissierHaut())
                            <li class="nav-item">
                                <a href="{{ route('caisse.index') }}"
                                    class="nav-link {{ request()->routeIs('caisse.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cash-register"></i>
                                    <p>Caisse</p>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->isAdmin() || auth()->user()->isMagasinier() || auth()->user()->isCaissier())
                            <li class="nav-item">
                                <a href="{{ route('bons-livraison.index') }}"
                                    class="nav-link {{ request()->routeIs('bons-livraison.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p>Bons de livraison</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->isAdmin() || auth()->user()->isSuperviseur() || auth()->user()->isCaissier() || auth()->user()->isCaissierHaut())

                            <li class="nav-item">
                                <a href="{{ route('credits.index') }}"
                                    class="nav-link {{ request()->routeIs('credits.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-hand-holding-usd"></i>
                                    <p>Crédits par client</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('depenses.index') }}"
                                    class="nav-link {{ request()->routeIs('depenses.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>Dépenses</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('remboursements.index') }}"
                                    class="nav-link {{ request()->routeIs('remboursements.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>Remboursements</p>
                                </a>
                            </li>

                        @endif
                        @if (auth()->user()->isAdmin())

                            <li class="nav-header">ADMINISTRATION</li>

                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}"
                                    class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Clients</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('fournisseurs.index') }}"
                                    class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Fournisseurs</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('rapports.index') }}"
                                    class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>Rapports</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Utilisateurs</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
        </aside>

        {{-- Content --}}
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Accueil</a>
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    @include('partials.alerts')
                    @yield('content')
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>AriExpertiz &copy; {{ date('Y') }}</strong>
            <div class="float-right"><b>Version</b> 1.0.0</div>
        </footer>

    </div>

    {{-- âœ… SCRIPTS - jQuery est dÃ©jÃ  chargÃ© dans le HEAD --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- âœ… AdminLTE 3 JS via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    {{-- Autres plugins --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

    <script>
        //  Toastr notifications 
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 4000,
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
        @if ($errors->any())
            toastr.error(@json($errors->first()));
        @endif

        $(document).ready(function() {

            // â”€â”€ Select2 â”€â”€
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // RESPONSIVE AUTO â€” rend tous les
            //    tableaux scrollables sur mobile
            if ($(window).width() <= 768) {
                $('table.table').each(function() {
                    if (!$(this).parent().hasClass('table-responsive')) {
                        $(this).wrap('<div class="table-responsive"></div>');
                    }
                });
            }

            // âœ… Formulaires filtres â€” col responsive auto
            $('.card-body .row .form-control, .card-body .row .btn').each(function() {
                const $col = $(this).closest('[class*="col-md-"]');
                if ($col.length && !$col.hasClass('col-12')) {
                    $col.addClass('col-12');
                }
            });

            // âœ… Dashboard small-boxes â€” 2 par ligne sur mobile
            if ($(window).width() <= 576) {
                $('.small-box').closest('[class*="col-md-"]').each(function() {
                    if (!$(this).hasClass('col-6')) {
                        $(this).addClass('col-6');
                    }
                });
            }

            // âœ… Boutons action dans tableaux â€” icÃ´nes seules sur mobile
            if ($(window).width() <= 768) {
                $('.btn-group-sm .btn').each(function() {
                    const $icon = $(this).find('i');
                    const text = $(this).clone().children().remove().end().text().trim();
                    if ($icon.length && text) {
                        $(this).html($icon[0].outerHTML);
                        $(this).attr('title', text);
                    }
                });
            }

            // âœ… Tables avec DataTables â€” responsive activÃ©
            if ($.fn.DataTable) {
                $('table.dataTable, #tableStock').each(function() {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                            },
                            pageLength: 25,
                        });
                    }
                });
            }

            // âœ… Modals â€” fermeture par swipe sur mobile
            if ($(window).width() <= 768) {
                let touchStartY = 0;
                $(document).on('touchstart', '.modal', function(e) {
                    touchStartY = e.originalEvent.changedTouches[0].clientY;
                });
                $(document).on('touchend', '.modal', function(e) {
                    const touchEndY = e.originalEvent.changedTouches[0].clientY;
                    if (touchEndY - touchStartY > 80) {
                        $(this).modal('hide');
                    }
                });
            }
        });

        // â”€â”€ Confirm delete â”€â”€
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Etes-vous sûr ?',
                text: 'Cette action est irréversible !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
