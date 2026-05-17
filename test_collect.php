<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = collect([1 => 'a']);
echo "Value with string key '1': " . ($c['1'] ?? 'null') . "\n";
echo "Value with int key 1: " . ($c[1] ?? 'null') . "\n";
