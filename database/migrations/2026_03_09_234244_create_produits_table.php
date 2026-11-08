<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained()->onDelete('restrict');
            $table->foreignId('fournisseur_id')->nullable()->constrained()->onDelete('set null');
            $table->string('libelle');
            $table->string('reference')->unique()->nullable();
            $table->string('code_barre')->unique()->nullable();
            $table->string('unite')->default('pièce');

            // Les 3 prix
            $table->decimal('prix_detail', 12, 2)->default(0)->nullable();
            $table->decimal('prix_moyen', 12, 2)->default(0)->nullable();
            $table->decimal('prix_gros', 12, 2)->default(0)->nullable();
            $table->decimal('prix_achat', 12, 2)->default(0)->nullable();

            // Stock
            $table->decimal('stock_actuel', 12, 2)->default(0);
            $table->decimal('stock_minimum', 12, 2)->default(0);
            $table->decimal('stock_maximum', 12, 2)->nullable();

            $table->boolean('actif')->default(true);
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->index('libelle');
            $table->index('code_barre');
            $table->index('categorie_id');
            $table->index('stock_actuel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};