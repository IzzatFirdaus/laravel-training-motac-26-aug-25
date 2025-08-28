<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Boot the application kernel to have facades available
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inv = App\Models\Inventory::find(46);
if (! $inv) {
    echo "no-inventory" . PHP_EOL;
    exit(1);
}

App\Jobs\InventoryCreatedJob::dispatch($inv);
echo "dispatched-script" . PHP_EOL;
