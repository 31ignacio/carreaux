<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['rupture', 'stock_faible', 'stock_negatif']);
            $table->decimal('stock_au_moment', 12, 2);
            $table->boolean('traitee')->default(false);
            $table->foreignId('traitee_par')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('traitee_le')->nullable();
            $table->timestamps();

            $table->index(['traitee', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};