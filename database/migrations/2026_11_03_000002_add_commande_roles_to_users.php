<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(30) NOT NULL DEFAULT 'caissier'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('users')->whereIn('role', ['secretaire', 'magasinier'])->update(['role' => 'caissier']);
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','caissier','superviseur','caissierHaut') NOT NULL DEFAULT 'caissier'");
        }
    }
};
