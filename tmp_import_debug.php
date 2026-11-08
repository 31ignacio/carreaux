<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('C:/Users/hp/Downloads/za.xlsx');
$rows = collect($spreadsheet->getActiveSheet()->toArray(null, true, true, false));
$import = new App\Imports\ProduitsImport(app(App\Services\ProduitImportService::class), 1);
$import->collection($rows);
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);
echo json_encode(['imported' => $import->importedCount, 'errors' => $import->errors], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
