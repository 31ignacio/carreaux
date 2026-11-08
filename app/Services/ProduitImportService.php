<?php

namespace App\Services;

use App\Models\MouvementStock;
use App\Models\Produit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProduitImportService
{
    public function createFromArray(array $data, ?int $userId = null, ?UploadedFile $image = null): Produit
    {
        $validated = $this->validateCreationData($data);
        $validated = $this->prepareValidatedData($validated);

        if ($image) {
            $validated['image'] = $image->store('produits', 'public');
        }

        return DB::transaction(function () use ($validated, $userId) {
            $produit = Produit::create($validated);

            if ($produit->stock_actuel > 0) {
                MouvementStock::create([
                    'produit_id' => $produit->id,
                    'user_id' => $userId,
                    'type' => 'entree',
                    'quantite' => $produit->stock_actuel,
                    'prix_unitaire' => 0,
                    'stock_avant' => 0,
                    'stock_apres' => $produit->stock_actuel,
                    'motif' => 'Stock initial',
                ]);
            }

            return $produit;
        });
    }

    public function validateCreationData(array $data): array
    {
        $validator = Validator::make(
            $this->normalizeInput($data),
            [
                'categorie_id' => 'required|exists:categories,id',
                'fournisseur_id' => 'nullable|exists:fournisseurs,id',
                'libelle' => 'required|string|max:255',
                'reference' => 'nullable|string|unique:produits,reference',
                'code_barre' => 'nullable|string|unique:produits,code_barre',
                'unite' => 'required|string|max:50',
                'stock_actuel' => 'required|numeric|min:0',
                'stock_minimum' => 'required|numeric|min:0',
            ],
            []
        );

        $validated = $validator->validate();
        return $validated;
    }

    public function prepareValidatedData(array $validated): array
    {
        return $validated;
    }

    private function normalizeInput(array $data): array
    {
        $numericFields = [
            'stock_actuel',
            'stock_minimum',
        ];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
                $data[$key] = $value === '' ? null : $value;
            }

            if (in_array($key, $numericFields, true)) {
                $data[$key] = $this->normalizeNumericValue($data[$key] ?? null);
            }
        }

        return $data;
    }

    private function normalizeNumericValue(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = str_replace(["\xc2\xa0", ' '], '', $value);
        $hasComma = str_contains($value, ',');
        $hasDot = str_contains($value, '.');

        if ($hasComma && $hasDot) {
            $value = strrpos($value, ',') > strrpos($value, '.')
                ? str_replace(',', '.', str_replace('.', '', $value))
                : str_replace(',', '', $value);
        } elseif ($hasComma) {
            $value = str_replace(',', '.', $value);
        }

        return $value;
    }
}
