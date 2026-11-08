<?php

// database/migrations/xxxx_xx_xx_add_emecef_to_factures_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->string('emecef_uid')->nullable()->after('reste_a_payer');
            $table->string('emecef_code')->nullable()->after('emecef_uid');       // codeMECeFDGI
            $table->text('emecef_qr')->nullable()->after('emecef_code');          // qrCode
            $table->string('emecef_datetime')->nullable()->after('emecef_qr');    // dateTime DGI
            $table->string('emecef_counters')->nullable()->after('emecef_datetime');
            $table->string('emecef_nim')->nullable()->after('emecef_counters');
            $table->boolean('normalisee')->default(false)->after('emecef_nim');
            $table->timestamp('normalisee_at')->nullable()->after('normalisee');
        });

        // Champ tax_group sur les produits
        Schema::table('produits', function (Blueprint $table) {
            // A=exonéré, B=TVA 18%, C=0%, D=TVA 18% différent, etc.
            $table->string('tax_group', 1)->default('A')->after('prix_gros');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn([
                'emecef_uid', 'emecef_code', 'emecef_qr',
                'emecef_datetime', 'emecef_counters', 'emecef_nim',
                'normalisee', 'normalisee_at',
            ]);
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('tax_group');
        });
    }
};