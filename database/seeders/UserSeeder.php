<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nom'       => 'ADMIN',
                'prenom'    => 'Super',
                'email'     => 'admin@gmail.com',
                'telephone' => '0022960000001',
                'password'  => Hash::make('00000000'),
                'role'      => 'admin',
                'actif'     => true,
            ],
            [
                'nom'       => 'CAISSE',
                'prenom'    => 'Jean',
                'email'     => 'caissier@Gamacorche.com',
                'telephone' => '0022960000002',
                'password'  => Hash::make('caisse123'),
                'role'      => 'caissier',
                'actif'     => true,
            ],
            [
                'nom'       => 'DUPONT',
                'prenom'    => 'Marie',
                'email'     => 'superviseur@Gamacorche.com',
                'telephone' => '0022960000003',
                'password'  => Hash::make('super123'),
                'role'      => 'superviseur',
                'actif'     => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }

        $this->command->info('✅ Users créés avec succès');
    }
}