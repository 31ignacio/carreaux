<?php

namespace App\Imports;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Services\ProduitImportService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Throwable;

class ProduitsImport implements ToCollection, SkipsEmptyRows
{
    public int $importedCount = 0;

    public array $errors = [];

    public function __construct(
        private readonly ProduitImportService $produitImportService,
        private readonly ?int $userId = null
    ) {
    }

    public function collection(Collection $rows): void
    {
        Log::info('Import produits: analyse du fichier', [
            'rows_count' => $rows->count(),
        ]);

        $layout = $this->resolveImportLayout($rows);

        if ($layout === null) {
            $this->errors[] = 'Impossible de détecter la ligne des colonnes dans le fichier importé.';
            return;
        }

        $headers = $layout['headers'];
        $dataStartIndex = $layout['data_start_index'];

        foreach ($rows->slice($dataStartIndex) as $index => $row) {
            $rowNumber = $index + 1;

            try {
                $data = $this->mapRow($headers, (array) $row);

                if ($this->isDataRowEmpty($data)) {
                    continue;
                }

                $this->produitImportService->createFromArray($data, $this->userId);
                $this->importedCount++;
            } catch (ValidationException $exception) {
                $this->errors[] = 'Ligne ' . $rowNumber . ' : ' . collect($exception->errors())->flatten()->implode(' ');
            } catch (Throwable $exception) {
                $this->errors[] = 'Ligne ' . $rowNumber . ' : ' . $exception->getMessage();
            }
        }
    }

    private function resolveImportLayout(Collection $rows): ?array
    {
        $headerIndex = $this->detectHeaderRowIndex($rows);

        if ($headerIndex !== null) {
            return [
                'headers' => $this->extractHeadersFromRow((array) $rows[$headerIndex]),
                'data_start_index' => $headerIndex + 1,
            ];
        }

        $defaultHeaders = [
            'reference',
            'categorie_id',
            'libelle',
            'stock_actuel',
            'prix_detail',
            'prix_moyen',
            'prix_gros',
            'unite',
            'seuil_detail',
            'seuil_moyen',
            'seuil_gros',
            'stock_minimum',
        ];

        foreach ($rows as $index => $row) {
            $normalizedRow = $this->normalizeDataRow($defaultHeaders, (array) $row);
            $nonEmptyValues = array_values(array_filter($normalizedRow, fn ($value) => $value !== null && $value !== ''));

            if (count($nonEmptyValues) < 3) {
                continue;
            }

            $reference = (string) ($nonEmptyValues[0] ?? '');
            $category = (string) ($nonEmptyValues[1] ?? '');
            $label = (string) ($nonEmptyValues[2] ?? '');

            if (preg_match('/^[A-Z0-9_-]{4,}$/i', $reference) && $category !== '' && $label !== '') {
                Log::info('Import produits: utilisation du format par défaut sans entête détectée', [
                    'data_start_index' => $index,
                ]);

                return [
                    'headers' => $defaultHeaders,
                    'data_start_index' => $index,
                ];
            }
        }

        foreach ($rows as $index => $row) {
            $normalizedRow = $this->normalizeDataRow($defaultHeaders, (array) $row);
            $nonEmptyValues = array_values(array_filter($normalizedRow, fn ($value) => $value !== null && $value !== ''));

            if (count($nonEmptyValues) >= 3) {
                Log::info('Import produits: fallback générique sans entête détectée', [
                    'data_start_index' => $index,
                ]);

                return [
                    'headers' => $defaultHeaders,
                    'data_start_index' => $index,
                ];
            }
        }

        return null;
    }

    private function mapRow(array $headers, array $row): array
    {
        $row = $this->associateRowWithHeaders($headers, $row);

        return [
            'categorie_id' => $this->resolveCategorieId($row),
            'fournisseur_id' => $this->resolveFournisseurId($row),
            'libelle' => $this->getValue($row, ['libelle', 'nom', 'designation']),
            'reference' => $this->getValue($row, ['reference']),
            'code_barre' => $this->getValue($row, ['code_barre', 'codebarre', 'barcode']),
            'unite' => $this->getValue($row, ['unite', 'unite_de_vente']),
            'stock_actuel' => $this->getValue($row, ['stock_actuel', 'stock_initial']),
            'stock_minimum' => $this->getValue($row, ['stock_minimum']),
        ];
    }

    private function detectHeaderRowIndex(Collection $rows): ?int
    {
        foreach ($rows as $index => $row) {
            $headers = $this->extractHeadersFromRow((array) $row);

            if (in_array('libelle', $headers, true) && (in_array('categorie_id', $headers, true) || in_array('categorie', $headers, true))) {
                return $index;
            }
        }

        return null;
    }

