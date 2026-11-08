<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('numero')->unique();
            $table->string('client_nom')->nullable();
            $table->string('client_telephone')->nullable();
            $table->enum('statut', ['en_cours', 'payee', 'annulee'])->default('en_cours');
            $table->enum('mode_paiement', ['espece', 'carte', 'mobile_money', 'credit'])
                  ->default('espece');
            $table->decimal('sous_total', 12, 2)->default(0);
             $table->decimal('reste_a_payer', 12, 2)->default(0);
            $table->decimal('remise', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('montant_recu', 12, 2)->default(0);
            $table->decimal('monnaie', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['statut', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};