<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('fournisseur_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('facture_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie', 'ajustement', 'retour', 'perte']);
            $table->decimal('quantite', 12, 2);
            $table->decimal('prix_unitaire', 12, 2)->default(0);
            $table->decimal('stock_avant', 12, 2);
            $table->decimal('stock_apres', 12, 2);
            $table->string('motif')->nullable();
            $table->string('reference_doc')->nullable();
            $table->timestamps();

            $table->index(['produit_id', 'type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};