    private function extractHeadersFromRow(array $row): array
    {
        $normalized = $this->normalizeHeaders($row);

        if ($this->containsExpectedHeaders($normalized)) {
            return $normalized;
        }

        foreach ($row as $cell) {
            $cell = $this->normalizeCellValue($cell);

            if (!is_string($cell) || trim($cell) === '') {
                continue;
            }

            $parts = preg_split('/\s*[;,|]\s*/', $cell);
            $parts = is_array($parts) ? array_values(array_filter($parts, fn ($part) => trim((string) $part) !== '')) : [];
            $candidate = $this->normalizeHeaders($parts);

            if ($this->containsExpectedHeaders($candidate)) {
                return $candidate;
            }
        }

        return $normalized;
    }

    private function normalizeHeaders(array $row): array
    {
        return array_map(
            function ($header) {
                $header = $this->normalizeCellValue($header);

                if ($header === null) {
                    return null;
                }

                return $this->normalizeKey((string) $header);
            },
            $row
        );
    }

    private function associateRowWithHeaders(array $headers, array $row): array
    {
        $associated = [];
        $row = $this->normalizeDataRow($headers, $row);

        foreach ($headers as $index => $header) {
            if ($header === null || $header === '') {
                continue;
            }

            $associated[$header] = $row[$index] ?? null;
        }

        return $associated;
    }

    private function normalizeDataRow(array $headers, array $row): array
    {
        $nonEmptyValues = array_values(array_filter(
            array_map(fn ($value) => $this->normalizeCellValue($value), $row),
            fn ($value) => $value !== null && $value !== ''
        ));

        if (count($nonEmptyValues) === 1 && is_string($nonEmptyValues[0])) {
            $parts = preg_split('/\s*[;,|]\s*/', $nonEmptyValues[0]);
            $parts = is_array($parts) ? array_values(array_filter($parts, fn ($part) => trim((string) $part) !== '')) : [];

            if (count($parts) >= count(array_filter($headers, fn ($header) => $header !== null && $header !== ''))) {
                return $parts;
            }
        }

        return array_map(fn ($value) => $this->normalizeCellValue($value), $row);
    }

    private function isDataRowEmpty(array $data): bool
    {
        foreach ($data as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    private function resolveCategorieId(array $row): mixed
    {
        $categorieId = $this->getValue($row, ['categorie_id']);

        if ($categorieId !== null) {
            if (is_numeric($categorieId)) {
                return $categorieId;
            }

            return Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower((string) $categorieId)])->value('id');
        }

        $categorieNom = $this->getValue($row, ['categorie', 'categorie_nom', 'nom_categorie']);

        if ($categorieNom === null) {
            return null;
        }

        return Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower((string) $categorieNom)])->value('id');
    }

    private function resolveFournisseurId(array $row): mixed
    {
        $fournisseurId = $this->getValue($row, ['fournisseur_id']);

        if ($fournisseurId !== null) {
            if (is_numeric($fournisseurId)) {
                return $fournisseurId;
            }

            return Fournisseur::whereRaw('LOWER(nom) = ?', [mb_strtolower((string) $fournisseurId)])->value('id');
        }

        $fournisseurNom = $this->getValue($row, ['fournisseur', 'fournisseur_nom', 'nom_fournisseur']);

        if ($fournisseurNom === null) {
            return null;
        }

        return Fournisseur::whereRaw('LOWER(nom) = ?', [mb_strtolower((string) $fournisseurNom)])->value('id');
    }

    private function getValue(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            $normalizedKey = $this->normalizeKey($key);

            if (!array_key_exists($normalizedKey, $row)) {
                continue;
            }

            $value = $row[$normalizedKey];
            $value = $this->normalizeCellValue($value);

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '') {
                return null;
            }

            return $value;
        }

        return null;
    }

    private function normalizeKey(string $key): string
    {
        $key = mb_strtolower(trim($key));
        $key = str_replace([' ', '-', '/', '\\'], '_', $key);
        $asciiKey = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $key);

        if ($asciiKey !== false) {
            $key = $asciiKey;
        }

        return preg_replace('/[^a-z0-9_]/', '', $key) ?: $key;
    }

    private function containsExpectedHeaders(array $headers): bool
    {
        return in_array('libelle', $headers, true)
            && (in_array('categorie_id', $headers, true) || in_array('categorie', $headers, true));
    }

    private function normalizeCellValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return collect($value)
                ->flatten()
                ->filter(fn ($item) => $item !== null && $item !== '')
                ->implode(' ');
        }

        return $value;
    }
}
