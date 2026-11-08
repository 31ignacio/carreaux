<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Seuil MAX de quantité pour chaque palier de prix
            // si qté <= seuil_detail  → prix_detail
            // si qté <= seuil_moyen   → prix_moyen  (si non null)
            // si qté >  seuil_moyen   → prix_gros   (si non null)
            $table->unsignedInteger('seuil_detail')->default(1)->after('prix_achat');
            $table->unsignedInteger('seuil_moyen')->nullable()->after('seuil_detail');
            $table->unsignedInteger('seuil_gros')->nullable()->after('seuil_moyen');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['seuil_detail', 'seuil_moyen', 'seuil_gros']);
        });
    }
};
