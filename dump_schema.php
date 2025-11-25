<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = array_map(fn($row) => array_values((array)$row)[0], DB::select('SHOW TABLES'));
    echo "# Database Schema\n\n";
    foreach ($tables as $table) {
        echo "## $table\n\n";
        echo "| Field | Type | Null | Key | Default | Extra |\n";
        echo "|---|---|---|---|---|---|\n";
        $columns = DB::select("DESCRIBE `$table`");
        foreach ($columns as $col) {
            echo "| {$col->Field} | {$col->Type} | {$col->Null} | {$col->Key} | {$col->Default} | {$col->Extra} |\n";
        }
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
