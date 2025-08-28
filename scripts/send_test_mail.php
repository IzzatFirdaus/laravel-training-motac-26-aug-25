<?php

require __DIR__.'/../vendor/autoload.php';

// Bootstrap the framework to access Mail facade
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

Mail::raw('Papercut test body', function ($m) {
    $m->to('hello@example.com')->subject('Papercut test');
});

echo "sent\n";
