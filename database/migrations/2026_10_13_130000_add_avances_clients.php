<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('solde_avance', 12, 2)->default(0)->after('solde_credit');
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->decimal('montant_avance', 12, 2)->default(0)->after('montant_recu');
            $table->uuid('operation_reference')->nullable()->unique()->after('numero');
        });

        Schema::create('mouvements_avances', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('facture_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('type', ['depot', 'utilisation']);
            $table->decimal('montant', 12, 2);
            $table->string('mode_paiement', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['client_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_avances');
        Schema::table('factures', function (Blueprint $table) {
            $table->dropUnique(['operation_reference']);
            $table->dropColumn(['operation_reference', 'montant_avance']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('solde_avance');
        });
    }
};
