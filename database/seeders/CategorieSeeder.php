<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom'         => 'Produits Alimentaires',
                'description' => 'Épicerie, conserves, céréales',
                'couleur'     => '#e74c3c',
            ],
            [
                'nom'         => 'Boissons',
                'description' => 'Eau, jus, sodas, boissons alcoolisées',
                'couleur'     => '#3498db',
            ],
            [
                'nom'         => 'Hygiène & Beauté',
                'description' => 'Savons, shampooings, cosmétiques',
                'couleur'     => '#9b59b6',
            ],
            [
                'nom'         => 'Électronique',
                'description' => 'Appareils électroniques, accessoires',
                'couleur'     => '#f39c12',
            ],
            [
                'nom'         => 'Vêtements',
                'description' => 'Habits, chaussures, accessoires mode',
                'couleur'     => '#1abc9c',
            ],
            [
                'nom'         => 'Produits Laitiers',
                'description' => 'Lait, yaourt, fromage, beurre',
                'couleur'     => '#f1c40f',
            ],
            [
                'nom'         => 'Viandes & Poissons',
                'description' => 'Viandes fraîches, poissons, charcuterie',
                'couleur'     => '#e67e22',
            ],
            [
                'nom'         => 'Fruits & Légumes',
                'description' => 'Fruits frais, légumes, herbes',
                'couleur'     => '#2ecc71',
            ],
            [
                'nom'         => 'Entretien Maison',
                'description' => 'Produits ménagers, nettoyage',
                'couleur'     => '#34495e',
            ],
            [
                'nom'         => 'Autres',
                'description' => 'Divers produits',
                'couleur'     => '#95a5a6',
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::updateOrCreate(['nom' => $categorie['nom']], $categorie);
        }

        $this->command->info('✅ Catégories créées avec succès');
    }
}
