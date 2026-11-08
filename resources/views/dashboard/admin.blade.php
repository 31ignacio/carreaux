<section aria-label="Indicateurs commerciaux">
    <div class="sales-grid">
        <a href="{{ route('rapports.ventes') }}" class="sales-card">
            <span class="finance-icon"><i class="fas fa-chart-line"></i></span>
            <span>
                <span class="sales-value d-block">{{ number_format($caJourCaissier, 0, ',', ' ') }} <small>FCFA</small></span>
                <span class="sales-label">Chiffre d’affaires du jour</span>
            </span>
        </a>
        <a href="{{ route('rapports.ventes') }}" class="sales-card">
            <span class="finance-icon"><i class="fas fa-calendar-week"></i></span>
            <span>
                <span class="sales-value d-block">{{ number_format($caSemaineTotal, 0, ',', ' ') }} <small>FCFA</small></span>
                <span class="sales-label">Chiffre d’affaires de la semaine</span>
            </span>
        </a>
        <a href="{{ route('rapports.ventes') }}" class="sales-card">
            <span class="finance-icon"><i class="fas fa-calendar-alt"></i></span>
            <span>
                <span class="sales-value d-block">{{ number_format($caMois, 0, ',', ' ') }} <small>FCFA</small></span>
                <span class="sales-label">Chiffre d’affaires du mois</span>
            </span>
        </a>
        <div class="sales-card">
            <span class="finance-icon"><i class="fas fa-box-open"></i></span>
            <span>
                <span class="sales-value d-block">{{ number_format($totalProduits, 0, ',', ' ') }}</span>
                <span class="sales-label">Produits actifs</span>
            </span>
        </div>
    </div>

    <div class="dashboard-panels">
        <section class="pd-card" aria-labelledby="salesChartTitle">
            <header class="pd-card-head">
                <span class="pd-card-ico"><i class="fas fa-chart-bar"></i></span>
                <h3 class="pd-card-title" id="salesChartTitle">Chiffre d’affaires — 7 derniers jours</h3>
            </header>
            <div class="chart-wrap">
                <div style="height:230px">
                    <canvas id="caChart" role="img" aria-label="Graphique du chiffre d’affaires des sept derniers jours"></canvas>
                </div>
            </div>
        </section>

        <section class="pd-card" aria-labelledby="topProductsTitle">
            <header class="pd-card-head">
                <span class="pd-card-ico"><i class="fas fa-award"></i></span>
                <h3 class="pd-card-title" id="topProductsTitle">Meilleures ventes du mois</h3>
            </header>
            @forelse ($topProduits as $index => $produit)
                <div class="top-item">
                    <span class="top-rank">{{ $index + 1 }}</span>
                    <span class="top-name">{{ $produit->libelle }}</span>
                    <span class="top-qty">{{ number_format($produit->total_vendu, 0, ',', ' ') }} u.</span>
                </div>
            @empty
                <p class="text-muted text-center px-3 py-4 mb-0">Aucune vente enregistrée ce mois.</p>
            @endforelse
        </section>
    </div>
</section>
