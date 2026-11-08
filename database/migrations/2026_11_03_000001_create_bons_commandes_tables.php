<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bons_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('statut', 20)->default('attente');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['statut', 'created_at']);
        });

        Schema::create('bon_commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_commande_id')->constrained('bons_commandes')->cascadeOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('libelle');
            $table->string('unite', 50)->nullable();
            $table->decimal('quantite_commandee', 12, 2);
            $table->timestamps();
            $table->index(['bon_commande_id', 'produit_id']);
        });

        Schema::create('livraisons_commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_commande_id')->constrained('bons_commandes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->boolean('complete')->default(false);
            $table->timestamp('livree_le');
            $table->timestamps();
            $table->index(['bon_commande_id', 'livree_le']);
        });

        Schema::create('livraison_commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livraison_id')->constrained('livraisons_commande')->cascadeOnDelete();
            $table->foreignId('bon_commande_ligne_id')->constrained('bon_commande_lignes')->restrictOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('libelle');
            $table->string('unite', 50)->nullable();
            $table->decimal('quantite', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livraison_commande_lignes');
        Schema::dropIfExists('livraisons_commande');
        Schema::dropIfExists('bon_commande_lignes');
        Schema::dropIfExists('bons_commandes');
    }
};
