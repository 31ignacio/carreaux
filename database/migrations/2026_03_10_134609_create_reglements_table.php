<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('reglements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('facture_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained(); // qui a enregistré le paiement
        $table->decimal('montant', 12, 2);
        $table->enum('mode_paiement', ['espece', 'mobile_money', 'carte']);
        $table->string('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglements');
    }
};
