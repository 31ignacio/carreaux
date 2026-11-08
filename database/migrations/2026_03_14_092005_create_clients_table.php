<?php
// database/migrations/xxxx_xx_xx_create_clients_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->enum('type', ['particulier', 'entreprise'])->default('particulier');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ifu', 20)->nullable()->unique();
            $table->decimal('solde_credit', 12, 2)->default(0); // créances en cours
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('telephone');
            $table->index('nom');
        });

        // Ajouter client_id à la table factures
        Schema::table('factures', function (Blueprint $table) {
            $table->foreignId('client_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained()
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
        Schema::dropIfExists('clients');
    }
};