<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('titre');
            $table->string('categorie_filtre')->default('toutes');
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->decimal('total_ecart_valeur', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('date_cloture')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};