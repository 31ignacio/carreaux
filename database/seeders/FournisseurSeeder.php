<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = [
            [
                'nom'             => 'SOBEMAP',
                'telephone'       => '0022921301234',
                'email'           => 'contact@sobemap.bj',
                'adresse'         => 'Zone Industrielle, Cotonou',
                'ville'           => 'Cotonou',
                'contact_personne' => 'M. HOUNKPE',
                'actif'           => true,
            ],
            [
                'nom'             => 'CASTEL BENIN',
                'telephone'       => '0022921305678',
                'email'           => 'contact@castel.bj',
                'adresse'         => 'Boulevard Saint Michel, Cotonou',
                'ville'           => 'Cotonou',
                'contact_personne' => 'Mme. ADJOVI',
                'actif'           => true,
            ],
            [
                'nom'             => 'PROSUMA',
                'telephone'       => '0022921309012',
                'email'           => 'contact@prosuma.bj',
                'adresse'         => 'Akpakpa, Cotonou',
                'ville'           => 'Cotonou',
                'contact_personne' => 'M. KPOSSOU',
                'actif'           => true,
            ],
            [
                'nom'             => 'AGRIDEV',
                'telephone'       => '0022921303456',
                'email'           => 'contact@agridev.bj',
                'adresse'         => 'Cadjehoun, Cotonou',
                'ville'           => 'Cotonou',
                'contact_personne' => 'M. DOSSOU',
                'actif'           => true,
            ],
            [
                'nom'             => 'ELECTRO BENIN',
                'telephone'       => '0022921307890',
                'email'           => 'contact@electro.bj',
                'adresse'         => 'Ganhi, Cotonou',
                'ville'           => 'Cotonou',
                'contact_personne' => 'Mme. HOUNDJI',
                'actif'           => true,
            ],
        ];

        foreach ($fournisseurs as $fournisseur) {
            Fournisseur::updateOrCreate(['nom' => $fournisseur['nom']], $fournisseur);
        }

        $this->command->info('✅ Fournisseurs créés avec succès');
    }
}
