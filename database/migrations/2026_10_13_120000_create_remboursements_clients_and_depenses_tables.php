<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('remboursements_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->decimal('montant', 12, 2);
            $table->enum('mode_paiement', ['espece', 'mobile_money', 'carte']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['client_id', 'created_at']);
        });

        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('categorie', 100)->index();
            $table->decimal('montant', 12, 2);
            $table->date('date_depense')->index();
            $table->enum('mode_paiement', ['espece', 'mobile_money', 'carte']);
            $table->string('beneficiaire')->nullable();
            $table->text('observations')->nullable();
            $table->string('justificatif')->nullable();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('annulee_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('annulee_at')->nullable();
            $table->text('motif_annulation')->nullable();
            $table->timestamps();
        });

        Schema::create('depense_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depense_id')->constrained('depenses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->json('avant');
            $table->json('apres');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depense_historiques');
        Schema::dropIfExists('depenses');
        Schema::dropIfExists('remboursements_clients');
    }
};
